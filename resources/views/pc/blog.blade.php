@extends('layouts.app')

@section('title','verihuo_blog')

@section('keywords', 'verihuo的博客')

@section('styles')
<style>
a:hover{
	text-decoration:none;
	cursor:pointer;
}
.blog-sub-list li {
	list-style-type:none;
	position: relative;
    border-top: 1px solid #c0bfbf;
    padding: 20px 0;
}
.blog-sub-list li:first-child {
    border-top: 0;
}
</style>
@endsection
@section('scripts')
<script>
$('.am-active').each(function(e){
	$($('.am-active').get(e)).removeClass('am-active');
});
$('.nav-blog').each(function(e){
	$($('.nav-blog').get(e)).addClass('am-active');
});
</script>
@endsection
@section('content')
<div class="am-slider am-slider-default" data-am-flexslider id="demo-slider-0">
  <ul class="am-slides">
	@foreach($blogKvs as $key => $value)
		<li><img style="max-height:400px" src="{{$value['nodes']['0']['localAttributes']['Image Path']['values'][1]['value']}}" />
		</li>
	@endforeach
  </ul>
</div>
<div class="blog-container">
	<div class="col-md-12">
		<div class="col-md-8">
			<p class="blog-main-list-title">New Posts</p>
			<div class="col-md-12 blog-main-list">
				@foreach($datas as $key => $value)
				<div class="blog-main-item">
					<div class="col-md-6 blog-item-img-container">
						<a href="/blog/{{$value->id}}">
						<img class="col-md-12 blog-item-img" src="{{$value->image_4_3}}">
						</a>
					</div>
					<div class="col-md-6 blog-item-info">

						<a href="/blog/{{$value->id}}"><p class="blog-item-title">{{$value->title}}</p></a>
						<p class="blog-item-date">{{$value->updated_at}}</p>
						<p class="blog-item-description">{{$value->description}}</p>
					</div>
				</div>
				@endforeach
			</div>
			{!! $datas->links() !!}
		</div>	
		<div class="col-md-4">
			<div class="col-md-8 col-md-offset-3 blog-sub-list">
				<div class="am-btn-group doc-js-btn-1" data-am-button>
				  <label class="am-btn am-btn-primary">
					<input type="radio" name="options" value="我是过去" id="option1"> 文章 
				  </label>
				</div>
			</div>
			<div class="col-md-8 col-md-offset-2 blog-sub-list">
					<ul>
						@php
							$index =1;
						@endphp	
						@foreach($special as $key => $value)
							<li>
								<div class="blog-sub-item">
									<div class="blog-sub-index">{{$index++}}.</div>	
									<a href="/blog/{{$value->id}}" class="blog-sub-title">{{$value->title}}</a>
								</div>
							</li>

						@endforeach
					</ul>
			</div>
		</div>	
	</div>
</div>
@endsection
