@extends('layouts.admin')

@section('title')
    学生管理
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

<script src="{{ asset('assets/admin/js/customer/customer.js') }}"></script>

@endpush

@section('content')

        @permission(['all', 'ad-create'])

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

        @endpermission
        <div class="table-responsive">
            <table id="tableCustomer" class="table table-striped row-border hover order-column">
                <thead>
                <th>ID</th>
                <th>学生名称</th>
                <th>学生电话</th>
                <th>学生邮件</th>


                <th>操作</th>



                </thead>
                <tfoot>
                </tfoot>
                <tbody>
                </tbody>
            </table>
        </div>

@endsection
