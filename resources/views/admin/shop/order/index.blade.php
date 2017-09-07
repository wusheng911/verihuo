@extends('layouts.admin')

@section('title')

@endsection

@push('stylesheets')
<style>
 #filterrow{font-size: 11px;}
</style>
@endpush

@push('scripts')
    <script type="text/javascript" src="/assets/admin/js/shop/order.js" ></script>
    <script>
     getListUrl = "{{ action('Admin\Shop\OrderController@listJson') }}";
    </script>
@endpush



@section('content')
    <div class="row">
        <!-- menu -->
        <div class="oo-menu panel panel-default col-lg-1">
            <div class="text-center list-group">
                <a href="\{{config('chaohun.admin_prefix')}}\shop\order" class="list-group-item list-group-item-info">订单总览</a>
                <a href="" class="list-group-item ">历史订单</a>
            </div>
        </div>
        <!-- body -->
        <div class="col-lg-11 col-lg-offset-1" >
            <div style="margin-left:7px;" >
                <div class="table-responsive">
                    <table id="table" class="table table-striped">
                        <thead>
                            <tr>
                                <th>单号</th>
                                <th>下单时间</th>
                                <th>收货地址</th>
                                <th>发货时间</th>
                                <th>订单状态</th>
                                <th>总价</th>
                                <th>支付</th>
                                <th>操作</th>
                            </tr>
                            <tr id="filterrow">
                                <th filter="sn">订单号</th>
                                <th filter="created_at">下单时间</th>
                                <th filter="consignee_address">收货地址</th>
                                <th filter="delivered_at">发货时间</th>
                                <th filter="status"></th>
                                <th>总价</th>
                                <th>支付状态</th>
                                <th ></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
