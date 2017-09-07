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
    <script src="{{ asset('assets/libs/jquerytree/treeview/jquery.treeview.js') }}"></script>
    <script src="{{ asset('assets/admin/js/shop/category/shop_category_index.js') }}"></script>
    <script src="{{ asset('assets/admin/js/shop/category/shop_category_create.js') }}"></script>
    <script src="{{ asset('assets/libs/elfinder/standalonepopup.js') }}"></script>
    <script src="{{ asset('assets/libs/jquerycolorbox/jquery.colorbox-min.js') }}"></script>
@endpush

@section('content')
    <div class="col-lg-12" >
        @permission(['all', 'ad-create'])
        <div style="display: inline">

            <button id="btnForNewCategory" type="button" style="display: inline" data-toggle="modal" data-target="#myModalNewCategory" class="btn btn-lg btn-success">新建</button>
            <button id="btnForNewChildCategory" type="button" style="display: inline" data-toggle="modal" data-target="#myModalNewChildCategory" class="btn btn-lg btn-success">新建子分类</button>

        </div>
        @endpermission
        <div class="table-responsive">
            <table id="tableCategory" class="table table-striped row-border hover order-column">
                <thead>

                <th>分类名称</th>
                <th>描述</th>
                <th>操作</th>
                </thead>
                <tfoot>
                </tfoot>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>


    <div class="modal fade" id="myModalNewCategory" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close"
                            data-dismiss="modal" aria-hidden="true">
                        &times;
                    </button>
                    <h4 class="modal-title" id="myModalLabel">
                        创建新分类
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
                                    <div class="form-group">
                                        <label class="col-lg-2 control-label">类别名称</label>
                                        <input id="newCategoryName" type="text" class="col-lg-10 form-control-static" name="category[name]" placeholder="请输入" value="" >
                                    </div>
                                    <br>
                                    <br>
                                    <div class="form-group">
                                        <label class="col-lg-2 control-label">位置</label>
                                        <input id="newCategoryPos" type="text" class="col-lg-10 form-control-static" name="category[pos]" placeholder="请输入" value="" >
                                    </div>
                                    <br>
                                    <br>
                                    <div class="form-group">
                                        <label class="col-lg-2 control-label">类别描述</label>
                                        <textarea id="newCategoryDescription" type="text" class="col-lg-10 form-control-static" name="category[description]" placeholder="请输入" ></textarea>
                                    </div>
                                    <br>
                                    <br>
                                    <input id="categoryPid" type="text"  name="category[pid]" value="" hidden="true" >
                                    <input id="categoryLevel" type="text"  name="category[level]" value="" hidden="true" >
                                    <br>
                                    <div>
                                        <span>品牌值</span>
                                        <div id="brandlist">
                                            @foreach($brands as $key=>$value)
                                                <input class="selectBrand"  id="{{$value->id}}" name="brand" type="checkbox" value="{{$value->name}}">
                                                <span style="margin-right: 25px" >{{$value->name}}</span>
                                            @endforeach
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <input id="btnForSureNewCategory" type="submit" style="width:100%;" class="btn btn-lg btn-success" value="保存">
                        </div>
                    </div>

                </div>
                <div class="modal-footer">

                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal -->
    </div>



    <div class="modal fade" id="myModalNewChildCategory" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close"
                            data-dismiss="modal" aria-hidden="true">
                        &times;
                    </button>
                    <h4 class="modal-title" id="myModalLabel">
                        新建子分类
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
                                    <div class="form-group">
                                        <label class="col-lg-2 control-label">类别名称</label>
                                        <input id="newChildCategoryName" type="text" class="col-lg-10 form-control-static" name="category[name]" placeholder="请输入" value="" >
                                    </div>
                                    <br>
                                    <br>
                                    <div class="form-group">
                                        <span class="col-lg-2 control-label">位置</span>
                                        <input id="newChildCategoryPos" type="text" class="col-lg-10 form-control-static" name="category[pos]" placeholder="请输入" value="" >
                                    </div>
                                    <br>
                                    <br>
                                    <div class="form-group">
                                        <label class="col-lg-2 control-label">类别描述</label>
                                        <textarea id="newChildCategoryDescription" type="text" class="col-lg-10 form-control-static" name="category[description]" placeholder="请输入" ></textarea>
                                    </div>
                                    <br>
                                    <br>
                                    <div class="form-group">
                                        <label class="col-lg-2 control-label">品牌值</label>
                                        <div id="brandlist">
                                            @foreach($brands as $key=>$value)
                                                <input class="childSelectBrand"  id="{{$value->id}}" name="brand" type="checkbox" value="{{$value->name}}">
                                                <span style="margin-right: 25px" >{{$value->name}}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input id="btnForSureNewChildCategory" type="submit" style="width:100%;" class="btn btn-lg btn-success" value="保存">
                        </div>
                    </div>

                </div>
                <div class="modal-footer">

                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal -->
    </div>
@endsection