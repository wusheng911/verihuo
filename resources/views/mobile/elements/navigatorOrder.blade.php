<div id="cart_navigator" class="chm-fixed-bottom chm-navigator-order">
    <nav class="top-bar bottom-bar" style="text-align:left;">
        <div style="float:left;padding-left:25px;padding-right:10px;">
            <span class="chm-span-normal-title" style="color:white !important;font-size:0.875rem;">
                费用总计:
            </span>  
            <span style="color:white !important;font-size:0.875rem;">
                {{$total_price}}元
            </span>
        </div>  
        <!-- 去付款 -->
        <div id="chm_go_pay" class="chm-cart-order {{empty($address)?"":"chm-cart-order-enable"}}">
            确认订单
        </div>
    </nav>
</div>