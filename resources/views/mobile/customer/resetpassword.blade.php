@extends('layouts.mobile', ['subnav' => true])

@section('meta')
    <link href="/all" type="application/rss+xml" rel="alternate" title="robots" />
@endsection


@section('title')
@endsection

@section('navigatorTop')
    @include('mobile.elements.navigatorTop2', ['title' => '忘记密码'])
@endsection


@section('content')

@include('mobile.elements.promptAlert')

<div class="row">
  <div class="small-12" style="height:25px;"></div>
</div>
    <form id="resetpassword_form" name="formCustomer" method="post" action="/customer/resetpassword" onsubmit="return mobile_resetpassword();">
      {!! csrf_field() !!}
      <div class="row">
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
          <div class="small-2 columns"><span>&nbsp;</span></div>
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
                    style="float:right;width:90%;padding:10px 12px 10px 12px;margin-bottom:0;height:2.4375rem;" 
                    value="发送验证码" />  
              </div>
            </div>                
          </div>
          <div class="small-2 columns"></div>
        </div>        
        <div class="row collapse">
          <div class="small-2 columns"><span>&nbsp;</span></div>
          <div class="small-2 columns">
            <span class="prefix"><i class="fa fa-key"></i></span>
          </div>
          <div class="small-6 columns">
            <input name="user_password" type="password" style="width:100%;"
                   label="false" placeholder="请输入6-18位字母数字组合密码" />            
          </div>
          <div class="small-2 columns">
            <img id="user_password_notice_yes" style="margin-top:5px;margin-left:8px;width:1.2rem;display:none;" src="/assets/img/check-icon.png"/>
            <img id="user_password_notice_no" style="margin-top:5px;margin-left:8px;width:1.2rem;display:none;" src="/assets/img/delete-icon.png"/>
          </div>
        </div>
        <div class="row collapse">
          <div class="small-2 columns"><span>&nbsp;</span></div>
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
          <div class="small-2 columns"><span>&nbsp;</span></div>
          <div class="small-8 columns">
            <input type="submit" style="width:100%;padding:10px 0;margin-top:0.675rem;margin-bottom:0.5rem;" class="button" name="Submit" value="完成"/>
          </div>
          <div class="small-2 columns"><span>&nbsp;</span></div>
        </div> 
      </div>
    </form>
@endsection


@section('script-file-bottom')
    <script type="text/javascript" src="/assets/js/mobile.customer.js"></script>
    <script type="text/javascript" src="/assets/js/utils.js"></script>
@endsection
