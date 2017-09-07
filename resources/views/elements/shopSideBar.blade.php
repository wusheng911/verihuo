<div id="sideBar" class="side-bar-container"> 
	<div class="side-bar-content">
		<a id="shopBarCart" style="text-decoration:none;" href={{action('ShopController@cartList')}}>
			@if(!empty($cartCnt))
				<div class="icon-container-cart-b">
					<div id="shopRedCircle" class="shop-red-circle">
						<span id='shopOrderCnt' class="shop-red-circle-text">新</span>
					</div>
			@else
				<div class="icon-container-cart">
			@endif

				<img class="two-dimension-code-img" src="/assets/img/cart-icon.png">
							</div>
		</a>
		<a id="shopBarOrder" href='/customer/order/search/all'>
			<div class="icon-container-order">
				<img class="two-dimension-code-img" src="/assets/img/order-icon.png">
			</div>
		</a>

		<a id="shopBarTwoCode" >
			<div class="icon-container-qrcode">
				<img class="two-dimension-code-img" src="/assets/img/qrcode-chaohun-icon.png">
			</div>
		</a>
	</div>
</div>
<div id="shopBarBoard" class="two-code-board">
	<div class="title"> <span class="text"> 更多精彩内容，请关注微信公众号</span> </div>
	<div class="two-code-container">
		<div class="two-code-item">
			<div class="item-img">	<img class="ch-img" src="/assets/img/qrcode-woyaochaohun-weixin.png">    </div>
		</div>
		<div class="two-code-item">
			<div class="item-img">	<img class="ch-img" src="/assets/img/qrcode-chaohunnvwang-weixin.png">    </div>
		</div>

	</div>
</div>
<script type="text/javascript">
	function displayBoard(){
		$('#shopBarBoard').css('display','inline');
	}
	function noneBoard(){
		$('#shopBarBoard').css('display','none');
	}
	$('#shopBarTwoCode').on('mouseover',displayBoard);
	$('#shopBarTwoCode').on('mouseout',noneBoard);
	$('#shopBarBoard').on('mouseover',displayBoard);
	$('#shopBarBoard').on('mouseout',noneBoard);

	$(window).on('scroll',function() {
		var userAgent = navigator.userAgent;
		var top = 0;
		if (userAgent.indexOf("Firefox") > -1) {
			top =document.documentElement.scrollTop;
		}else{

		 top = $(document.body).scrollTop();
		}
		if(top>60){
			$('#sideBar').css('top',0);
		}else{
			$('#sideBar').css('top',60 - top);
		}
});
</script>
