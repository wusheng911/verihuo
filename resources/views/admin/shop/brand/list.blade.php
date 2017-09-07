@extends('layouts.admin')

@section('title')
    商品分类列表
@endsection

@push('stylesheets')
<link media="all" type="text/css" rel="stylesheet" href="{{ asset('assets/libs/jquerytree/css/jquery.treeview.css') }}" />
<link media="all" type="text/css" rel="stylesheet" href="{{ asset('assets/libs/jquerycolorbox/colorbox.css') }}">

<style>
    table tr a.del{
        color: red;
        font-size: 12px;
    }
    table tr a.edit{
        color: blue;
        font-size: 18px;
    }
    table tr td{
        border-left: dashed 1px lightgray;
        border-right: dashed 1px lightgray;
    }
    tr.highlight{
        background-color: #f0a400 !important;
    }
    td.highlight {
        background-color: whitesmoke !important;
    }
</style>
@endpush

@push('scripts')
    <script src="{{ asset('assets/libs/jquerytree/lib/jquery.cookie.js') }}"></script>
    <script src="{{ asset('assets/libs/elfinder/standalonepopup.js') }}"></script>
    <script src="{{ asset('assets/libs/jquerycolorbox/jquery.colorbox-min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/shop/brand/brand.js') }}"></script>
@endpush

@section('content')
    <div class="col-lg-12" >
        @permission(['all', 'ad-create'])
        <div style="display: inline">

            <button id="btnForNewBrand" type="button" onClick="window.location.href='{{ action('Admin\Shop\BrandController@create') }}';" class="btn btn-lg btn-success">新建</button>
            <button id="btnForEditBrand" type="button" style="display: inline" data-toggle="modal" data-target="#myModalEditShopBrand" class="btn btn-lg btn-success">编辑品牌</button>

        </div>
        @endpermission
        <div class="table-responsive">
            <table id="tableBrand" class="table table-striped row-border hover order-column">
                <thead>
                <th>名称</th>
                <th>logo</th>
                <th>描述</th>
                </thead>
                <tfoot>
                </tfoot>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>


    <div class="modal fade" id="myModalNewBrand" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close"
                            data-dismiss="modal" aria-hidden="true">
                        &times;
                    </button>
                    <h4 class="modal-title" id="myModalLabel">
                        创建新品牌
                    </h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">基础选项</h3>
                                </div>
                                <div class="panel-body">
                                    <div class="input-group">
                                        <span class="input-group-addon">品牌名称</span>
                                        <input id="newBrandName" type="text" class="form-control" name="brand[name]" placeholder="请输入" value="" >
                                    </div>
                                    <br>
                                    <div class="input-group">
                                        <span class="input-group-addon">品牌描述</span>
                                        <textarea id="newBrandDescription" type="text" class="form-control" name="brand[description]" placeholder="请输入" ></textarea>
                                    </div>
                                    <br>
                                    <div class="col-lg-5" >
                                            <a href=""  class="popup_selector" data-inputid="feature_image_1"><img id="img_feature_image_1" style="width:150px;height:150px;" for="feature_image_1" alt="请选择品牌logo" src="/assets/img/article_icon_1_1.jpg"></a>
                                        <input hidden type="text" id="feature_image_1" name="brand[image]" value=""/>
                                    </div>

                                </div>
                            </div>
                            <input id="btnForSureNewBrand" type="submit" style="width:100%;" class="btn btn-lg btn-success" value="保存">
                        </div>
                    </div>

                </div>
                <div class="modal-footer">

                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal -->
    </div>
@endsection