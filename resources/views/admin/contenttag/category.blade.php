@extends('layouts.admin')
@section('title', '编辑:: '.$category->title.' :: 资讯标签分组')

    @push('stylesheets')
    <style>
    </style>
    @endpush

    @push('scripts')
    <script>
     function onMouseDown(){
         if(event.target.id == "sureAndBackList"){
             $('#backtype').attr('value',0);
         }else{
             $('#backtype').attr('value',1);
         }
     }
    </script>
    @endpush

    @section('content')
        {!! Form::open(['url' => action('Admin\ContentTagController@updateCategory', ['id'=>$category->id]), 'method' => 'put']) !!}
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="row">
            <div class="col-lg-12" >
                <div class="panel panel-default">
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title text-center">{{ $category->title }} 组 -- 关键字选择</h3>
            </div>

            <div class="panel-body form-horizontal">
                <div class="row" >
                    <div class="col-lg-10 col-lg-offset-1" >
                        @foreach ($catTags as $c => $tags)
                            @if (!empty($tags))
                                <div class="panel panel-default" >
                                    <div class="panel-heading">
                                        <h3 class="panel-title text-center">{{ $c }} </h3>
                                    </div>
                                    <div class="panel-body" >
                                        @foreach ($tags as $tag)
                                            <label class="col-lg-2 control-label">{{ $tag->name }}</label>
                                            <input class="col-lg-1 form-control-static" name="content_category_tags[]" type="checkbox"
                                                   {{ in_array($tag->id, $checkeds) ? "checked" : "" }}
                                                   value="{{ $tag->id }}" aria-describedby="basic-addon1">
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="row" >
            <div class="col-md-4" ></div>
            <input hidden="true" id="backtype" class="col-lg-9 form-control-static" name="other[backtype]" type="text" value="0" aria-describedby="basic-addon1">

            <div class="col-md-4" >
                <input id="sureAndBackList" onmousedown="onMouseDown()" type="submit" style="width:100%;" class="btn btn-lg btn-success" value="保存并返回列表">
            </div>
            <div class="col-md-4" >
                
                <input id="sureAndBack" onmousedown="onMouseDown()" type="submit" style="width:100%;" class="btn btn-lg btn-success" value="保存">
            </div>
        </div>
        {!! Form::close() !!}
    @endsection
