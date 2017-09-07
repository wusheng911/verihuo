@extends('layouts.admin')
@section('title', '嘉宾列表')
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
    <script src="/assets/admin/js/visitor/visitor.js"></script>
    <script>
    </script>
@endpush
@section('content')
      <button type="button"  onClick="window.location.href='{{ action('Admin\Visit\VisitorController@create') }}';" class="btn btn-lg btn-success">新建</button>
      <button type="button" style="margin-left:40px;" onClick="window.location.href='{{ action('Admin\Visit\VisitorController@uploadFilePage') }}';" class="btn btn-lg btn-success">导入</button>
    <!--a href='{{ action('Admin\Visit\VisitorController@template') }}'>下载模板</a --!>
      <button id="createCodeBtn"  type="button" style="margin-left:40px;" class="btn btn-lg btn-success">刷新CODE</button >
      <button type="button" style="margin-left:40px;" onClick="window.location.href='{{ action('Admin\Visit\VisitorController@down') }}';" class="btn btn-lg btn-success">导出嘉宾信息</button>
        <div class="table-responsive">
            <table id="table" class="table table-striped">
                <thead>
                    <th>ID</th>
                    <th>名称</th>
                    <th>电话</th>
                    <th>CODE</th>
                    <th>VIP</th>
                    <th>发送邀请码</th>
                    <th>入场时间</th>
                    <th>操作</th>
                </thead>
                <tfoot>
                    <tr>
                        <th>ID</th>
                        <th>名称</th>
                        <th>电话</th>
                        <th>CODE</th>
                        <th>VIP</th>
                        <th>发送邀请码</th>
                        <th>入场时间</th>
                        <th>操作</th>
                    </tr>
                </tfoot>
                <tbody>
                </tbody>
            </table>
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
                                确定要删除游客信息吗？
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
