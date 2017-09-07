@extends('layouts.mobile', ['subnav' => true])

@section('meta')
    <link href="/all" type="application/rss+xml" rel="alternate" title="robots" />
@endsection


@section('title')
@endsection

@section('navigatorTop')
    @include('mobile.elements.navigatorTop2', ['title' => '会员中心'])
@endsection

@section('content')
    <div class="panel chm-account-name chm-account-panel" style="height:5.625rem;">
        <a href="/customer/info">
            <div class="row">
                <div class="small-11 columns">
                    <span>当前登录账号：@if(!empty($customer['phone'])){{ $customer['phone'] }}@endif</span></br>
                    <span>用户名：@if(!empty($customer['nick_name'])) {{ $customer['nick_name'] }} @else {{ $customer['user_name'] }} @endif</span>
                </div>
                <div class="small-1 columns"></div>
            </div>
        </a>
    </div>
    <div class="panel chm-account-panel" style="padding: 0.625rem 1.250rem;">
        <div class="row">
            <a href="">
                <div class="small-4 columns text-center chm-account-comments">
                    <i class="fa fa-commenting-o" aria-hidden="true"></i> 评论
                </div>
            </a>
            <a href="">
                <div class="small-4 columns text-center chm-account-Favorites">
                    <i class="fa fa-star-o" aria-hidden="true"></i> 收藏
                </div>
            </a>
            <a href="">
                <div class="small-4 columns text-center chm-account-news">
                    <i class="fa fa-envelope-o" aria-hidden="true"></i> 消息
                </div>
            </a>
        </div>
    </div>
    <div class="panel chm-account-panel" style="">
        <h3 style="border-bottom: 0.125rem solid #D6D6D4;padding-bottom: 0.625rem;">订单信息</h3>
        <div class="chm-account-content">
            <div class="row">
                <a href="/customer/order/search/current">
                    <div class="small-4 columns text-center chm-account-current-order">
                        <img src="/assets/img/current_order.png">
                        <p>当前订单</p>
                    </div>
                </a>
                <a href="/customer/order/search/returns">
                    <div class="small-4 columns text-center chm-account-returns">
                        <img src="/assets/img/returns.png">
                        <p>退换货</p>
                    </div>
                </a>
                <a href="/customer/order/search/all">
                    <div class="small-4 columns text-center chm-account-all-orders">
                        <img src="/assets/img/all_orders.png">
                        <p>全部订单</p>
                    </div>
                </a>
            </div>
            <div class="row">
                <a href="/shop/cart">
                    <div class="small-4 columns text-center chm-account-cart">
                        <img src="/assets/img/cart.png">
                        <p>购物车</p>
                    </div>
                </a>
                <a href="">
                    <div class="small-4 columns text-center chm-account-coupon">
                        <img src="/assets/img/coupon.png">
                        <p>优惠券</p>
                    </div>
                </a>
                <a href="/my/address">
                    <div class="small-4 columns text-center chm-account-delivery-address">
                        <img src="/assets/img/delivery_address.png">
                        <p>配送地址</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection

@section('navigatorBottom')
    @include('mobile.elements.navigatorBottom')
@endsection

@section('script-file-bottom')
    <script type="text/javascript" src="/assets/js/mobile.customer.js"></script>
@endsection
