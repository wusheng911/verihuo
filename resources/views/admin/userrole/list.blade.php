@extends('layouts.admin')
@section('title', '用户角色配置')

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
    <script src="{{ asset('assets/admin/js/userrole_list.js') }}"></script>
    <script>
     getListUrl = "{{ action('Admin\UserRoleController@listJson') }}";
    </script>
    @endpush

    @section('content')
        @if (Auth::guard('admin')->user()->id === 1 || Auth::guard('admin')->user()->can('all', 'setting-perms'))
        <button type="button" onClick="window.location.href='{{ action('Admin\RoleController@refreshRolesPerms') }}';" class="btn btn-lg btn-success">刷新角色权限</button>
        @endif
        <div class="table-responsive">
            <table id="table" class="table table-striped">
                <thead>
                    <th>操作</th>
                    <th>ID</th>
                    <th>用户名</th>
                    <th>邮箱</th>
                    <th>角色</th>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    @endsection
