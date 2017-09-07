@extends('layouts.admin')

@push('stylesheets')
<link media="all" type="text/css" rel="stylesheet" href="{{ asset('assets/libs/jquerytree/css/jquery.treeview.css') }}" />
<style type="text/css">
 .treeview a.selected
 {
     background-color:#385DB1;
 }
 .list-group-item {
     position: relative;
     display: block;
     padding: 3px 3px 3px 15px;
     margin-bottom: -1px;
     background-color: #fff;
     border: 1px solid #ddd;
 }
 a.list-group-item:hover
 {
     color:#ffffff;
     background-color:#339933;
 }
</style>
@endpush

@push('scripts')

<script src="{{ asset('assets/admin/js/category/index_category.js') }}"></script>
<script src="{{ asset('assets/libs/jquerytree/treeview/jquery.treeview.js') }}"></script> 
<script src="{{ asset('assets/libs/jquerytree/lib/jquery.cookie.js') }}"></script> 

@endpush

@section('content')
    
    
    <div class="row">
        <div class="col-md-2"> 
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">分类列表</h3>
                </div>
                <div class="panel-body">
                    <ul id="tree">
                        @if (!empty($categoryData) )
                            
                            @if (empty($formData) || !($formData && ($formData[0]->pageMode=="edit" || $formData[0]->pageMode=="create")))
                                @foreach ($categoryData[0] as $firstKey => $firstValue)
                                    @if (!empty($formData) && ($formData[0]->id == $firstKey))
                                        <li><a class="list-group-item active" href="{{ $categoryData[1][$firstKey]['href'] }}">{{ $categoryData[1][$firstKey]['title'] }}</a>
                                    @else
                                            <li><a class="list-group-item" href="{{ $categoryData[1][$firstKey]['href'] }}">{{ $categoryData[1][$firstKey]['title'] }}</a>
                                    @endif
                                    @if (count($firstValue)>0)
                                        <ul>
                                            @foreach($firstValue as $secondKey => $secondValue)
                                                @if (!empty($formData) && ($formData[0]->id == $secondKey))
                                                    <li><a class="list-group-item active" href="{{ $categoryData[1][$secondKey]['href'] }}">{{ $categoryData[1][$secondKey]['title'] }}</a>
                                                @else
                                                        <li><a class="list-group-item" href="{{ $categoryData[1][$secondKey]['href'] }}"> {{ $categoryData[1][$secondKey]['title'] }}</a>
                                                @endif
                                                @if (count($secondValue)>0)
                                                    <ul>  
                                                        @foreach($secondValue as $thirdKey => $thirdValue)
                                                            @if (!empty($formData) && ($formData[0]->id == $thirdKey))
                                                                <li><a class="list-group-item active" href="{{ $categoryData[1][$thirdKey]['href'] }}"> {{ $categoryData[1][$thirdKey]['title'] }}</a>
                                                            @else
                                                                    <li><a class="list-group-item" href="{{ $categoryData[1][$thirdKey]['href'] }}"> {{ $categoryData[1][$thirdKey]['title'] }}</a>
                                                            @endif
                                                                    </li>
                                                        @endforeach	
                                                    </ul>
                                                @endif
                                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                                        </li>
                                @endforeach
                            @endif
                        @endif
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-8"> 
            @if (!empty($formData))
                {!! Form::open(['url'=>$formData[0]->actionPath,'method'=>$formData[0]->method]) !!}
                <div class="form-group">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-addon">类别名称</span>
                            <input type="text" class="form-control" name="title" placeholder="请输入" value="{{ $formData[0]->title }}" {{ $formData[0]->editable ? "" : "readonly" }}>
                        </div>
                        <br>
                        <div class="input-group">
                            <span class="input-group-addon">类别描述</span>
                            <textarea type="text" class="form-control" name="description" placeholder="请输入"  {{ $formData[0]->editable ? "" : "readonly" }} >{{ $formData[0]->description }}</textarea>
                        </div>
                        <br>
                        
                        <div class="input-group">
                            <span class="input-group-addon" >父级类别</span>
                            <input type="text"  id="ptitle"  onclick="onFocus()" class="form-control" name="ptitle" placeholder="默认为顶级分类" value="{{ $formData[0]->ptitle }}" readOnly>
                            <span class="input-group-addon" onclick="setNull()">清除</span>
                        </div>
                        <input type="hidden"  id="pid" class="form-control" name="pid" placeholder="默认为顶级分类" value="{{ $formData[0]->pid }}" readOnly="true">
                        <br>
                        
                        
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-addon">SEO标题</span>
                            <input type="text" class="form-control" name="seo_title" placeholder="请输入" value="{{ $formData[0]->seo_title }}"  {{ $formData[0]->editable ? "" : "readonly" }}>
                        </div>
                        <br>
                        <div class="input-group">
                            <span class="input-group-addon">SEO描述</span>
                            <textarea type="text" class="form-control" name="seo_description" placeholder="请输入"  {{ $formData[0]->editable ? "" : "readonly" }}>{{ $formData[0]->title }}</textarea>
                        </div>
                        <br>
                        
                        <div class="input-group">
                            <span class="input-group-addon">SEO密码</span>
                            <input type="password" class="form-control" name="seo_keywords" placeholder="请输入" value="{{ $formData[0]->seo_keywords }}"  {{ $formData[0]->editable ? "" : "readonly" }}>
                        </div>
                        <br>
                        
                        
                    </div>
                </div>
                
                <br />
                
                <ul id="tree1" style="display:none" >
                    @if (!empty($categoryData) && $formData[0]->pageMode!="show")
                        <div class="col-md-4" >
                            @foreach ($categoryData[0] as $firstKey => $firstValue)
                                @if ($firstKey == $formData[0]->id)
                                    <li ><a class="list-group-item active" onclick=""><strong id="{{ $firstKey }}"> {{ $categoryData[1][$firstKey]['title'] }}</strong></a>
                                @else
                                        <li ><a class="list-group-item"  onclick="onSelectPID()"><strong id="{{ $firstKey }}"> {{ $categoryData[1][$firstKey]['title'] }}</strong></a>
                                @endif
                                @if(count($firstValue)>0)
                                    <ul>
                                        @foreach($firstValue as $secondKey => $secondValue)
                                            @if ($secondKey == $formData[0]->id)
                                                <li><a class="list-group-item active" onclick=""><strong id="{{ $secondKey }}"> {{ $categoryData[1][$secondKey]['title'] }}</strong></a>
                                            @else
                                                    <li><a class="list-group-item" onclick="onSelectPID()"><strong id="{{ $secondKey }}"> {{ $categoryData[1][$secondKey]['title'] }}</strong></a>
                                            @endif
                                            @if(count($secondValue)>0)
                                                <ul>
                                                    @foreach($secondValue as $thirdKey => $thirdValue)
                                                        @if ($thirdKey == $formData[0]->id)
                                                            <li ><a class="list-group-item active"  onclick=""><strong id="{{ $thirdKey }}"> {{ $categoryData[1][$thirdKey]['title'] }}</strong></a>
                                                        @else
                                                                <li ><a class="list-group-item" onclick=""><strong id="{{ $thirdKey }}"> {{ $categoryData[1][$thirdKey]['title'] }}</strong></a>
                                                        @endif
                                                                </li>
                                                    @endforeach	
                                                </ul>
                                            @endif
                                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                                                    </li>
                            @endforeach
                        </div>
                    @endif
                </ul>
                
                <dir class="col-md-6">
                    @if ($formData[0]->pageMode == "edit")
                        <button type="submit" class="btn btn-default" value="确定">确定</button>
                    @elseif ($formData[0]->pageMode == "show")
                        <a href="{{ $formData[0]->btnPath }}"><button type="button" class="btn btn-default" >编辑</button></a>
                    @elseif ($formData[0]->pageMode == "create")
                        <button type="submit" class="btn btn-default" value="提交新分类">提交新分类</button>
                    @endif
                    {!! Form::close() !!}
                </dir>
            @else
                <a href="{{ action('Admin\ContentCatalogController@create') }}"><button type="button" class="btn btn-primary btn-lg" >创建新分类</button></a>
            @endif

        </div>
    </div>
@endsection
