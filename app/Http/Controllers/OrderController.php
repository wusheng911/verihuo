<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use Logis;
use JavaScript;
use App\Http\Requests;
use App\Services\Helpers;
use App\Customer;
use App\Models\Shop\Order;
use App\Models\Shop\OrderLog;
use App\Models\Shop\OrderStatus;

class OrderController extends Controller
{
    /**
     * 判断用户是否登录
     */
    public function isLogin()
    {
        if(Auth::check()){
            return redirect('/customer/account');
        }
    }
    /**
     * 用户订单
     * @param string $type
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($type='')
    {
        $this->isLogin();
        $customer = Helpers::getCurrentUser();

        //全部订单
        $orders = Order::where('customer_id',$customer['id'])->orderBy('id', 'desc')->get();
        foreach ($orders as $key=>$order) {
            $product_quantity = 0;
            foreach ($order->orderSkus as $orderSku) {
                $product_quantity += $orderSku->quantity;
            }
            $order->product_quantity = $product_quantity;
        }

        //当前订单
        $currentOrders = Order::where('customer_id',$customer['id'])
            ->whereNotIn('status',[2,7,6])
            ->orderBy('id', 'desc')
            ->get();
        foreach ($currentOrders as $key=>$currentOrder) {
            $product_quantity = 0;
            foreach ($currentOrder->orderSkus as $orderSku) {
                $product_quantity += $orderSku->quantity;
            }
            $currentOrder->product_quantity = $product_quantity;
        }

        //退换货

        //dd($type);
        return view(Helpers::getViewTemplateHeaderByAgent() . '.customer.order',
            ['customer' => $customer,'type' => $type,'orders' => $orders,'currentOrders' => $currentOrders,'returnOrders'=>'']);
    }

    /**
     * 订单详情
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function order($id)
    {
        $this->isLogin();
        $order = Order::find($id);
        $product_quantity = 0;
        foreach ($order->orderSkus as $orderSku) {
            $product_quantity += $orderSku->quantity;
        }
        $order->product_quantity = $product_quantity;
        return view(Helpers::getViewTemplateHeaderByAgent() . '.customer.orderdetails',['order' => $order]);
    }

    /**
     * 取消订单
     * @param $id 订单ID
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function cancelOrder($id)
    {
        $this->isLogin();
        $order = Order::find($id);
        return view(Helpers::getViewTemplateHeaderByAgent() . '.customer.cancelorder',['order' => $order]);
    }

    public function update(Request $request)
    {
        $this->isLogin();
        $orderId = $request->orderId;
        $formAction = $request->formAction;
        $orderAction = $request->orderAction;
        //取消订单
        if($formAction == OrderStatus::Cancelled) {
            //更新订单状态
            Order::where('id',$orderId)->update(['status'=>$formAction]);

            //取消订单，更新相对应商品库存
            $order = Order::find($orderId);
            $orderSkus = $order->orderSkus;
            //dd($orderSkus);
            foreach ($orderSkus as $orderSku) {
                $order_product_quantity = $orderSku->quantity;
                //$product_quantity = $orderSku->sku()->quantity;
                $product_quantity = $orderSku->sku()->lists('quantity')->reduce(function ($carry, $item) {
                    return $carry + $item;
                }, $order_product_quantity);
                $orderSku->sku()->update(['quantity'=>$product_quantity]);
            }

            //保存订单取消原因
            //$cancelOrderCanse = $request->cancelOrder.'('.$request->cancelOrderCause.')';
            //if($cancelOrderCanse) OrderLog::create(['order_id'=>$request->orderId,'remark'=>'取消订单','info'=>$cancelOrderCanse]);
            return redirect()->to(action('OrderController@index',['type'=>'all']));
        } elseif($orderAction == OrderStatus::Completed || $orderAction == OrderStatus::Checked) {
            Order::where('id',$orderId)->update(['status'=>$orderAction]);
            return response()->json(['success']);
        } elseif($orderAction == 'delete') {
            Order::where('id',$orderId)->delete();
            return response()->json(['success']);
        }
    }

    /**
     * @param int id
     */
    public function ajaxDelete($id)
    {

    }

    public function logistics($id)
    {
        $this->isLogin();
        if(!empty($id)){
            $order = Order::find($id);
            //$logistics = Logis::get($order->logistic->code, $order->logistic_no)->traces;
            //dd($logistics);
        }

        return view(Helpers::getViewTemplateHeaderByAgent() . '.customer.orderlogistics',['order' => $order]);
    }

    public function listLogisticJson(Request $request){
        $id = (int) $request->input('id');
        $json = ['status' => 'failed'];
        if(!empty($id)){
            $order = Order::find($id);
            $ljson = Logis::get($order->logistic->code, $order->logistic_no)->traces;
            $json['status'] = 'success';
            $json['data'] = json_encode($ljson);
        }

        return response()->json($json);
    }
}