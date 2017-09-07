@extends('layouts.mobile', ['no_scroll' => true, 'is_shop' => true, 'has_nav_bottom'=> true])

@section('meta')
    @include('elements.metaSEO')
@endsection

@section('title', 'veriHuo- College application essays and advice')

@section('css-top')
    <link rel="stylesheet" type="text/css" href="/assets/vendor/slick/slick.css" />
    <link rel="stylesheet" type="text/css" href="/assets/vendor/slick/slick-theme.css" /> 
@endsection

@section('content')
    <style>
        body {
            background-color: white !important;
        }
    </style> 

    @include('mobile.elements.promptAlert')
    <?php 
        $products = isset($nodes['Mobile|Shop|Home|A1']['localAttributes']['Product Id']['values'])?
            $nodes['Mobile|Shop|Home|A1']['localAttributes']['Product Id']['values']:null;
        
        $last_product = null;
        
        if (isset($products)) {
            foreach($products as $key => &$product) {
                if ($product == end($products)) {
                    $last_product = &$product;
                }
                
                $image = array_filter($product['value']['images'], function($item) {
                    return intval($item['is_first']) > 0 && intval($item['device']) === config("constants.DEVICE_MOBILE");
                }); 
                if (!isset($image[0])) {
                    $image = array_first($product['value']['images']);
                }
                $product['value']['thumbnail'] = isset($image[0]['image'])?$image[0]['image']:'';
                $product['value']['name'] = isset($product['value']['name'])?$product['value']['name']:'';
                $product['value']['info'] = isset($product['value']['info'])?$product['value']['info']:'';
                
                $show_price = Helpers::getShowPrice($product['value']['show_price']);
                
                $product['value']['show_price'] = isset($show_price)?
                    "&yen;{$show_price}元":'';
            }
        }
    ?>
    
    @if (isset($products))
        @foreach ($products as $key => $prod)  
            <?php
                $input_classname = "chm-hidden-coverflow";
                if ($key == '5') {
                    $input_classname = $input_classname . " chm-coverflow-data-nextnext";
                }
                if ($prod == end($products)) {
                    $input_classname = $input_classname . " chm-coverflow-data-prev";
                }
            ?>
            <input class="{{$input_classname}}" type="hidden" name="{{'cover_image_' . $key}}" 
                value="{{$prod['value']['id'] . '|' . $prod['value']['name'] . 
                '|' . $prod['value']['info'] . '|' . $prod['value']['show_price'] . 
                '|' . $prod['value']['thumbnail'] }}"/>
        @endforeach  
    @endif      

    <!-- CONTENT SECTION -->
    <section id="coverflow-container" class="chm-coverflow-container">
        <div class="chm-coverflow-page-prev">
            @if (isset($last_product))
                <div class="chm-coverflow-pageitem" style="background-image: 
                    url({{$last_product['value']['thumbnail']}});" data-productid="{{$last_product['value']['id']}}">  
                    <div class="chm-coverflow-pageitem-background"></div>
                    <div>
                      <p class="chm-coverflow-pageitem-title">{{$last_product['value']['name']}}</p>
                      <p class="chm-coverflow-pageitem-info">{{$last_product['value']['info']}}</p>
                    </div>
                    <div>
                      <p class="chm-coverflow-pageitem-price">{{$last_product['value']['show_price']}}</p>
                    </div>             
                </div>         
            @endif
        </div>        
        <div class="chm-coverflow-page-top">
            @if (isset($products['1']['value']))
                <div class="chm-coverflow-pageitem" style="background-image: 
                    url({{$products['1']['value']['thumbnail']}});" data-productid="{{$products['1']['value']['id']}}"> 
                    <div class="chm-coverflow-pageitem-background"></div>
                    <div>
                      <p class="chm-coverflow-pageitem-title">{{$products['1']['value']['name']}}</p>
                      <p class="chm-coverflow-pageitem-info">{{$products['1']['value']['info']}}</p>
                    </div>
                    <div>
                      <p class="chm-coverflow-pageitem-price">{{$products['1']['value']['show_price']}}</p>
                    </div> 
                </div>        
            @endif
        </div>
        <div class="chm-coverflow-page-middle">
            @if (isset($products['2']['value']))
                <div class="chm-coverflow-pageitem" style="background-image: 
                    url({{$products['2']['value']['thumbnail']}});" data-productid="{{$products['2']['value']['id']}}">  
                    <div class="chm-coverflow-pageitem-background"></div>
                    <div>
                      <p class="chm-coverflow-pageitem-title">{{$products['2']['value']['name']}}</p>
                      <p class="chm-coverflow-pageitem-info">{{$products['2']['value']['info']}}</p>
                    </div>
                    <div>
                      <p class="chm-coverflow-pageitem-price">{{$products['2']['value']['show_price']}}</p>
                    </div>   
                </div> 
            @endif
        </div>
        <div class="chm-coverflow-page-bottom">
            @if (isset($products['3']['value']))
                <div class="chm-coverflow-pageitem" style="background-image: 
                    url({{$products['3']['value']['thumbnail']}});" data-productid="{{$products['3']['value']['id']}}">  
                    <div class="chm-coverflow-pageitem-background"></div>
                    <div>
                      <p class="chm-coverflow-pageitem-title">{{$products['3']['value']['name']}}</p>
                      <p class="chm-coverflow-pageitem-info">{{$products['3']['value']['info']}}</p>
                    </div>
                    <div>
                      <p class="chm-coverflow-pageitem-price">{{$products['3']['value']['show_price']}}</p>
                    </div>   
                </div>
            @endif
        </div>
        <div class="chm-coverflow-page-next">
            @if (isset($products['4']['value']))
                <div class="chm-coverflow-pageitem" style="background-image: 
                    url({{$products['4']['value']['thumbnail']}});" data-productid="{{$products['4']['value']['id']}}">  
                    <div class="chm-coverflow-pageitem-background"></div>
                    <div>
                      <p class="chm-coverflow-pageitem-title">{{$products['4']['value']['name']}}</p>
                      <p class="chm-coverflow-pageitem-info">{{$products['4']['value']['info']}}</p>
                    </div>
                    <div>
                      <p class="chm-coverflow-pageitem-price">{{$products['4']['value']['show_price']}}</p>
                    </div>             
                </div>         
            @endif
        </div> 
    </section>
@endsection

@section('navigatorBottom')
    @include('mobile.elements.navigatorBottom')
@endsection

@section('script-file-bottom')
    <script type="text/javascript" src="/assets/vendor/jquery-transit/jquery.transit.js"></script>
    <script type="text/javascript" src="/assets/js/mobile.shopCoverflow.js"></script>
@endsection
