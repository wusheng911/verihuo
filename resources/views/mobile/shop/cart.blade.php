@extends('layouts.mobile', ['subnav' => true, 'is_shop' => true, 'has_nav_bottom'=> true])

@section('meta')
    @include('elements.metaSEO')
@endsection

@section('title', 'veriHuo- College application essays and advice')

@section('navigatorTop')
    @include('mobile.elements.navigatorTop3', ['title' => "购物车", 'type'=>'cart'])
@endsection

@section('content')
    <style>
        body {
            background-color: #f2f2f2 !important;
        }
    </style>

    @include('mobile.elements.promptAlert')
      
    <!-- CONTENT SECTION -->
    <section class="full-width content-section">   
        @if (isset($carts) && count($carts) > 0)
            <div class="chm-cart-container">
			@foreach($merchants as $key=>$merchant)
			@if(!empty($merchant['info']))
				<div class="chm-cartitem-row">
					<div class="chm-cartitem-merchant-checkbox">
						<span>商家</span>
					</div>
					<div class="chm-cartitem-merchant-content">
						<img class="chm-cartitem-merchant-icon" src="{{$merchant['info']->logo}}">
						<span>{{$merchant['info']->name}}</span>
					</div>
				</div>
			@endif
			@if(!empty($merchant['carts']))
			@foreach($merchant['carts'] as $cart)
			    <div class="chm-cartitem-row" data-cartid="{{$cart["id"]}}">
				<div class="chm-cartitem-checkbox-container">
				    <input class="chm-cartitem-checkbox" type="checkbox"/>
				</div>
				<div class="chm-cartitem-content-container">
				    <img class="chm-cartitem-thumbnail" src="{{isset($cart["image"])?$cart["image"]:""}}" 
					alt="{{$cart["product_name"]}}" />
				    <div class="chm-cartitem-contentblock">
					<h5 class="{{($cart["sku_quantity"] > 0 && $cart["is_available_product"] == 1 && $cart["product_status"] == 2)?"":"chm-cartitem-invalid"}}">
					    <a href="/view/product/{{$cart["product_id"]}}">{{$cart["product_name"]}}</a>
					</h5>
					@if(isset($cart["sku_attrs"]) and count($cart["sku_attrs"]) > 0)
					    <ul class="no-bullet inline-list">
						@foreach($cart["sku_attrs"] as $sku_attr)
						    <li>
							<span>
							    {{$sku_attr["name"] . ":" . $sku_attr["value"]}}
							</span>
						    </li>
						@endforeach
					    </ul>
					@endif
				    </div> 
				    <div class="chm-cartitem-bottombar">
					<span class="chm-cartitem-price">¥</span>
					<span class="chm-cartitem-price chm-cartitem-pricevalue" data-cartid="{{$cart["id"]}}" 
					    data-pricevalue="{{$cart["sku_price"]}}">
					    {{Helpers::getShowPrice($cart["sku_price"])}}
					</span>
					<div class="chm-cartitem-edit-container">
					    <div style="padding-right: 0px" class="chm-cartitem-quantitybtn-spandiv">
						<span class="chm-cartitem-edit-subtractbtn {{$cart["quantity"] > 1?"":"chm-btn-disabled"}}">
						-
						</span>
					    </div>
					    <div style="padding-right: 0px" class="chm-cartitem-edit-spandiv">
						<span class="chm-cartitem-edit-quantity">{{$cart["quantity"]}}</span>
					    </div>
					    <div style="padding-right: 0px" class="chm-cartitem-quantitybtn-spandiv">
						<span class="chm-cartitem-edit-addbtn" value="+">+</span>
					    </div>
					</div>
				    </div>
				</div>
			    </div>
			@endforeach  
			@endif
		@endforeach
            </div>      
        @endif        
    </section>
    
    @include('mobile.elements.modalConfirm')
    @include('mobile.elements.modalConfirmForEditCount')
@endsection

@section('navigatorBottom')
    @include('mobile.elements.navigatorCart')
@endsection

@section('script-file-bottom')
    <script type="text/javascript">
        displayTotalPrice();        
    </script>    
@endsection
