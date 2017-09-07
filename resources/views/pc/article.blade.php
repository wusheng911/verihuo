@extends('layouts.app')

@section('meta')
    <meta name="description" content="" />
    <meta name="keywords" content="" />
@endsection

@section('title')

@endsection

@section('styles')
<style>
.am-article{
	display:block;
	padding:30px;
}

.am-article-title {
    font-size: 2.8rem;
    line-height: 1.15;
    font-weight: 400;
	text-align:center;
}
.am-article-meta {
    font-size: 1.2rem;
    line-height: 1.5;
    color: #999;
	text-align:center;
}
.article-footer{
	position:absolute;
	bottom:0;
	width:100%;
	height:100px;
{
</style>
@endsection
@section('scripts')
    <script>
     $(document).ready(function(){

     });
    </script>
@endsection

@section('content')
<article class="am-article">
  <div class="am-article-hd">
    <h1 class="am-article-title">{{$article->title}}</h1>
    <p class="am-article-meta">{{$article->created_by}}</p>
  </div>
                    <div style="margin-bottom:100px;" class="content-body">
                        {!! $article->content !!}
                    </div>
</article>
    
@endsection
