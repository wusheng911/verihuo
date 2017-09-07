@extends('layouts.shopapp')

@section('scripts')
    <script>
     $(document).ready(function() {
         $(".dropdown").hover(
             function(){ $(this).addClass('open') },
             function(){ $(this).removeClass('open') }
         );
     });
    </script>
@endsection
<?php $adjacents = 3; ?>

@section('content')
        <?php $baseUrl = $products->url($products->currentPage());?>
    <div style="width:1300px;margin:auto;">
        @if(!empty($shopCategoryTops))
            <div class="news-one-ad-top">
                    @foreach ($shopCategoryTops as $adposition)
                        @if ($adposition['adposition_code'] == "PC|ShopCategory|A1")
                            @include('nodes.index', ['position' => $adposition['adposition_code']])
                        @endif
                        @if ($adposition['adposition_code'] == "PC|ShopCategory|A2")
                            @include('nodes.index', ['position' => $adposition['adposition_code']])
                        @endif
                        @if ($adposition['adposition_code'] == "PC|ShopCategory|A3")
                            @include('nodes.index', ['position' => $adposition['adposition_code']])
                        @endif
                        @if ($adposition['adposition_code'] == "PC|ShopCategory|A4")
                            @include('nodes.index', ['position' => $adposition['adposition_code']])
                        @endif
                    @endforeach
            </div>
        @endif
        @if(isset($category))
            <div class="ch-shop-category-filter-pannel" >
                <span>
                    {{ $category->level == 0 ? $category->name : $category->parent->name }}
                </span>
                <ul>
                    <a href="{{ url('/shop/category/'. ($category->level == 0 ? $category->id : $category->parent->id)) }}">
                        <li class="{{ $category->level == 0 ? 'active' : '' }}">
                            全部
                        </li>
                    </a>
                    <?php $categories = $category->level == 0 ? $category->children : $category->siblingsAndI; ?>
                    @if(!empty($categories))
                        @foreach($categories as $cc)
                            <a href="{{ url('/shop/category/'.$cc->id) }}">
                                <li class="{{ $cc->id == $category->id ? 'active' : '' }}">
                                    {{ $cc->name }}
                                </li>
                            </a>
                        @endforeach
                    @endif
                </ul>
            </div>
        @endif
        <div class="ch-shop-product-filter-pannel" >
            <ul>
                <li> <div class="dropdown">
                    <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        销量
                        @if($orderby == 'sale_quantity')
                            <span class="caret-solid-{{ $order == 'asc' ? 'up' : 'down' }}"></span>
                        @else
                            <span class="caret-hollow-down"></span>
                        @endif
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                        <li><a href="{{ $baseUrl.'&orderby=sale_quantity&order=desc' }}"> 销量从高到低 </a></li>
                        <li><a href="{{ $baseUrl.'&orderby=sale_quantity&order=asc' }}"> 销量从低到高 </a></li>
                    </ul>
                </div> </li>
                <li> <div class="dropdown">
                    <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        价格
                        @if($orderby == 'show_price')
                            <span class="caret-solid-{{ $order == 'asc' ? 'up' : 'down' }}"></span>
                        @else
                            <span class="caret-hollow-down"></span>
                        @endif
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                        <li><a href="{{ $baseUrl.'&orderby=show_price&order=desc' }}"> 价格从高到低 </a></li>
                        <li><a href="{{ $baseUrl.'&orderby=show_price&order=asc' }}"> 价格从低到高 </a></li>
                    </ul>
                </div> </li>
            </ul>

        </div>
        <div class="ch-shop-product-list" >
            @if(!empty($products))
                <ul>
                    @foreach($products as $product)
                        <a href="{{ '/view/product/'.$product->id }}">
                            <li>
                                <img src="{{ !empty($product->firstImage->image) ? $product->firstImage->image : '' }}" alt="{{ $product->name }}">
                                <div class="title">【{{$product->getBrandName() }}】{{ $product->name }}</div>
                                <div class="subtitle">{{ $product->info }}</div>
                                <div class="title">
                                    <span>￥{{ $product->show_price }}元</span>
                                    <span class="quantity" >销量{{ $product->sale_quantity  }}件</span>
                                </div>
                            </li>
                        </a>
                    @endforeach
                </ul>
            @endif
        </div>
        <div style="clear:both;"></div>

        <div class="s-page">
            <ul class="pagination">

                <li class="previous {{ $products->previousPageUrl() ? '' : 'disabled' }}">
                    <a href="{{ $products->previousPageUrl() ?: 'javascript:void()' }}">
                        <i class="fa fa-angle-double-left"></i>
                    </a>
                </li>
                @if( $products->currentPage()<6 )
                    <?php $lastPage = ($products->lastPage()<=5) ? $products->lastPage() : 5 ;?>
                    @for($i = 1; $i <= $lastPage; $i++)
                        <li class="{{ ($products->currentPage() == $i) ? ' active' : '' }}">
                            <a href="{{ $products->url($i) }}">{{ $i }}</a>
                        </li>
                    @endfor
                    @if($products->lastPage()>=6) <li><a>...</a></li> @endif
                    @else
                        <li class=""><a href="{{ $products->url(1) }}">1</a></li>
                        @if($products->currentPage() > ($adjacents + 2))
                            <li class=""><a href="">...</a></li>
                        @endif
                        <?php $pmin = ($products->currentPage() > $adjacents) ? ($products->currentPage() - $adjacents) : 1; ?>
                        <?php $pmax = ($products->currentPage() < ($products->lastPage() - $adjacents)) ? ($products->currentPage() + $adjacents) : $products->lastPage(); ?>
                        @for ($i = $pmin; $i <= $pmax; $i++)
                            <li class="{{ ($products->currentPage() == $i) ? ' active' : '' }}">
                                <a href="{{ $products->url($i) }}">{{ $i }}</a>
                            </li>
                        @endfor

                        @if($products->currentPage() < ($products->lastPage() - $adjacents - 1))
                            <li><a href="">...</a></li>
                        @endif

                        @if ($products->currentPage() < ($products->lastPage() - $adjacents))
                            <li class=""><a href="{{ $products->url($products->lastPage()) }}">{{ $products->lastPage() }}</a></li>
                        @endif
                    @endif

                    <li class="next {{ $products->nextPageUrl() ? '' : 'disabled' }}">
                        <a href="{{ $products->nextPageUrl() ?: 'javascript:void()' }}">
                            <i class="fa fa-angle-double-right"></i>
                        </a>
                    </li>
                    <li><span class="goPageBox">跳转至<span><input id="btn_go_input"  class="form-control" value="" type="text"></span>页<button type="button" class="btn btn-default" id="goPage">Go</button></span></li>
            </ul>
        </div>
        <script type="text/javascript">
         $(document).ready(function(){
             $('#goPage').click(function(){
                 var page = $('#btn_go_input').val();
                 @if(isset($category))
                 var url = "/shop/category/{{ $category->id }}?page=";
                 @elseif(isset($key))
                 var url = "/shop/productsearch/{{ $key }}?page=";
                 @endif
                 var lastPage = {{ $products->lastPage() }};
                 if(!isNaN(page)){
                     if(page>lastPage) page=lastPage;
                     if(page<1) page=1;
                     location.href = url+page;
                 } else {
                     alert('请输入要正确的跳转页数!');
                 }
             });
         });
        </script>
        <!-- 分页结束 -->
    </div>
@endsection
