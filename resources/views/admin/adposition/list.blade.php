@extends('layouts.admin')
@section('title', '广告位')

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
    <script src="/assets/admin/js/adposition/adposition.js"></script>
    <script>
     getListUrl = "{{ action('Admin\AdPositionController@listJson') }}";
     createUrl = "{{ action('Admin\AdPositionController@fillAdpositionForCategory') }}";
     adIndexUrl = "{{ action('Admin\AdPositionController@index') }}";
     resetNodeUrl = "{{ action('Admin\AdPositionController@resetNode') }}";
     delAdUrl = "{{ action('Admin\AdPositionController@index') }}";
    </script>
@endpush


@section('content')
      <button type="button" onClick="window.location.href='{{ action('Admin\AdPositionController@create') }}';" class="btn btn-lg btn-success">新建</button>
      @permission(['all', 'ad-genby-category'])
      @endpermission
        <div class="table-responsive">
            <table id="table" class="table table-striped">
                <thead>
                    <th>操作</th>
                    <th>ID</th>
                    <th>名称</th>
                    <th>数据码</th>
                    <th>描述</th>
                    <th>节点类型</th>
                </thead>
                <tfoot>
		            <tr>
		                <th>操作</th>
		                <th>ID</th>
		                <th>名称</th>
		                <th>数据码</th>
		                <th>描述</th>
                        <th>节点类型</th>
		            </tr>
		        </tfoot>
                <tbody>
                </tbody>
            </table>
        </div>
        
         <!-- 重置广告位模态框（Modal） -->
            <div class="modal fade" id="myModalReset" tabindex="-1" role="dialog" 
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
                                确定要重置数据吗？
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" 
                                        data-dismiss="modal">后悔药
                                </button>
                                <button id="sure-reset" type="button" class="btn btn-primary">
                                    果断重置
                                </button>
                            </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal -->	
            </div>
            
            
             <!-- 删除广告位模态框（Modal） -->
            <div class="modal fade" id="myModalDel" tabindex="-1" role="dialog" 
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
                                确定要删除数据位吗？
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" 
                                        data-dismiss="modal">后悔药
                                </button>
                                <button id="sure-del" type="button" class="btn btn-primary">
                                    果断删除
                                </button>
                            </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal -->	
            </div>
            
             <!-- 批量生成广告位模态框（Modal） -->
            <div class="modal fade" id="myModalAddAdpositions" tabindex="-1" role="dialog" 
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
                                确定要根据文章分类生成对应的广告位吗？
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" 
                                        data-dismiss="modal">后悔药
                                </button>
                                <button id="sure-addAdpositions" type="button" class="btn btn-primary">
                                    是哒！
                                </button>
                            </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal -->	
            </div>
            
            <!-- 一键创建PC分类广告位模态框（Modal） -->
            <div class="modal fade" id="myModalAddAdpositionsForPC" tabindex="-1" role="dialog" 
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
                                确定要根据文章分类生成对应PC的广告位吗？
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" 
                                        data-dismiss="modal">后悔药
                                </button>
                                <button id="sure-addAdpositionsForPC" type="button" class="btn btn-primary">
                                    是哒！
                                </button>
                            </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal -->	
            </div>
@endsection
