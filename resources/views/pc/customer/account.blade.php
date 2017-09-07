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
            <div class="customer-content-header">个人信息</div>
            <div class="customer-content-body">
                <div class="customer-info">
                    <span>当前登录账号:{{ $customer->getUserName() }}</span>
                    <span>用户名：@if(!empty($customer['nick_name'])) {{ $customer['nick_name'] }} @else {{ $customer['user_name'] }} @endif</span>
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
