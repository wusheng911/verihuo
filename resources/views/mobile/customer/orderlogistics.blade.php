@extends('layouts.mobile', ['subnav' => true])

@section('meta')
    <meta name="description" content="veriHuo- College application essays and advice" />
    <meta name="keywords" content="veriHuo- College application essays and advice" />
    <link href="/all" type="application/rss+xml" rel="alternate" title="robots" />
@endsection


@section('title')
    {{'veriHuo- College application essays and advice'}}
@endsection

@section('navigatorTop')
    @include('mobile.elements.navigatorTop2', ['title' => '物流信息'])
@endsection

@section('content')
    <div class="panel chm-account-panel chm-account-order" style="padding: 0.625rem;">
        <div class="row" style="font-size: 0.75rem;">
            <span class="left">订单编号：{{ $order->logistic_no }}</span>
            <span class="right">交易状态：{{ $order->orderStatus->description }}</span>
        </div>
        <div class="row chm-account-order-logistics">
        </div>
    </div>
    <div class="panel chm-account-panel chm-account-order" style="padding: 0.625rem;font-size: 0.75rem;">
        <div class="" style="padding-bottom: 0.3125rem;border-bottom: 0.0625rem solid #d8d8d8;">
            <div class="row">
                <div class="small-3 columns">物流公司:</div>
                <div class="small-9 columns text-left">{{ $order->logistic->name }}</div>
            </div>
            <div class="row">
                <div class="small-3 columns ">运单号码:</div>
                <div class="small-9 columns text-left">{{ $order->sn }}</div>
            </div>
            <div class="row">
                <div class="small-3 columns">物流电话:</div>
                <div class="small-9 columns text-left">{{ $order->logistic->phone }}</div>
            </div>
        </div>
        <div class="row">
            <ul class="chm-account-order-track" style="font-size: 0.75rem; margin: 0.625rem 0;">

            </ul>
        </div>
    </div>
@endsection

@section('script-file-bottom')
    <script type="text/javascript" src="/assets/js/mobile.customer.js"></script>
    <script type="text/javascript" src="/assets/js/mobile.shop.js"></script>
    <script type="text/javascript" src="/assets/js/utils.js"></script>
    <script type="text/javascript" src="/assets/libs/moment-with-locales.js"></script>
    <script type="text/javascript" src="/assets/libs/underscore-min.js"></script>
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
                                $.alert({
                                    content:"次订单暂无物流信息"
                                });
                            }
                        }
                    }
                });
            }
            //console.log(traces);
            html = '';
            $.each(traces, function (index, item) {
                //console.log(item.AcceptStation);
                html += '<li><i class="fa fa-circle" aria-hidden="true"></i><span class="track-content">'+item.AcceptStation+'</span><span class="track-time">'+item.AcceptTime+'</span> </li>';
            })
            $(".chm-account-order-track").html(html);
        })
    </script>
@endsection
