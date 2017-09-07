@extends('layouts.shopapp', ['is_shop' => true])

@section('meta')
    @include('elements.metaSEO')
@endsection

@section('title')
@endsection


@section('styles')
    <link rel="stylesheet" type="text/css" href="/assets/css/pc.css" />
    <link rel="stylesheet" type="text/css" href="/assets/vendor/slick/slick.css" />
    <link rel="stylesheet" type="text/css" href="/assets/vendor/slick/slick-theme.css" /> 
@endsection


    <?php
        echo "<script type=\"text/javascript\">"; 

        if (isset($skus) && count($skus) > 0) { 
            echo "window.skus = " . json_encode($skus) . ";";
        }

        if (isset($shop_attrs) && count($shop_attrs) > 0) { 
            echo "window.shop_attrs = " . json_encode($shop_attrs) . ";";
        }

        echo "</script>";
    ?>

@section('content')

    <style>  
        body {
            background-color: white !important;
        }
        

        .slick-prev:hover, 
        .slick-prev:focus, 
        .slick-next:hover,
        .slick-next:focus {
            background: #333 none repeat scroll 0 0 !important;
        } 
        
        .slick-prev::before, .slick-next::before {
          font-size: 26px;
        }  

        .slick-prev {
            left: 0;
            top: 50px;
            z-index:99;
            width:45px;
            height:100px;
            background-color: #333;
        }
        .slick-next {
            right: 0;
            top: 50px;
            z-index:99;
            width:45px;
            height:100px;
            background-color: #333;
        }
        
        .slick-slide {
            width: 115px !important;
            cursor: pointer !important;
        }
        
        .slick-list {
            /* margin-left: 45px !important; */
            /* margin-right: 45px !important; */
        }
        
        
        
    </style>
    
    <?php 
        $product_thumbnail = [];
        foreach ($product['images'] as $product_image) {             
            if ($product_image['device'] == config('constants.DEVICE_PC') && $product_image['is_first'] == '1') {
                $product_thumbnail = $product_image;
                break;
            }
        }         
    ?>

    <section class="chp-block-main">
        <section class="chp-shop-product-blockleft">
            <div id="chp-thumbnail" class="chp-thumbnail" style="float:left;width:100%;height:650px;margin-top:10px;border: #cfcfcf solid 1px;">
                <img id="product_thumbnail" src="{{!empty($product_thumbnail)?$product_thumbnail['image']:''}}"  
                    alt="{{$product['name']}}"
                    style="width:650px;"/>                  
            </div>
            

    
            <div style="display: inline-flex;"> 
            <img id="product-arrow-left" style="height:100px;margin-top:15px;cursor:pointer;" src="/assets/img/arrow-left.jpg">
            <div id="products-img-list" class="chp-slick-productslider" style="padding:0;margin:15px 0 20px 0;width:561px;height:100px;
                position:relative;border: #cfcfcf solid 1px;">     
                {{-- Render the first image --}}            
                @if (!empty($product_thumbnail))
                    <div>
                        <img id="product_thumbnail" src="{{$product_thumbnail['image']}}"  
                              alt="{{$product['name']}}"
                              style="width:100px;height:100px;"/>  
                    </div>                        
                @endif  
                {{-- Render the other images in sequence, records in $product['images'] array has been ordered --}}
                @foreach ($product['images'] as $product_image)                
                    @if ($product_image['device'] == config('constants.DEVICE_PC') && $product_image['is_first'] != '1')
                        <div>
                            <img src="{{$product_image['image']}}"  
                                  alt="{{$product['name']}}"
                                  style="width:100px;height:100px;"/>
                        </div>                        
                    @endif
                @endforeach                 
            </div>   
            <img id="product-arrow-right" style="cursor:pointer;height:100px;margin-top:15px;" src="/assets/img/arrow-right.jpg">
            </div>
            <div style="float:left;font-size:16px;margin-bottom:30px;text-align: justify;">
                {!! $product["html_info"] !!}
            </div>
        </section>
        <section id="chp-shop-product-blockright" class="chp-shop-product-blockright">
            <div class="chp-product-attribute-container" style="margin-left:20px;padding: 30px 0 30px 20px;float:left;width:380px;
                 background-color:#f4f4f4;">
                <h3 style="font-size:18px;color:#333;padding:0;text-align:left;font-weight:bold;">
                    <?php
                        if (isset($product["brand_name"]) && strlen($product["brand_name"]) > 0) {
                            $brand_name = "【" . $product["brand_name"] . "】";
                        } else {
                            $brand_name = "";
                        }
                    ?>
                    {{$brand_name . $product["name"]}}
                </h3>
                <p class="chp-product-price" data-price="{{$product["show_price"]}}">
                    {{$product["show_price"] > 0?"&yen;" . Helpers::getShowPrice($product["show_price"]):""}}
                </p>
                <p style="color:#333;padding-bottom:15px;font-size:16px;font-weight:bold;">商品详情</p>
                <div id="chp_product_attributes">
                    
                </div>
                <p style="color:#333;padding-bottom:15px;font-size:16px;font-weight:bold;">产品介绍</p>
                <p style="color:#666;padding-bottom:20px;font-size:16px;">
                {{$product["info"]}}
                </p>
                <div class="chp-productattr-container">
                    @foreach($shop_attrs as $index => $shop_attr)
                        @if($shop_attr["is_sell"] == "1")
                            <div>
                                <p style="color:#333;padding-bottom:15px;font-size:16px;font-weight:bold;">
                                    {{$shop_attr["shop_attribute_showname"]}}
                                </p>
                                <p class="chp-product-attr">
                                    @foreach($shop_attr["shop_attribute_values"] as $key => $attr_value)
                                        <span class="chp-productattr-value"
                                            data-attr="{{$shop_attr["shop_attribute_id"]}}"
                                            data-attrvalue="{{$attr_value["value_id"]}}" 
                                            data-skuids="{{implode(",", $attr_value["sku_ids"])}}">
                                            {{$attr_value["value"]}}
                                        </span>
                                    @endforeach                                
                                </p>
                            </div>
                        @endif
                    @endforeach                    
                </div>
                <p style="color:#333;padding-bottom:15px;font-size:16px;font-weight:bold;">数量</p>
                
                <div class="chp-quantity-edit-container">
                    <div style="padding-right: 0px" class="chp-quantitybtn-spandiv">
                        <span class="chp-quantity-edit-subtractbtn">
                        -
                        </span>
                    </div>
                    <div style="padding-right: 0px" class="chp-quantity-edit-spandiv">
                        <input type="text" class="chp-edit-quantity" value="1"/>
                    </div>
                    <div style="padding-right: 0px" class="chp-quantitybtn-spandiv">
                        <span class="chp-quantity-edit-addbtn" value="+">+</span>
                    </div>
                    <span style="font-size:16px;line-height:30px;height:30px;">（库存： <span id="chp_sku_count">{{$sku_count}}</span>件）</span>
                </div> 
                
                <div id="chp_addto_cart" class="chp-product-addtocart">加入购物车</div>
      
            </div>
        </section>
    </section>


@endsection('content')

@section('scripts')
    <script type="text/javascript" src="/assets/vendor/slick/slick.min.js"></script>
    <script type="text/javascript" src="/assets/js/pc.shop.product.js"></script>
    <script type="text/javascript" src="/assets/js/pc.shop.cart.animate.js"></script>
    <script type="text/javascript">
        //如果用户已登录且购物车里已有当前商品,则需要显示总价
        /*
        if (window.carts) {
            if (window.carts.length > 0) {
                renderPriceQuantityByCarts(window.carts, window.skus);
            }
        }
        */
        //页面首次加载, 显示所有商品属性
        display_attrs = getDisplayAttributes(window.shop_attrs, []);
        $("#chp_product_attributes").html(renderProductAttributes(display_attrs));
        
        $(document).ready(function(){        
            $('.chp-slick-productslider').slick({
                slidesToShow: 5,
                slidesToScroll: 1,
                variableWidth: true
            });     
        });
  
    </script>
@endsection
