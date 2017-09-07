@extends('layouts.admin')

@section('title')
编辑广告位节点类型属性
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
    <script src="{{ asset('assets/admin/js/nodetype/addNodeTypeAttr.js') }}"></script>
    <script>
        setNodeTypeAttrUrl = "{{ action('Admin\NodeTypeController@setNodeTypeAttr') }}"
   	 	nodeTypeUrl = "{{ action('Admin\NodeTypeController@index') }}";
    </script>
@endpush

@section('content')
 		
       @if (empty($nodeType))
            {!! Form::open(['url' => action('Admin\NodeTypeController@store'), 'method' => 'post']) !!}
            	 <div class="col-lg-6" >
                <div class="panel panel-default">
                    <div class="panel-heading"> <h3 class="panel-title">基础选项(可编辑)</h3> </div>
                    <div class="panel-body form-horizontal">
                    
                        <div class="form-group">
                            <label class="col-lg-2 control-label">名称</label>
                            <input class="col-lg-9 form-control-static" name="nodeType[name]" type="text" value="" placeholder="可编辑" aria-describedby="basic-addon1" >
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">模板</label>
                            <input class="col-lg-9 form-control-static" name="nodeType[template]" type="text" value="" placeholder="可编辑" aria-describedby="basic-addon1" >
                        </div>
                         <div class="form-group">
                            <label  class="col-lg-2 control-label">描述</label>
                            <input  id='nodeTypeId'  class="col-lg-9 form-control-static" name="nodeType[description]" type="text" value="" placeholder="可编辑" aria-describedby="basic-addon1" >
                        </div>
                    </div>
                    <input type="submit" style="width:100%;" class="btn btn-lg btn-success" value="保存">
                </div>
            </div>
             
       		 {!! Form::close() !!}
        @else
        	{!! Form::open(['url' => action('Admin\NodeTypeController@update',['id'=>$nodeType->id]), 'method' => 'put']) !!}
        		 <div class="col-lg-6" >
	                <div class="panel panel-default">
	                    <div class="panel-heading"> <h3 class="panel-title">基础选项(可编辑)</h3> </div>
	                    <div class="panel-body form-horizontal">
	                    
	                        <div class="form-group">
	                            <label class="col-lg-2 control-label">名称</label>
	                            <input class="col-lg-9 form-control-static" name="nodeType[name]" type="text" value="{{ $nodeType->name }}" placeholder="可编辑" aria-describedby="basic-addon1" >
	                            <input hidden id='nodeTypeID' class="col-lg-9 form-control-static" name="nodeType[id]" type="text" value="{{ $nodeType->id }}" placeholder="可编辑" aria-describedby="basic-addon1" >
	                            
	                        </div>
	                        <div class="form-group">
	                            <label class="col-lg-2 control-label">显示标签</label>
	                            <input class="col-lg-9 form-control-static" name="nodeType[template]" type="text" value="{{ $nodeType->template }}" placeholder="可编辑" aria-describedby="basic-addon1" >
	                        </div>
	                         <div class="form-group">
	                            <label  class="col-lg-2 control-label">描述</label>
	                            <input  id='nodeTypeId'  class="col-lg-9 form-control-static" name="nodeType[description]" type="text" value="{{ $nodeType->description }}" placeholder="可编辑" aria-describedby="basic-addon1" >
	                        </div>
	                    </div>
	                    <input type="submit" style="width:100%;" class="btn btn-lg btn-success" value="保存">
	                </div>
            	</div>
                <div id="nodeAttrs" data="{{$nodeType->id}}">
                        节点属性数据<br/><br />
                        @foreach($allAttrs as $attr)
                            @if($attr['has']==true)

                            <div><label><input data="{{$attr['id']}}" type="checkbox" checked="true" /> {{$attr['name']}} </label></div>
                            @else
                            <div><label><input data="{{$attr['id']}}" type="checkbox" /> {{$attr['name']}} </label></div>
                            @endif
                        @endforeach
                </div>
            <input type="button" class="btn btn-lg btn-success" id='setNodeTypeAttrBtn' value='设置节点类型属性' ></button>
            {!! Form::close() !!}
        @endif
@endsection
