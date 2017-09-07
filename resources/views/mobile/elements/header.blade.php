<div class="chm-header">
    <nav class="tab-bar" data-offcanvas>
        <section class="left-small">
            <a class="left-off-canvas-toggle menu-icon" href="#"><span></span></a>
        </section>

        <section class="middle tab-bar-section">
          <div class="chm-logo" onclick="javascript:window.location.href='/'">&nbsp;</div>  
        </section>
       @if(isset($is_shop) && $is_shop)
            <section class="right-small chm-cart-logo-container">
                <div>
                    <i class="fa fa-shopping-cart fa-lg chm-cart-logo"></i>
                    <div class="chm-cart-logo-number" style="{{Session::has('Customer.cart_quantity') && (Session::get('Customer.cart_quantity') > 0)?'':'display:none;'}}">
                        {{Session::has('Customer.cart_quantity') && (Session::get('Customer.cart_quantity') > 0)?Session::get('Customer.cart_quantity'):''}}
                    </div>
                </div>
            </section> 
        @endif
        
    </nav>
</div>