@extends('layouts.mobile', ['subnav' => true])

@section('meta')
    <link href="/all" type="application/rss+xml" rel="alternate" title="robots" />
@endsection


@section('title')
    {{'verihuo'}}
@endsection

@section('navigatorTop')
    @include('mobile.elements.navigatorTop2', ['title' => '订单详情'])
@endsection

@section('content')
    <div class="panel chm-account-panel chm-account-order" style="padding: 0.625rem;">
        <div class="row chm-account-order-head">
            商家：@if(isset($order->merchant))<img src="{{ $order->merchant->logo }}" style="width:3.125rem;height:1.5625rem;">{{ $order->merchant->name }}@endif
        </div>
        <div class="row chm-account-order-productlist">
            <table class="chm-account-order-product">
                @foreach($order->orderSkus as $key=>$orderSku)
                    <tr>
                        <td class="small-3" style="">
                            <img src="@if(isset($orderSku->productFirstImage)){{ $orderSku->productFirstImage->image }}@endif">
                        </td>
                        <td class="small-9" style="position: relative;">
                            <div style="position: absolute;top: 0.625rem;width: 100%;">
                                <span class="left" style="margin-left: 0.3125rem;">{{ str_limit($orderSku->product_name,20,'') }}</span>
                                <span class="right">x{{ $orderSku->quantity }}</span>
                            </div>
                            <div style="position: absolute;bottom: 0.625rem;width: 100%;">
                                <span class="left" style="margin-left: 0.3125rem;color:#989898;font-size:0.75rem;">分类: {{ $orderSku->product->productCategoryWithTrashed->name }} &nbsp;包装: {{ $orderSku->product->package }}</span>
                                <span class="right" style="color: #C30E21"><i class="fa fa-rmb" aria-hidden="true"></i>{{ $orderSku->market_price }}</span>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
    <div class="row text-right panel " style="border:none;background-color:#F9F9F9;padding: 0.625rem 0.9375rem 0 0;">
        共 <span style="color: #C30E21">{{ $order->product_quantity }}</span> 件商品，合计：<span style="color: #C30E21">{{ $order->total }}</span> 元
    </div>
    <div class="panel chm-account-panel chm-account-order" style="padding: 0.625rem;font-size: 0.875rem;">
        <div class="row">
            <div class="columns" style="width: 28%">订单编号:</div>
            <div class="columns left" style="width: 72%">{{ $order->sn }}</div>
        </div>
        <div class="row">
            <div class="columns" style="width: 28%">下单时间:</div>
            <div class="columns left" style="width: 72%">{{ $order->created_at }}</div>
        </div>
        @if(!empty($order->paid_at))
            <div class="row">
                <div class="columns" style="width: 28%">付款时间:</div>
                <div class="columns left" style="width: 72%">{{ $order->paid_at }}</div>
            </div>
        @endif
        <div class="row">
            <div class="columns" style="width: 28%">送货地址:</div>
            <div class="columns left" style="width: 72%">
                <p style="font-size: 0.875rem;">{{ $order->consignee_address }} ({{ $order->consignee_name }}收,{{ $order->consignee_phone }} )</p>
            </div>
        </div>
        @if(!empty($order->payment_method_name))
            <div class="row">
                <div class="columns" style="width: 28%">支付方式:</div>
                <div class="columns left" style="width: 72%">{{ $order->payment_method_name }}</div>
            </div>
        @endif
        <div class="row">
            <div class="columns" style="width: 28%">支付状态:</div>
            <div class="columns left" style="width: 72%;@if($order->paid_status == 0){{ 'color:red;' }}@endif"> @if($order->paid_status == 1) 已支付 @else 未支付 @endif</div>
        </div>
        @if(isset($order->logistic))
            <div class="row">
                <div class="columns" style="width: 28%">配送方式:</div>
                <div class="columns left" style="width: 72%">{{ $order->logistic->name }}</div>
            </div>
        @endif
        @if(!empty($order->delivered_at))
            <div class="row">
                <div class="columns" style="width: 28%">配送时间:</div>
                <div class="columns left" style="width: 72%">{{ $order->delivered_at }}</div>
            </div>
        @endif
        @if(isset($order->invoice))
            <div class="row">
                <div class="columns" style="width: 28%">发票信息:</div>
                <div class="columns left" style="width: 72%">{{ $order->invoice->content }}</div>
            </div>
        @endif
        @if(!empty($order->remark))
            <div class="row">
                <div class="columns" style="width: 28%">备注信息:</div>
                <div class="columns left" style="width: 72%">{{ $order->remark }}</div>
            </div>
        @endif
        @if(!empty($order->tips))
            <div class="row">
                <div class="columns" style="width: 28%">商家建议:</div>
                <div class="columns left" style="width: 72%">{{ $order->tips }}</div>
            </div>
        @endif
    </div>
    <div class="row text-right panel" style="border:none;background-color:#F9F9F9;padding: 0.625rem 0.9375rem 0 0;">
        @if($order->status == \App\Models\Shop\OrderStatus::Raw && $order->paid_status == 0)
            <a href="/customer/order/cancel/{{$order->id}}" class="small-4 chm-account-order-b">取消订单</a>
            <a href="/pay/orders/{{$order->id}}" class="small-4 chm-account-order-b">确认付款</a>
        @elseif($order->status == \App\Models\Shop\OrderStatus::Completed)
            <a href="http://cs.ecqun.com/mobile/rand?id=1678907" class="small-4 chm-account-order-b">售后服务</a>
        @elseif($order->status == \App\Models\Shop\OrderStatus::Shipping)
            <a href="http://cs.ecqun.com/mobile/rand?id=1678907" class="small-4 chm-account-order-b">售后服务</a>
            <a href="/customer/order/logistics/{{$order->id}}" class="small-4 chm-account-order-b">查看物流</a>
            <a href="#" class="small-4 chm-account-order-b chm-account-order-receipt" data-orderid="{{$order->id}}">确认收货</a>
        @elseif($order->status == \App\Models\Shop\OrderStatus::Checked)
            <a href="http://cs.ecqun.com/mobile/rand?id=1678907" class="small-4 chm-account-order-b">售后服务</a>
        @endif
    </div>
    @include('mobile.elements.modalConfirm')
@endsection

@section('script-file-bottom')
    <script type="text/javascript" src="/assets/js/mobile.customer.js"></script>
    <script type="text/javascript" src="/assets/js/mobile.shop.js"></script>
    <script type="text/javascript" src="/assets/js/utils.js"></script>
@endsection
