@extends('layouts.mobile', ['subnav' => true, 'has_nav_bottom'=> true])

@section('meta')
    <meta name="description" content="{{$article['seo_description']}}" />
    <meta name="keywords" content="{{empty($article['seo_keywords'])?"婚礼策划,时尚,娱乐,明星,生活,潮流,资讯,策划培训":$article['keywords']}}" />
@endsection

@section('title')
    {{$article['title']. '_' . 'verihuo'}}
@endsection

@section('css-top')
    <link rel="stylesheet" href="/assets/css/app-wap.css">
    <link rel="stylesheet" href="/assets/css/mobile.nativeShare.css"/>
    <script type="text/javascript" src="/assets/js/mobile.nativeShare.js"></script>
@endsection

@section('navigatorTop')
    @include('mobile.elements.navigatorTop', ['active_nav' => $category_code])
@endsection

@section('content')
    <div class="row">
        <div class="small-12 columns  chm-article-columns" style="padding-top:0.938rem;">
            <h3 class="text-left" style="font-weight:600;margin-bottom:0.675rem;">{{trim($article['title'])}}</h3>
        </div>
    </div>
    <div class="row chm-article-columns" style="margin-bottom:1rem;">
        <div class="small-4 columns text-left" style="padding:0 0.2rem;">
            <h5>
                <small><i class="fa fa-clock-o fa-lg"></i>&nbsp;{{date("Y-m-d", strtotime($article['post_at']))}}</small>
            </h5>
        </div>
        <div class="small-4 columns text-center" style="padding:0 0.2rem;">
            <h5>
                <small><i class="fa fa-pencil fa-lg"></i>&nbsp;{{$article['show_author']}}</small>
            </h5>
        </div>
        <div class="small-4 columns text-right" style="padding:0 0.2rem;">
            <h5>
                <small><i class="fa fa-eye fa-lg"></i>&nbsp;{{intval($article['view_count'])}}</small>
            </h5>
        </div>
    </div>
    <div id="article_content" data-article="{{$article['id']}}" class="row">
        <div class="small-12 columns chm-article-columns">
            {!! $article['content'] !!}          
        </div>
    </div> 

    @if (intval($article['rest_rate']) > 0)
        <div class="chm-article-loadmore-container">
            <a class="chm-article-loadmore" data-pos="{{$article['position']}}" 
               href="javascript:;" >
                <span>展开剩余{{$article['rest_rate']}}%</span>
                <span><i class="fa fa-angle-down fa-lg"></i></span></a>
        </div>
    @endif 
    <hr/>
    @include('mobile.elements.blockArtComments', ['comments' => $comments, 'article_id' => $article['id']])      

    @if (count($related_articles) > 0)
        @include('mobile.elements.blockHeaderInArticle', ['blockHeader' => '热门推荐'])
    
        @foreach ($related_articles as $art)
            @include('mobile.elements.blockArticle', ['content' => $art])
            <hr class="chm-divider-transparent-4"/>
        @endforeach
    @endif
    
    @foreach ($adpositions as $adposition)
        @if (stripos($adposition['adposition_code'], 'Mobile|Article|A') !== false)
            @include('nodes.index', ['position' => $adposition['adposition_code']]) 
            <hr class="chm-divider-transparent-2"/>
        @endif
    @endforeach  
        
    @include('mobile.elements.modalShare', ['article' => $article])
    @include('mobile.elements.modalQRCode', ['article' => $article])
    @include('mobile.elements.modalShareSDK', ['article' => $article])
    
    <div class="chm-back-to-top">
      <a href="javascript:;"><img src="/assets/img/back-to-top.png"/></a>
    </div>
@endsection

@section('navigatorBottom')
    @include('mobile.elements.navigatorArticle', ['article' => $article, 'total_comments' => count($comments)>0?$comments[0]['total_count']:0])
@endsection

@section('script-file-bottom')
    <script type="text/javascript" src="/assets/js/mobile.navigatorArticle.js"></script>
    <script type="text/javascript" src="/assets/js/mobile.artComments.js"></script>
  
@endsection
