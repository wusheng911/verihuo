@extends('layouts.mobile', ['subnav' => true, 'is_shop' => true])

@section('meta')
    @include('elements.metaSEO')
@endsection

@section('title', 'veriHuo- College application essays and advice')

@section('navigatorTop')
    @include('mobile.elements.navigatorTop3', [
        'title' => isset($category["name"])?$category["name"]:"", 
        'type'=>'list',
        'sort'=>strtolower($sort),
        'order'=>strtolower($order)
        ])
@endsection

@section('content')
    <style>
        body {
            background-color: white !important;
        }
    </style> 
    
    @include('mobile.elements.promptAlert')
      
    <!-- CONTENT SECTION -->
    <section class="full-width content-section">    
      <div class="chm-product-list-container">
        @foreach ($products as $key => $product)
            @if ($key%2 == 0)
                <div class="chm-product-list-row">
                    <div>
                        <a href="{{'/view/product/' . $product['id']}}">
                            <img class="chm-product-list-thumbnail" src="{{isset($product['images'][0]->image)?$product['images'][0]->image:""}}" 
                                 alt="{{isset($product['name'])?$product['name']:""}}"/>
                        </a> 
                    </div>
                    <!-- 中间的空白区域 -->
                    <div>                    
                    </div>
                    <div>
                        @if(isset($products[$key+1])) 
                            <a href="{{'/view/product/' . $products[$key+1]['id']}}">
                                <img class="chm-product-list-thumbnail" src="{{isset($products[$key+1]['images'][0]->image)?$products[$key+1]['images'][0]->image:""}}" 
                                alt="{{isset($products[$key+1]['name'])?$products[$key+1]['name']:""}}" />
                            </a> 
                        @endif
                  </div>
                </div> 
                <div class="chm-product-list-row">
                    <div class="chm-product-info-container" style="background-color: #efefef;border-top:#999 solid 1px;">
                        <div>
                            <p class="chm-product-title">
                                <a href="{{'/view/product/' . $product['id']}}">
                                    {{isset($product['name'])?$product['name']:""}}
                                </a>
                            </p>
                            <p class="chm-product-info">{{isset($product['info'])?$product['info']:""}}</p>
                            <p class="chm-product-info">{{isset($product['show_price']) && ($product['show_price'] > 0)?Helpers::getShowPrice($product['show_price']) . "元":""}}</p>
                        </div>                      
                    </div>
                    <!-- 中间的空白区域 -->
                    <div>                    
                    </div>
                    <div class="chm-product-info-container" style="{{isset($products[$key+1])?"background-color: #efefef;border-top:#999 solid 1px;":""}}">
                        @if(isset($products[$key+1])) 
                            <div>
                                <p class="chm-product-title">
                                    <a href="{{'/view/product/' . $products[$key+1]['id']}}">
                                        {{isset($products[$key+1]['name'])?$products[$key+1]['name']:""}}
                                    </a>
                                </p>
                                <p class="chm-product-info">{{isset($products[$key+1]['info'])?$products[$key+1]['info']:""}}</p>
                                <p class="chm-product-info">{{isset($products[$key+1]['show_price']) && ($products[$key+1]['show_price'] > 0)?Helpers::getShowPrice($products[$key+1]['show_price']) . "元":""}}</p>
                            </div>
                        @endif
                    </div>                  
                </div>  
                <div style="display:table-row;height:10px;">
                </div>
            @endif          
        @endforeach 
        
      </div>
        @include('mobile.elements.pagination', ['paginator' => $products->appends(['sort'=>$sort, 'order' => $order])])
        <div style="height:10px;padding-bottom: 50px;"></div>
    </section>
@endsection

@section('navigatorBottom')
    @include('mobile.elements.navigatorBottom')
@endsection

@section('script-file-bottom')
    <script type="text/javascript">
        //按价格/销量排对商品列表排序
        $(".chm-select-products").change(function(){
            select_value=$(".chm-select-products").val();
            
            current_arr = new Array();
            current_arr = select_value.split('|');
      
            goto = window.location.href;            
            goto = goto.substring(0,goto.indexOf('?')) + "?sort=" + current_arr[0] + "&order="+ current_arr[1]+"&page=1";
                window.location.href = goto;
        });
    </script>
@endsection
