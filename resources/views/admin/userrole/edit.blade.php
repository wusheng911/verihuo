@extends('layouts.admin')
@section('title', '关联 '.$user->name.' 用户角色')

    @push('stylesheets')
    <style>

    </style>
    @endpush

    @push('scripts')
    
    <script src="{{ asset('assets/admin/js/userrole_edit.js') }}"></script>
    @endpush

    @section('content')
        @if ($user->id == null)
            {!! Form::open(['url' => action('Admin\UserRoleController@store'), 'method' => 'post']) !!}
        @else
            {!! Form::open(['url' => action('Admin\UserRoleController@update', ['id'=>$user->id]), 'method' => 'put']) !!}
        @endif
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="row">
            <div class="col-lg-12" >
                <div class="panel panel-default">
                    <div class="panel-heading"> <h3 class="panel-title text-center">用户信息</h3> </div>
                    <div class="panel-body form-horizontal">
                        <div class="form-group">
                            <label class="col-lg-2 control-label">用户名</label>
                            <input class="col-lg-9 form-control-static" disabled name="role[name]" type="text" value="{{$user->name}}" placeholder="请输入角色识别名称" aria-describedby="basic-addon1">
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">用户邮箱</label>
                            <input class="col-lg-9 form-control-static" disabled name="role[description]" type="text" value="{{$user->email}}" placeholder="请输入角色描述信息" aria-describedby="basic-addon1">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title text-center">角色选择</h3>
            </div>

            <div class="panel-body form-horizontal">
                <div class="row" >
                    @foreach ($roles as $role)

                        <label class="col-lg-2 control-label">{{ $role->display_name }}</label>
                        <input class="col-lg-1 form-control-static" name="user_roles[]" type="checkbox"
                               {{ in_array($role->id, $checks) ? "checked" : "" }}
                               value="{{ $role->id }}" aria-describedby="basic-addon1">
                    @endforeach
                </div>
            </div>
        </div>
        <div class="row" >
            <div class="col-md-4" ></div>
            <input hidden="true" id="backtype" class="col-lg-9 form-control-static" name="other[backtype]" type="text" value="0" aria-describedby="basic-addon1">

            <div class="col-md-4" >
                <input id="sureAndBackList" onmousedown="onMouseDown()" type="submit" style="width:100%;" class="btn btn-lg btn-success" value="保存并返回权限列表">
            </div>
            <div class="col-md-4" >
                
                <input id="sureAndBack" onmousedown="onMouseDown()" type="submit" style="width:100%;" class="btn btn-lg btn-success" value="保存">
            </div>
        </div>
        {!! Form::close() !!}
    @endsection
