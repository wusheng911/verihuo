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
    @include('mobile.elements.navigatorTop2', ['title' => '登录'])
@endsection


@section('content')

    @include('mobile.elements.promptAlert')

    @if(\App\Services\Helpers::isWechat())
        
        <div class="row text-center" style="height:100%">
            <br/><br/>
            <a href="weixin://contacts/profile/wx95827c7ac1203843">点击</a>
            <h3><a id="login-btn" href="/customer/login">点击登陆(<span id="seconds">5</span>)</a></h3>
        </div>
        @section('script-file-bottom')
            <script type="text/javascript" >
             $(document).ready(function(){
                 function lp(){
                     var seconds = $("#seconds").html();
                     if($("#seconds").html() < 1){
                         window.location.href = "/customer/login";
                     }else{
                         $("#seconds").html(seconds - 1);
                         setTimeout(lp,1000) 
                     }
                 }
                 setTimeout(lp,1000) 

             });
            </script>
        @endsection
    @else
        <div class="row">
            <div class="small-12" style="height:30px;"></div>
            <div class="small-10 small-centered large-centered columns">
                <form id="login_form" name="formCustomer" action="/customer/login" method="post">
                    {!! csrf_field() !!}
                    <div class="row log-in-form">
                        <div class="row collapse">
                            <div class="small-2 columns">
                                <span class="prefix"><i class="fa fa-user"></i></span>
                            </div>
                            <div class="small-10 columns">
                                <input name="login_name" type="text" style="width:100%"
                                       label="false" placeholder="手机号/邮箱/用户名"/>
                            </div>
                        </div>
                        <div class="row collapse">
                            <div class="small-2 columns">
                                <span class="prefix"><i class="fa fa-key"></i></span>
                            </div>
                            <div class="small-10 columns">
                                <input name="user_password" type="password" style="width:100%;"
                                       label="false" placeholder="请输入密码"/>
                            </div>
                        </div>          
                        <div class="row collapse">
                            <div class="small-12 columns">
                                <input style="width:100%;padding:10px 0;" type="submit" class="button" name="Submit" value="登 录"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="small-6 column" style="padding-right:0;">
                                <input id="remember_me" type="checkbox" checked="checked"><label style="vertical-align:top;margin-right:0;">记住登录信息</label>  
                            </div>
                            <div class="small-6 column" style="padding-left:0;padding-right:0;">
                                <label class="text-right"><a href="/customer/register">免费注册</a> | <a href="/customer/resetpassword">找回密码</a></label>  
                            </div>           
                        </div>

                        <div class="row collapse">
                            <div class="small-2 columns"><span>&nbsp;</span></div>
                            <div class="small-8 columns">
                                <ul class="small-block-grid-2">
                                    <li class="name text-center">
                                        <a href="https://graph.qq.com/oauth2.0/authorize?response_type=code&client_id=101303225&redirect_uri=http://www.51chaohun.com/members/login/channel/qq">
                                            <img style="width:3rem;height:3rem;" src="/assets/img/qq.png"/>
                                        </a>
                                    </li>
                                    <!--
                                         <li class="name text-center">
                                         <a href="https://open.weixin.qq.com/connect/qrconnect?appid=wx397120b793e960d5&redirect_uri=http://www.51chaohun.com/members/login/channel/weixin&response_type=code&scope=snsapi_login&state=STATE#wechat_redirect">
                                         <img style="width:3rem;height:3rem;"src="/assets/img/weixin_friend.png"/>
                                         </a>
                                         </li>
                                       -->
                                </ul>            
                            </div>
                            <div class="small-2 columns"><span>&nbsp;</span></div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    @endif
@endsection

@section('script-file-bottom')
    <script type="text/javascript" src="/assets/js/mobile.customer.js"></script>
    <script type="text/javascript" src="/assets/js/utils.js"></script>
@endsection
