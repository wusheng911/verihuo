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
    <script src="{{ asset('assets/admin/js/category/edit_category.js') }}"></script>
    @endpush
    
    
    @section('content')
        
        <div class="col-md-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">分类列表</h3>
                </div>
                <div class="panel-body">
                    <ul id="tree1">
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
            {!! Form::open(['url'=> action('Admin\ContentCatalogController@update', ['id'=>$contentcategory->id]),'method'=>'put']) !!}
            <div class="form-group">
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">基础选项</h3>
                        </div>
                        <div class="panel-body">
                            <div class="input-group">
                                <span class="input-group-addon">类别名称</span>
                                <input type="hidden"  id="id" class="form-control" name="contentcategory[id]" placeholder="id" value="{{ $contentcategory->id }}" readOnly>
                                <input type="text" class="form-control" name="contentcategory[title]" placeholder="请输入" value="{{$contentcategory->title}}" >
                            </div>
                            <br>
                            <div class="input-group">
                                <span class="input-group-addon">类别描述</span>
                                <textarea type="text" class="form-control" name="contentcategory[description]" placeholder="请输入" >{{$contentcategory->description}}</textarea>
                            </div>
                            <br>
                            <div class="form-group">
                                <span class="">父类</span>
                                <div  style="display: inline;" class="input-group" id="selectCategoryContainer" onchange='oncategorychange()'></div>
                                <input id="content_category_id" type="hidden" class="col-lg-9 form-control-static" name="contentcategory[pid]" type="text" value="{{$contentcategory->pid}}" placeholder="父级类别" aria-describedby="basic-addon1">
                            </div>
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
                                <input type="text" class="form-control" name="contentcategory[seo_title]" placeholder="请输入" value="{{$contentcategory->seo_title}}">
                            </div>
                            <br>
                            <div class="input-group">
                                <span class="input-group-addon">SEO描述</span>
                                <textarea type="text" class="form-control" name="contentcategory[seo_description]" placeholder="请输入">{{$contentcategory->seo_description}}</textarea>
                            </div>
                            <br>
                            
                            <div class="input-group">
                                <span class="input-group-addon">SEO关键字</span>
                                <input type="text" class="form-control" name="contentcategory[seo_keywords]" placeholder="请输入" value="{{$contentcategory->seo_keywords}}"  >
                            </div>
                            <br>
                        </div>
                    </div>
                </div>
            </div>
            
            <br />
            
            
            <dir class="col-md-6">
                <button type="submit" class="btn btn-primary btn-lg" value="提交">提交</button>
                
            </dir>
            
            {!! Form::close() !!}
            <dir class="col-md-6">
                <button class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">删除</button>
            </dir>
            <!-- 模态框（Modal） -->
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" 
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
                        
                        @if( $contentcategory->childrencount > 0)
                            <div class="modal-body">
                                请先删除该分类的所有子分类（一般人我不告诉他）！
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" 
                                        data-dismiss="modal">关闭
                                </button>
                            </div>
                        @else
                            <div class="modal-body">
                                确定要删除吗？再考虑一下好不好？
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" 
                                        data-dismiss="modal">后悔药
                                </button>
                                <button id="sure-del" type="button" class="btn btn-primary">
                                    果断删除
                                </button>
                            </div>
                        @endif
                    </div><!-- /.modal-content -->
                </div><!-- /.modal -->	
            </div>
        </div>
        
    @endsection
