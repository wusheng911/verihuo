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
            <h3 class="customer-content-header">个人信息</h3>
            <div class="customer-content-body">
                <div class="customer-info">
                    <h3>当前登录账号:@if(!empty($customer['phone'])){{ $customer['phone'] }}@endif</h3>
                    <h3>用户名：@if(!empty($customer['nick_name'])) {{ $customer['nick_name'] }} @else {{ $customer['user_name'] }} @endif</h3>
                </div>
                <div class="account-binding">
                    <ul>
                        <li class="binding"><i class="fa fa-phone" aria-hidden="true"></i>已绑定手机号：@if(!empty($customer['phone'])){{ $customer['phone'] }}@endif</li>
                        <li><i class="fa fa-envelope" aria-hidden="true"></i>绑定邮箱</li>
                        <li><i class="fa fa-qq" aria-hidden="true"></i>绑定QQ</li>
                        <li><i class="fa fa-weixin" aria-hidden="true"></i>绑定微信</li>
                        <li><i class="fa fa-weibo" aria-hidden="true"></i>绑定微博</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript" src="/assets/js/pc.customer.js"></script>
@endsection
