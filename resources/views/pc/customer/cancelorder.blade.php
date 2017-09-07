@extends('layouts.shopapp')

@section('meta')
    <link href="/all" type="application/rss+xml" rel="alternate" title="robots" />
@endsection

@section('title')
@endsection

@section('content')
    <div class="customer container">
        @include('pc.customer.accountleft')
        <div class="pull-right customer-content">
            <div class="customer-content-header">订单取消</div>
            <div id="orderTabContent" class="tab-content">
                <div class="order-cancel">
                    <div style="overflow:hidden;" >
                        <span class="pull-left">订单编号：{{ $order->sn }}</span>
                        <span class="pull-right">交易状态：{{ $order->orderStatus->description }}</span>
                    </div>
                    <form  class="form-horizontal" role="form" name="formCancelOrder" method="post" action="/customer/order/update">
                        {!! csrf_field() !!}
                        <input name="orderId" type="hidden" value="{{$order->id}}" />
                        <input name="formAction" type="hidden" value="7" />
                        <div class="radio">
                            <label>
                                <input type="radio" name="cancelOrder" value="拍错商品" checked>拍错商品
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" name="cancelOrder" value="地址填写有误">地址填写有误
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" name="cancelOrder" value="我不想买了">我不想买了
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" name="cancelOrder" value="其它" style="margin-top: 35px;">其它
                                <input name="cancelOrderCause" value="" style="height: 40px;" />
                            </label>
                        </div>
                        <div class="radio">
                            <button type="submit" class="btn btn-default btn-lg active">提交</button>
                        </div>
                    </form>
                </div>
            </div>
            <a class="back-allOrders" href="/customer/order/search/all"><i class="fa fa-hand-o-left" aria-hidden="true"></i>&nbsp;&nbsp;返回</a>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript" src="/assets/js/pc.customer.js"></script>
@endsection
