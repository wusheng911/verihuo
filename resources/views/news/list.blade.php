@extends('layouts.app')

@section('title', '')

@section('keywords', '')

@push('stylesheets')
@endpush

@push('scripts')

@endpush

@section('content')
<div id="main" class="main" >
  <div id="main-container">
  
	<div id="current-position" class="s-current-position t-a-l">
      <span> 当前位置:
        <ol class="breadcrumb" style="padding-left:12px;background-color:transparent;display:inline-block">
              <a style="text-decoration:none;" href="/">
                  <li style="display:inline-block">
                      首页 
                  </li>
              </a>
          @foreach($categorytree as $category)
              <a style="text-decoration:none;" href="/articles/{{$category->id}}">
                  <li style="display:inline-block;" >
					 / {{$category->title}}
                  </li>
              </a>
	 	@endforeach
        </ol>
      </span>
    </div>
    
    <div style="width:1300px;margin:auto;font-size: 0px;">
      <div id="display-container" class="q-display-container f-s-0 p-r">
       @foreach($articles as $article)
	<div id="display-item-1" class="display-item d-i-b p-r">
	  <div id="display-imgdiv" class="imgdiv">
		<a target="_blank" href="{{$articlePath}}{{ $article->id }}"><img alt="" src="{{ $article->image_4_3}}"></a>
	  </div>
	  <div id="display-data" class="display-data p-r">
	    <div id="data-titlediv" class="data-titlediv t-a-l o-h">
	    	<a target="_blank" href="{{$articlePath}}{{ $article->id }}">{{$article->title}}</a>
	    </div>

	    <div id="data-datediv" class="data-datediv t-a-l o-h" >
	      <span>{{ date('Y年m月d日', strtotime($article->updated_at)) }}</span>
	    </div>
	    <div id="data-otherdiv" class="data-otherdiv t-a-l">
	      <span class="span">
	      	{{$article->view_count}}
              </span>
	      <span>人气 / </span>
	      <span class="span">
	      {{$comments[$article->id]}}
              </span>
	      <span>评论 / </span>
	      <span class="span">{{$article->votes}}</span>
	      <span>推荐</span>
	    </div>
	  </div>
	</div>
      @endforeach
 
      </div>
     {{!! $articles->links()  !!}}
	<div style="clear:both;"></div>
  	
    </div>
  </div>
</div>
@endsection