@extends('layouts.admin')

@section('title')
    老师管理
@endsection

@push('stylesheets')

<link media="all" type="text/css" rel="stylesheet" href="{{ asset('assets/libs/jquerytree/css/jquery.treeview.css') }}" />
<link media="all" type="text/css" rel="stylesheet" href="{{ asset('assets/libs/jquerycolorbox/colorbox.css') }}">

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
    tr.highlight{
        background-color: #f0a400 !important;
    }
    td.highlight {
        background-color: whitesmoke !important;
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('assets/libs/elfinder/standalonepopup.js') }}"></script>
<script src="{{ asset('assets/libs/jquerycolorbox/jquery.colorbox-min.js') }}"></script>

<script src="{{ asset('assets/admin/js/teacher/teacher.js') }}"></script>

@endpush

@section('content')

        <button type="button" onClick="window.location.href='{{ action('Admin\TeacherController@create') }}';" class="btn btn-lg btn-success">新建老师</button>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

        <div class="table-responsive">
            <table id="tableTeacher" class="table table-striped row-border hover order-column">
                <thead>
                <th>ID</th>
                <th>老师名称</th>
                <th>老师电话</th>
                <th>老师邮件</th>
                <th>头像</th>
                <th>老师介绍</th>
                <th>是否master</th>

                <th>操作</th>



                </thead>
                <tfoot>
                </tfoot>
                <tbody>
                </tbody>
            </table>
        </div>

@endsection
