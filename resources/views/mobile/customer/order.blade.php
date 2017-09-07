@extends('layouts.mobile', ['subnav' => true])

@section('meta')
    <meta name="description" content="veriHuo- College application essays and advice" />
    <meta name="keywords" content="veriHuo- College application essays and advice" />
    <link href="/all" type="application/rss+xml" rel="alternate" title="robots" />
@endsection


@section('title')
    {{'veriHuo- College application essays and advice'}}
@endsection

@section('navigatorTop')
    <div class="chm-fixed-navigator">
        <nav class="top-bar" style="height:1.875rem;margin:0.25rem 0;">
            <div class="row" style="line-height:1.875rem;height:1.875rem;">
                <a href="javascript:window.history.back();" style="font-size: 1.5rem;">
                    <div class="small-1 columns">
                        <i class="fa fa-angle-left" aria-hidden="true"></i>
                    </div>
                </a>
                <div class="small-11 columns">
                    <ul class="tabs chm-account-order-tab" data-tab="" style="line-height:1.875rem;height:1.875rem;">
                        <li class="tab-title small-4 columns text-center @if($type=='' || $type=="current") active @endif"><a href="#current-orders">当前订单</a></li>
                        <li class="tab-title small-4 columns text-center @if($type=="returns") active @endif"><a href="#return-orders" >退换货</a></li>
                        <li class="tab-title small-4 columns text-center @if($type=="all") active @endif"><a href="#all-orders">全部订单</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        @if(isset($hide_divider) && $hide_divider)
        @else
        @include('mobile.elements.dividerImage')
        @endif
    </div>
@endsection

@section('content')
    @include('mobile.elements.promptAlert')
    <div class="tabs-content">
        {{--当前订单--}}
        <div class="content @if($type=='' || $type=="current") active @endif" id="current-orders">
            @foreach($currentOrders as $key=>$currentOrder)
                <div class="panel chm-account-panel chm-account-order" style="padding: 0.625rem;">
                    <a href="/customer/order/{{ $currentOrder->id }}">
                        <div class="row chm-account-order-head">
                            <span class="left">订单编号：{{ $currentOrder->sn }}</span>
                            <span class="right">交易状态：{{ $currentOrder->orderStatus->description or '未知状态' }}@if($currentOrder->paid_status == 1 && $currentOrder->orderStatus->id == 1 ) {{ '(已支付)' }} @elseif($currentOrder->paid_status == 0 && $currentOrder->orderStatus->id == 1 ) {{ '(未支付)' }} @endif</span>
                        </div>
                        <div class="row chm-account-order-productlist">
                            <table class="chm-account-order-product">
                                @foreach($currentOrder->orderSkus as $key=>$orderSku)
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
                                    @if($key==1)
                                        @break @endif
                                @endforeach
                            </table>
                        </div>
                        <div class="row chm-account-order-total text-right">
                            共 <span style="color: #C30E21">{{ $currentOrder->product_quantity }}</span> 件商品，合计：<span style="color: #C30E21">{{ $currentOrder->total }}</span> 元
                        </div>
                    </a>
                </div>
                <div class="row text-right panel" style="border:none;background-color:#F9F9F9;padding: 0.625rem 0.9375rem 0 0;">
                    @if($currentOrder->status == \App\Models\Shop\OrderStatus::Raw && $currentOrder->paid_status == 0)
                        <a href="/customer/order/cancel/{{$currentOrder->id}}" class="small-4 chm-account-order-b">取消订单</a>
                        <a href="/pay/orders/{{$currentOrder->id}}" class="small-4 chm-account-order-b chm-account-order-b-red">确认付款</a>
                    @elseif($currentOrder->status == \App\Models\Shop\OrderStatus::Completed)
                        <a href="http://cs.ecqun.com/mobile/rand?id=1678907" class="small-4 chm-account-order-b">售后服务</a>
                    @elseif($currentOrder->status == \App\Models\Shop\OrderStatus::Shipping)
                        <a href="http://cs.ecqun.com/mobile/rand?id=1678907" class="small-4 chm-account-order-b">售后服务</a>
                        <a href="/customer/order/logistics/{{$currentOrder->id}}" class="small-4 chm-account-order-b">查看物流</a>
                        <a href="#" data-orderid="{{$currentOrder->id}}" class="small-4 chm-account-order-b chm-account-order-receipt chm-account-order-b-red">确认收货</a>
                    @elseif($currentOrder->status == \App\Models\Shop\OrderStatus::Checked)
                        <a href="http://cs.ecqun.com/mobile/rand?id=1678907" class="small-4 chm-account-order-b">售后服务</a>
                        <a href="#" data-orderid="{{$currentOrder->id}}" class="small-4 chm-account-order-b chm-account-delete-order">删除订单</a>
                    @endif
                </div>
            @endforeach
        </div>
        {{--退换货--}}
        <div class="content @if($type=="returns") active @endif" id="return-orders">
            <h3 class="">亲，如您需退换货，可以联系我们的售后客服：</h3>
            <a href="tel:4009005151" style="display: block;width:100%;padding: 0.625rem 0;"><i class="fa fa-phone"></i> 4009005151（点击拨打）</a>
            <a href="http://cs.ecqun.com/mobile/rand?id=1678907" class="small-4 chm-account-order-b button success">售后服务</a>
        </div>
        {{--全部订单--}}
        <div class="content @if($type=="all") active @endif" id="all-orders">
            @foreach($orders as $key=>$order)
            <div class="chm-account-order" data-orderid="{{$order->id}}">
                <a href="/customer/order/{{ $order->id }}">
                    <div class="panel chm-account-panel chm-account-order-content">
                    <div class="row chm-account-order-head">
                        <span class="left">订单编号：{{ $order->sn }}</span>
                        <span class="right">交易状态：{{ $order->orderStatus->description or '未知状态' }}@if($order->paid_status == 1 && $order->orderStatus->id == 1 ) {{ '(已支付)' }} @elseif($order->paid_status == 0 && $order->orderStatus->id == 1 ) {{ '(未支付)' }} @endif</span>
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
                                            <div style="position: absolute;bottom: 0.625rem;width: 100%;"><span class="left" style="margin-left: 0.3125rem;font-size:0.75rem;color:#989898;">分类: {{ $orderSku->product->productCategoryWithTrashed->name }} &nbsp;包装: {{ $orderSku->product->package }}</span>
                                                <span class="right" style="color: #C30E21"><i class="fa fa-rmb" aria-hidden="true"></i>{{ $orderSku->market_price }}</span>
                                            </div>
                                        </td>
                                    </tr>
                                    @if($key==1)
                                        @break @endif
                                @endforeach
                            </table>
                    </div>
                    <div class="row chm-account-order-total text-right">
                        共 <span style="color: #C30E21">{{ $order->product_quantity }}</span> 件商品，合计：<span style="color: #C30E21">{{ $order->total }}</span> 元
                    </div>
                </div>
                </a>
                <div class="row text-right panel" style="border:none;background-color:#F9F9F9;padding: 0.625rem 0.9375rem 0 0;">
                    @if($order->status == \App\Models\Shop\OrderStatus::Raw && $order->paid_status == 0)
                        <a href="/customer/order/cancel/{{$order->id}}" class="small-4 chm-account-order-b chm-account-cancel-order">取消订单</a>
                        <a href="/pay/orders/{{$order->id}}" class="small-4 chm-account-order-b chm-account-confirm-payment chm-account-order-b-red">确认付款</a>
                    @elseif($order->status == \App\Models\Shop\OrderStatus::Completed)
                        <a href="http://cs.ecqun.com/mobile/rand?id=1678907" class="small-4 chm-account-order-b">售后服务</a>
                    @elseif($order->status == \App\Models\Shop\OrderStatus::Shipping)
                        <a href="http://cs.ecqun.com/mobile/rand?id=1678907" class="small-4 chm-account-order-b">售后服务</a>
                        <a href="/customer/order/logistics/{{$order->id}}" class="small-4 chm-account-order-b">查看物流</a>
                        <a href="#" data-orderid="{{$order->id}}" class="small-4 chm-account-order-b chm-account-order-receipt chm-account-order-b-red">确认收货</a>
                    @elseif($order->status == \App\Models\Shop\OrderStatus::Checked)
                        <a href="http://cs.ecqun.com/mobile/rand?id=1678907" class="small-4 chm-account-order-b">售后服务</a>
                        <a href="#" data-orderid="{{$order->id}}" class="small-4 chm-account-order-b chm-account-delete-order">删除订单</a>
                    @elseif($order->status == \App\Models\Shop\OrderStatus::Cancelled)
                        <a href="#" data-orderid="{{$order->id}}" class="small-4 chm-account-order-b chm-account-delete-order">删除订单</a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @include('mobile.elements.modalConfirm')
@endsection

@section('script-file-bottom')
    <script type="text/javascript" src="/assets/js/mobile.customer.js"></script>
    <script type="text/javascript" src="/assets/js/mobile.shop.js"></script>
    <script type="text/javascript" src="/assets/js/utils.js"></script>
@endsection
