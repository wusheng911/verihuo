@extends('layouts.shopapp', ['is_shop' => true])

@section('meta')
    @include('elements.metaSEO')
@endsection

@section('title')
@endsection


@section('styles')
    <link rel="stylesheet" type="text/css" href="/assets/css/pc.css" />
@endsection


@section('content')

    <style>  
        body {
            background-color: white !important;
        }
    </style>
    
    <section class="chp-block-main" style="width:960px !important;">
        <div>
            <h4 style="margin-top:30px;font-weight:bold;">商品结算</h4>
        </div>
        @if (isset($merchants) && count($merchants) > 0)
            @foreach($merchants as $merchant_id => $merchant)
                <div style="margin:15px 0;font-size:14px;">
                    @if(isset($merchant["name"]))
                        <span>商家:{{$merchant["name"]}}</span>
                    @else
                        <span>购买商品</span>
                    @endif                    
                </div>
                <div style="display:table;text-align:center;">
                    <div style="display:table-row;background-color:#333;color:white;
                        font-weight:bold;font-size:14px;">
                        <div style="display:table-cell;width:380px;padding-top:5px;padding-bottom:5px;">
                            商品信息
                        </div>
                        <div style="display:table-cell;width:120px;">
                            单价（元）
                        </div>
                        <div style="display:table-cell;width:120px;">
                            数量
                        </div>
                        <div style="display:table-cell;width:120px;">
                            小计（元）
                        </div>
                        <div style="display:table-cell;width:35px;">
                        </div>
                        <div style="display:table-cell;width:185px;t">
                            合计（元）
                        </div>
                    </div>
                </div>
                <div style="display:table;font-size:14px;text-align:center;vertical-align: middle;">
                    <div style="display:table-row;">
                        <div style="display:table-cell;width:775px;">
                            <div style="display:table;">
                                @foreach($merchant["carts"] as $cart) 
                                    <div style="display:table-row;">
                                        <div class="chp-order-cartitem-container" data-cartid="{{$cart["id"]}}">
                                            <div style="padding:15px;">
                                                <img style="width:50px;height:50px;float:left;margin-right:15px;" src="{{isset($cart["image"])?$cart["image"]:""}}" 
                                                    alt="{{$cart["product_name"]}}"/>
                                                <div class="float:left;">
                                                    <p style="font-size:14px;color:#333;margin-bottom:10px;">{{$cart["product_name"]}}</p>
                                                    @if(isset($cart["sku_attrs"]) and count($cart["sku_attrs"]) > 0)                            
                                                            @foreach($cart["sku_attrs"] as $sku_attr)
                                                                <span style="display:inline-block;padding-right:15px;font-size:12px;
                                                                    color:#666;">
                                                                    {{$sku_attr["name"] . ": " . $sku_attr["value"]}}
                                                                </span>
                                                            @endforeach
      
                                                    @endif
                                                </div> 
                                            </div>
                                        </div>
                                        <div style="display:table-cell;width:120px;border-bottom:#ccc 1px solid;vertical-align: middle;">
                                            <span>¥</span>
                                            <span>
                                                {{(floor($cart["sku_price"]) == $cart["sku_price"])?floor($cart["sku_price"]):$cart["sku_price"]}}
                                            </span>
                                        </div>
                                        <div style="display:table-cell;width:120px;border-bottom:#ccc 1px solid;vertical-align: middle;">
                                            <span>{{$cart["quantity"]}}</span>
                                        </div>
                                        <div style="display:table-cell;width:120px;border-bottom:#ccc 1px solid;vertical-align: middle;">
                                            <span>¥</span>
                                            <span>
                                                {{(floor($cart["sku_price"]) == $cart["sku_price"])?floor($cart["sku_price"])*$cart["quantity"]:$cart["sku_price"]*$cart["quantity"]}}
                                            </span>   
                                        </div>
                                        <div style="display:table-cell;width:35px;border-bottom:#ccc 1px solid;"></div>
                                    </div>
                                @endforeach
                                <div style="display:table-row;">
                                    <div style="display:table-cell;border-left:#ccc 1px solid;
                                        border-bottom:#ccc 1px solid;vertical-align: middle;text-align:left;">
                                        <div style="padding:15px 0 15px 15px;">
                                            <span class="chp-span-normal-title">备注:</span>
                                            <span>
                                                <input type="text" class="chm-input chp-order-remark" data-merchantid="{{$merchant_id}}" 
                                                placeholder="请输入备注内容" value="" />
                                            </span>
                                        </div>
                                    </div>
                                    <div style="display:table-cell;border-bottom:#ccc 1px solid;">
                                    </div>
                                    <div style="display:table-cell;border-bottom:#ccc 1px solid;">
                                    </div>
                                    <div style="display:table-cell;border-bottom:#ccc 1px solid;">
                                    </div> 
                                    <div style="display:table-cell;border-bottom:#ccc 1px solid;">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div style="display:table-cell;width:185px;border-left:#ccc 1px solid;
                            border-bottom:#ccc 1px solid;border-right:#ccc 1px solid;vertical-align: middle;">
                            <span>¥</span>
                            <span>{{$merchant["total_price"]}}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
        
        <div>
            <h4 style="margin-top:30px;font-weight:bold;">配送方式</h4>
        </div>
        <hr style="background: #333 repeat 0 0;height:5px;width:100%;display:block;border:0;margin:0;" />
        <div style="margin:15px 0;font-size:14px;">
            <span class="chp-span-normal-title">收货地址:</span>            
            <span class="chp-order-addressitem {{!isset($address) || empty($address)?"chp-hide":""}}">
                @if (isset($address) && !empty($address))
                    {{$address["full_path"] . $address["details"] . "（ " . 
                    $address["consignee_name"] . " 收，&nbsp;" . $address["consignee_phone"] . "）"}}
                    <i class="fa fa-edit fa-lg chp-order-address-edit" data-addressid="{{$address["id"]}}"></i>
                @endif
            </span>
            <span class="chp-order-address-add {{isset($address) && !empty($address)?"chp-hide":""}}">添加地址[+]</span>          
        </div>        
        <div style="margin:15px 0;font-size:14px;">
                <span class="chp-span-normal-title">配送方式:</span>
                <span>快递</span>
        </div>         
        <div style="margin:15px 0;font-size:14px;">
            <span class="chp-span-normal-title">支付方式:</span>
            <input type="radio" name="chp_pay_method" value="2" id="chp_pay_alipay" checked="checked">
            <label for="chp_pay_alipay"><img src="/assets/img/alipay-pc.jpg" style="padding-right:10px;"/>支付宝</label>
            <input type="radio" name="chp_pay_method" value="4" id="chp_pay_wechat">
            <label for="chp_pay_wechat"><img src="/assets/img/wechat-pc.jpg" style="padding-right:10px;"/>微信支付</label>                
        </div>         
        <hr style="background: #333 repeat 0 0;height:5px;width:100%;display:block;border:0;margin:0;" />
        <div style="text-align:right;margin-top:15px;height:36px;margin-bottom:15px;">
            <!-- 结算 -->
            <div id="chp_order_button" class="button" style="width:120px;height:36px;float:right;padding:0 !important;margin:0 !important;">
                <span style="height:16px;line-height:16px;font-size:16px;margin:10px 0;display:block;">
                    去付款
                </span>
            </div>
            <div style="float:right;height:16px;margin:10px 0;line-height:16px;font-size:16px;font-weight:bold;">
                <span class="chp-span-normal-title">
                    您需要实际支付金额:
                </span>  
                <span style="color:#cc0000;">
                    {{$total_price}}
                </span>
                <span style="padding-left:0.375rem;padding-right:20px;">
                    元
                </span>
            </div>

            
        </div>
    </section>

    @include('pc.elements.modalAddressList')
    @include('pc.elements.modalAddressItem')
@endsection('content')

@section('scripts')
    <script type="text/javascript" src="/assets/vendor/foundation/js/vendor/modernizr.js"></script>
    <script type="text/javascript" src="/assets/vendor/foundation/js/foundation.min.js"></script>
    <script>
    $(document).ready(function() {
        $(document).foundation();
    })
    </script>
@endsection('scripts')
