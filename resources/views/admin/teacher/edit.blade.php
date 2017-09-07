@extends('layouts.admin')

@section('title','老师管理')


@push('stylesheets')
<link media="all" type="text/css" rel="stylesheet" href="{{ asset('assets/libs/jquerycolorbox/colorbox.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('assets/libs/jquerycolorbox/jquery.colorbox-min.js') }}"></script>
<script src="{{ asset('assets/libs/elfinder/standalonepopup.js') }}"></script>
<script src="{{ asset('assets/admin/js/shop/brand/brand_create.js') }}"></script>


<link rel="stylesheet" type="text/css" href="/assets/libs/uploadifive/Huploadify.css"/>

<script type="text/javascript" src="/assets/libs/uploadifive/jquery.Huploadify.js"></script>
<style type="text/css">
</style>
<script type="text/javascript">
    $(function(){
        $('#upload').Huploadify({
            auto:true,
            fileTypeExts:'*.jpg;*.png;*.gif',
            multi:true,
            formData:{key:123456,key2:'vvvv',_token:"{{csrf_token()}}"},
            fileSizeLimit:9999,
            showUploadedPercent:true,//是否实时显示上传的百分比，如20%
            showUploadedSize:true,
            removeTimeout:9999999,
            uploader:"{{url('admin/upimgteacher')}}",
            onUploadStart:function(){

            },
            onInit:function(){

            },
            'onUploadSuccess' : function(file, data, response) {

                $('input[name="teacher[portrait]"]').val(data);
                $('#thumb').attr('src',data);
                alert('图片上传成功');
            },
            onDelete:function(file){
                console.log('删除的文件：'+file);
                console.log(file);
            }
        });
    });

function isEmpty(){
    var x = document.getElementById("portrait").value;

if(trim(x)==""){
alert("portrait不能为空!");
return false;
}
}
</script>


@endpush

@section('content')

    @if(empty($teacher))
        {!! Form::open(['url'=>action('Admin\TeacherController@store'),'method'=>'post', 'name'=>'form1']) !!}


        <div class="row form-group">
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">基础选项</h3>
                    </div>
                    <div class="panel-body">


                        <div class="row form-group">
                            <label class="col-lg-2 control-label">老师名称</label>
                            <input type="text" class="col-lg-8 form-control-static" name="teacher[name]" placeholder="请输入" value="" >
                        </div>
                        <br>


                        <div class="row form-group">
                            <label class="col-lg-2 control-label">老师电话</label>
                            <input type="text" class="col-lg-8 form-control-static" name="teacher[phone]" placeholder="请输入" value="" >
                        </div>
                        <br>
                        <div class="row form-group">
                            <label class="col-lg-2 control-label">老师邮件</label>
                            <input type="text" class="col-lg-8 form-control-static" name="teacher[email]" placeholder="请输入" value="" >
                        </div>
                        <br>
                        <div class="row form-group">
                            <label class="col-lg-2 control-label">老师密码</label>
                            <input type="text" class="col-lg-8 form-control-static" name="teacher[password]" placeholder="请输入" value="" >
                        </div>
                        <br>


                        <div class="row form-group">
                            <label class="col-lg-2 control-label">头像</label>
                            <input type="text" class="col-lg-8 form-control-static" name="teacher[portrait]" id="portrait" value="" style="display:none">
                            <input type="hidden" name="userid" value="<?php $a=Auth::guard('admin')->user();print_r( $a->id );?>">

                        </div>


                        <div class="row">
                            <div class="col-lg-4"><div id="upload"></div></div>
                            <div class="col-lg-2">
                                <img src="" id="thumb" style="max-height: 100px;max-width:100px" />
                            </div>

                        </div>


                        <br>
                        <br>
                        <div class="row form-group">
                            <label class="col-lg-2 control-label">老师介绍</label>


                            <textarea class="col-lg-8 form-control-static" name="teacher[introduction]" rows="5" value=""></textarea>
                        </div>
                        <br>
                        <br>
                        <div class="row form-group">
                            <label class="col-lg-2 control-label">是否master</label>
                            <input class="col-lg-3 form-control-static" name="teacher[level]" type="checkbox"
                                    {{ empty($teacher->level) ? "" : "checked" }}>
                        </div>



                        <br>
                    </div>
                </div>
                <input id="sureAndBack" type="submit" style="width:100%;" class="btn btn-lg btn-success" value="保存" onclick="isEmpty()">
            </div>
        </div>



        {!! Form::close() !!}
    @else
        {!! Form::open(['url'=>action('Admin\TeacherController@update',['id'=>$teacher->id]),'method'=>'put']) !!}
        <div class="form-group row">
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">基础选项</h3>
                    </div>
                    <div class="panel-body">
                        <div class="form-group row">
                            <label class="col-lg-2 control-label">老师名称</label>
                            <input type="text" class="col-lg-8 form-control-static" name="teacher[name]" placeholder="请输入" value="{{$teacher->name}}">

                        </div>
			


                        <div class="form-group row">
                            <label class="col-lg-2 control-label">老师电话</label>
                            <input type="text" class="col-lg-8 form-control-static" name="teacher[phone]" placeholder="请输入" value="{{$teacher->phone}}">

                        </div>

                        <div class="form-group row">
                            <label class="col-lg-2 control-label">老师邮件</label>
                            <input type="text" class="col-lg-8 form-control-static" name="teacher[email]" placeholder="请输入" value="{{$teacher->email}}">

                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 control-label">老师密码</label>
                            <input type="text" class="col-lg-8 form-control-static" name="teacher[password]" placeholder="不修改请留空" value="">

                        </div>




                        <div class="form-group row">
                            <label class="col-lg-2 control-label">头像</label>



                            <input type="text" class="col-lg-8 form-control-static" name="teacher[portrait]"  value="{{$teacher->portrait}}" id="portrait" style="display:none">
                            <input type="hidden" name="userid" value="<?php $a=Auth::guard('admin')->user();print_r( $a->id );?>">


                        </div>
                        <br>

                        <div class="row">
                            <div class="col-lg-4"><div id="upload"></div></div>
                            <div class="col-lg-2">
                                <img src="{{$teacher->portrait}}" id="thumb" style="max-height: 100px;max-width:100px" />
                            </div>

                        </div>
                        <br>
                        <div class="form-group row">
                            <label class="col-lg-2 control-label">老师介绍</label>


                            <textarea class="col-lg-8 form-control-static" name="teacher[introduction]" rows="5" value="">{{$teacher->introduction}}</textarea>
                        </div>
                        <br>

                        <div class="form-group row">
                            <label class="col-lg-2 control-label">是否master</label>
                            <input class="col-lg-3 form-control-static" name="teacher[level]" type="checkbox"
                                    {{ empty($teacher->level) ? "" : "checked" }}>
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
