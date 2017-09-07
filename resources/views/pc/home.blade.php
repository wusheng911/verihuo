@extends('layouts.app')

@section('title', 'verihuo首页')


@section('keywords', 'verihuo是一个企业')
@section('description',"")


@section('scripts')
    <script defer type="text/javascript" src="{{ asset('assets/js/jquery.aniview.min.js') }}"></script>
    <script defer type="text/javascript" src="{{ asset('assets/js/jquery.goup.min.js') }}"></script>
    <script defer type="text/javascript" src="{{ asset('assets/js/modernizr.custom.46884.js') }}"></script>
    <script defer type="text/javascript" src="{{ asset('assets/js/jquery.slicebox.js') }}"></script>
    <script defer type="text/javascript" src="{{ asset('assets/js/pc.home.js') }}"></script>
<script>
	$('.am-active').each(function(e){
		$($('.am-active').get(e)).removeClass('am-active');
	});
	$('.nav-home').each(function(e){
		$($('.nav-home').get(e)).addClass('am-active');
	});
	//$('.home-module-button').on('click',function(e){
		//var id = $(e.currentTarget).attr('id');
		//var index = id.substr(id.length-1,1)
		//$('#itemHide-'+ index).fadeToggle();
	//});	
	//$('.home-module-button-white').on('click',function(e){
		//console.log(e.currentTarget);
		//var id = $(e.currentTarget).attr('id');
		//var index = id.substr(id.length-1,1)
		//$('#itemHide-'+ index).fadeToggle();
	//});
</script>
@endsection

@section('styles')
<style>
.sb-slider {
	margin: 0px auto;
	position: relative;
	overflow: hidden;
	width: 100%;
	list-style-type: none;
	padding: 0;
}

.sb-description {
	padding: 0px;
	bottom: 10px;
	left: 10px;
	right: 10px;
	z-index: 1000;
	position: absolute;
	background: none;
	border-left:0px;
	/*background: #CBBFAE;*/
	/*background: rgba(190,176,155, 0.4);*/
	/*border-left: 4px solid rgba(255,255,255,0.7);*/
	-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";
	filter: alpha(opacity=0);	
	opacity: 0;
	color: #fff;

	-webkit-transition: all 200ms;
	-moz-transition: all 200ms;
	-o-transition: all 200ms;
	-ms-transition: all 200ms;
	transition: all 200ms;
}
.sb-slider li.sb-current .sb-description:hover {
	-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=99)";
	filter: alpha(opacity=99);	
	background:none;
	/*background: rgba(190,176,155, 0.7) */;
}
.navbar{
    position: relative;
    min-height: 50px;
    margin-bottom: 0px;
    border: 1px solid transparent;
	z-index:999;
}
</style>
@endsection
@section('content')
<div style="position:relative;margin-top:-20px;margin-bottom:0px;" class="am-slider am-slider-default" data-am-flexslider id="demo-slider-0">
  <ul class="am-slides">
	@foreach($homeKvs as $key => $value)
		<li><img src="{{$value['nodes']['0']['localAttributes']['Image Path']['values'][1]['value']}}" />
		</li>
	@endforeach
  </ul>
@foreach($homeKVText as $key => $value)
	<div class="home-kv-text1" >
		{{$value['nodes']['0']['localAttributes']['title']['values'][1]['value']}}
	</div>
	<div class="home-kv-text2" >
		{{$value['nodes']['0']['localAttributes']['description']['values'][1]['value']}}
	</div>
@endforeach
</div>
@php
	$index = 0;
	
@endphp

@foreach($homeItems as $key => $value)
@php
	$adCode = substr($value["adposition_code"], -1);
@endphp
@if($index % 2 == 0)
		
		<div id="test-collapse" class="home-module">
		<img class="home-bg-img" src="{{$value['nodes']['0']['localAttributes']['Image Path']['values'][1]['value']}}">
			<div class="col-md-8 col-md-offset-2 home-module-container">
				<div class="col-md-6 home-module-img">
				</div>
				<div class="col-md-6 home-module-content">
					
					<div class="home-module-title-white" data-am-scrollspy="{animation: 'slide-left'}">
						{{$value['nodes']['0']['localAttributes']['title']['values'][1]['value']}}
					</div>
					<div class="home-module-description-white" data-am-scrollspy="{animation: 'slide-right'}">
						<p>
						@php
						$description = $value['nodes']['0']['localAttributes']['description']['values'][1]['value'];
						if(strlen($description) > 75){
							$description = mb_substr($description,0,75);
						}
						@endphp
						{{$description}}
						</p>
					</div>
					<div class="col-md-12 home-module-button-container" data-am-scrollspy="{animation: 'scale-up'}">
						<button id="itemBtn-{{$index}}" class="home-module-button" data-am-collapse="{parent:'#test-collapse' ,target: '#neirong{{$index}}'}">{{$value['nodes']['0']['localAttributes']['label']['values'][1]['value']}}</button>	
					</div>
				</div>
			</div>
		</div>
		<div id="neirong{{$index}}" style="margin:30px;" class="am-collapse ">
			<p></p>	
					 {{$value['nodes']['0']['localAttributes']['description']['values'][1]['value']}}
		</div>
@else

	<div class="home-module">
		<img class="home-bg-img" src="{{$value['nodes']['0']['localAttributes']['Image Path']['values'][1]['value']}}">
		<div class="col-md-8 col-md-offset-2 home-module-container">
			<div class="col-md-6 home-module-content">
				
				<div class="home-module-title" data-am-scrollspy="{animation: 'slide-bottom',delay:'100'}">
						{{$value['nodes']['0']['localAttributes']['title']['values'][1]['value']}}
				</div>
				<div class="home-module-description" data-am-scrollspy="{animation: 'slide-bottom',delay:'200'}">
					<p>
						@php
						$description = $value['nodes']['0']['localAttributes']['description']['values'][1]['value'];
						if(strlen($description) > 75){
							$description = mb_substr($description,0,75);
						}
						@endphp
						{{$description}}
					</p>
				</div>
				<div class="col-md-12 home-module-button-container" data-am-scrollspy="{animation: 'slide-bottom',delay:'300'}">
						<button id="itemBtn-{{$index}}" class="home-module-button-white" data-am-collapse="{parent:'#test-collapse' ,target: '#neirong{{$index}}'}">{{$value['nodes']['0']['localAttributes']['label']['values'][1]['value']}}</button>	
				</div>
			</div>
			<div class="col-md-6 home-module-img">
			</div>
		</div>
	</div>
		<div id="neirong{{$index}}" style="margin:30px" class="am-collapse ">
			<p></p>	
				 {{$value['nodes']['0']['localAttributes']['description']['values'][1]['value']}}
		</div>
@endif
@php
	$index++;
@endphp
@endforeach
@endsection
