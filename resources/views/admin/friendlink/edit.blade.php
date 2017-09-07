@extends('layouts.admin')

@section('title',' 新建属性')


@push('stylesheets')
<link media="all" type="text/css" rel="stylesheet" href="{{ asset('assets/libs/jquerycolorbox/colorbox.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('assets/libs/jquerycolorbox/jquery.colorbox-min.js') }}"></script>
<script src="{{ asset('assets/libs/elfinder/standalonepopup.js') }}"></script>
<script src="{{ asset('assets/admin/js/shop/brand/brand_create.js') }}"></script>
@endpush

@section('content')

    @if(empty($friendLink))
    {!! Form::open(['url'=>action('Admin\FriendLinkController@store'),'method'=>'post']) !!}
    <div class="form-group">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">基础选项</h3>
                </div>
                <div class="panel-body">


                    <div class="form-group">
                        <label class="col-lg-2 control-label">标题</label>
                        <input type="text" class="col-lg-10 form-control-static" name="friendlink[title]" placeholder="请输入" value="" >
                    </div>
                    <br>
                    <br>
                    <div class="form-group">
                        <label class="col-lg-2 control-label">链接</label>
                        <input type="text" class="col-lg-10 form-control-static" name="friendlink[link]" placeholder="请输入" value="" >
                    </div>
                    <br>
                    <br>
                    <div class="form-group">
                        <label class="col-lg-2 control-label">位置</label>
                        <input type="text" class="col-lg-10 form-control-static" name="friendlink[pos]" placeholder="请输入" value="" >
                    </div>
                    <br>
                    <br>
                    <div class="form-group">
                        <label class="col-lg-2 control-label">启用</label>
                        <input class="col-lg-3 form-control-static" name="friendlink[status]" type="checkbox"
                               value="1">
                    </div>
                    <br>
                    <div class="form-group" >
                        <label class="col-lg-2 control-label">logo</label>
                        <a href=""  class="popup_selector col-lg-10 form-control-static" data-inputid="feature_image_1"><img id="img_feature_image_1" style="width:150px;height:150px;" for="feature_image_1" alt="请选择品牌logo" src="/assets/img/article_icon_1_1.jpg"></a>
                        <input hidden type="text" id="feature_image_1" name="friendlink[logo]" value=""/>
                    </div>
                </div>
            </div>
            <input id="sureAndBack" type="submit" style="width:100%;" class="btn btn-lg btn-success" value="保存">
        </div>
        </div>

    {!! Form::close() !!}
    @else
    {!! Form::open(['url'=>action('Admin\FriendLinkController@update',['id'=>$friendLink->id]),'method'=>'put']) !!}
            <div class="form-group">
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">基础选项</h3>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label class="col-lg-2 control-label">标题</label>
                                <input type="text" class="col-lg-10 form-control-static" name="friendlink[title]" placeholder="请输入" value="{{$friendLink->title}}" >
                            </div>
                            <br>
                            <br>
                            <div class="form-group">
                                <label class="col-lg-2 control-label">链接</label>
                                <input type="text" class="col-lg-10 form-control-static" name="friendlink[link]" placeholder="请输入" value="{{$friendLink->link}}" >
                            </div>
                            <br>
                            <br>
                            <div class="form-group">
                                <label class="col-lg-2 control-label">位置</label>
                                <input type="text" class="col-lg-10 form-control-static" name="friendlink[pos]" placeholder="请输入" value="{{$friendLink->pos}}" >
                            </div>
                            <br>
                            <br>
                            <div class="form-group">
                                <label class="col-lg-2 control-label">启用</label>
                                <input class="col-lg-3 form-control-static" name="friendlink[status]" type="checkbox"
                                       {{ empty($friendLink->status) ? "" : "checked" }}>
                            </div>
                            <br>
                            <div class="form-group" >
                                <label class="col-lg-2 control-label">logo</label>
                                <a href=""  class="popup_selector col-lg-10 form-control-static" data-inputid="feature_image_1"><img id="img_feature_image_1" style="width:150px;height:150px;" for="feature_image_1" alt="请选择品牌logo" src="{{$friendLink->logo}}"></a>
                                <input hidden type="text" id="feature_image_1" name="friendlink[logo]" value="{{$friendLink->logo}}"/>
                            </div>
                        </div>
                    </div>

                    <input id="sureAndBack" type="submit" style="width:100%;" class="btn btn-lg btn-success" value="保存">
                </div>
            </div>
        {!! Form::close() !!}
    @endif
@endsection