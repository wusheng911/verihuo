<div id="modalAddressList" class="medium reveal-modal" data-reveal>
    <p>
        <span style="padding:0 0 0 45px;height:45px;line-height:45px;margin:0;text-align:left;font-size:24px;">收货地址</span>
        <span class="chp-modal-close" aria-label="Close">&#215;</span>
    </p>
    <hr style="background: #333 repeat 0 0;height:5px;width:100%;display:block;border:0;margin:0;" />
    <div style="padding:15px 35px 0;">
        <div class="chp-addresslist-container">
            @if(isset($addresses) && count($addresses) > 0)
                @include('pc.elements.addressList', ['addresses' => $addresses])
            @endif
        </div>       
    </div>
    <p style="padding-top:20px;padding-bottom:20px;">
        <span id="chp_add_address" style="font-size:21px;color:#aa976e;margin-left:45px;cursor:pointer;">添加地址[+]</span>
    </p>
    <div style="text-align:center;">
        <input type="button" class="button chp-addresslist-submit" name="Submit" value="确 定"/>
        <input type="button" class="button chp-addresslist-cancel" name="Cancel" value="取消"/>      
    </div>
</div>