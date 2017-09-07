@extends('layouts.admin')
@section('title', '商品管理')

@push('stylesheets')
<style>
    .product-search .bootstrap-select button { background-color: #fff !important; }
</style>
@endpush

@push('scripts')
<script>
    getListUrl = "{{ action('Admin\Shop\ProductController@listJson') }}";
    delContentUrl = "{{ action('Admin\Shop\ProductController@index') }}";
</script>
<script src="{{ asset('assets/admin/js/shop/product.js') }}"> </script>
@endpush

@section('content')
    <div class="row">
        <div class="col-sm-2 pull-right">
            <a type="button" class="btn btn-md btn-success btn-block" href="{{ action('Admin\Shop\ProductController@create') }}">添加商品</a>
        </div>
    </div><br>
    <div class="row product-search">
        <div class="col-sm-3">
            <select class="selectpicker" id="category_search">
                <option value="">商品分类筛选</option>
                @foreach($productCategories as $key=>$productCategory)
                    <option value="{{ $productCategory->id }}">{{ $productCategory->name }}</option>
                        @if(!empty($productCategory->child))
                            @foreach($productCategory->child as $key=>$value)
                            <option value="{{ $value->id }}">├─  {{ $value->name }} </option>
                                @if(!empty($value->child))
                                    @foreach($value->child as $v)
                                    <option value="{{ $v->id }}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└─  {{ $v->name }} </option>
                                    @endforeach
                                @endif
                            @endforeach
                        @endif
                @endforeach
            </select>
        </div>
        <div class="col-sm-2">
            <select class="selectpicker" id="product_available">
                <option value="">是否上架筛选</option>
                <option value="1">是</option>
                <option value="2">否</option>
            </select>
        </div>
        <div class="col-sm-2">
            <select class="selectpicker" id="product_status">
                <option value="">商品状态筛选</option>
                <option value="0">无效商品</option>
                <option value="1">未审核</option>
                <option value="2">已审核</option>
            </select>
        </div>
        <div class="col-sm-5 right">
            <div class="input-group">
                <input id="product_name" type="text" class="form-control input-md" placeholder="请输入要搜索的商品名称"><span class="input-group-addon btn btn-primary s-product-name">搜索</span>
            </div>
        </div>
    </div><br>
    <div class="table-responsive">
        <table id="product_table" class="table table-striped">
            <thead>
                <th>ID</th>
                <th>商品名称</th>
                <th>所属分类</th>
                <th>是否上架</th>
                <th>商品状态</th>
                <th>价格</th>
                <th>数量</th>
                <th>操作</th>
            </thead>
            <tfoot>
                <tr>
                    <th>ID</th>
                    <th>商品名称</th>
                    <th>所属分类</th>
                    <th>是否上架</th>
                    <th>商品状态</th>
                    <th>价格</th>
                    <th>数量</th>
                    <th>操作</th>
                </tr>
            </tfoot>
            <tbody>
            </tbody>
        </table>
    </div>
@endsection