<div class="pull-left customer-nav-menu">
    <ul>
        <li>
            <a href="#" class="drop_down">
                &nbsp;&nbsp;个人中心&nbsp;&nbsp;
                <i class="fa fa-chevron-circle-up hide" aria-hidden="true"></i>
                <i class="fa fa-chevron-circle-down" aria-hidden="true"></i>
            </a>
            <ul class="drop_down_menu">
                <li class="@if(!empty($customer) && empty($orders)){{ 'active' }}@endif"><a href="/customer/account">个人信息</a></li>
                <li><a href="">密码修改</a></li>
                <li><a href="">收藏夹</a></li>
                <li><a href="">评论</a></li>
                <li><a href="">消息</a></li>
            </ul>
        </li>
        <li>
            <a href="#" class="drop_down">
                <i class="fa fa-chevron-circle-up hide" aria-hidden="true"></i>
                <i class="fa fa-chevron-circle-down" aria-hidden="true"></i>
            </a>
            <ul class="drop_down_menu">
                <li class="@if(!empty($orders) || !empty($order)){{ 'active' }}@endif"><a href="/customer/order/search/all" >我的订单</a></li>
                <li class="@if(!empty($addresses)){{ 'active' }}@endif"><a href="/my/address">收货地址</a></li>
                <li class="@if(!empty($cartsGroup)){{ 'active' }}@endif"><a href="/shop/cart">购物车</a></li>
                <li><a href="">优惠券</a></li>
            </ul>
        </li>
        <li style="width: 100%;height:100px;background-color: #e5e5e5;"></li>
    </ul>
</div>
<script type="text/javascript">
    $('.drop_down').on('click',function(){
        $(this).children('.fa-chevron-circle-up').toggleClass('hide');
        $(this).children('.fa-chevron-circle-down').toggleClass('hide');
        $(this).next().toggleClass('hide');
    })
</script>
