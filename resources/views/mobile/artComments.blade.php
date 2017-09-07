<style type="text/css">
    .shares{width:100%;height:89.5%;position:absolute;left:0;top:38;z-index:9990;position:fixed;}
</style>
@extends('layouts.mobile', ['subnav' => true, 'has_nav_bottom'=> true])

@section('meta')
    @include('elements.metaSEO')
@endsection


@section('title')
    {{$article['title']. '_' . 'verihuo'}}
@endsection


@section('css-top')
    <link rel="stylesheet" href="/assets/css/mobile.nativeShare.css"/>
    <script type="text/javascript" src="/assets/js/mobile.nativeShare.js"></script>
@endsection

@section('navigatorTop')
    @include('mobile.elements.navigatorTop', ['active_nav' => $category_code])
@endsection

@section('content')    
    @include('mobile.elements.blockArtComments', ['comments' => $comments, 'article_id' => $article['id']])
    <div class="row columns">
      <div class="small-12 columns" style="height:2.6rem;">&nbsp;</div>
    </div>    
    @include('mobile.elements.modalQRCode')
    @include('mobile.elements.modalShare', ['article' => $article])
    @include('mobile.elements.modalShareSDK', ['article' => $article])
@endsection

@section('navigatorBottom')
    @include('mobile.elements.navigatorArticle', ['article' => $article, 'total_comments' => count($comments)>0?$comments[0]['total_count']:0])
@endsection

@section('script-file-bottom')
    <script type="text/javascript" src="/assets/js/mobile.nativeShare.js"></script>
    <script type="text/javascript" src="/assets/js/mobile.navigatorArticle.js"></script>
    <script type="text/javascript" src="/assets/js/mobile.artComments.js"></script>
@endsection
