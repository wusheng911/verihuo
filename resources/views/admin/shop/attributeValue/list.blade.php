@extends('layouts.admin')

@section('title')
    编辑商品属性值
@endsection

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
<scrpit>
    getListUrl = "{{ action('Admin\Shop\AttributeValueController@listJson') }}";
</scrpit>
<script src="/assets/admin/js/shop/attributevalue/attributevalue.js"></script>
@endpush

@section('content')
    @permission(['all', 'ad-create'])
    <button type="button" onClick="window.location.href='{{ action('Admin\Shop\AttributeValueController@create') }}';" class="btn btn-lg btn-success">新建</button>
    @endpermission
    <div class="table-responsive">
        <table id="table" class="table table-striped">
            <thead>
            <th>操作</th>
            <th>ID</th>
            <th>目标属性</th>
            <th>值</th>
            <th>状态</th>
            <th>位置</th>
            </thead>
            <tfoot>
            <tr>
                <th>操作</th>
                <th>ID</th>
                <th>目标属性</th>
                <th>值</th>
                <th>状态</th>
                <th>位置</th>
            </tr>
            </tfoot>
            <tbody>
            </tbody>
        </table>
    </div>
@endsection