@extends('layouts.shopapp')



@section('scripts')
    <script type="text/javascript">
        $(document).ready(function(){
            var tag_id = '';
            $("#main-search-btn").click(function(){
                var text = $.trim($("#main-search-input").val());
                (text=='') ? alert('请输入要搜索的关键字！') : searchTagId(text);
            });
            $("#main-search-input").on("keydown", function(event){
                if ( event.which == 13 ) {
                    event.preventDefault();
                    var text = $.trim($("#main-search-input").val());
                    (text=='') ? alert('请输入要搜索的关键字！') : searchTagId(text);
                }
            });
            function searchTagId(s_tag) {
                window.open("/shop/productsearch/"+s_tag);
            }
        });
    </script>
 	
@endsection

@section('content')

    <div class="shop-container">

        <div class="shop-search-container">
            <div class="shop-search-frame">
                <input id='main-search-input' class="shop-search-kuang" placeholder="想买什么就搜什么">
                <div id="main-search-btn" class="shop-search-btn">
                    <a class="btn-text">搜索</a>
                </div>
            </div>
            <div class="shop-keywords-container">
                @if(!empty($keywords))
                    @foreach($keywords as $key=>$value)
                        <div class="shop-keywords">
                            <a target="_blank" href="/shop/productsearch/{{$value}}">{{$value}}</a>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
        <div class="layout-1">
            <div class="top">
                @foreach ($adpositions as $adposition)
                    @if (stripos($adposition['adposition_code'], 'PC|Shop|Home|A1') !== false)
                        @include('nodes.index', ['position' => $adposition['adposition_code']])
                    @endif
                @endforeach
            </div>
        </div>
        <div class="layout-2">
            <div class="left">
                @foreach ($adpositions as $adposition)
                    @if (stripos($adposition['adposition_code'], 'PC|Shop|Home|A2') !== false)
                        @include('nodes.index', ['position' => $adposition['adposition_code']])
                    @endif
                @endforeach
            </div>
            <div class="right">
                <div class="img-top">
                    <div class="top-left">
                        @foreach ($adpositions as $adposition)
                            @if (stripos($adposition['adposition_code'], 'PC|Shop|Home|A3') !== false)
                                @include('nodes.index', ['position' => $adposition['adposition_code']])
                            @endif
                        @endforeach
                    </div>
                    <div class="top-right">
                        @foreach ($adpositions as $adposition)
                            @if (stripos($adposition['adposition_code'], 'PC|Shop|Home|A4') !== false)
                                @include('nodes.index', ['position' => $adposition['adposition_code']])
                            @endif
                        @endforeach
                    </div>
                </div>
                <div class="img-bottom">
                    @foreach ($adpositions as $adposition)
                        @if (stripos($adposition['adposition_code'], 'PC|Shop|Home|A5') !== false)
                            @include('nodes.index', ['position' => $adposition['adposition_code']])
                        @endif
                    @endforeach

                </div>
            </div>
        </div>
        <div class="layout-2">
            <div class="right">
                <div class="img-top">
                    <div class="top-left">
                        @foreach ($adpositions as $adposition)
                            @if (stripos($adposition['adposition_code'], 'PC|Shop|Home|A6') !== false)
                                @include('nodes.index', ['position' => $adposition['adposition_code']])
                            @endif
                        @endforeach
                    </div>
                    <div class="top-right">
                        @foreach ($adpositions as $adposition)
                            @if (stripos($adposition['adposition_code'], 'PC|Shop|Home|A7') !== false)
                                @include('nodes.index', ['position' => $adposition['adposition_code']])
                            @endif
                        @endforeach
                    </div>
                </div>
                <div class="img-bottom">
                    @foreach ($adpositions as $adposition)
                        @if (stripos($adposition['adposition_code'], 'PC|Shop|Home|A8') !== false)
                            @include('nodes.index', ['position' => $adposition['adposition_code']])
                        @endif
                    @endforeach
                </div>
            </div>
            <div class="left-1">
                @foreach ($adpositions as $adposition)
                    @if (stripos($adposition['adposition_code'], 'PC|Shop|Home|A9') !== false)
                        @include('nodes.index', ['position' => $adposition['adposition_code']])
                    @endif
                @endforeach
            </div>
        </div>
        <div class="shop-topic">
            <div class="line-left"></div>
            <div class="topic-text">专题</div>
            <div class="line-right"></div>
        </div>
        <div class="shop-topic-content">
            <div class="div-1">
                @foreach ($adpositions as $adposition)
                    @if (stripos($adposition['adposition_code'], 'PC|Shop|Home|B1') !== false)
                        @include('nodes.index', ['position' => $adposition['adposition_code']])
                    @endif
                @endforeach
            </div>
            <div class="div-2">
                @foreach ($adpositions as $adposition)
                    @if (stripos($adposition['adposition_code'], 'PC|Shop|Home|B2') !== false)
                        @include('nodes.index', ['position' => $adposition['adposition_code']])
                    @endif
                @endforeach
            </div>
            <div class="div-1">
                @foreach ($adpositions as $adposition)
                    @if (stripos($adposition['adposition_code'], 'PC|Shop|Home|B3') !== false)
                        @include('nodes.index', ['position' => $adposition['adposition_code']])
                    @endif
                @endforeach
            </div>
            <div class="div-3">
                @foreach ($adpositions as $adposition)
                    @if (stripos($adposition['adposition_code'], 'PC|Shop|Home|B4') !== false)
                        @include('nodes.index', ['position' => $adposition['adposition_code']])
                    @endif
                @endforeach
            </div>
        </div>
    </div>
    @endsection
