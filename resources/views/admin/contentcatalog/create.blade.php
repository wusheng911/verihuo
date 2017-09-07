@extends('layouts.admin')

@section('title', '博客分类')
    
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
    <script>
     indexUrl = "{{ action('Admin\ContentCatalogController@index') }}";
     getParentsUrl = "{{ action('Admin\ContentCatalogController@getParentCategorys') }}";
     getChildrenUrl = "{{ action('Admin\ContentCatalogController@getChildernCategorys') }}";
     getChildrenListUrl = "{{ action('Admin\ContentCatalogController@childrenListJson') }}"; 
    </script>
    <script src="{{ asset('assets/libs/jquerytree/treeview/jquery.treeview.js') }}"></script> 
    <script src="{{ asset('assets/libs/jquerytree/lib/jquery.cookie.js') }}"></script> 
    <script src="{{ asset('packages/sleepingowl/default/libs/bootstrap/js/bootstrap.min.js') }}"></script> 
    <script src="{{ asset('assets/admin/js/category/category_data.js') }}"></script>
    <script src="{{ asset('assets/admin/js/category/create_category.js') }}"></script>
    @endpush
    
    
    @section('content')
        
        <div class="col-md-2"> 
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">分类列表</h3>
                </div>
                <div class="panel-body">
                    <ul id="tree">
                        @foreach ($categoryData[0] as $firstKey => $firstValue)
                            <li><a class="list-group-item" href="{{ $categoryData[1][$firstKey]['href'] }}">{{ $categoryData[1][$firstKey]['title'] }}</a>
                                @if (count($firstValue)>0)
                                    <ul>
                                        @foreach($firstValue as $secondKey => $secondValue)
                                            <li><a class="list-group-item" href="{{ $categoryData[1][$secondKey]['href'] }}"> {{ $categoryData[1][$secondKey]['title'] }}</a>
                                                @if (count($secondValue)>0)
                                                    <ul>  
                                                        @foreach($secondValue as $thirdKey => $thirdValue)
                                                            <li><a class="list-group-item" href="{{ $categoryData[1][$thirdKey]['href'] }}"> {{ $categoryData[1][$thirdKey]['title'] }}</a>
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
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-8"> 
            {!! Form::open(['url'=>action('Admin\ContentCatalogController@store'),'method'=>'post']) !!}
            <div class="form-group">
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">基础选项</h3>
                        </div>
                        <div class="panel-body">
                            <div class="input-group">
                                <span class="input-group-addon">类别名称</span>
                                <input type="text" class="form-control" name="contentcategory[title]" placeholder="请输入" value="" >
                            </div>
                            <br>
                            <div class="input-group">
                                <span class="input-group-addon">类别描述</span>
                                <textarea type="text" class="form-control" name="contentcategory[description]" placeholder="请输入" ></textarea>
                            </div>
                            <br>
                            <div class="form-group">
                                <span >父类</span>
                                <div style="display: inline;" id="selectCategoryContainer" onchange='oncategorychange()'></div>
                                <input id="content_category_id" type="hidden" class="col-lg-9 form-control-static" name="contentcategory[pid]" type="text" value="{{$contentcategory->pid}}" placeholder="父级类别" aria-describedby="basic-addon1">
                            </div>
                            
                            <br>
                        </div>
                    </div> 
                </div>
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">SEO相关</h3>
                        </div>
                        <div class="panel-body">
                            <div class="input-group">
                                <span class="input-group-addon">SEO标题</span>
                                <input type="text" class="form-control" name="contentcategory[seo_title]" placeholder="请输入" value="">
                            </div>
                            <br>
                            <div class="input-group">
                                <span class="input-group-addon">SEO描述</span>
                                <textarea type="text" class="form-control" name="contentcategory[seo_description]" placeholder="请输入"></textarea>
                            </div>
                            <br>
                            
                            <div class="input-group">
                                <span class="input-group-addon">SEO关键字</span>
                                <input type="text" class="form-control" name="contentcategory[seo_keywords]" placeholder="请输入" value=""  >
                            </div>
                            <br>
                        </div>
                    </div>
                </div>
            </div>
            
            <br />
            
            <ul id="tree" style="display:none" >
                @if (!empty($categoryData))
                    <div class="col-md-4" >
                        @foreach ($categoryData[0] as $firstKey => $firstValue)
                            <li ><a class="list-group-item"  onclick="onSelectPID()"><strong id="{{ $firstKey }}"> {{ $categoryData[1][$firstKey]['title'] }}</strong></a>
                                @if(count($firstValue)>0)
                                    <ul>
                                        @foreach($firstValue as $secondKey => $secondValue)
                                            <li><a class="list-group-item" onclick="onSelectPID()"><strong id="{{ $secondKey }}"> {{ $categoryData[1][$secondKey]['title'] }}</strong></a>
                                                @if(count($secondValue)>0)
                                                    <ul>
                                                        @foreach($secondValue as $thirdKey => $thirdValue)
                                                            <li ><a class="list-group-item" onclick=""><strong id="{{ $thirdKey }}"> {{ $categoryData[1][$thirdKey]['title'] }}</strong></a>
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
            <!--  <dir class="col-md-6">
                 <button class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">父类</button>
                 </dir>  -->
            <button type="submit" class="btn btn-primary btn-lg" value="提交新分类">提交</button>
            <a href="/admin/contentcatalog/"><button type="button" class="btn btn-primary btn-lg" value="撤销新分类">取消</button></a>
            {!! Form::close() !!}
            
            
        </div>
    @endsection
