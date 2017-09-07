@extends('layouts.admin')
@section('title', '节点属性类型')

@push('stylesheets')
	<link media="all" type="text/css" rel="stylesheet" href="/packages/sleepingowl/default/libs/datatables/css/dataTables.bootstrap.min.css">
    <link media="all" type="text/css" rel="stylesheet" href="/packages/sleepingowl/default/libs/datatables/css/jquery.dataTables.min.css">
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
	<script src="/packages/sleepingowl/default/libs/datatables/js/jquery.dataTables.min.js"></script>
    <script src="/packages/sleepingowl/default/js/datatables.min.js"></script>
    <script src="/packages/sleepingowl/default/libs/datatables-responsive/js/dataTables.responsive.js"></script>
    <script src="{{ asset('assets/admin/js/nodeattrtype/nodeattrtype.js') }}"> </script>
    <script>
    getListUrl = "{{ action('Admin\NodeAttrTypeController@listJson') }}";
    delAdUrl = "{{ action('Admin\NodeAttrTypeController@index') }}";
    </script>
@endpush


@section('content')
      		<button type="button" onClick="window.location.href='{{ action('Admin\NodeAttrTypeController@create') }}';" class="btn btn-lg btn-success">新建节点属性类型</button>
		<div class="table-responsive">
            <table id="table" class="table table-striped">
                <thead>
                    <th>操作</th>
                    <th>ID</th>
                    <th>名称</th>
                    <th>display_label</th>
                    <th>值类型</th>
                </thead>
                <tfoot>
		            <tr>
		                <th>操作</th>
		                <th>ID</th>
		                <th>名称</th>
		                <th>display_label</th>
		                <th>值类型</th>
		            </tr>
		        </tfoot>
                <tbody>
                </tbody>
            </table>
        </div>
        
        <!-- 模态框（Modal） -->
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
                    </div><!-- /.modal-content -->
                </div><!-- /.modal -->	
            </div>
@endsection
