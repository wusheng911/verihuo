@extends('layouts.admin')

@section('title',' 新建商品分类')



@push('stylesheets')
<link media="all" type="text/css" rel="stylesheet" href="{{ asset('assets/libs/jquerycolorbox/colorbox.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('assets/libs/jquerycolorbox/jquery.colorbox-min.js') }}"></script>
<script src="{{ asset('assets/libs/elfinder/standalonepopup.js') }}"></script>
<script src="{{ asset('assets/admin/js/shop/brand/brand_create.js') }}"></script>
@endpush

@section('content')

        {!! Form::open(['url'=>action('Admin\Shop\BrandController@store'),'method'=>'post']) !!}
        <div class="form-group">
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">基础选项</h3>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="col-lg-2 control-label">品牌名称</label>
                            <input id="newBrandName" type="text" class="col-lg-10 form-control-static" name="brand[name]" placeholder="请输入" value="" >
                        </div>
                        <br>
                        <br>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">品牌描述</label>
                            <textarea id="newBrandDescription" type="text" class="col-lg-10 form-control-static" name="brand[description]" placeholder="请输入" ></textarea>
                        </div>
                        <br>
                        <br>
                        <div class="form-group" >
                            <label class="col-lg-2 control-label">logo</label>
                            <a href=""  class="popup_selector col-lg-10 form-control-static" data-inputid="feature_image_1"><img id="img_feature_image_1" style="width:150px;height:150px;" for="feature_image_1" alt="请选择品牌logo" src="/assets/img/article_icon_1_1.jpg"></a>
                            <input hidden type="text" id="feature_image_1" name="brand[logo]" value=""/>
                        </div>

                    </div>
                </div>
                <input id="btnForSureNewBrand" type="submit" style="width:100%;" class="btn btn-lg btn-success" value="保存">
            </div>
        </div>

        {!! Form::close() !!}



@endsection