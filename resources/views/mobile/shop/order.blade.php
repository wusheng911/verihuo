@extends('layouts.mobile', ['subnav' => true, 'is_shop' => true, 'has_nav_bottom'=> true])

@section('meta')
    @include('elements.metaSEO')
@endsection

@section('title', 'veriHuo- College application essays and advice')

@section('navigatorTop')
    @include('mobile.elements.navigatorTop3', ['title' => "订单确认", 'type'=>'order'])
@endsection

@section('content')
    <style>
        body {
            background-color: #f2f2f2 !important;
        }
    </style>

    @include('mobile.elements.promptAlert')
      
    <!-- CONTENT SECTION -->
    <section class="full-width content-section">   
        <div style="height:10px;"></div>
        @if (isset($merchants) && count($merchants) > 0)
            @foreach($merchants as $merchant_id => $merchant)
                <div class="row">        
                    <div class="small-12 columns" style="margin:0;padding:0 5px;">
                        <div class="chm-order-merchant">
                            @if(isset($merchant["name"]))
                                <span class="chm-span-normal-title">商家:</span><span>{{$merchant["name"]}}</span>
                            @else
                                <span>购买商品</span>
                            @endif
                        </div>
                    </div>
                </div>
                @foreach($merchant["carts"] as $cart) 
                    @if ($cart == end($merchant["carts"]))
                        <?php $bottom_style= "border-bottom-left-radius:5px;border-bottom-right-radius:5px;"; ?>
                    @else
                        <?php $bottom_style=""; ?>
                    @endif
                    <div class="row">        
                        <div class="small-12 columns" style="margin:0;padding:0 5px;">
                            <div class="chm-order-cartitem-container" style="{{$bottom_style}}" data-cartid="{{$cart["id"]}}">
                                <img class="chm-cartitem-thumbnail" src="{{isset($cart["image"])?$cart["image"]:""}}" 
                                    alt="{{$cart["product_name"]}}"/>
                                <div class="chm-cartitem-contentblock">
                                    <div class="chm-cartitem-quantity-container">
                                        <span>x </span>
                                        <span class="chm-cartitem-quantity">{{$cart["quantity"]}}</span>
                                    </div>
                                    <h5><a href="/view/product/{{$cart["product_id"]}}">{{$cart["product_name"]}}</a></h5>
                                    @if(isset($cart["sku_attrs"]) and count($cart["sku_attrs"]) > 0)
                                        <ul class="no-bullet inline-list">
                                            @foreach($cart["sku_attrs"] as $sku_attr)
                                                <li>
                                                    <span>
                                                        {{$sku_attr["name"] . ":" . $sku_attr["value"]}}
                                                    </span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div> 
                                <div class="chm-cartitem-bottombar">
                                    <span class="chm-cartitem-price">¥</span>
                                    <span class="chm-cartitem-price chm-cartitem-pricevalue" data-pricevalue="{{$cart["sku_price"]}}">
                                        {{(floor($cart["sku_price"]) == $cart["sku_price"])?floor($cart["sku_price"]):$cart["sku_price"]}}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>                   
                @endforeach
                <div class="chm-order-item-container">
                    <div>
                        <div>
                            <span style="font-size:0.875rem;">备注</span>
                        </div>
                        <div>
                            <input type="text" class="chm-input chm-order-remark" data-merchantid="{{$merchant_id}}" 
                                placeholder="请输入备注内容" value="" />                            
                        </div>                        
                    </div>
                </div>
                <div class="row">        
                    <div class="small-12 columns text-right" style="margin:0;padding:10px 15px;font-size:0.875rem;">
                        <span>共</span>
                        <span color="red">{{$merchant["quantity"]}}</span>
                        <span>件商品, </span>
                        <span class="chm-span-normal-title">合计:</span>
                        <span color="red">{{$merchant["total_price"]}}</span>
                        <span>元</span>
                    </div>
                </div>
            @endforeach
            
            <div class="chm-address-container chm-order-address" 
                data-addressid="{{(isset($address) && count($address) > 0)?$address["id"]:""}}">                
                <div style="display:table-row;">                    
                    <div class="chm-address-cell-content" >
                        @if(isset($address) && count($address) > 0)
                            <p>
                                <span class="chm-span-normal-title">收件人:</span>
                                <span style="margin-right:1rem;">{{$address["consignee_name"]}}</span>
                                <span class="chm-span-normal-title">电话:</span>
                                <span>{{$address["consignee_phone"]}}</span>
                            </p>
                            <p>
                                <span class="chm-span-normal-title">地址:</span>
                                <span>{{$address["full_path"] . $address["details"]}}</span>
                            </p> 
                        @else 
                            <p>请选择收货地址</p>
                        @endif
                    </div>
                    <div class="chm-address-cell-edit">
                        <p><i class="fa fa-angle-right fa-lg"></i></p>
                    </div>
                </div>               
            </div>   
            <div class="chm-order-item-container">
                <div>
                    <div>
                        <span>付款方式</span>
                    </div>
                    <div>
                        <span>{{Helpers::isWechat()?"微信支付":"支付宝"}}</span>                           
                    </div>                        
                </div>
            </div>             
            <div class="chm-order-item-container">
                <div>
                    <div>
                        <span>配送方式</span>
                    </div>
                    <div>
                        <span>快递包邮</span>                           
                    </div>                        
                </div>
            </div>          
        @endif      
    </section>
    
    @include('mobile.elements.modalConfirm')
@endsection

@section('navigatorBottom')
    @include('mobile.elements.navigatorOrder', ['total_price'=> $total_price, 'address' => $address])
@endsection

@section('script-file-bottom')
    <script type="text/javascript">
        displayTotalPrice();       
    </script>    
@endsection
