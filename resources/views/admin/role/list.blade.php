@extends('layouts.admin')
@section('title', '角色设置')

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
    <script src="/packages/sleepingowl/default/libs/datatables/js/dataTables.bootstrap.min.js"></script>
    <script src="/packages/sleepingowl/default/js/datatables.min.js"></script>
    <script src="/packages/sleepingowl/default/libs/datatables-responsive/js/dataTables.responsive.js"></script>
    <script src="{{ asset('assets/admin/js/role_list.js') }}"></script>
    <script>
     getListUrl = "{{ action('Admin\RoleController@listJson') }}";
    </script>
    @endpush

    @section('content')
        <button type="button" onClick="window.location.href='{{ action('Admin\RoleController@create') }}';" class="btn btn-lg btn-success">新建</button>
        <div class="table-responsive">
            <table id="table" class="table table-striped">
                <thead>
                    <th>操作</th>
                    <th>ID</th>
                    <th>角色名称</th>
                    <th>角色描述</th>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    @endsection
