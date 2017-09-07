@extends('layouts.admin')

@section('title')
编辑广告位节点属性
@endsection

@push('stylesheets')
	<link media="all" type="text/css" rel="stylesheet" href="{{ asset('assets/libs/datetimepicker-2.3.7/build/jquery.datetimepicker.min.css') }}">
    <link media="all" type="text/css" rel="stylesheet" href="{{ asset('assets/libs/jquerycolorbox/colorbox.css') }}">
    <style>

    </style>
@endpush

@push('scripts')
	<script>

	</script>
    <script src="{{ asset('assets/libs/datetimepicker-2.3.7/build/jquery.datetimepicker.full.min.js') }}"></script>
    <script src="{{ asset('assets/libs/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('assets/libs/jquerycolorbox/jquery.colorbox-min.js') }}"></script>
    <script src="{{ asset('assets/libs/elfinder/standalonepopup.js') }}"></script>
    <scrpit src="{{ asset('packages/sleepingowl/default/libs/bootstrap/js/bootstrap.min.js') }}"></scrpit>
    <script src="{{ asset('assets/admin/js/adposition/selectNodeAttrType.js') }}"></script>
    
@endpush

@section('content')
 		
       @if (empty($nodeAttrType))
            {!! Form::open(['url' => action('Admin\NodeAttrTypeController@store'), 'method' => 'post']) !!}
            	 <div class="col-lg-6" >
                <div class="panel panel-default">
                    <div class="panel-heading"> <h3 class="panel-title">基础选项(可编辑)</h3> </div>
                    <div class="panel-body form-horizontal">
                    
                        <div class="form-group">
                            <label class="col-lg-2 control-label">名称</label>
                            <input class="col-lg-9 form-control-static" name="nodeAttrType[name]" type="text" value="" placeholder="可编辑" aria-describedby="basic-addon1" >
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">显示标签</label>
                            <input class="col-lg-9 form-control-static" name="nodeAttrType[display_label]" type="text" value="" placeholder="可编辑" aria-describedby="basic-addon1" >
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">值类型</label>
                            <input class="col-lg-9 form-control-static" name="nodeAttrType[value_type]" type="text" value="" placeholder="可编辑" aria-describedby="basic-addon1" >
                        </div>
                         <div class="form-group">
                            <label  class="col-lg-2 control-label">描述</label>
                            <input  id='nodeTypeId'  class="col-lg-9 form-control-static" name="nodeAttrType[description]" type="text" value="" placeholder="可编辑" aria-describedby="basic-addon1" >
                        </div>
                    </div>
                    <input type="submit" style="width:100%;" class="btn btn-lg btn-success" value="保存">
                </div>
            </div>
             
       		 {!! Form::close() !!}
        @else
        	{!! Form::open(['url' => action('Admin\NodeAttrTypeController@update',['id'=>$nodeAttrType->id]), 'method' => 'put']) !!}
        		 <div class="col-lg-6" >
	                <div class="panel panel-default">
	                    <div class="panel-heading"> <h3 class="panel-title">基础选项(可编辑)</h3> </div>
	                    <div class="panel-body form-horizontal">
	                    
	                        <div class="form-group">
	                            <label class="col-lg-2 control-label">名称</label>
	                            <input class="col-lg-9 form-control-static" name="nodeAttrType[name]" type="text" value="{{ $nodeAttrType->name }}" placeholder="可编辑" aria-describedby="basic-addon1" >
	                            <input hidden class="col-lg-9 form-control-static" name="nodeAttrType[id]" type="text" value="{{ $nodeAttrType->id }}" placeholder="可编辑" aria-describedby="basic-addon1" >
	                            
	                        </div>
	                        <div class="form-group">
	                            <label class="col-lg-2 control-label">显示标签</label>
	                            <input class="col-lg-9 form-control-static" name="nodeAttrType[display_label]" type="text" value="{{ $nodeAttrType->display_label }}" placeholder="可编辑" aria-describedby="basic-addon1" >
	                        </div>
	                        <div class="form-group">
	                            <label class="col-lg-2 control-label">值类型</label>
	                            <input class="col-lg-9 form-control-static" name="nodeAttrType[value_type]" type="text" value="{{ $nodeAttrType->value_type }}" placeholder="可编辑" aria-describedby="basic-addon1" >
	                        </div>
	                         <div class="form-group">
	                            <label  class="col-lg-2 control-label">描述</label>
	                            <input  id='nodeTypeId'  class="col-lg-9 form-control-static" name="nodeAttrType[description]" type="text" value="{{ $nodeAttrType->description }}" placeholder="可编辑" aria-describedby="basic-addon1" >
	                        </div>
	                    </div>
	                    <input type="submit" style="width:100%;" class="btn btn-lg btn-success" value="保存">
	                </div>
            	</div>
        	
        	{!! Form::close() !!}
        @endif
@endsection
