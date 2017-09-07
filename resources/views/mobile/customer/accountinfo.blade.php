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
    @include('mobile.elements.promptAlert')
    <div class="panel chm-account-info">
        <p>当前登录账号：@if(!empty($customer['phone'])){{ $customer['phone'] }}@endif </br>用户名：@if(!empty($customer['nick_name'])) {{ $customer['nick_name'] }} @else {{ $customer['user_name'] }} @endif</p>
    </div>
    @if(!is_wechat())
        <div class="panel chm-account-panel chm-account-resetpassword" style="padding: 0.625rem 1.250rem;">
            <a href="/customer/resetpassword">
                <div class="row">
                    <div class="small-11 columns">密码修改</div>
                    <div class="small-1 columns"></div>
                </div>
            </a>
        </div>
    @endif
    <div class="panel chm-account-panel chm-account-bindaccounts" style="padding: 0.625rem 1.250rem;">
        <div class="row">
            <div class="small-11 columns">
                <a href="/customer/resetpassword">绑定账号</a>
            </div>
            <div class="small-1 columns"></div>
        </div>
    </div>
@endsection

@section('script-file-bottom')
    <script type="text/javascript" src="/assets/js/mobile.customer.js"></script>
    <script type="text/javascript" src="/assets/js/utils.js"></script>
@endsection
