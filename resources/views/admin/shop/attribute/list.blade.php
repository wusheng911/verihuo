@extends('layouts.admin')

@section('title')
    编辑商品属性
@endsection

@push('stylesheets')
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
<scrpit>
    getListUrl = "{{ action('Admin\Shop\AttributeController@listJson') }}";
</scrpit>
<script src="{{ asset('assets/admin/js/shop/category/categoryRelate.js') }}"></script>
<script src="/assets/admin/js/shop/attribute/attribute.js"></script>
<script src="{{ asset('assets/admin/js/shop/category/shop_category_create.js') }}"></script>

@endpush

@section('content')

    <div class="col-lg-6" >
        @permission(['all', 'ad-create'])
        <div style="padding-bottom: 10px">

            <button id="btnForNewAttribute" type="button" style="display: inline"; data-toggle="modal" data-target="#myModalNewAttribute" class="btn btn-lg btn-success">新建</button>
            <button id="btnForEditAttribute" type="button" style="display: inline" data-toggle="modal" data-target="#myModalEditShopAttribute" class="btn btn-lg btn-success">编辑属性</button>
            <button id="btnForDelAttribute" type="button" style="display:inline" class="btn btn-lg btn-success">删除属性</button>
            <button id="btnForAddChildAttribute" type="button" style="display:none" data-toggle="modal" data-target="#myModalAddShopChildAttribute" class="btn btn-lg btn-success">添加子属性</button>


        </div>
        <div id="selectFilterContainer" class="col-sm-4" class="input-group">
            <select id="selectFilter" class="form-control" level="0">
                <option>--</option>
            </select>
        </div>
        @endpermission
        <div class="table-responsive">
            <table id="tableAttribute" class="table table-striped row-border hover order-column">
                <thead>
                <th>属性名称</th>
                <th>分类名称</th>
                <th>展示名称</th>
                <th>必填</th>
                <th>类型</th>
                </thead>
                <tfoot>
                </tfoot>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-lg-6 col-lg-offset-0" >
        @permission(['all', 'ad-create'])
        <div style="display: inline">
            <button id="btnForEditAttributeValue" type="button" style="display: inline" data-toggle="modal" data-target="#myModalEditShopAttributeValue" class="btn btn-lg btn-success">编辑</button>
            <button id="btnForAddAttributeValue" type="button" style="display: inline" data-toggle="modal" data-target="#myModalAddShopAttributeValue" class="btn btn-lg btn-success">添加属性值</button>
            <button id="btnForCreateAimAttributeChildAttribute" type="button" style="display: inline" data-toggle="modal" data-target="#myModalCreateAimAttributeChildAttribute" class="btn btn-lg btn-success">以此创建新属性</button>
            <button id="btnForDelAttributeValue" type="button" style="display: inline" class="btn btn-lg btn-success">删除</button>
        </div>
        @endpermission
        <div  class="table-responsive">
            <table id="tableAttributeValue" class="table table-striped">
                <thead>
                <th>值</th>
                <th>启用</th>
                <th>位置</th>
                </thead>
                <tfoot>
                </tfoot>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="myModalAddShopAttributeValue" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close"
                            data-dismiss="modal" aria-hidden="true">
                        &times;
                    </button>
                    <h4 class="modal-title" id="myModalLabel">
                        为属性创建新的属性值
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
                                        <label class="col-lg-1 control-label">值</label>
                                        <input id="addAttributeValueValue" type="text" class="col-lg-11 form-control-static" name="attributeValue[value]" placeholder="请输入" value="" >
                                    </div>
                                    <br>
                                    <br>
                                    <div class="form-group">
                                        <label class="col-lg-1 control-label">位置</label>
                                        <input  id="addAttributeValuePos" type="text" class="col-lg-11 form-control-static" name="attributeValue[pos]" placeholder="请输入" value="" >
                                    </div>
                                    <br>
                                    <br>
                                    <div class="input-group">
                                        <input id="addAttributeValueStatus" checked="true" style="width:15px;" class="col-lg-3 form-control-static" name="attributeValue[status]" type="checkbox"
                                               value="" >
                                        <label style="width:60px; padding-left: 0px" class="col-lg-2 control-label">启用</label>
                                    </div>
                                    <br>
                                    <div class="input-group">
                                        <span id="aimAttributeName"></span>

                                        <input  id="attributeIdForAddAttributeValue" type="text" name="attributeValue[shop_attribute_id]"  value="" hidden="true">

                                    </div>
                                </div>
                            </div>
                            <input id="sureAddAttributeValue" type="submit" style="width:100%;" class="btn btn-lg btn-success" value="保存">
                        </div>
                    </div>


                </div>
                <div class="modal-footer">

                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal -->
    </div>




    <div class="modal fade" id="myModalEditShopAttribute" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close"
                            data-dismiss="modal" aria-hidden="true">
                        &times;
                    </button>
                    <h4 class="modal-title" id="myModalLabel">
                        编辑商品属性
                    </h4>
                </div>
                <div class="modal-body">

                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><span id="InfoTextForAttribute">基础选项</span></h3>
                                </div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <label class="col-lg-1 control-label">名称</label>
                                        <input id="editAttributeName" type="text" class="col-lg-10 form-control-static" name="attribute[name]" placeholder="请输入" value="" >
                                    </div>
                                    <br>
                                    <br>
                                    <div class="form-group">
                                        <label class="col-lg-1 control-label">位置</label>
                                        <input  id="editAttributePos" type="text" class="col-lg-10 form-control-static" name="attribute[pos]" placeholder="请输入" value="" >
                                    </div>
                                    <br>
                                    <br>
                                    <div class="form-group">
                                        <label class="col-lg-2 control-label">显示名称</label>
                                        <input id="editAttributeShowName" type="text" class="col-lg-9 form-control-static" name="attribute[show_name]" placeholder="请输入" value="" >
                                    </div>

                                    <br>
                                    <br>
                                    <div class="input-group">

                                        <input id="editAttributeStatus" style="display: none" class="col-lg-3 form-control-static" name="" type="checkbox"
                                               value="" >
                                        <label style="display: none; padding-left: 0px" class="col-lg-2 control-label">启用</label>

                                        <input  id="editAttributeRequired" style="width:15px;" class="col-lg-3 form-control-static" name="" type="checkbox"
                                                value="" >
                                        <label style="width:90px; padding-left: 0px" class="col-lg-2 control-label">必填属性</label>
                                    </div>
                                    <div class="form-group">



                                        <input  id="editAttributeBase" style="display: none" class="col-lg-3 form-control-static" name="radio" type="radio"
                                               value="">
                                        <label style="display: none" class="col-lg-2 control-label">基础属性</label>


                                        <input id="editAttributeSell" style="display: none" class="col-lg-3 form-control-static" name="radio" type="radio"
                                                value="" >
                                        <label style="display: none" class="col-lg-2 control-label">销售属性</label>


                                        <input id="editAttributeOptional" style="display: none" class="col-lg-3 form-control-static" name="radio" type="radio"
                                               value="">
                                        <label style="display: none" class="col-lg-2 control-label">可操作</label>
                                    </div>

                                </div>

                            </div>

                            <input id="sureEditAttribute"  type="submit" style="width:100%;" class="btn btn-lg btn-success" value="保存">
                        </div>
                    </div>


                </div>
                <div class="modal-footer">

                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal -->div>
    　</div>


    <div class="modal fade" id="myModalAddShopChildAttribute" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close"
                            data-dismiss="modal" aria-hidden="true">
                        &times;
                    </button>
                    <h4 class="modal-title" id="myModalLabel">
                        新建子属性
                    </h4>
                </div>
                <div class="modal-body">

                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><span id="InfoTextForAddChildAttribute">基础选项</span></h3>
                                </div>
                                <div class="panel-body">
                                    <div class="input-group">
                                        <span class="input-group-addon">名称</span>
                                        <input id="addChildAttributeName" type="text" class="form-control" name="attribute[name]" placeholder="请输入" value="" >
                                    </div>
                                    <br>
                                    <div class="input-group">
                                        <span class="input-group-addon">显示名称</span>
                                        <input id="addChildAttributeShowName" type="text" class="form-control" name="attribute[show_name]" placeholder="请输入" value="" >
                                    </div>
                                    <br>
                                    <div class="input-group">
                                        <span class="input-group-addon">位置</span>
                                        <input  id="addChildAttributePos" type="text" class="form-control" name="attribute[pos]" placeholder="请输入" value="" >
                                    </div>
                                    <br>
                                    <div class="input-group">

                                        <input id="addChildAttributeStatus" checked="true" style="width:15px;" class="col-lg-3 form-control-static" name="" type="checkbox"
                                               value="" >
                                        <label style="width:60px; padding-left: 0px" class="col-lg-2 control-label">启用</label>

                                        <input  id="addChildAttributeRequired" style="width:15px;" class="col-lg-3 form-control-static" name="" type="checkbox"
                                                value="" >
                                        <label style="width:90px; padding-left: 0px" class="col-lg-2 control-label">必填属性</label>
                                    </div>
                                    <div class="form-group">



                                        <input id="addChildAttributeBase" style="width:15px;" class="col-lg-3 form-control-static" name="radio" type="radio"
                                               value="">
                                        <label style="width:90px;padding-left: 0px" class="col-lg-2 control-label">基础属性</label>


                                        <input id="addChildAttributeSell" style="width:15px;" class="col-lg-3 form-control-static" name="radio" type="radio"
                                               value="" >
                                        <label style="width:90px; padding-left: 0px" class="col-lg-2 control-label">销售属性</label>


                                        <input id="addChildAttributeOptional" style="width:15px;" class="col-lg-3 form-control-static" name="radio" type="radio"
                                               value="">
                                        <label style="width:90px;padding-left: 0px" class="col-lg-2 control-label">操作属性</label>
                                    </div>

                                </div>

                            </div>

                            <input id="sureAddChildAttribute"  type="submit" style="width:100%;" class="btn btn-lg btn-success" value="保存">
                        </div>
                    </div>


                </div>
                <div class="modal-footer">

                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal -->
    </div>



    <div class="modal fade" id="myModalEditShopAttributeValue" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close"
                            data-dismiss="modal" aria-hidden="true">
                        &times;
                    </button>
                    <h4 class="modal-title" id="myModalLabel">
                        编辑商品属性的属性值
                    </h4>
                </div>
                <div class="modal-body">

                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><span id="InfoTextForAttributeValue" >基础选项</span></h3>
                                </div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <label class="col-lg-1 control-label">值</label>
                                        <input id="editAttributeValueValue" type="text" class="col-lg-10 form-control-static" name="" placeholder="请输入" value="" >
                                    </div>
                                    <br>
                                    <br>
                                    <div class="form-group">
                                        <label class="col-lg-1 control-label">位置</label>
                                        <input id="editAttributeValuePos" type="text" class="col-lg-10 form-control-static" name="" placeholder="请输入" value="" >
                                    </div>
                                    <br>
                                    <br>
                                    <div class="input-group">

                                        <input id="editAttributeValueStatus" style="width:15px;" class="col-lg-3 form-control-static" name="editstatus" type="checkbox"
                                               value="" >
                                        <label style="width:60px; padding-left: 0px"  class="col-lg-2 control-label">启用</label>

                                    </div>
                                    <br>
                                </div>
                            </div>
                            <input id="sureEditAttributeValue" type="submit" style="width:100%;" class="btn btn-lg btn-success" value="保存">
                        </div>
                    </div>


                </div>
                <div class="modal-footer">

                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal -->
    </div>



    <div class="modal fade" id="myModalNewAttribute" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close"
                            data-dismiss="modal" aria-hidden="true">
                        &times;
                    </button>
                    <h4 class="modal-title" id="myModalLabel">
                        创建新属性
                    </h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><span id="">基础选项</span></h3>
                                </div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <label class="col-lg-2 control-label">名称</label>
                                        <input id="newAttributeName" type="text" class="col-lg-10 form-control-static" name="attribute[name]" placeholder="请输入" value="" >
                                    </div>
                                    <br>
                                    <br>
                                    <div class="form-group">
                                        <span class="col-lg-2 control-label">位置</span>
                                        <input  id="newAttributePos" type="text" class="col-lg-10 form-control-static" name="attribute[pos]" placeholder="请输入" value="" >
                                    </div>
                                    <br>
                                    <br>
                                    <div class="form-group">
                                        <span class="col-lg-2 control-label">显示名称</span>
                                        <input id="newAttributeShowName" type="text" class="col-lg-10 form-control-static" name="attribute[show_name]" placeholder="请输入" value="" >
                                    </div>
                                    <br>
                                    <br>
                                    <span >所属分类</span>
                                    <div id="selectContainer" class="input-group">
                                        <select id="parentCategory_0" level="0">
                                            <option>--</option>
                                        </select>
                                    </div>
                                    <input id="categoryPid" type="text"  name="attribute[shop_category_id]" value="" hidden="true" >
                                    <input id="categoryLevel" type="text"  name="category[level]" value="" hidden="true" >
                                    <br>
                                    <div class="input-group">

                                        <input id="newAttributeStatus" checked="true" style="display: none" class="col-lg-3 form-control-static" name="status" type="checkbox"
                                               value="" >
                                        <label style="display: none; padding-left: 0px" class="col-lg-2 control-label">启用</label>

                                        <input  id="newAttributeRequired" style="width:15px;" class="col-lg-3 form-control-static" name="" type="checkbox"
                                                value="" >
                                        <label style="width:90px; padding-left: 0px"  class="col-lg-2 control-label">必填属性</label>
                                    </div>
                                    <div class="form-group">



                                        <input id="newAttributeBase" checked="true" style="width:15px;" class="col-lg-3 form-control-static" name="radio" type="radio"
                                               value="">
                                        <label style="width:90px; padding-left: 0px" class="col-lg-2 control-label">基础属性</label>


                                        <input id="newAttributeSell" style="width:15px;" class="col-lg-3 form-control-static" name="radio" type="radio"
                                               value="" >
                                        <label style="width:90px; padding-left: 0px" class="col-lg-2 control-label">销售属性</label>


                                        <input id="newAttributeOptional" style="width:15px;" class="col-lg-3 form-control-static" name="radio" type="radio"
                                               value="">
                                        <label style="width:90px; padding-left: 0px" class="col-lg-2 control-label">可操作</label>
                                    </div>

                                </div>

                            </div>

                            <input id="sureCreateNewAttribute"  type="submit" style="width:100%;" class="btn btn-lg btn-success" value="保存">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">

                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal -->
    </div>


    <!-- 根据属性值创建属性（Modal） -->
    <div class="modal fade" id="myModalCreateAimAttributeChildAttribute" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close"
                            data-dismiss="modal" aria-hidden="true">
                        &times;
                    </button>
                    <h4 class="modal-title" id="myModalLabel">
                        系统提示
                    </h4>
                </div>
                <div class="modal-body">
                    确定要以此创建新属性吗?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default"
                            data-dismiss="modal">后悔药
                    </button>
                    <button id="sureCreateAimAttributeChildAttribute" type="button" class="btn btn-primary">
                        是哒！
                    </button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal -->
    </div>
@endsection