@extends('layouts.shopapp')
@section('meta')
    <link href="/all" type="application/rss+xml" rel="alternate" title="robots" />
@endsection

@section('title')
@endsection

@section('content')
    <div class="customer container">
        @include('pc.customer.accountleft')
        <div class="pull-right customer-content">
            <div class="customer-content-header">物流详情</div>
            <div id="orderTabContent" class="tab-content">
                <div class="tab-pane fade in active" id="all-orders">
                    <table>
                        <thead>
                        <tr>
                            <td>
                                <span class="merchant">商家：@if(isset($order->merchant))<img src="{{ $order->merchant->logo }}" style="width:3.125rem;height:1.5625rem;">{{ $order->merchant->name }}@endif</span>
                                <span class="order-number">订单编号：{{ $order->sn }}</span>
                            </td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td style="text-align: left;">
                                <div class="order-details">
                                    <span>物流公司：</span>
                                    <span>{{ $order->logistic->name }}</span>
                                </div>
                                <div class="order-details">
                                    <span>运单号码：</span>
                                    <span>{{ $order->logistic_no }}</span>
                                </div>
                                <div class="order-details">
                                    <span>物流电话：</span>
                                    <span>{{ $order->logistic->phone }}</span>
                                </div>
                                <div class="order-details">
                                    <ul class="order-track"></ul>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                        <tfoot></tfoot>
                    </table>
                </div>
            </div>
            <a class="back-allOrders" href="/customer/order/search/all"><i class="fa fa-hand-o-left" aria-hidden="true"></i>&nbsp;&nbsp;返回</a>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript" src="/assets/js/pc.customer.js"></script>
    <script>
        getListUrl = "{{ action('OrderController@listLogisticJson') }}";
        orderId = "{{ $order->id }}";
        $(document).ready(function(){
            var lo = localStorage.getItem('customer.logisticOnline');
            var traces = [];
            // 设定刷新物流信息的时间， 6个小时
            if(lo && moment(JSON.parse(lo).when).isAfter(moment().subtract(6, 'hours'))){
                var data = JSON.parse(JSON.parse(lo).data);
                traces = data;
            }
            // 空的话继续请求
            if(traces.length == 0){
                $.ajax({
                    url: getListUrl,
                    data:{'id': orderId},
                    async: false,
                    method: 'GET',
                    success: function(json){
                        if(json.status == "success"){
                            console.log(json);
                            localStorage.setItem('customer.logisticOnline', JSON.stringify({"when":moment(), "data":json.data}));
                            traces = JSON.parse(json.data);
                            if(traces.length == 0){
                                alert("本次订单暂无物流信息");
                            }
                        }
                    }
                });
            }
            //console.log(traces);
            html = '';
            $.each(traces, function (index, item) {
                //console.log(item.AcceptStation);
                html = '<li><i class="fa fa-circle" aria-hidden="true"></i><span class="track-content">'+item.AcceptStation+'</span><span class="track-time">'+item.AcceptTime+'</span> </li>'+html;
            })
            $(".order-track").html(html);
        })
    </script>
@endsection
