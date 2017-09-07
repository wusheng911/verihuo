@extends('layouts.mobile')

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
        .slick-dots {
          bottom: -5px;
          margin-left: 0; /* Fixes the visible horizontal scroll bar */
        }

        .slick-dots li button::before {
          font-size: 8px;
          color: rgba(255,255,255,0.8);
          opacity: 1;
        }

        .slick-dots li.slick-active button::before {
          color: #b30900;
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
    </style>

    @include('mobile.elements.promptAlert')

    <!-- CONTENT SECTION -->
    <section class="full-width content-section">      
        @include('mobile.elements.dividerImage')
        <div class="row">
            <div class="small-12 columns chm-slick-multislider" style="padding:0;margin:0;">  
                @foreach ($adpositions as $adposition)
                    @if (stripos($adposition['adposition_code'], 'Mobile|Home|A') !== false)
                        @include('nodes.index', ['position' => $adposition['adposition_code']])  
                    @endif
                @endforeach                
            </div>
        </div>
        
        @include('mobile.elements.dividerImage')
        <div class="row">
            <div class="small-6 columns" style="padding:0px;">
                <a href="/news/{{config('chaohun.art_parent_category_1')}}"><img style="width:100%;" src="/assets/img/wo.jpg"/></a>
            </div>
            <div class="small-6 columns" style="padding:0px;">
                <a href="/news/{{config('chaohun.art_parent_category_2')}}"><img style="width:100%;" src="/assets/img/yao.jpg"/></a>
            </div>
        </div>
        <div class="row">
            <div class="small-6 columns" style="padding:0px;">
                <a href="/news/{{config('chaohun.art_parent_category_3')}}"><img style="width:100%;" src="/assets/img/chao.jpg"/></a>
            </div>
            <div class="small-6 columns" style="padding:0px;">
                <a href="/news/{{config('chaohun.art_parent_category_4')}}"><img style="width:100%;" src="/assets/img/hun.jpg"/></a>
            </div>
        </div>         
        @include('mobile.elements.dividerImage')
        
        @include('mobile.elements.blockHeader', 
            [
                'blockHeader' => isset($queen_category['title']) ? $queen_category['title'] : '', 
                'blockMore' => '/news/' . (isset($queen_category['id']) ? $queen_category['id'] : '')
            ]
        )
        {{-- Banner --}}
        @include('nodes.index', ['position' => 'Mobile|Home|B1']) 
        <hr class="chm-divider-transparent-4"/>
        {{-- Article1 --}}
        @include('nodes.index', ['position' => 'Mobile|Home|B2']) 
        <hr class="chm-divider-transparent-4"/>
        {{-- Article2 --}}
        @include('nodes.index', ['position' => 'Mobile|Home|B3'])                   
        <hr class="chm-divider-transparent-4"/>
        <?php $ad_carea_count = 0; ?>
        @foreach ($adpositions as $adposition)
            @if (stripos($adposition['adposition_code'], 'Mobile|Home|C') !== false)
                @if($ad_carea_count)
                    <hr class="chm-divider-transparent-2"/>
                @else
                    @include('mobile.elements.dividerImage')
                    <?php $ad_carea_count++; ?>
                @endif
                @include('nodes.index', ['position' => $adposition['adposition_code']]) 
            @endif
        @endforeach      
    </section>
@endsection

@section('navigatorBottom')
    {{-- @include('mobile.elements.navigatorBottom') --}}
@endsection

@section('script-file-bottom')
    <script type="text/javascript" src="/assets/vendor/slick/slick.min.js"></script>
    <script type="text/javascript" src="/assets/js/mobile.slider.js"></script>
    <!-- EC Lite在线客服 -->
    <!--
    <script type="text/javascript" src="http://cs.ecqun.com/?id=1678907" charset="utf-8"></script>
    -->
@endsection
