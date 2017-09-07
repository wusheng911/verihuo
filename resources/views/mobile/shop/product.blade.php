@extends('layouts.mobile', ['subnav' => true, 'is_shop' => true, 'has_nav_bottom'=> true])

@section('meta')
    <meta name="description" content="{{$product['info']}}" />
    <meta name="keywords" content="婚礼策划,时尚,娱乐,明星,生活,潮流,资讯,策划培训" />
@endsection

@section('title')
    {{isset($product['name'])?$product['name']:'veriHuo- College application essays and advice'}}
@endsection

@section('css-top')
    <link rel="stylesheet" type="text/css" href="/assets/vendor/slick/slick.css" />
    <link rel="stylesheet" type="text/css" href="/assets/vendor/slick/slick-theme.css" /> 
@endsection

@section('navigatorTop')
    @include('mobile.elements.navigatorTop2', ['title' => isset($category["name"])?$category["name"]:"", 'hide_divider' => true])
@endsection

@section('content')
    <style>  
        body {
            background-color: white !important;
        }
            
        .slick-dots {
          bottom: 0px;
          margin-left: 0; /* Fixes the visible horizontal scroll bar */
        }

        .slick-dots li button::before {
          font-size: 10px;
          color: #999;
          opacity: 1;
        }

        .slick-dots li.slick-active button::before {
          color: #fff;
        }

        .slick-dots li button:hover::before, .slick-dots li button:focus::before {

        }

        .slick-prev::before, .slick-next::before {
          font-size: 26px;
          opacity: 0.8;
        }  

        .slick-prev {
            left: 0;
            z-index:99;
            width:30px;
            height:40px;
        }
        .slick-next {
            right: 0;
            z-index:99;
            width:30px;
            height:40px;
        }
        
        .columns {
            padding-left:10px;
            padding-right:10px;
        }
    </style>

    @include('mobile.elements.promptAlert')
    
    <?php
        echo "<script type=\"text/javascript\">"; 

        if (isset($skus) && count($skus) > 0) { 
            echo "window.skus = " . json_encode($skus) . ";";
        }

        if (isset($shop_attrs) && count($shop_attrs) > 0) { 
            echo "window.shop_attrs = " . json_encode($shop_attrs) . ";";
        }

        //if (isset($carts) && count($carts) > 0) { 
        //    echo "window.carts = " . json_encode($carts) . ";";
        //}            

        echo "</script>";
    ?>
                 
    <!-- CONTENT SECTION -->
    <section class="full-width content-section">      
        <div class="row">
            <div class="small-12 columns chm-slick-productslider" style="padding:0;margin:0;">     
                {{-- Render the first image --}}
                @foreach ($product['images'] as $product_image)                
                    @if ($product_image['device'] == config('constants.DEVICE_MOBILE') && $product_image['is_first'] == '1')
                        <div class="text-center" style="position:relative;">
                            <img id="product_thumbnail" src="{{$product_image['image']}}"  
                                  alt="{{$product['name']}}"
                                  style="width:100%;height:100%;"/>  
                            <?php $product_thumbnail = $product_image['image']; ?>
                        </div>                        
                    @endif
                @endforeach   
                {{-- Render the other images in sequence, records in $product['images'] array has been ordered --}}
                @foreach ($product['images'] as $product_image)                
                    @if ($product_image['device'] == config('constants.DEVICE_MOBILE') && $product_image['is_first'] != '1')
                        <div class="text-center" style="position:relative;">
                            <img src="{{$product_image['image']}}"  
                                  alt="{{$product['name']}}"
                                  style="width:100%;height:100%;"/>
                        </div>                        
                    @endif
                @endforeach                 
            </div>
        </div>
        <div class="row">
            <div class="small-12 columns chm-productinfo-container">
                <span>销量： {{$product["sale_quantity"]}}件</span>
                <span>库存： {{$sku_count}}件</span>
            </div>
        </div>
        <div class="row">
            <div class="small-12 columns chm-productattr-container">
                @foreach($shop_attrs as $index => $shop_attr)
                    @if($shop_attr["is_sell"] == "1")
                        <div clas="row">
                            <div class="small-12">                           
                                <ul class="inline-list chm-product-attr">
                                    <li class="chm-productattr-item">{{$shop_attr["shop_attribute_showname"]}}：</li>
                                    @foreach($shop_attr["shop_attribute_values"] as $key => $attr_value)
                                        <li class="chm-productattr-value" data-attr="{{$shop_attr["shop_attribute_id"]}}" 
                                            data-attrvalue="{{$attr_value["value_id"]}}"
                                            data-skuids="{{implode(",", $attr_value["sku_ids"])}}">{{$attr_value["value"]}}</li>
                                    @endforeach
                                </ul>                           
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>            
        </div>
        {{-- 商品属性 --}}
        @if (isset($shop_attrs) && count($shop_attrs) > 0)
            <div class="row">
                <div class="small-12 columns chm-product-htmlinfo-container">
                    <h3 style="margin-bottom:0.375rem;">商品详情</h3>
                    <hr style="margin:0 0 0.375rem 0;border-color: #666 !important;" />                
                </div>
            </div> 
            <div class="row">
                <div class="small-12 columns" style="line-height:1.6;font-weight:bold;">
                    {{$product['name']}}
                </div>
            </div>         
            <div id="product-attributes" class="row">
                <div class="small-12 columns chm-product-htmlinfo-container">
                </div>
            </div>        
        @endif        
        {{-- 产品介绍（详情） --}}
        <div class="row">
            <div class="small-12 columns chm-product-htmlinfo-container">
                <h3 style="margin-bottom:0.375rem;">产品介绍</h3>
                <hr style="margin:0 0 0.375rem 0;border-color: #666 !important;" />
                {!! $product["html_info"] !!}
            </div>
        </div>         
    </section>
@endsection

@section('navigatorBottom')
    @include('mobile.elements.navigatorProduct', ['product' => $product])
@endsection

@section('script-file-bottom')
    <script type="text/javascript" src="/assets/vendor/slick/slick.min.js"></script>
    <script type="text/javascript" src="/assets/js/mobile.slider.js"></script>
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
        $("#product-attributes").children("div").html(renderProductAttributes(display_attrs));
        
        //如果缺货， 不允许添加购物车
        if (typeof(window.skus) == "undefined") {
            $("#add_to_cart").attr("disabled",true).removeAttr('href').css("background-color","#666"); 
        }        
    </script>
@endsection








