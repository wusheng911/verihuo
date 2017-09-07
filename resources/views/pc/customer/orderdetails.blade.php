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
            <div class="customer-content-header">订单详情</div>
            <div id="orderTabContent" class="tab-content">
                <div class="order-table-header">
                    <ul>
                        <li class="product-info">商品信息</li>
                        <li>单价(元)</li>
                        <li style="width: 70px;">数量</li>
                        <li>小计(元)</li>
                        <li>合计(元)</li>
                        <li style="width: 130px;">交易状态</li>
                    </ul>
                </div>
                <div class="tab-pane fade in active" id="all-orders">
                        <table>
                            <thead>
                                <tr>
                                    <td colspan="7">
                                        <span class="merchant">商家：@if(isset($order->merchant))<img src="{{ $order->merchant->logo }}" style="width:3.125rem;height:1.5625rem;">{{ $order->merchant->name }}@endif</span>
                                        <span class="order-number">订单编号：{{ $order->sn }}</span>
                                    </td>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($order->orderSkus as $key=>$orderSku)
                                <tr>
                                    <td width="80">
                                        <img src="@if(isset($orderSku->productFirstImage)){{ $orderSku->productFirstImage->image }}@endif" width="50" height="50">
                                    </td>
                                    <td width="220" style="text-align: left">
                                        <span class="product-name">{{ str_limit($orderSku->product_name,20,'') }}</span>
                                        <span class="product-category">分类: {{ $orderSku->product->productCategoryWithTrashed->name }} &nbsp;包装: {{ $orderSku->product->package }}</span>
                                    </td>
                                    <td width="100"><i class="fa fa-rmb" aria-hidden="true"></i>{{ $orderSku->market_price }}</td>
                                    <td width="70">x{{ $orderSku->quantity }}</td>
                                    <td width="100">
                                        <i class="fa fa-rmb" aria-hidden="true"></i>{{ ($orderSku->quantity)*($orderSku->market_price) }}
                                    </td>
                                    @if($key==0)
                                        <td width="100" class="order-total" rowspan="@if($order->orderSkus->count()>1){{ $order->orderSkus->count() }}@endif">
                                            <i class="fa fa-rmb" aria-hidden="true"></i>{{ $order->total }}
                                        </td>
                                        <td width="130" class="order-status" rowspan="@if($order->orderSkus->count()>1){{ $order->orderSkus->count() }}@endif">
                                            <span>{{ $order->orderStatus->description or '未知状态' }}@if($order->paid_status == 1 && $order->orderStatus->id == 1 ) {{ '(已支付)' }} @elseif($order->paid_status == 0 && $order->orderStatus->id == 1 ) {{ '(未支付)' }} @endif</span><br>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="7" style="text-align: left;">
                                    <div class="order-details">
                                        <span>订单编号：</span>
                                        <span>{{ $order->sn }}</span>
                                    </div>
                                    <div class="order-details">
                                        <span>下单时间：</span>
                                        <span>{{ $order->created_at }}</span>
                                    </div>
                                    @if(!empty($order->paid_at))
                                    <div class="order-details">
                                        <span>付款时间：</span>
                                        <span>{{ $order->paid_at }}</span>
                                    </div>
                                    @endif
                                    <div class="order-details">
                                        <span>送货地址：</span>
                                        <span>{{ $order->consignee_address }} ({{ $order->consignee_name }}收,{{ $order->consignee_phone }} )</span>
                                    </div>
                                    @if(!empty($order->payment_method_name))
                                    <div class="order-details">
                                        <span>支付方式：</span>
                                        <span>{{ $order->payment_method_name }}</span>
                                    </div>
                                    @endif
                                    @if(isset($order->logistic->name)&&!empty($order->logistic->name))
                                    <div class="order-details">
                                        <span>配送方式：</span>
                                        <span>{{ $order->logistic->name }}</span>
                                    </div>
                                    @endif
                                    @if(!empty($order->delivered_at))
                                    <div class="order-details">
                                        <span>配送时间：</span>
                                        <span>{{ $order->delivered_at }}</span>
                                    </div>
                                    @endif
                                    @if(isset($order->invoice->content)&&empty($order->invoice->content))
                                    <div class="order-details">
                                        <span>发票时间：</span>
                                        <span>{{ $order->invoice->content }}</span>
                                    </div>
                                    @endif
                                </td>
                            </tr>
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="7"><div>
                                        共 <span style="color: #C30E21">{{ $order->product_quantity }}</span> 件商品，合计：<span style="color: #C30E21">{{ $order->total }}</span> 元
                                        @if($order->status == \App\Models\Shop\OrderStatus::Raw && $order->paid_status == 0)
                                            <a href="/pay/orders/{{$order->id}}" class="order-b-red">确认付款</a>
                                            <a href="/customer/order/cancel/{{$order->id}}" class="">取消订单</a>
                                        @elseif($order->status == \App\Models\Shop\OrderStatus::Completed)
                                            <a href="http://cs.ecqun.com/mobile/rand?id=1678907" class="" style="margin-right: 15px;">售后服务</a>
                                        @elseif($order->status == \App\Models\Shop\OrderStatus::Shipping)
                                            <a href="#" data-id="{{$order->id}}" class="order-b-red receipt-order-btn">确认收货</a>
                                            <a href="/customer/order/logistics/{{$order->id}}" class="">查看物流</a>
                                            <a href="http://cs.ecqun.com/mobile/rand?id=1678907" class="">售后服务</a>
                                        @elseif($order->status == \App\Models\Shop\OrderStatus::Checked)
                                            <a href="#" data-id="{{$order->id}}" class="delete-order-btn" style="margin-right: 15px;">删除订单</a>
                                            <a href="http://cs.ecqun.com/mobile/rand?id=1678907" data-reveal-id="myModal" class="">售后服务</a>
                                        @elseif($order->status == \App\Models\Shop\OrderStatus::Cancelled)
                                            <a href="#" data-id="{{$order->id}}" class="delete-order-btn" style="margin-right: 15px;">删除订单</a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            </tfoot>
                        </table>
                </div>
            </div>
            <a class="back-allOrders" href="/customer/order/search/all"><i class="fa fa-hand-o-left" aria-hidden="true"></i>&nbsp;&nbsp;返回</a>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript" src="/assets/libs/jquery-confirm/dist/jquery-confirm.min.js"></script>
    <script type="text/javascript" src="/assets/js/pc.customer.js"></script>
@endsection
