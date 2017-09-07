@extends('layouts.admin')
@section('title', '资讯标签')

    @push('stylesheets')
    <style>
     .hidden-id{
         display: none;
     }
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
     div.dataTables_wrapper div.dataTables_paginate ul.pagination {
         margin-top: 22px;
         white-space: nowrap;
     }
    </style>
    @endpush

    @push('scripts')
    <script>
     tagListUrl = "{{ action('Admin\ContentTagController@listJson') }}";
     catListUrl = "{{ action('Admin\ContentTagController@categoryListJson') }}";
     newTagUrl = "{{ action('Admin\ContentTagController@ajaxNewTag') }}";
     modTagUrl = "{{ action('Admin\ContentTagController@ajaxUpdateTag') }}";
     delTagUrl = "{{ action('Admin\ContentTagController@ajaxDeleteTag') }}";
     reGenerateTagsUrl = "{{ action('Admin\ContentTagController@ajaxReGenerateTags') }}";
    </script>
    <script src="{{ asset('assets/admin/js/contenttag.js') }}"> </script>
    @endpush

    @section('content')
        <div class="row" >
            <div class="col-lg-5 " >
                <button type="button" name="gentag" class="col-lg-5 btn  btn-success">一键更新所有文章关键字</button>
                <span style="margin-top: 10px; display: inline-block; color: red;">此操作会耗费一定时间，请勿重复点击</span>
            </div>
            <div class="col-lg-5 col-lg-offset-7" >
                <button type="button" name="newtag" class="col-lg-1 col-lg-offset-1 btn  btn-success">＋</button>

                <div id="newtag" class="col-lg-5 col-lg-offset-1 input-group" style="display:none;">
                    <input id="input-newtag" type="text" class="form-control" placeholder="请输入标签名称" aria-describedby="basic-addon2">
                    <span class="input-group-addon btn" id="newtag-save">确认</span>
                </div>
            </div>
        </div>
        <div class="row" >
            <div class="col-lg-7" >
                <div class="table-responsive">
                    <table id="tagTable" class="table table-striped">
                        <thead>
                            <th></th>
                            <th>操作</th>
                            <th>分组</th>
                            <th>标签</th>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-lg-5 col-lg-offset-0" >
                <div class="table-responsive">
                    <table id="table" class="table table-striped">
                        <thead>
                            <th></th>
                            <th>名称</th>
                            <th>操作</th>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
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
                        确定要删除吗？
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" 
                                data-dismiss="modal">取消
                        </button>
                        <button id="sure-del" type="button" class="btn btn-primary">
                            删除
                        </button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal -->	
        </div>
    @endsection
