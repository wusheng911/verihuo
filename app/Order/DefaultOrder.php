<?php namespace App\Order;

use DB;
use Log;
use Carbon\Carbon;
use App\Models\Address;
use App\Models\Shop\Sku;
use App\Models\Shop\Order;
use App\Models\Shop\OrderSku;
use App\Models\Shop\OrderStatus;
use App\Models\Shop\PaymentMethod;
use App\Contracts\Order\Repository;
use App\Order\PlaceOrderException;

use App\Services\Helpers;

class DefaultOrder implements Repository{

    /**
     * place order.
     * @param array $skus [['sku_id' => 2, 'quantity' => 99], ...]
     * @param int $address_id selected address id
     * @param int $paymethod_id payment method id 
     * @param string $remark the customer leaves message
     * @return Order or false
     */
    public function create($skus, $address_id, $paymethod_id, $remark){
        //!!! need more than 3 seconds on live
        $interval = config('settings.shop_order_create_interval', 0);
        sleep($interval);

        $uid = md5(uniqid(md5(microtime(true)),true));
        Log::debug("<$uid> "."begin create order");
        // check sku quantity, check user address, check others
        if(!(is_array($skus) && count($skus) > 0)){
            return false;
        }
        $enoughGoods = true;
        $merchant_id = 0;
        foreach($skus as $k => $sku){
            $s = Sku::find((int) $sku['sku_id']);
            if(!$s || $s->quantity < (int) $sku['quantity']){
                $enoughGoods = false;
                Log::debug("<$uid> "."no more goods for (sku: ".$sku['sku_id'].")");
                break;
            }
            // init merchant id
            if($merchant_id == 0){
                $merchant_id = $s->merchant_id;
            }
            if($merchant_id != $s->merchant_id){
                Log::debug("<$uid> "."must one merchant in per order.");
                break;
            }
            $skus[$k]['o'] = $s;
        }
        if(!$enoughGoods){
            return false;
        }

        $address = Address::find((int) $address_id);

        if(!$address){
            Log::debug("<$uid> "."not found valide address.");
            return false;
        }
        $address->fulladdress = isset($address->zone->province) ? $address->zone->province.' ' : '';
        $address->fulladdress .= isset($address->zone->city) ? $address->zone->city.' ' : '';
        $address->fulladdress .= isset($address->zone->district) ? $address->zone->district.' ' : '';
        $address->fulladdress .= isset($address->zone->town) ? $address->zone->town.' ' : '';
        $address->fulladdress .= $address->details;

        $pm = PaymentMethod::find((int) $paymethod_id);
        if(!$pm){
            Log::debug("<$uid> "."not found valide payment method.");
            return false;
        }

        $user = Helpers::getCurrentUser();
        if(empty($user)){
            Log::debug("<$uid> "."not found customer.");
            return false;
        }

        // get order sn from order pool loop 10 max times
        $times = 1;
        while($times++ <= 10){
            $snbulk = DB::table('order_pool')->whereNull('used')->orderBy('id', 'asc')->first();
            if($snbulk){
                DB::table('order_pool')->where('id', '=', $snbulk->id)
                    ->whereNull('used')->update(['used' => $uid]);
            }else{
                Log::debug("<$uid> "."order_pool exhausted.");
                return false;
            }
            $snbulkNew = DB::table('order_pool')->where('id', '=', $snbulk->id)->first();
            if($snbulkNew->used == $uid){
                break;
            }
            sleep(1);
        }
        if($snbulkNew->used != $uid){
            Log::debug("<$uid> "."get sn over 10 times from order_pool, but can not catch any one.");
            return false;
        }

        // first char defined as below;
        $sn1 = Helpers::isMobile() ? '6' : '2';
        $sn = $sn1.substr(Carbon::now()->format('ymd'), 1).$snbulkNew->sn;
        // begin transaction
        $skus = collect($skus);
        $order = new Order;
        $order->sn = $sn;
        $order->customer_id = $user['id'];
        $order->customer_name = $user['user_name'];
        $order->consignee_zone_id = $address->zone_id;
        $order->consignee_name = $address->consignee_name;
        $order->consignee_phone = $address->consignee_phone;
        $order->consignee_address = $address->fulladdress;
        $order->payment_method_id = $pm->id;
        $order->payment_method_name = $pm->name;
        $order->status = OrderStatus::Raw;
        $order->total = $skus->reduce(function($c, $i){
            return $c + $i['o']->price * $i['quantity'];
        }, 0.00);
        $order->prime_total = $skus->reduce(function($c, $i){
            return $c + $i['o']->prime_price * $i['quantity'];
        }, 0.00);
        $order->discount = 0;
        $order->book_agent = app()->request->header('user-agent');
        $order->book_ip = app()->request->ip();
        $order->remark = $remark;
        $order->created_at = Carbon::now();
        try{
            $skus = $skus->sortBy('sku_id');
            DB::transaction(function()use(&$order, $skus) {
                // new order
                $order->save();
                foreach($skus as $k => $item){
                    $sku = $item['o'];
                    $orderSku = new OrderSku();
                    $orderSku->order_id = $order->id;
                    $orderSku->sku_id = $sku->id;
                    $orderSku->sku_sn = $sku->sn;
                    $orderSku->product_id = $sku->product_id;
                    $orderSku->product_name = $sku->product_name;
                    $orderSku->quantity = $item['quantity'];
                    $orderSku->market_price = $sku->price;
                    $orderSku->total = $sku->price * $item['quantity'];
                    $orderSku->prime_price = $sku->prime_price;

                    // lock sku and update quantity
                    $sku = Sku::lockForUpdate()->find((int) $sku->id);
                    if($sku->quantity - $item['quantity'] >=0){
                        $sku->decrement('quantity', $item['quantity']);
                    }else{
                        throw new PlaceOrderException("no more goods for (sku: $sku->id, quantity: $sku->quantity), need ".$item['quantity']);
                    }
                    $orderSku->save();
                }
            });
        }catch(PlaceOrderException $ex){
            DB::table('order_pool')->where('id', '=', $snbulkNew->id)->update(['used' => null]);
            Log::debug("<$uid> ".$ex->getMessage());
            return false;
        }
        // end transaction
        Log::debug("<$uid> "."end create order (id: $order->id, sn: $order->sn).");
        return $order->id > 0 ? $order : false;
    }
}