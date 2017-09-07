@extends('layouts.admin')

@section('title','学校管理')


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
            uploader:"{{url('admin/upimg')}}",
            onUploadStart:function(){

            },
            onInit:function(){

            },
            'onUploadSuccess' : function(file, data, response) {

                $('input[name="school[logo]"]').val(data);
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
    var x = document.getElementById("logo").value;

if(trim(x)==""){
alert("logo不能为空!");
return false;
}
}
</script>


@endpush

@section('content')

    @if(empty($school))
        {!! Form::open(['url'=>action('Admin\SchoolController@store'),'method'=>'post', 'name'=>'form1']) !!}
        <div class="form-group row">
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">基础选项</h3>
                    </div>
                    <div class="panel-body">


                        <div class="form-group row">
                            <label class="col-lg-2 control-label">学校名称</label>
                            <input type="text" class="col-lg-8 form-control-static" name="school[name]" placeholder="请输入" value="" >
                        </div>
                        <br>


                        <div class="form-group row">
                            <label class="col-lg-2 control-label">Logo</label>



                            <input type="text" class="col-lg-8 form-control-static" name="school[logo]" id="logo" value="" style="display:none">
                            <input type="hidden" name="userid" value="<?php $a=Auth::guard('admin')->user();print_r( $a->id );?>">


                        </div>
                        <br>

                        <div class="row">
                            <div class="col-lg-4"><div id="upload"></div></div>
                            <div class="col-lg-2">
                                <img src="" id="thumb" style="max-height: 100px;max-width:100px" />
                            </div>

                        </div>
                        <br>
                        <div class="form-group row">
                            <label class="col-lg-2 control-label">学校介绍</label>


                            <textarea class="col-lg-8 form-control-static" name="school[introduction]" rows="5" value=""></textarea>
                        </div>
                        <br>
                        <br>

                        <br>
                        <br>
                        <div class="form-group row">
                            <label class="col-lg-2 control-label">学校类型</label>


                            <select class="col-lg-3 form-control-static" name="school[type]">

                                <option value ="1">高校</option>

                            </select>
                        </div>

                        <br>
                    </div>
                </div>
                <input id="sureAndBack" type="submit" style="width:100%;" class="btn btn-lg btn-success" value="保存" onclick="isEmpty()">
            </div>
        </div>

        {!! Form::close() !!}
    @else
        {!! Form::open(['url'=>action('Admin\SchoolController@update',['id'=>$school->id]),'method'=>'put']) !!}
        <div class="form-group row">
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">基础选项</h3>
                    </div>
                    <div class="panel-body">
                        <div class="form-group row">
                            <label class="col-lg-2 control-label">学校名称</label>
                            <input type="text" class="col-lg-8 form-control-static" name="school[name]" placeholder="请输入" value="{{$school->name}}">

                        </div>
                        <br>




                        <div class="form-group row">
                            <label class="col-lg-2 control-label">Logo</label>



                            <input type="text" class="col-lg-8 form-control-static" name="school[logo]"  value="{{$school->logo}}" id="logo" style="display:none">
                            <input type="hidden" name="userid" value="<?php $a=Auth::guard('admin')->user();print_r( $a->id );?>">



                        </div>


                        <br/>
                        <div class="row">
                            <div class="col-lg-4"><div id="upload"></div></div>
                            <div class="col-lg-2">
                                <img src="{{$school->logo}}" id="thumb" style="max-height: 100px;max-width:100px" />
                            </div>
                        </div>

                        <br>
                        <div class="form-group row">
                            <label class="col-lg-2 control-label">学校介绍</label>


                            <textarea class="col-lg-8 form-control-static" name="school[introduction]" rows="5" value="">{{$school->introduction}}</textarea>
                        </div>
                        <br>

                        <br>
                        <br>
                        <div class="form-group row">
                            <label class="col-lg-2 control-label">学校类型</label>
                            <select class="col-lg-3 form-control-static" name="school[type]">


                                <option value ="1">高校</option>

                            </select>
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
