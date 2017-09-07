@extends('layouts.admin')

@section('title',' 新建属性值')



@push('stylesheets')
@endpush

@push('scripts')
@endpush

@section('content')

    @if(empty($attributeValue))
    {!! Form::open(['url'=>action('Admin\Shop\AttributeValueController@store'),'method'=>'post']) !!}
    <div class="form-group">
        <div class="col-md-6">
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
                        <span >目标属性</span>
                        <select class="selectpicker">
                            <option>--</option>
                            @foreach($attributeList as $key=>$value)
                                <option id="{{$value->id}}">{{$value->name}}</option>

                            @endforeach
                            <input type="text" name="attributeValue[shop_attribute_id]"  value="" hidden="true">
                        </select>

                    </div>
                </div>
            </div>
            <input id="sureAndBack" onmousedown="onMouseDown()" type="submit" style="width:100%;" class="btn btn-lg btn-success" value="保存">
        </div>
        </div>

    {!! Form::close() !!}
    @else
    {!! Form::open(['url'=>action('Admin\Shop\AttributeValueController@update',['id'=>$attributeValue->id]),'method'=>'put']) !!}
            <div class="form-group">
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">基础选项</h3>
                        </div>
                        <div class="panel-body">
                            <div class="input-group">
                                <span class="input-group-addon">值</span>
                                <input type="text" class="form-control" name="attributeValue[value]" placeholder="请输入" value="{{$attributeValue->value}}" >
                            </div>
                            <br>
                            <div class="input-group">
                                <span class="input-group-addon">状态</span>
                                <input type="text" class="form-control" name="attributeValue[status]" placeholder="请输入" value="{{$attributeValue->status}}" >
                            </div>
                            <br>
                            <div class="input-group">
                                <span class="input-group-addon">位置</span>
                                <input type="text" class="form-control" name="attributValue[pos]" placeholder="请输入" value="{{$attributeValue->pos}}" >
                            </div>
                            <br>

                            <div class="input-group">
                                <span >目标属性</span>
                                <select class="selectpicker">
                                    <option>--</option>
                                    @foreach($attributeList as $key=>$value)
                                        <option id="{{$value->id}}">{{$value->name}}</option>

                                    @endforeach
                                    <input type="text" name="attributeValue[shop_attribute_id]"  value="" hidden="true">
                                </select>

                            </div>
                        </div>
                    </div>
                    <input id="sureAndBack" onmousedown="onMouseDown()" type="submit" style="width:100%;" class="btn btn-lg btn-success" value="保存">
                </div>
            </div>
        {!! Form::close() !!}
    @endif
@endsection