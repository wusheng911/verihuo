@extends('layouts.mobile', ['subnav' => true])

@section('meta')
@endsection

@if(!empty($seoTitle))
    @section('title', $seoTitle)
@else
    @section('title', 'veriHuo- College application essays and advice')
@endif

@if(!empty($seoDescription))
    @section('description', $seoDescription)
@else
    @section('description', 'veriHuo- College application essays and advice')
@endif

@if(!empty($seoKeywords))
    @section('keywords', $seoKeywords)
@else
    @section('keywords', 'veriHuo- College application essays and advice')
@endif

@section('navigatorTop')
    @if(isset($category['code']))
        @include('mobile.elements.navigatorTop', ['active_nav' => $category['code']])
    @endif
@endsection

@section('content')
    <div class="row columns">
        <div class="small-7 columns chm-block-header-columns text-center">
            <span class="chm-block-header">{{$category['title']}}</span>
        </div>
        <div class="small-5 columns chm-block-header-columns text-right">
          <ul class="inline-list chm-sortby-nav">
            <li class="{{$status["latest"]}}" data-tag="post_at">最新</li>
            <li class="{{$status["hot"]}}" data-tag="view_count">热门</li>
          </ul>
        </div>
    </div>  

    @foreach ($contents as $content)
        @include('mobile.elements.blockArticle', ['content' => json_decode(json_encode($content), true)])
        <hr class="chm-divider-transparent-4"/>
    @endforeach
    
    @include('mobile.elements.pagination', ['paginator' => $contents->appends(['sort'=>$sort])])
    
    <hr class="chm-divider-transparent-4"/>
    
    {{-- Bottom Banner --}}
    @foreach ($adpositions as $adposition)
        @if (stripos($adposition['adposition_code'], 'Mobile|News|More|A') !== false)
            @include('nodes.index', ['position' => $adposition['adposition_code']]) 
            <hr class="chm-divider-transparent-2"/>
        @endif
    @endforeach  
    
@endsection


@section('navigatorBottom')
    {{-- @include('mobile.elements.navigatorBottom') --}}
@endsection

@section('script-file-bottom')
    <script type="text/javascript">
        $(".chm-sortby-nav li").click(function(){
            //$(this).addClass('selected').siblings().removeClass('selected');
            if (!$(this).hasClass("selected")) {
                var goto = window.location.href;            
                goto = goto.substring(0,goto.indexOf('?')) + "?sort=" + $(this).data("tag") + "&page=1";
                window.location.href = goto;
            }
        });
    
    </script>
@endsection


