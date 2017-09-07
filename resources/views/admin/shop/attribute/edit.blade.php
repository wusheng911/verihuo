@extends('layouts.admin')

@section('title',' 新建属性')



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
</style>
@endpush

@push('scripts')
    <scrpit>
        getListUrl = "{{ action('Admin\Shop\AttributeValueController@listJson') }}";
    </scrpit>
    <script src="/assets/admin/js/shop/attributevalue/attributevalue.js"></script>
@endpush

@section('content')

    @if(empty($attribute))
    {!! Form::open(['url'=>action('Admin\Shop\AttributeController@store'),'method'=>'post']) !!}
    <div class="form-group">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">基础选项</h3>
                </div>
                <div class="panel-body">
                    <div class="input-group">
                        <span class="input-group-addon">名称</span>
                        <input type="text" class="form-control" name="attribute[name]" placeholder="请输入" value="" >
                    </div>
                    <br>
                    <div class="input-group">
                        <span class="input-group-addon">销售名称</span>
                        <input type="text" class="form-control" name="attribute[show_name]" placeholder="请输入" value="" >
                    </div>
                    <br>
                    <div class="input-group">
                        <span class="input-group-addon">位置</span>
                        <input type="text" class="form-control" name="attribute[pos]" placeholder="请输入" value="" >
                    </div>
                    <br>

                    <div class="input-group">
                        <span class="input-group-addon">状态</span>
                        <input type="text" class="form-control" name="attribute[status]" placeholder="请输入" value="" >
                    </div>
                    <div class="form-group">
                        <label class="col-lg-2 control-label">必填属性</label>
                        <input  style="width:30px;" class="col-lg-3 form-control-static" name="attribute[required]" type="checkbox"
                               {{ empty($attribute->required) ? "" : "checked" }} value="1" >

                        <label class="col-lg-2 control-label">基础属性</label>
                        <input style="width:30px;" class="col-lg-3 form-control-static" name="attribute[is_base]" type="checkbox"
                               {{ empty($attribute->is_base) ? "" : "checked" }} value="1">

                        <label class="col-lg-2 control-label">销售属性</label>
                        <input style="width:30px;" class="col-lg-3 form-control-static" name="attribute[is_sell]" type="checkbox"
                               {{ empty($attribute->is_sell) ? "" : "checked" }} value="1" >

                        <label class="col-lg-2 control-label">操作属性</label>
                        <input style="width:30px;" class="col-lg-3 form-control-static" name="attribute[is_optional]" type="checkbox"
                               {{ empty($attribute->is_optional) ? "" : "checked" }} value="1">
                    </div>
                </div>
            </div>
            <input id="sureAndBack" type="submit" style="width:100%;" class="btn btn-lg btn-success" value="保存">
        </div>
        </div>

    {!! Form::close() !!}
    @else
    {!! Form::open(['url'=>action('Admin\Shop\AttributeController@update',['id'=>$attribute->id]),'method'=>'put']) !!}
            <div class="form-group">
                <div class="col-md-10">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">基础选项</h3>
                        </div>
                        <div class="panel-body">
                            <div class="input-group">
                                <span class="input-group-addon">名称</span>
                                <input type="text" class="form-control" name="attribute[name]" placeholder="请输入" value="{{$attribute->name}}" >
                            </div>
                            <br>
                            <div class="input-group">
                                <span class="input-group-addon">销售名称</span>
                                <input type="text" class="form-control" name="attribute[show_name]" placeholder="请输入" value="{{$attribute->show_name}}" >
                            </div>
                            <br>
                            <div class="input-group">
                                <span class="input-group-addon">位置</span>
                                <input type="text" class="form-control" name="attribute[pos]" placeholder="请输入" value="{{$attribute->pos}}" >
                            </div>
                            <br>

                            <div class="input-group">
                                <span class="input-group-addon">状态</span>
                                <input type="text" class="form-control" name="attribute[status]" placeholder="请输入" value="{{$attribute->status}}" >
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 control-label">必填属性</label>
                                <input  style="width:30px;" class="col-lg-3 form-control-static" name="attribute[required]" type="checkbox"
                                        {{ empty($attribute->required) ? "" : "checked" }} value="1" >

                                <label class="col-lg-2 control-label">基础属性</label>
                                <input style="width:30px;" class="col-lg-3 form-control-static" name="attribute[is_base]" type="checkbox"
                                       {{ empty($attribute->is_base) ? "" : "checked" }} value="1">

                                <label class="col-lg-2 control-label">销售属性</label>
                                <input style="width:30px;" class="col-lg-3 form-control-static" name="attribute[is_sell]" type="checkbox"
                                       {{ empty($attribute->is_sell) ? "" : "checked" }} value="1" >

                                <label class="col-lg-2 control-label">操作属性</label>
                                <input style="width:30px;" class="col-lg-3 form-control-static" name="attribute[is_optional]" type="checkbox"
                                       {{ empty($attribute->is_optional) ? "" : "checked" }} value="1">
                            </div>
                            <br>
                            <div class="input-group">
                                <button type="button" data-toggle="modal" data-target="#myModalAddAttributeValue" class="btn btn-lg btn-success">添加属性值</button>
                            </div>
                        </div>
                    </div>

                    <input id="sureAndBack" type="submit" style="width:100%;" class="btn btn-lg btn-success" value="保存">
                </div>
            </div>
        {!! Form::close() !!}


    <div class="modal fade" id="myModalAddAttributeValue" tabindex="-1" role="dialog"
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
                    {!! Form::open(['url'=>action('Admin\Shop\AttributeValueController@store'),'method'=>'post']) !!}
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">基础选项</h3>
                                </div>
                                <div class="panel-body">
                                    <div class="input-group">
                                        <span class="input-group-addon">值</span>
                                        <input type="text" class="form-control" name="attributeValue[value]" placeholder="请输入" value="" >
                                    </div>
                                    <br>
                                    <div class="input-group">
                                        <span class="input-group-addon">状态</span>
                                        <input type="text" class="form-control" name="attributeValue[status]" placeholder="请输入" value="" >
                                    </div>
                                    <br>
                                    <div class="input-group">
                                        <span class="input-group-addon">位置</span>
                                        <input type="text" class="form-control" name="attributeValue[pos]" placeholder="请输入" value="" >
                                    </div>
                                    <br>

                                    <div class="input-group">
                                        <span >目标属性:{{$attribute->name}}</span>

                                            <input  id="shopAttributeId" type="text" name="attributeValue[shop_attribute_id]"  value="{{$attribute->id}}" hidden="true">

                                    </div>
                                </div>
                            </div>
                            <input id="sureAndBack" type="submit" style="width:100%;" class="btn btn-lg btn-success" value="保存">
                        </div>
                    </div>

                    {!! Form::close() !!}
                </div>
                <div class="modal-footer">

                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal -->
    </div>
    @endif
@endsection