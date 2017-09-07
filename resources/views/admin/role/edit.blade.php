@extends('layouts.admin')
@section('title', '编辑 '.$role->name.' 权限')

    @push('stylesheets')
    <style>

    </style>
    @endpush

    @push('scripts')
    
    <script src="{{ asset('assets/admin/js/role_edit.js') }}"></script>
    @endpush

    @section('content')
        @if ($role->id == null)
            {!! Form::open(['url' => action('Admin\RoleController@store'), 'method' => 'post']) !!}
        @else
            {!! Form::open(['url' => action('Admin\RoleController@update', ['id'=>$role->id]), 'method' => 'put']) !!}
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
                    <div class="panel-heading"> <h3 class="panel-title text-center">角色信息</h3> </div>
                    <div class="panel-body form-horizontal">
                        <div class="form-group">
                            <label class="col-lg-2 control-label">识别名称</label>
                            @if ($errors->has('email'))
                                <input class="col-lg-9 form-control-static" name="role[name]" type="text" value="{{ old('role[name]') }}" placeholder="请输入角色识别名称" aria-describedby="basic-addon1">
                                <span class="help-block">
                                    <strong>{{ $errors->first('role[name]') }}</strong>
                                </span>
                            @else
                                <input class="col-lg-9 form-control-static" name="role[name]" type="text" value="{{$role->name}}" placeholder="请输入角色识别名称" aria-describedby="basic-addon1">
                            @endif
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">名称</label>
                            <input class="col-lg-9 form-control-static" name="role[display_name]" type="text" value="{{$role->display_name}}" placeholder="请输入角色名" aria-describedby="basic-addon1">
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">角色说明</label>
                            <input class="col-lg-9 form-control-static" name="role[description]" type="text" value="{{$role->description}}" placeholder="请输入角色描述信息" aria-describedby="basic-addon1">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title text-center">权 限</h3>
            </div>

            <div class="panel-body form-horizontal">
                <div class="row" >
                    @foreach ($perms as $perm)

                        <label class="col-lg-2 control-label">{{ $perm->display_name }}</label>
                        <input class="col-lg-1 form-control-static" name="role_perms[]" type="checkbox"
                               {{ in_array($perm->id, $checks) ? "checked" : "" }}
                               value="{{ $perm->id }}" aria-describedby="basic-addon1">
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
