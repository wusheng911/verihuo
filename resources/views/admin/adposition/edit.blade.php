@extends('layouts.admin')

@section('title')
编辑广告位
@endsection

@push('stylesheets')
	<link media="all" type="text/css" rel="stylesheet" href="{{ asset('assets/libs/datetimepicker-2.3.7/build/jquery.datetimepicker.min.css') }}">
    <link media="all" type="text/css" rel="stylesheet" href="{{ asset('assets/libs/jquerycolorbox/colorbox.css') }}">
    <style>

    </style>
@endpush

@push('scripts')
	<script>

		getNodeListUrl = "{{ action('Admin\NodeAttrController@listJson') }}";
		saveNodeAttrUrl = "{{ action('Admin\NodeAttrController@saveAttrJson') }}";
		getAllContentsUrl = "{{ action('Admin\ContentController@getAllContents') }}"
		getArtilesListUrl = "{{ action('Admin\ContentController@getArticlesListByName') }}"
	</script>
    <script src="{{ asset('assets/libs/datetimepicker-2.3.7/build/jquery.datetimepicker.full.min.js') }}"></script>
    <script src="{{ asset('assets/libs/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('assets/libs/jquerycolorbox/jquery.colorbox-min.js') }}"></script>
    <script src="{{ asset('assets/libs/elfinder/standalonepopup.js') }}"></script>
    <script src="{{ asset('packages/sleepingowl/default/libs/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/adposition/selectNodeAttrType.js') }}"></script>
    <script src="{{ asset('assets/admin/js/adposition/create_adposition.js') }}"></script>
    
@endpush

@section('content')
 		@if (empty($ad))
            {!! Form::open(['url' => action('Admin\AdPositionController@store'), 'method' => 'post']) !!}
            	 <div class="col-lg-6" >
                <div class="panel panel-default">
                    <div class="panel-heading"> <h3 class="panel-title">基础选项(可编辑)</h3> </div>
                    <div class="panel-body form-horizontal">
                    
                        <div class="form-group">
                            <label class="col-lg-2 control-label">名称</label>
                            <input class="col-lg-9 form-control-static" name="ad[name]" type="text" value="" placeholder="可编辑" aria-describedby="basic-addon1" >
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">数据码</label>
                            <input id="adcode" class="col-lg-9 form-control-static" name="ad[adposition_code]" type="text" value="" placeholder="可编辑" aria-describedby="basic-addon1" >
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">描述</label>
                            <input class="col-lg-9 form-control-static" name="ad[description]" type="text" value="" placeholder="可编辑" aria-describedby="basic-addon1" >
                        </div>
                         <div class="form-group">
                            <label  class="col-lg-2 control-label">节点类型</label>
                            	@if(!empty($nodeTypes))
                            	 <select id="selectNodeTypeContainer">
                            	 	<option>--</option>
                            		@foreach($nodeTypes as $key => $value)
                            			<option id="{{$value->id}}">{{$value->name}}</option>
                            		@endforeach
                           		 </select>
                            	@endif
                           
                            <input hidden='true' id='nodeTypeId'  class="col-lg-9 form-control-static" name="ad[node_type_id]" type="text" value="" placeholder="可编辑" aria-describedby="basic-addon1" >
                        </div>
                    </div>
                    <input  id='sureCreateAdposition' type="submit" style="width:100%;" class="btn btn-lg btn-success" value="保存">
                </div>
            </div>
             
       		 {!! Form::close() !!}
        @else
            <div class="col-lg-6" >
                <div class="panel panel-default">
                    <div class="panel-heading"> <h3 class="panel-title">数据内容(可编辑)</h3> </div>
                    <div class="panel-body form-horizontal">
                    <div id="containerList">
                    @foreach ($nodeAttrs as $key => $value)
                    	@if($value->node_attr_id->value_type == 'filepath')
                    	<div id="container">
	                    	<div class="form-group">
	                            <label class="col-lg-2 control-label">{{ $value->node_attr_id->display_label }}</label>
	                           <div class="col-lg-5" >
	                            <a href="" class="popup_selector" data-inputid="feature_image_{{ $key }}"><img id="img_feature_image_{{ $key }}" style="width:150px;height:150px;" for="feature_image_{{ $key }}" alt="" src="{{$value->value}}"></a> 
				    			<input hidden='true' type="text" data="{{$value->node_id}},{{$value->node_attr_id->id}},{{$value->seq_no}}" name="{{$value->node_attr_id}}" id="feature_image_{{ $key }}"  value="{{$value->value}}"/>
	                        </div>
	                        </div>
                        </div>
                    	@elseif( $value->node_attr_id->value_type == 'strval')
                    	<div id="container">
	                    	<div class="form-group">
	                            <label class="col-lg-2 control-label">{{ $value->node_attr_id->display_label }}</label>
	                            <input  name="" data="{{$value->node_id}},{{$value->node_attr_id->id}},{{$value->seq_no}}"  class="col-lg-9 form-control-static" type="text" value="{{$value->value}}" >
	                        </div>
                        </div>
                    	@elseif( $value->node_attr_id->value_type == 'article')
                    	<div id="container">
	                    	<div class="form-group">
	                            <label id='nameLabel' class="col-lg-2 control-label">{{ $value->node_attr_id->display_label }}</label>
	                            <input id="articleInput"  oninput='onInputChange()' name="" data="{{$value->node_id}},{{$value->node_attr_id->id}},{{$value->seq_no}}" class="col-lg-9 form-control-static" type="text" value="{{$value->value}}" >
	                            <div id='promptBox'></div>
	                        </div>
                        </div>
                        @else
                        <div id="container">
	                    	<div class="form-group">
	                            <label class="col-lg-2 control-label">{{ $value->node_attr_id->display_label }}</label>
	                            <input  name="" data="{{$value->node_id}},{{$value->node_attr_id->id}},{{$value->seq_no}}"  class="col-lg-9 form-control-static" type="text" value="{{$value->value}}" >
	                        </div>
                        </div>
                    	@endif
                    @endforeach
                    </div>
                    <input type="button" onclick='addItem()' class="btn btn-primary btn-lg" value='新建内容'>
                    <input type="button" onclick='saveItems()' class="btn btn-primary btn-lg" value='保存'>
                    </div>
                </div>
                
            </div>
            {!! Form::open(['url' => action('Admin\AdPositionController@editBase'), 'method' => 'post']) !!}
             <div class="col-lg-6" >
                <div class="panel panel-default">
                    <div class="panel-heading"> <h3 class="panel-title">基础选项(部分可编辑)</h3> </div>
                    <div class="panel-body form-horizontal">
                    
                     	<div class="form-group">
                            <label class="col-lg-2 control-label">ID</label>
                            <input class="col-lg-9 form-control-static" name="ad[id]" type="text" value="{{$ad->id}}" placeholder="不可编辑" aria-describedby="basic-addon1" readonly>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">名称(可编辑)</label>
                            <input class="col-lg-9 form-control-static" name="ad[name]" type="text" value="{{$ad->name}}" placeholder="可编辑" aria-describedby="basic-addon1" >
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">数据码</label>
                            <input class="col-lg-9 form-control-static" name="ad[adposition_code]" type="text" value="{{$ad->adposition_code}}" placeholder="不可编辑" aria-describedby="basic-addon1" readonly>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">描述(可编辑)</label>
                            <input class="col-lg-9 form-control-static" name="ad[description]" type="text" value="{{$ad->description}}" placeholder="可编辑" aria-describedby="basic-addon1" >
                        </div>
                         <div class="form-group">
                            <label class="col-lg-2 control-label">节点类型名称</label>
                            <input class="col-lg-9 form-control-static" name="" type="text" value="{{$nodeType->name}}" placeholder="不可编辑" aria-describedby="basic-addon1" readonly>
                        </div>
                         <div class="form-group">
                            <label class="col-lg-2 control-label">所用模板</label>
                            <input class="col-lg-9 form-control-static" name="" type="text" value="{{$nodeType->template}}" placeholder="不可编辑" aria-describedby="basic-addon1" readonly>
                        </div>
                        @if(!empty($node))
                         <div class="form-group">
                            <label hidden='true' class="col-lg-2 control-label">节点ID</label>
                            <input id='nodeId' hidden='true' class="col-lg-9 form-control-static" name="nodeId" type="text" value="{{ $node->nodeId }}" placeholder="不可编辑" aria-describedby="basic-addon1" readonly>
                            <input id='nodeTypeId' hidden='true'  class="col-lg-9 form-control-static" name="nodeTypeId" type="text" value="{{$ad->node_type_id}}" placeholder="不可编辑" aria-describedby="basic-addon1" readonly>
                        </div>
                        @endif
                        <input type="submit" style="width:100%;" class="btn btn-lg btn-success" value="保存">
                    </div>
                </div>
                 
            </div>
            {!! Form::close() !!}
        @endif
       
@endsection
