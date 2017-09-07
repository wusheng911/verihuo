@extends('layouts.admin')
@section('title', '编辑 '.$content->title.' 其它文章')

    @push('stylesheets')
    <link media="all" type="text/css" rel="stylesheet" href="{{ asset('assets/libs/datetimepicker-2.3.7/build/jquery.datetimepicker.min.css') }}">
    <link media="all" type="text/css" rel="stylesheet" href="{{ asset('assets/libs/jquerycolorbox/colorbox.css') }}">
    <style>

    </style>
    @endpush

    @push('scripts')

    <script type="text/javascript">
    </script>
    
    <script src="{{ asset('assets/libs/datetimepicker-2.3.7/build/jquery.datetimepicker.full.min.js') }}"></script>
    <script src="{{ asset('assets/libs/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('assets/libs/jquerycolorbox/jquery.colorbox-min.js') }}"></script>
    <script src="{{ asset('assets/libs/elfinder/standalonepopup.js') }}"></script>

    <script src="{{ asset('assets/admin/js/other_content_edit.js') }}"> </script>
    @endpush

    @section('content')
        @if ($content->id == null)
            {!! Form::open(['url' => action('Admin\OtherContentController@store'), 'method' => 'post']) !!}
        @else
            {!! Form::open(['url' => action('Admin\OtherContentController@update', ['id'=>$content->id]), 'method' => 'put']) !!}
        @endif
        <div class="row">
            <div class="col-lg-6" >
                <div class="panel panel-default">
                    <div class="panel-heading"> <h3 class="panel-title">基础选项</h3> </div>
                    <div class="panel-body form-horizontal">
                        <div class="form-group">
                            <label class="col-lg-2 control-label">标题</label>
                            <input class="col-lg-9 form-control-static" name="content[title]" type="text" value="{{$content->title}}" placeholder="请输入标题" aria-describedby="basic-addon1">
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">子标题</label>
                            <input class="col-lg-9 form-control-static" name="content[subtitle]" type="text" value="{{$content->subtitle}}" placeholder="请输入标题" aria-describedby="basic-addon1">
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">显示作者名</label>
                            <input class="col-lg-9 form-control-static" name="content[show_author]" type="text" value="{{$content->show_author}}" placeholder="请输入待显示作者的名称" aria-describedby="basic-addon1">
                        </div>
                        <input hidden='true' class="col-lg-3 form-control-static" name="content[need_logined]" value="0">
                        <input hidden='true' id = "articleType" name="content[type]" type="text" value="2">
                        <input hidden="true" id="backtype" class="col-lg-9 form-control-static" name="other[backtype]" type="text" value="0" placeholder="请输入待显示作者的名称" aria-describedby="basic-addon1">
                    </div>
                </div>
            </div>

            <div class="col-lg-6" >
                <div class="panel panel-default">
                    <div class="panel-heading"> <h3 class="panel-title">SEO相关</h3> </div>
                    <div class="panel-body form-horizontal">
                        <div class="form-group">
                            <label class="col-lg-3 control-label">SEO标题</label>
                            <input class="col-lg-8 form-control-static" name="content[seo_title]" type="text" value="{{$content->seo_title}}" placeholder="请输入SEO标题" aria-describedby="basic-addon1">
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">SEO关键字</label>
                            <input class="col-lg-8 form-control-static" name="content[seo_keywords]" type="text" value="{{$content->seo_keywords}}" placeholder="请输入SEO关键字" aria-describedby="basic-addon1">
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">SEO描述</label>
                            <input class="col-lg-8 form-control-static" name="content[seo_description]" type="text" value="{{$content->seo_description}}" placeholder="请输入SEO描述" aria-describedby="basic-addon1">
                        </div>
                    </div>
                </div>
            </div>
            

        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">文章内容</h3>
            </div>

            <div class="row" >
                <div class="col-lg-1" ></div>
                <div class="col-lg-11 panel-body">
                    <!-- <input type="textarea" id="content" name="content[content]" value=""> -->
                    <textarea id="content" name="content[content]">{{$content->content}}</textarea>
                </div>
            </div>
        </div>
        <div class="row" >
            <div class="col-md-4" ></div>
            <div class="col-md-4" >
            <input id="sureAndBackList" onmousedown="onMouseDown()" type="submit" style="width:100%;" class="btn btn-lg btn-success" value="保存并返回资讯列表">
            </div>
            <div class="col-md-4" >
             
                <input id="sureAndBack" onmousedown="onMouseDown()" type="submit" style="width:100%;" class="btn btn-lg btn-success" value="保存">
            </div>
        </div>
        {!! Form::close() !!}
    @endsection
