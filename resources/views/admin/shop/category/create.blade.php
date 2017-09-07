@extends('layouts.admin')

@section('title',' 新建商品分类')



@push('stylesheets')
<link media="all" type="text/css" rel="stylesheet" href="{{ asset('assets/libs/jquerycolorbox/colorbox.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('assets/libs/jquerycolorbox/jquery.colorbox-min.js') }}"></script>
<script src="{{ asset('assets/libs/elfinder/standalonepopup.js') }}"></script>
<script src="{{ asset('assets/admin/js/shop/category/shop_category_create.js') }}"></script>
@endpush

@section('content')

        {!! Form::open(['url'=>action('Admin\Shop\CategoryController@store'),'method'=>'post']) !!}
        <div class="form-group">
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">基础选项</h3>
                    </div>
                    <div class="panel-body">
                        <div class="input-group">
                            <span class="input-group-addon">类别名称</span>
                            <input type="text" class="form-control" name="category[name]" placeholder="请输入" value="" >
                        </div>
                        <br>
                        <div class="input-group">
                            <span class="input-group-addon">类别描述</span>
                            <textarea type="text" class="form-control" name="category[description]" placeholder="请输入" ></textarea>
                        </div>
                        <br>
                        <div class="input-group">
                            <span class="input-group-addon">位置</span>
                            <input type="text" class="form-control" name="category[pos]" placeholder="请输入" value="" >
                        </div>
                        <br>
                        <span >父类</span>
                        <div id="selectContainer" class="input-group">
                            <select id="parentCategory_0" level="0">
                                <option>--</option>
                            </select>
                        </div>
                        <input id="categoryPid" type="text"  name="category[pid]" value="" hidden="true" >
                        <input id="categoryLevel" type="text"  name="category[level]" value="" hidden="true" >

                    </div>
                </div>
                <input id="sureAndBack" type="submit" style="width:100%;" class="btn btn-lg btn-success" value="保存">
            </div>
        </div>

        {!! Form::close() !!}



@endsection