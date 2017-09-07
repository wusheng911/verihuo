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
    @include('mobile.elements.navigatorTop2', ['title' => '取消订单'])
@endsection

@section('content')
    <div class="panel chm-account-name chm-account-panel" style="height:auto;font-size: 0.75rem;line-height: 0px;">
        <span class="left">订单编号：{{ $order->sn }}</span>
        <span class="right">交易状态：{{ $order->orderStatus->description }}</span>
    </div>
    <div class="panel chm-account-panel" style="">
        <form id="" name="formCancelOrder" method="post" action="/customer/order/update">
            {!! csrf_field() !!}
            <input name="orderId" type="hidden" value="{{$order->id}}" />
            <input name="formAction" type="hidden" value="7" />
            <div class="row">
                <div class="small-1 columns">
                    <input name="cancelOrder" type="radio" value="拍错商品" />
                </div>
                <div class="small-11 columns">拍错商品</div>
            </div>
            <div class="row">
                <div class="small-1 columns">
                    <input name="cancelOrder" type="radio" value="地址填写有误" />
                </div>
                <div class="small-11 columns">地址填写有误</div>
            </div>
            <div class="row">
                <div class="small-1 columns">
                    <input name="cancelOrder" type="radio" value="我不想买了" />
                </div>
                <div class="small-11 columns">我不想买了</div>
            </div>
            <div class="row">
                <div class="small-1 columns">
                    <input name="cancelOrder" type="radio" value="其它" />
                </div>
                <div class="small-2 columns">其它</div>
                <div class="small-9 columns">
                    <input name="cancelOrderCause" value=""/>
                </div>
            </div>
            <div class="row text-center">
                <button type="submit" class="small-9">提交</button>
            </div>
        </form>
    </div>
@endsection

@section('navigatorBottom')
    @include('mobile.elements.navigatorBottom')
@endsection

@section('script-file-bottom')
    <script type="text/javascript" src="/assets/js/mobile.customer.js"></script>
@endsection
