@extends('layouts.admin.auth')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-0">
                <div class="panel panel-info">
                    <div class="panel-heading text-center"><h3>欢迎登陆－verihuo管理平台</h3></div>
                    <div class="panel-body">
                        <form class="form-horizontal" role="form" method="POST" action="{{ action('Admin\Auth\AuthController@login') }}">
                            {!! csrf_field() !!}

                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label class="col-md-4 control-label">eMail</label>

                                <div class="col-md-6">
                                    <input type="email" class="form-control" name="email" value="{{ old('email') }}">

                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <label class="col-md-4 control-label">密码</label>

                                <div class="col-md-6">
                                    <input type="password" class="form-control" name="password">

                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="remember"> 记住我
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-10 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fa fa-btn fa-sign-in"></i>登陆
                                    </button>

                                    <a class="btn btn-link" href="{{ action('Admin\Auth\PasswordController@showResetForm') }}">忘记密码?</a>
                                    <!-- <a class="btn btn-link" href="{{  action('Admin\Auth\AuthController@showRegistrationForm') }}">申请管理员?</a> -->
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
