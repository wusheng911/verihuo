@extends('layouts.shopapp')

@section('meta')
    <link href="/all" type="application/rss+xml" rel="alternate" title="robots" />
@endsection

@section('title')
@endsection

@section('content')

@include('elements.promptAlert')

<div style="background-color:#fff;width:1300px;margin:auto;clear:both;border-bottom: 1px solid #CCC;padding-top:50px;padding-bottom:50px;">
  <div class="form-main-list">
    <div class="coLogin_box">
      <form id="login_form" name="formCustomer" action="/customer/login" method="post">
      {!! csrf_field() !!}
      <div class="coLogin_header">
        <span style="float:left;color:#4c4c4c;">如有帐号, 请登录</span>
      </div>
      <div class="coLogin_one">
        <input name="login_name" type="text" class="coLogin_txt" autofocus="true"
            style="height:35px;font-size:14px;" size="17" label="false" 
            placeholder="手机号/邮箱/用户名"/>
      </div>  
      
      <div class="coLogin_one">
        <input name="user_password" class="coLogin_txt" type="password" 
          style="height:35px;font-size:14px;" size="17" 
          label="false" placeholder="请输入密码"/>
      </div>
      <div class="coLogin_three">
        <input type="submit" value="登录" style="float:left;display:inline;width:120px;" class="biLogin_btn" name="Submit"/>
      </div>
      <div class="coLogin_two" style="width:100%;"> 
        <input name="remember_me" type="checkbox" class="checkbox_txt" style="margin-top:3px;"/>
        保存登录信息   
        <div style="float:right">
          <a style="color:#36c;" href="/customer/resetpassword">忘记密码</a>&nbsp;|
          <a style="color:#36c;" href="/customer/register">免费注册</a>
        </div>
        <?php $qq_url="https://graph.qq.com/oauth2.0/authorize?response_type=code&client_id=101303225&redirect_uri=http://www.51chaohun.com/members/login/channel/qq" ;
          $weixin_url = "https://open.weixin.qq.com/connect/qrconnect?appid=wx397120b793e960d5&redirect_uri=http://www.51chaohun.com/members/login/channel/weixin&response_type=code&scope=snsapi_login&state=STATE#wechat_redirect"; ?>
      </div>      
      <a href="<?= $qq_url; ?>" class="connect-qq"><span icon-bg2="icon_qq_n"></span></a>
      <a href="<?= $weixin_url; ?>" class="connect-weixin"><span icon-bg3="icon_weixin_n"></span></a>
      </form>
    </div>
	</div>
</div>

@endsection

