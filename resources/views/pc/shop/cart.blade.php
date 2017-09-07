@extends('layouts.shopapp')

@section('meta')
    <link href="/all" type="application/rss+xml" rel="alternate" title="robots" />
@endsection

@section('title')
@endsection

@section('styles')
    <link href="{{ asset('assets/libs/jquery-confirm/css/jquery-confirm.css') }}" media="all" type="text/css" rel="stylesheet">
@endsection

@section('content')
    <div class="customer container">
        @include('pc.customer.accountleft')
        <div class="pull-right customer-content my-cart">
            <div class="customer-content-header">购物车</div>
            <div id="cartTabContent" class="tab-content">
                <div class="cart-table-header">
                    <ul>
                        <li class="product-info">商品信息</li>
                        <li>单价(元)</li>
                        <li style="width: 150px;">数量</li>
                        <li>小计(元)</li>
                        <li>操作</li>
                    </ul>
                </div>
            </div>
            @if(!empty($cartsGroup))
                {!! Form::open(['url'=>action('ShopController@startOrder'),'method'=>'post']) !!}
                <input type="hidden" name="cart_ids" value="">
                <div class="cart-list">
                        <ul>
                            @foreach($cartsGroup as $key=>$cartGroup)
                                @if($cartGroup[0]->merchant)
                                    <li class="">
                                        <div class="checkbox cart-merchants">
                                            <label><input name="" type="checkbox" class="select-product-group">商家:{{--<img src="{{ $cartGroup[0]->merchant->logo }}" >--}}{{ $cartGroup[0]->merchant->name }}</label>
                                        </div>
                                        <div class="cart-product">
                                            <table>
                                                @foreach($cartGroup as $cart)
                                                    <tr>
                                                        <td width="30"><input name="" type="checkbox" class="cart-product-select"></td>
                                                        <td width="50"><img width="50" height="50" src="{{ $cart->image }}"></td>
                                                        <td width="270" class="cart-product-info">
                                                            <span class="cart-product-name">{{ $cart->product_name }}</span>
                                                            <span class="cart-product-category">分类：{{ $cart->category }}&nbsp;&nbsp;&nbsp;&nbsp;包装：{{ $cart->package }}</span>
                                                        </td>
                                                        <td width="100">￥{{ $cart->sku->price }}</td>
                                                        <td width="150">
                                                            <span class="cart-product-quantity-sub">-</span>
                                                            <input class="cart-product-quantity" value="{{ $cart->quantity }}" data-cartid="{{ $cart->id }}" data-price="{{ $cart->sku->price }}" disabled="disabled" />
                                                            <span class="cart-product-quantity-add">+</span>
                                                        </td>
                                                        <td width="100" style="color:#cc0033;">￥<span class="cart-product-total">{{ $cart->sku->price*$cart->quantity }}</span></td>
                                                        <td width="100"><span class="cart-product-delete" data-id="{{ $cart->id }}">删除</span></td>
                                                    </tr>
                                                @endforeach
                                            </table>
                                        </div>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                    <div class="cart-total">
                        <div class="checkbox">
                            <label><input name="" type="checkbox" class="cart-select-all">全选</label>
                        </div>
                        <div class="cart-total-info">
                            <span class="delete-cart-products">删除</span>
                            已选商品 <span class="quantity-total">0</span> 件，总计 <span class="price-total">￥0</span>  元
                            <button class="btn btn-default cart-submit cart-submit-disabled" disabled="disabled" data-cartids="">结算</button>
                        </div>
                    </div>
                {!! Form::close() !!}
            @endif
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript" src="/assets/libs/jquery-confirm/dist/jquery-confirm.min.js"></script>
    <script type="text/javascript" src="/assets/js/pc.customer.js"></script>
    <script type="text/javascript" src="/assets/js/pc.cart.js"></script>
@endsection
