<div id="product_navigator" class="chm-fixed-bottom chm-navigator-product">
    <nav class="top-bar bottom-bar">
        <div style="float:left;padding:0 10px;">
            <span style="color:#666;font-size:0.875rem;">
                单价:
            </span>    
            <span id="navigator_product_price" style="color:#666;font-size:0.875rem;"
                data-price="{{$product["show_price"]}}">
                {{$product["show_price"] > 0?Helpers::getShowPrice($product["show_price"]):""}}
            </span> 
            <span style="color:#666;font-size:0.875rem;">
                {{$product["show_price"] > 0?"元":""}}
            </span> 
        </div>
        <!-- add to cart -->
        <a id="chm_addto_cart" style="display:block;height:100%;float:right; background-color:#333;
            padding:0 0.675rem;" href="javascript:;">
            <span style="font-size:1rem;color:white;font-weight:600;">加入购物车</span>
        </a>
        <!--
        <div id="cat_details" style="display:table;display:none;">
          <div style="display:table-cell;padding-left:10px;padding-right:10px;">
              <span id="cat_count" style="color:#666;font-size:0.875rem;">
              </span>                    
          </div>
          <div style="display:table-cell;padding-left:10px;font-size:1rem;line-height:2.6rem;">
              <span id="cat_total_price" style="color:#333;">
              </span> 
          </div>
        </div>
        -->
    </nav>
</div>