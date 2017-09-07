<?php namespace App\Http\Controllers\Pay;

use Log;
use Agent;
use Response;
use Redirect;
use Carbon\Carbon;
use Omnipay\Omnipay;
use Omnipay\Alipay\Common\Signer;
use App\Services\Helpers;
use App\Http\Controllers\Controller;

use App\Customer;
use App\Models\Oauth;
use App\Models\Shop\Order;
use App\Models\Shop\PaymentMethod;
use App\Models\Shop\OrderTransaction;
use App\Models\Shop\OrderTransactionStatus;


use EasyWeChat\Foundation\Application;
use EasyWeChat\Payment\Order as POrder;

use Illuminate\Http\Request;

class OrderPayController extends Controller
{    

    public function index($ids){
        $ids = explode(',', $ids);
        $ids = collect($ids)->sort()->reverse();

        $orders = [];
        foreach($ids as $id){
            $order = Order::find((int) $id);
            if($order && $order->paid_status == 0){
                $orders[] = $order;
            }else{
                return redirect('/customer/order/search/all/');
            }
        }

        $order = $orders[0];
        $orders = collect($orders);
        $total = $orders->reduce(function($c, $a){
            return $c + $a->total;
        }, 0.0);

        $gw = $order->paymentMethod->gateway;

        if(is_wechat()){
            $gw = 'WechatPay_Js';
        }

        $payment_method = null;
        $ordersStr = $orders->reduce(function($c, $a){
            return $c.','.$a->id;
        });
        $ordersStr = ltrim($ordersStr, ',');

        $ot = OrderTransaction::where([['order_id', '=', $order->id],
                                       ['orders', '=', $ordersStr],
                                       ['status', '=', OrderTransactionStatus::Preparing],
                                       ['total', '=', $total],])->orderBy('id', 'desc')->first();
        if(!$ot){
            $ot = new OrderTransaction();
            $ot->order_id = $order->id;
            $ot->total = $total;
            $ot->status = OrderTransactionStatus::Preparing;
            $ot->customer_id = 1;
            $ot->description = '准备网络支付中';
            $ot->orders = $ordersStr;
            $ot->save();
        }
        if(!$ot){
            throw new Exception('支付过程生成临时信息出错');
        }
        $firstSkus = $order->orderSkus;
        $firstSku = $firstSkus[0];
        if(explode('_', $gw)[0] == 'Alipay'){
            $params = [
                'out_trade_no' => $order->sn.$ot->id,
                'subject'      => '我要 订单: '.$order->sn.'  '.$firstSku->product_name.' 等',
                'total_fee'    => $total,
            ];
            if(Agent::isMobile()){
                $payment_method = PaymentMethod::where('gateway', '=', 'Alipay_AopWap')->first();
                $ot->payment_method_id = $payment_method->id;
                $gateway = $this->getGateway('Alipay_AopWap');
                $request = $gateway->purchase();

                $params = array_merge($params, [
                                          'total_amount'=> $total,
                                          'product_code' => $order->sn,
                                      ]);
                $request->setBizContent($params);
            }else{
                $payment_method = PaymentMethod::where('gateway', '=', 'Alipay_LegacyExpress')->first();
                $ot->payment_method_id = $payment_method->id;
                $ot->save();
                $gateway = $this->getGateway('Alipay_LegacyExpress');

                $request = $gateway->purchase($params);
            }
            $ot->save();

            if(isset($request)){
                $response = $request->send();
                $response->redirect();
            }else{
                return redirect('/customer/order/search/current');
            }
            // alipay end
        }

        if(explode('_', $gw)[0] == 'WechatPay'){

            $ot->delete();
            $ot = new OrderTransaction();
            $ot->order_id = $order->id;
            $ot->total = $total;
            $ot->status = OrderTransactionStatus::Preparing;
            $ot->customer_id = 1;
            $ot->description = '准备网络支付中';
            $ot->orders = $ordersStr;
            $ot->save();
            $payment_method = PaymentMethod::where('gateway', '=', 'WechatPay_Native')->first();
            $ot->payment_method_id = $payment_method->id;
            if(is_wechat()){
                $gateway = $this->getGateway('WechatPay_Js');
                $user = Helpers::getCurrentUser();
                $user = Customer::find($user['id']);
                $ou = $user->oauth;
                $params = [
                    'out_trade_no' => $order->sn.$ot->id,
                    'openid' => $ou->open_id,
                    'body'      => '我要 订单: '.$order->sn.'  '.$firstSku->product_name.' 等',
                    'total_fee'    => $total * 100, // 订单金额为元，微信支付金额为分
                    'spbill_create_ip'  => '127.0.0.1',
                    'fee_type'          => 'CNY'
                ];
            }else{
                $gateway = $this->getGateway('WechatPay_Native');
                $params = [
                    'out_trade_no' => $order->sn.$ot->id,
                    'body'      => '我要 订单: '.$order->sn.'  '.$firstSku->product_name.' 等',
                    'total_fee'    => $total * 100, // 订单金额为元，微信支付金额为分
                    'spbill_create_ip'  => '127.0.0.1',
                    'fee_type'          => 'CNY'
                ];
            }

            $request  = $gateway->purchase($params);
            $response = $request->send();
            //$succ = $response->isSuccessful();
            $da = $response->getData(); //For debug
            Log::info($da);
            if($da['return_code'] == 'SUCCESS' && $da['return_msg'] == 'OK' && $da['result_code'] == 'SUCCESS'){
                $ot->save();
                if(Helpers::isWechat()){
                    $data = $response->getJsOrderData();
                    session(['devorder' => ['order'=>$order, 'data'=>$data, 'rt'=>url('/customer/order/'.$order->id)]]);
                    Log::info('session');
                    return redirect('http://devch.51chaohun.com/pay/devorders/?sessionid='.session()->getId().'&orders='.$ids);

                    return view('mobile.shop.pay', ['order' => $order, 'data' => $data]);        
                }else{
                    $wechatUri = $response->getCodeUrl(); //For Native Trade Type
                    return view('pc.pay', ['order' => $order, 'url' => $wechatUri]);        
                }
            }

        }else{
            return redirect('/customer/order/search/current');
        }
    }

    function devOrder(Request $request){
        $queries = $request->query();
        $sessionid = $queries['sessionid'];
        $orderid = $queries['orders'];
        $ses = \DB::table('sessions')->where('id', '=', $sessionid)->first();
        if($ses){
            $value = base64_decode($ses->payload);
            $s = unserialize($value);
            Log:info($s['devorder']);
            return view('mobile.shop.pay', ['order' => $s['devorder']['order'],
                                            'data' => $s['devorder']['data'],
                                            'rt' => $s['devorder']['rt']]);        
        }

    }

    function payReturn(Request $request, $gateway){
        if($gateway == 'alipay_aopwap'){
            $gateway = $this->getGateway('Alipay_AopWap');
            $pm = PaymentMethod::where('gateway', 'Alipay_AopWap')->first();
            return $this->alipayAopWapReturn($gateway, $pm);
        }
        if($gateway == 'alipay_legacyexpress'){
            $gateway = $this->getGateway('Alipay_LegacyExpress');
            $pm = PaymentMethod::where('gateway', 'Alipay_LegacyExpress')->first();
            return $this->alipayLegacyExpressReturn($gateway, $pm);
        }
    }

    function payNotify(Request $request, $gateway){
        if($gateway == 'alipay_aopwap'){
            $gateway = $this->getGateway('Alipay_AopWap');
            $pm = PaymentMethod::where('gateway', 'Alipay_AopWap')->first();
            return $this->alipayAopWapNotify($gateway, $pm);
        }
        if($gateway == 'alipay_legacyexpress'){
            $gateway = $this->getGateway('Alipay_LegacyExpress');
            $pm = PaymentMethod::where('gateway', 'Alipay_LegacyExpress')->first();
            return $this->alipayLegacyExpressNotify($gateway, $pm);
        }
        if($gateway == 'wechatpay_native'){
            $gateway = $this->getGateway('WechatPay');
            $pm = PaymentMethod::where('gateway', 'Wechatpay_Native')->first();
            return $this->wechatpayNotify($gateway, $pm);
        }
    }

    protected function alipayAopWapReturn($gateway, $payment_method){
        $params = array_merge($_POST, $_GET);
        $request = $gateway->completePurchase();
        $request->setParams($params); 
        try {
            $response = $request->send();
            $id = substr($params['out_trade_no'], 10);
            $ot = OrderTransaction::find((int) $id);
            $order_ids = explode(',', $ot->orders);
            if($response->isPaid()){
                $orders = Order::whereIn('id', $order_ids)->get();
                $retTotal = $params['total_amount'];
                $total = $orders->reduce(function($c, $a){ return $c + $a->total; }, 0.00);
                if(abs($retTotal - $total) < 0.01 && $ot->status != 1){
                    $ot->description = '支付完成';
                    $ot->payment_trace_no = $params['trade_no'];
                    $ot->status = 1;
                    $ot->save();
                    foreach($orders as $order){
                        $order->paid_at = Carbon::now();
                        $order->payment_method_id = $payment_method->id;
                        $order->payment_method_name = $payment_method->show_name;
                        $order->paid_status = 1;
                        $order->pay_order_id = $ot->order_id;
                        $order->paid_transaction = $request['trade_no'];
                        $order->paid_total = $retTotal;
                        $order->save();
                    }
                    Log::info('orders: '. $ot->orders. ' pay completed'); //The notify response should be 'success' only
                }
            }
        } catch (Exception $e) {
            Log::info('exception '.$e->getMessage()); //The notify response
            return redirect('/customer/order/search/all');
        }
        if(count($order_ids) > 1){
            return redirect('/customer/order/search/all');
        }else{
            return redirect('/customer/order/'.$order_ids[0]);
        }
    }

    protected function wechatpayNotify($gateway, $payment_method){
        $request = $gateway->completePurchase(['request_params' => file_get_contents('php://input')]);
        $response = $request->send();

        if ($response->isPaid()) {
            //pay success
            $params = $request->getData();
            Log::info($params);

            $id = substr($params['out_trade_no'], 10);
            $ot = OrderTransaction::find((int) $id);
            $orders = Order::whereIn('id', explode(',', $ot->orders))->get();
            $retTotal = $params['total_fee'] / 100.00;
            $total = $orders->reduce(function($c, $a){ return $c + $a->total; }, 0.00);
            if(abs($retTotal - $total) < 0.01 && $ot->status != 1){
                $ot->description = '支付完成';
                $ot->payment_trace_no = $params['transaction_id'];
                $ot->status = 1;
                $ot->save();
                foreach($orders as $order){
                    $order->paid_at = Carbon::now();
                    $order->payment_method_id = $payment_method->id;
                    $order->payment_method_name = $payment_method->show_name;
                    $order->paid_status = 1;
                    $order->pay_order_id = $ot->order_id;
                    $order->paid_transaction = $params['transaction_id'];
                    $order->paid_total = $retTotal;
                    $order->save();
                }
                Log::info('orders: '. $ot->orders. ' pay completed'); //The notify response should be 'success' only
                die('success');
            }
            die('fail');
        }else{
            Log::info($response->isPaid());
        }
    }

    protected function alipayAopWapNotify($gateway, $payment_method){
        $params = array_merge($_POST, $_GET);
        $request = $gateway->completePurchase();
        $request->setParams($params); 
        try {
            $response = $request->send();
            if($response->isPaid()){
                $id = substr($params['out_trade_no'], 10);
                $ot = OrderTransaction::find((int) $id);
                $orders = Order::whereIn('id', explode(',', $ot->orders))->get();
                $retTotal = $params['total_amount'];
                $total = $orders->reduce(function($c, $a){ return $c + $a->total; }, 0.00);
                if(abs($retTotal - $total) < 0.01 && $ot->status != 1){
                    $ot->description = '支付完成';
                    $ot->payment_trace_no = $params['trade_no'];
                    $ot->status = 1;
                    $ot->save();
                    foreach($orders as $order){
                        $order->paid_at = Carbon::now();
                        $order->payment_method_id = $payment_method->id;
                        $order->payment_method_name = $payment_method->show_name;
                        $order->paid_status = 1;
                        $order->pay_order_id = $ot->order_id;
                        $order->paid_transaction = $params['trade_no'];
                        $order->paid_total = $retTotal;
                        $order->save();
                    }
                    Log::info('orders: '. $ot->orders. ' pay completed'); //The notify response should be 'success' only
                    die('success');
                }
                die('fail');
            }
        } catch (Exception $e) {
            Log::info('exception '.$e->getMessage()); //The notify response
            die('exception');
        }
    }

    protected function alipayLegacyExpressReturn($gateway, $payment_method){
        $params = array_merge($_POST, $_GET);
        $request = $gateway->completePurchase();
        $request->setParams($params); 
        try {
            $response = $request->send();
            $id = substr($params['out_trade_no'], 10);
            $ot = OrderTransaction::find((int) $id);
            $order_ids = explode(',', $ot->orders);
            if($response->isPaid()){
                $orders = Order::whereIn('id', $order_ids)->get();
                $retTotal = $params['total_fee'];
                $total = $orders->reduce(function($c, $a){ return $c + $a->total; }, 0.00);
                if(abs($retTotal - $total) < 0.01 && $ot->status != 1){
                    $ot->description = '支付完成';
                    $ot->payment_trace_no = $params['trade_no'];
                    $ot->status = 1;
                    $ot->save();
                    foreach($orders as $order){
                        $order->paid_at = Carbon::now();
                        $order->payment_method_id = $payment_method->id;
                        $order->payment_method_name = $payment_method->show_name;
                        $order->paid_status = 1;
                        $order->pay_order_id = $ot->order_id;
                        $order->paid_transaction = $request['trade_no'];
                        $order->paid_total = $retTotal;
                        $order->save();
                    }
                    Log::info('orders: '. $ot->orders. ' pay completed'); //The notify response should be 'success' only
                }
            }
        } catch (Exception $e) {
            Log::info('exception '.$e->getMessage()); //The notify response
            return redirect('/customer/order/search/all');
        }
        if(count($order_ids) > 1){
            return redirect('/customer/order/search/all');
        }else{
            return redirect('/customer/order/'.$order_ids[0]);
        }
    }

    protected function alipayLegacyExpressNotify($gateway, $payment_method){
        $params = array_merge($_POST, $_GET);
        Log::info($params);
        $request = $gateway->completePurchase();
        $request->setParams($params); 
        try {
            $response = $request->send();
            if($response->isPaid()){
                $id = substr($params['out_trade_no'], 10);
                $ot = OrderTransaction::find((int) $id);
                $orders = Order::whereIn('id', explode(',', $ot->orders))->get();
                $retTotal = $params['total_fee'];
                $total = $orders->reduce(function($c, $a){ return $c + $a->total; }, 0.00);
                if(abs($retTotal - $total) < 0.01 && $ot->status != 1){
                    $ot->description = '支付完成';
                    $ot->payment_trace_no = $params['trade_no'];
                    $ot->status = 1;
                    $ot->save();
                    foreach($orders as $order){
                        $order->paid_at = Carbon::now();
                        $order->payment_method_id = $payment_method->id;
                        $order->payment_method_name = $payment_method->show_name;
                        $order->paid_status = 1;
                        $order->pay_order_id = $ot->order_id;
                        $order->paid_transaction = $params['trade_no'];
                        $order->paid_total = $retTotal;
                        $order->save();
                    }
                    Log::info('orders: '. $ot->orders. ' pay completed'); //The notify response should be 'success' only
                    die('success');
                }
                die('fail');
            }
        } catch (Exception $e) {
            Log::info('exception '.$e->getMessage()); //The notify response
            die('exception');
        }
    }

    protected function getGateway($gateway){
        if($gateway == 'Alipay_AopWap'){
            $gateway = Omnipay::create('Alipay_AopWap');
            if(config('laravel-omnipay.gateways.Alipay_AopWap.SandBox')){
                $gateway->sandbox();
            }
            $gateway->setAppId(config('laravel-omnipay.gateways.Alipay_AopWap.AppID'));
            $gateway->setPrivateKey(config('laravel-omnipay.gateways.Alipay_AopWap.PrivateKey'));
            $gateway->setAlipayPublicKey(config('laravel-omnipay.gateways.Alipay_AopWap.PublicKey'));
            $gateway->setReturnUrl(url(config('laravel-omnipay.gateways.Alipay_AopWap.RU')));
            $gateway->setNotifyUrl(url(config('laravel-omnipay.gateways.Alipay_AopWap.NU')));

        }

        if($gateway == 'Alipay_LegacyExpress'){
            $gateway = Omnipay::create('Alipay_LegacyExpress');
            $gateway->setPartner(config('laravel-omnipay.gateways.Alipay_LegacyExpress.PartnerID'));
            $gateway->setSellerEmail(config('laravel-omnipay.gateways.Alipay_LegacyExpress.SellerEmail'));
            $gateway->setSellerId(config('laravel-omnipay.gateways.Alipay_LegacyExpress.SellerID'));
            $gateway->setKey(config('laravel-omnipay.gateways.Alipay_LegacyExpress.Key'));
            $gateway->setReturnUrl(url(config('laravel-omnipay.gateways.Alipay_LegacyExpress.RU')));
            $gateway->setNotifyUrl(url(config('laravel-omnipay.gateways.Alipay_LegacyExpress.NU')));
            
        }
        if($gateway == 'WechatPay_Native'){
            $gateway = Omnipay::create('WechatPay_Native');
            $gateway->setMchId(config('laravel-omnipay.gateways.WechatPay.MerchantID'));
            $gateway->setAppId(config('laravel-omnipay.gateways.WechatPay.AppID'));
            $gateway->setApiKey(config('laravel-omnipay.gateways.WechatPay.Key'));
            $gateway->setNotifyUrl(''.url(config('laravel-omnipay.gateways.WechatPay.NU_Native')));
        }
        if($gateway == 'WechatPay_Js'){
            $gateway = Omnipay::create('WechatPay_Js');
            $gateway->setMchId(config('laravel-omnipay.gateways.WechatPay.MerchantID'));
            $gateway->setAppId(config('laravel-omnipay.gateways.WechatPay.AppID'));
            $gateway->setApiKey(config('laravel-omnipay.gateways.WechatPay.Key'));
            $gateway->setNotifyUrl(''.url(config('laravel-omnipay.gateways.WechatPay.NU_Native')));
        }
        if($gateway == 'WechatPay'){
            $gateway = Omnipay::create('WechatPay');
            $gateway->setMchId(config('laravel-omnipay.gateways.WechatPay.MerchantID'));
            $gateway->setAppId(config('laravel-omnipay.gateways.WechatPay.AppID'));
            $gateway->setApiKey(config('laravel-omnipay.gateways.WechatPay.Key'));
            $gateway->setNotifyUrl(''.url(config('laravel-omnipay.gateways.WechatPay.NU_Native')));
        }
        return $gateway;
    }

    function ajaxIsPaid(Request $request, $id){
        $json = ['status' => 1,
                 'is_paid' => 0];
        $order = Order::find($id);
        if($order && $order->paid_status) {
            $json['is_paid'] = 1;
        }

        return response()->json($json);
    }
}