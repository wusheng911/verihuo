@extends('layouts.mobile', ['subnav' => true])

@section('meta')
    <link href="/all" type="application/rss+xml" rel="alternate" title="robots" />
@endsection


@section('title')
@endsection

@section('navigatorTop')
    @include('mobile.elements.navigatorTop2', ['title' => '注册'])
@endsection


@section('content')

<style> 
    input[type="text"], input[type="password"], textarea {
        margin: 0 0 0.675rem;
    }
</style>

@include('mobile.elements.promptAlert')


<div class="row">
    <div class="small-12" style="height:25px;"></div>
</div>


<form id="register_form" name="formCustomer" method="post" action="/customer/register" onsubmit="return mobile_register();">
    {!! csrf_field() !!}
    <div class="row">
        <div class="small-2 columns"><span>&nbsp;</span></div>
        <div class="small-10 columns text-left">
            <a href="/customer/login">有帐号, 立即登录</a>
        </div>
    </div>
    <div class="row collapse">
        <div class="small-2 columns"><span>&nbsp;</span></div>
        <div class="small-2 columns">
            <span class="prefix"><i class="fa fa-phone"></i></span>
        </div>
        <div class="small-6 columns">
            <input name="mobile_number" type="text"
                style="width:100%;" label="false" placeholder="请输入手机号码" />
        </div>
        <div class="small-2 columns">
            <img id="mobile_number_notice_yes" style="margin-top:5px;margin-left:8px;width:1.2rem;display:none;" src="/assets/img/check-icon.png"/>
            <img id="mobile_number_notice_no" style="margin-top:5px;margin-left:8px;width:1.2rem;display:none;" src="/assets/img/delete-icon.png"/>
        </div>
    </div>
    <div class="row collapse">
        <div class="small-2 columns">&nbsp;</div>
        <div class="small-2 columns">
            <span class="prefix"><i class="fa fa-key"></i></span>
        </div>
        <div class="small-6 columns">
            <input name="user_password" type="password" style="width:100%;"
                label="false" placeholder="密码为6-20位字符" />            
        </div>
        <div class="small-2 columns">
            <img id="user_password_notice_yes" style="margin-top:5px;margin-left:8px;width:1.2rem;display:none;" src="/assets/img/check-icon.png"/>
            <img id="user_password_notice_no" style="margin-top:5px;margin-left:8px;width:1.2rem;display:none;" src="/assets/img/delete-icon.png"/>
        </div>
    </div>
    <div class="row collapse">
        <div class="small-2 columns">&nbsp;</div>
        <div class="small-2 columns">
            <span class="prefix"><i class="fa fa-key"></i></span>
        </div>
        <div class="small-6 columns">
            <input name="confirm_password" type="password" style="width:100%;"
                label="false" placeholder="请再次输入密码" />              
        </div>
        <div class="small-2 columns">
            <img id="confirm_password_notice_yes" style="margin-top:5px;margin-left:8px;width:1.2rem;display:none;" src="/assets/img/check-icon.png"/>
            <img id="confirm_password_notice_no" style="margin-top:5px;margin-left:8px;width:1.2rem;display:none;" src="/assets/img/delete-icon.png"/>
        </div>          
    </div>
    <div class="row collapse">
        <div class="small-2 columns">&nbsp;</div>
        <div class="small-2 columns">
            <span class="prefix"><i class="fa fa-user"></i></span>
        </div>
        <div class="small-6 columns">
            <input name="user_name" type="text" style="width:100%;"
                label="false" placeholder="请设置用户名" />              
        </div>
        <div class="small-2 columns">
            <img id="user_name_notice_yes" style="margin-top:5px;margin-left:8px;width:1.2rem;display:none;" src="/assets/img/check-icon.png"/>
            <img id="user_name_notice_no" style="margin-top:5px;margin-left:8px;width:1.2rem;display:none;" src="/assets/img/delete-icon.png"/>
        </div>            
    </div>
    <div class="row collapse">
        <div class="small-2 columns">&nbsp;</div>
        <div class="small-2 columns">
            <span class="prefix"><i class="fa fa-envelope-o"></i></span>
        </div>
        <div class="small-6 columns">
            <input name="email" type="text" style="width:100%;"
                label="false" placeholder="请填写邮箱" />              
        </div>
        <div class="small-2 columns">
            <img id="email_notice_yes" style="margin-top:5px;margin-left:8px;width:1.2rem;display:none;" src="/assets/img/check-icon.png"/>
            <img id="email_notice_no" style="margin-top:5px;margin-left:8px;width:1.2rem;display:none;" src="/assets/img/delete-icon.png"/>
        </div>            
    </div>
    <div class="row collapse">
        <div class="small-2 columns">&nbsp;</div>
        <div class="small-2 columns">
            <span class="prefix"><i class="fa fa-shield"></i></span>
        </div>
        <div class="small-6 columns">
            <div class="row collapse">
                <div class="small-5 columns">
                    <input name="mobile_code" type="text" style="width:100%;"
                        label="false" placeholder="验证码" />                
                </div>
                <div class="small-7 columns">
                    <input id="zphone" name="zphone" type="button" class="button tiny" 
                        style="float:right;width:90%;padding:10px 12px 10px 12px;height:2.4375rem;" 
                        value="发送验证码" />  
                </div>
            </div>                
        </div>
        <div class="small-2 columns"><span>&nbsp;</span></div>
    </div>
    <div class="row collapse">
        <div class="small-2 columns">&nbsp;</div>
        <div class="small-8 columns">
            <input type="submit" style="width:100%;padding:10px 0;margin-bottom:0.5rem;" class="button" name="Submit" value="注 册"/>
        </div>
        <div class="small-2 columns" style="padding-left:0;">&nbsp;</div>
    </div> 
</form>
<div class="row collapse">
    <div class="small-2 columns"><span>&nbsp;</span></div>
    <div class="small-10 columns">
    <input id="chm_agreement" name="agreement" type="checkbox" checked="checked" style="vertical-align:middle;margin-bottom:0rem;">
    <label for="chm_agreement" style="vertical-align:middle;margin-left:0;margin-right:0;font-size:0.875rem;">
        我已阅读并接受《<a href="/customer/agreement" style="color:#3990e6;">用户协议</a>》             
    </label>  
    </div>
</div>  

@endsection


@section('script-file-bottom')
    <script type="text/javascript" src="/assets/js/mobile.customer.js"></script>
    <script type="text/javascript" src="/assets/js/utils.js"></script>
@endsection
