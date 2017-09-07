@extends('layouts.admin')

@section('title')
    学校管理
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

<script src="{{ asset('assets/admin/js/school/school.js') }}"></script>

@endpush

@section('content')

        <button type="button" onClick="window.location.href='{{ action('Admin\SchoolController@create') }}';" class="btn btn-lg btn-success">新建学校</button>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <button type="button" onClick="window.location.href='{{ action('Admin\SchoolController@phpexcel') }}';" class="btn btn-lg btn-success">批量上传学校</button>
        <div class="table-responsive">
            <table id="tableSchool" class="table table-striped row-border hover order-column">
                <thead>
                <th>ID</th>
                <th>学校名称</th>
                <th>Logo</th>
                <th>学校介绍</th>
                <th>学校类型</th>
                <th>操作</th>



                </thead>
                <tfoot>
                </tfoot>
                <tbody>
                </tbody>
            </table>
        </div>

@endsection
