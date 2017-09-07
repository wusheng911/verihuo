@extends('layouts.admin')

@section('title',' 课程管理')


@push('stylesheets')
<link media="all" type="text/css" rel="stylesheet" href="{{ asset('assets/libs/jquerycolorbox/colorbox.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('assets/libs/jquerycolorbox/jquery.colorbox-min.js') }}"></script>
<script src="{{ asset('assets/libs/elfinder/standalonepopup.js') }}"></script>
<script src="{{ asset('assets/admin/js/shop/brand/brand_create.js') }}"></script>
@endpush

@section('content')

    @if(empty($class))
        {!! Form::open(['url'=>action('Admin\ClassController@store'),'method'=>'post']) !!}
        <div class="form-group row">
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">基础选项</h3>
                    </div>
                    <div class="panel-body">


                        <div class="form-group row">
                            <label class="col-lg-2 control-label">课程名称</label>
                            <input type="text" class="col-lg-8 form-control-static" name="class[name]" placeholder="请输入" value="" >
                        </div>
                        <br>
                        <br>
                        <div class="form-group row">
                            <label class="col-lg-2 control-label">课程介绍</label>


                            <textarea class="col-lg-8 form-control-static" name="class[introduction]" rows="5" value=""></textarea>
                        </div>
                        <br>
                        <br>

                        <br>
                        <br>
                        <div class="form-group row">
                            <label class="col-lg-2 control-label">启用</label>
                            <input class="col-lg-3 form-control-static" name="class[status]" type="checkbox"
                                   value="1">
                        </div>
                        <br>
                    </div>
                </div>
                <input id="sureAndBack" type="submit" style="width:100%;" class="btn btn-lg btn-success" value="保存">
            </div>
        </div>

        {!! Form::close() !!}
    @else
        {!! Form::open(['url'=>action('Admin\ClassController@update',['id'=>$class->id]),'method'=>'put']) !!}
        <div class="form-group row">
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">基础选项</h3>
                    </div>
                    <div class="panel-body">
                        <div class="form-group row">
                            <label class="col-lg-2 control-label">课程名称</label>
                            <input type="text" class="col-lg-8 form-control-static" name="class[name]" placeholder="请输入" value="{{$class->name}}" >
                        </div>
                        <br>
                        <br>
                        <div class="form-group row">
                            <label class="col-lg-2 control-label">课程介绍</label>


                            <textarea class="col-lg-8 form-control-static" name="class[introduction]" rows="5" value="">{{$class->introduction}}</textarea>
                        </div>
                        <br>

                        <br>
                        <br>
                        <div class="form-group row">
                            <label class="col-lg-2 control-label">启用</label>
                            <input class="col-lg-3 form-control-static" name="class[status]" type="checkbox"
                                    {{ empty($class->status) ? "" : "checked" }}>
                        </div>
                        <br>
                    </div>
                </div>

                <input id="sureAndBack" type="submit" style="width:100%;" class="btn btn-lg btn-success" value="保存">
            </div>
        </div>
        {!! Form::close() !!}
    @endif
@endsection
