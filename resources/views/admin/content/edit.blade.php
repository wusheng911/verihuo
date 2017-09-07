@extends('layouts.admin')
@section('title', '编辑 '.$content->title.' 资讯文章')

    @push('stylesheets')
    <link media="all" type="text/css" rel="stylesheet" href="{{ asset('assets/libs/datetimepicker-2.3.7/build/jquery.datetimepicker.min.css') }}">
    <link media="all" type="text/css" rel="stylesheet" href="{{ asset('assets/libs/jquerycolorbox/colorbox.css') }}">
    <style>

    </style>
    @endpush

    @push('scripts')

    <script type="text/javascript">
     getParentsUrl = "{{ action('Admin\ContentCatalogController@getParentCategorys') }}";
     getChildrenUrl = "{{ action('Admin\ContentCatalogController@getChildernCategorys') }}";
     getChildrenListUrl = "{{ action('Admin\ContentCatalogController@childrenListJson') }}"; 
     getTagsHtml = "{{ action('Admin\ContentTagController@listTagsHtml') }}"; 
    </script>
    
    <script src="{{ asset('assets/libs/datetimepicker-2.3.7/build/jquery.datetimepicker.full.min.js') }}"></script>
    <script src="{{ asset('assets/libs/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('assets/libs/jquerycolorbox/jquery.colorbox-min.js') }}"></script>
    <script src="{{ asset('assets/libs/elfinder/standalonepopup.js') }}"></script>

    <script src="{{ asset('assets/admin/js/content_edit.js') }}"> </script>
    <script src="{{ asset('assets/admin/js/category/category_data.js')}}" type="text/javascript"></script>
    @endpush

    @section('content')
        @if ($content->id == null)
            {!! Form::open(['url' => action('Admin\ContentController@store'), 'method' => 'post']) !!}
        @else
            {!! Form::open(['url' => action('Admin\ContentController@update', ['id'=>$content->id]), 'method' => 'put']) !!}
        @endif
        <div class="row">
            <div class="col-lg-6" >
                <div class="panel panel-default">
                    <div class="panel-heading"> <h3 class="panel-title">基础选项</h3> </div>
                    <div class="panel-body form-horizontal">
                        <div class="form-group">
                            <label class="col-lg-2 control-label">发布时间</label>
                            <input class="col-lg-9 form-control-static" name="content[post_at]" id="post_at" type="text" value="{{$content->post_at}}" placeholder="请选则默认发布时间" aria-describedby="basic-addon1">
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">标题</label>
                            <input class="col-lg-9 form-control-static" name="content[title]" type="text" value="{{$content->title}}" placeholder="请输入标题" aria-describedby="basic-addon1">
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">子标题</label>
                            <input class="col-lg-9 form-control-static" name="content[subtitle]" type="text" value="{{$content->subtitle}}" placeholder="请输入标题" aria-describedby="basic-addon1">
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label" style="color: red">描述(必填)</label>
                            <textarea id="contentDescription" class="col-lg-9 form-control-static" name="content[description]" type="text" value="{{$content->description}}" placeholder="请输入标题" aria-describedby="basic-addon1">{{$content->description}}</textarea>
                        </div>
                        <div class="row form-group">
                            <label class="col-lg-2 control-label">标签</label>
                            <div class="col-lg-10" >
                                <div class="row checkbox" >
                                    <ul id="tag-lists" class="list-inline">
                                    </ul>
                                    <label class="text-red">
                                    当前标签:  
                                        @foreach($content->tags as $tag)
                                            {{$tag->name}}
                                        @endforeach
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">显示作者名</label>
                            <input class="col-lg-9 form-control-static" name="content[show_author]" type="text" value="{{$content->show_author}}" placeholder="请输入待显示作者的名称" aria-describedby="basic-addon1">
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label" style="color: red">所属类别(必填)</label>
                            <div style="display: inline;" id="selectCategoryContainer" onchange='oncategorychange()'></div>
                            <input id="content_category_id" type="hidden" class="col-lg-9 form-control-static" name="content[content_category_id]" type="text" value="{{$content->content_category_id}}" placeholder="所属类别" aria-describedby="basic-addon1">
                        </div>

                        <div class="row form-group">
                            <label class="col-lg-2 control-label">查看权限</label>
                            <div class="col-lg-10" >
                                <div class="row checkbox" >
                                    <label >
                                        <input name="content[need_logined]" type="checkbox"
                                               {{ empty($content->need_logined) ? "" : "checked" }} >
                                        仅允许已登陆用户
                                    </label>
                                </div>
                            </div>
                        </div>
                       
                        <input hidden="true" id="backtype" class="col-lg-9 form-control-static" name="other[backtype]" type="text" value="0" placeholder="请输入待显示作者的名称" aria-describedby="basic-addon1">
                        <input hidden='true' id = "articleType" name="content[type]" type="text" value="1">

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
            
            <div class="col-lg-6" >
                <div class="panel panel-default">
                    <div class="panel-heading"> <h3 class="panel-title" style="color: red">头图设置(必填)</h3> </div>
                    <div class="panel-body form-horizontal">
                         <div  style="display:none;" class="col-lg-5" >
                         @if (!empty($content->image))                            
                            <a href=""  class="popup_selector" data-inputid="feature_image_1"><img id="img_feature_image_1" style="width:150px;height:150px;" for="feature_image_1" alt="" src="{{$content->image}}"></a>
                         @else
                            <a href="" class="popup_selector" data-inputid="feature_image_1"><img id="img_feature_image_1" style="width:150px;height:150px;" for="feature_image_1" alt="" src="/assets/img/article_icon_1_1.jpg"></a> 
                        @endif
			    <input hidden type="text" id="feature_image_1" name="content[image]" value="{{$content->image}}"/>
                        </div>
                        <div class="col-lg-6" >
                         @if (!empty($content->image_4_3))
                            <a href="" class="popup_selector" data-inputid="feature_image_2"><img id="img_feature_image_2" style="width:200px;height:150px;" for="feature_image_2" alt="" src="{{$content->image_4_3}}"></a>
                         @else
                            <a href="" class="popup_selector" data-inputid="feature_image_2"><img id="img_feature_image_2" style="width:200px;height:150px;" for="feature_image_2" alt="" src="/assets/img/article_icon_4_3.jpg"></a>
                         @endif
			    <input hidden type=""text" id="feature_image_2" name="content[image_4_3]" value="{{$content->image_4_3}}"/>
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
