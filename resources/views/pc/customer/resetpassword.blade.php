@extends('layouts.shopapp')

@section('meta')
    <link href="/all" type="application/rss+xml" rel="alternate" title="robots" />
@endsection

@section('title')
@endsection

@section('content')

@include('elements.promptAlert')

<div style="background-color:#fff;height:550px;margin:0 auto;width:1300px;margin:auto;clear:both;">
  <div style="height:50px;background-color:#fff;display:block;">&nbsp;</div> 
  <div class="coLogin_box">
    <form id="resetpassword_form" name="formCustomer" method="post" action="/customer/resetpassword" onsubmit="return mobile_resetpassword();">
    {!! csrf_field() !!}
    <div class="coLogin_header">
      &nbsp;
    </div>
    <div class="coLogin_one" style="clear:both;">
      <input name="mobile_number" type="text" class="coLogin_txt" size="17"
        accept=""style="height:35px; width:260px;font-size:14px;" 
        label="false" placeholder="请输入11位手机号码" />
      <span id="username_notice" style="color:#ff4560;"></span> 
    </div>
    <div class="coLogin_one" id="zmobile_code">
        <input name="mobile_code" class="coLogin_txt" type="text" 
            size="8" style="height:35px;width:80px;display:inline;font-size:14px;"
            label="false" placeholder="验证码" /> 
        <input id="zphone" name="zphone" type="button" value="发送验证码" style="color:#4c4c4c;height:35px; width:163px;margin-left:7px;display:inline;" />  
        <span style="color:#ff4560;line-height:35px;" id="mobile_code_notice"></span> 
    </div>
    <div class="coLogin_one">
        <input name="user_password" class="coLogin_txt" type="password" 
            style="height:35px; width:260px;font-size:14px;"
            label="false" placeholder="请输入6-18位字母数字组合密码" /> 
        <span style="color:#ff4560;" id="password_notice"></span> 
    </div>
    <div class="coLogin_one">
        <input name="confirm_password" type="password" style="height:35px; width:260px;font-size:14px;"
            label="false" placeholder="请再次输入密码" /> 
    </div>
    <div class="coLogin_three">
      <input type="submit" value="完成" style="float:left;display:inline;" class="biLogin_btn" name="Submit"/>
    </div>
    </form>
  </div>
</div>
  <div class="mid_seperator" style="height:20px;"></div>
</div>

@endsection

@section('scripts')
    <script type="text/javascript" src="/assets/libs/jquery-confirm/dist/jquery-confirm.min.js"></script>
    <script type="text/javascript" src="/assets/js/pc.customer.js"></script>
    <script type="text/javascript" src="/assets/js/utils.js"></script>
@endsection

