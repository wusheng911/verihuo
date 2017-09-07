@extends('layouts.app')

@section('content')
<div class='oauth-message'>
<p class="oauth-message-p">{{$message or '请填入注册信息!'}}</p>
</div>
<div class='oauth-container'>
<div class='oauth-left'>
		<div class='img-container'>
            <img class='img-element' src="{{$headimgurl or ''}}">
		</div>
		<p class="text-state" >亲爱的用户</p>
		<p class="text-state">您好!</p>
</div>
<div class='oauth-right'>
	<div class="right-content">
			<p class="text-title-state">注册登录 一步之遥</p>
			<p class="text-descript-state">快速创建verihuo帐号</p>
		{!! Form::open(['url' =>'customer/authregister','method'=>'post'])	 !!}	
			<div class="form-content">
                <div id="registerAlert" class='phone-register-alert phone-register-alert-none'>
                    该号码已注册，提交后会合并已注册信息
                </div>
				<div class="form-row" style="display:none">
					<label class="row-label">渠道:</label>
					<div class="form-input-container">
                        <input	class="form-input" value="{{$channel or ''}}" name="channel">
					</div>
				</div>
				<div class="form-row" style="display:none">
					<label class="row-label">头像:</label>
					<div class="form-input-container">
                        <input	class="form-input" value="{{$headimgurl or ''}}" name="headimgurl">
					</div>
				</div>
				<div class="form-row">
					<label class="row-label">姓名:</label>
					<div class="form-input-container">
                        <input	class="form-input" value="{{$userInfo['user_name'] or ''}}" name="user_name">
					</div>
				</div>
				<div class="form-row">
					<label class="row-label">手机号码:</label>
					<div class="form-input-container">
						<input	class="form-input" maxlength="11" value="{{$userInfo['mobile_number'] or ''}}" name="mobile_number">
					</div>
				</div>
				<div class="form-row">
					<label class="row-label">设置密码:</label>
					<div class="form-input-container password-icon-container">
                        <i id="password-icon-true" class="fa fa-eye password-icon" aria-hidden="false"></i>
                        <i id="password-icon-false" class="fa fa-eye-slash password-icon password-icon-hidden" aria-hidden="false"></i>
						<input id="password-input" class="form-input" value="{{$userInfo['user_password'] or ''}}" type="password" name="user_password">
					</div>
				</div>
				<div class="form-row">
					<label class="row-label">邮箱:</label>
					<div class="form-input-container">
						<input class="form-input" value="{{$userInfo['email'] or ''}}" name="email">
					</div>
				</div>
				<div class="form-row">
					<label class="row-label">验证码:</label>
					<div class="form-input-container">
						<input class="form-identifying-code" name="mobile_code">
						<input id="zphone" name="zphone" type="button"  value="获取验证码" class="form-get-indentifying-code" >
					</div>
				</div>

				<div>
						<input class="form-submit" type="submit" value="注册并绑定帐号">
				</div>
				<div class="form-agreement"> 
						<input name="agreement" type="checkbox" value="1" checked="checked"  class="" style="margin:3px;"/>
						<b>我已看过并接受《<a target="_blank" style="color:#36c;" href="{{'/view/article/'.config('settings.customer_agreement')}}">用户协议</a>》</b>
				</div>
			</div>	
		{!! Form::close() !!}
	</div>
</div>
</div>

@endsection

@section('scripts')
		<script type="text/javascript" src="/assets/libs/jquery-confirm/dist/jquery-confirm.min.js"></script>
		<script type="text/javascript" src="/assets/js/pc.customer.js"></script>
		<script type="text/javascript" src="/assets/js/oauth.login.js"></script>
		<script type="text/javascript" src="/assets/js/utils.js"></script>
@endsection
