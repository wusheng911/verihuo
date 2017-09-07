@extends('layouts.admin')
@section('title', '资讯文章')

    @push('stylesheets')
    <style>
     table{
         margin: 0 auto;
         width: 100%;
         clear: both;
         border-collapse: collapse;
         table-layout: fixed; // ***********add this
         word-wrap:break-word; // ***********and this
     }
     table tr td{
         border-left: dashed 1px lightgray;
         border-right: dashed 1px lightgray;
     }
    </style>
    @endpush

    @push('scripts')
    <script src="{{ asset('assets/admin/js/content.js') }}"> </script>
    <script>
     getListUrl = "{{ action('Admin\ContentController@listJson') }}";
     delContentUrl = "{{ action('Admin\ContentController@index') }}";
    </script>
    @endpush

    @section('content')
        <button type="button" onClick="window.location.href='{{ action('Admin\ContentController@create') }}';" class="btn btn-lg btn-success">新建</button>
        <div class="table-responsive">
            <table id="table" class="table table-striped">
                <thead>
                    <th>ID</th>
                    <th>类别</th>
                    <th>图标</th>
                    <th>标题</th>
                    <th>标签</th>
                    <th>操作</th>
                </thead>
                <tfoot>
                    <tr>
                        <th>ID</th>
                        <th>类别</th>
                        <th>图标</th>
                        <th>标题</th>
                        <th>标签</th>
                        <th>操作</th>
                    </tr>
                </tfoot>
                <tbody>
                </tbody>
            </table>
        </div>
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
