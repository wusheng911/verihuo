<div id="modalAddressItem" class="medium reveal-modal" data-reveal>
    <p>
        <span style="padding:0 0 0 45px;height:45px;line-height:45px;margin:0;text-align:left;font-size:24px;">收货地址</span>
        <span class="chp-modal-close" aria-label="Close">&#215;</span>
    </p>
    <hr style="background: #333 repeat 0 0;height:5px;width:100%;display:block;border:0;margin:0;" />
    <section style="padding-left:45px;padding-right:45px;">   
        <div class="row">
            <div class="small-12 columns" style="margin:0;padding:10px;">
                <div class="chp-addressitem-container">
                    <div class="chp-addressitem-row">
                        <div class="chp-addressitem-cell-title">收货人</div>
                        <div style="display:table-cell;">
                            <input type="text" name="chp_address_consignee_name" class="chp-input" 
                                style="width:15rem !important;" placeholder="姓名" 
                                value=""/>
                        </div>
                    </div>
                    <div class="chp-addressitem-row">
                        <div class="chp-addressitem-cell-gap"><hr /></div>
                        <div class="chp-addressitem-cell-gap"><hr /></div>
                    </div>
                    <div class="chp-addressitem-row">
                        <div class="chp-addressitem-cell-title">联系电话</div>
                        <div style="display:table-cell;">
                            <input type="text" name="chp_address_consignee_phone" class="chp-input" 
                                style="width:15rem !important;" placeholder="电话" 
                                value=""/>
                        </div>
                    </div>   
                    <div class="chp-addressitem-row">
                        <div class="chp-addressitem-cell-gap"><hr /></div>
                        <div class="chp-addressitem-cell-gap"><hr /></div>
                    </div>
                    <div class="chp-addressitem-row">
                        <div class="chp-addressitem-cell-title">所在地区</div>
                        <div class="chp-addressitem-cell-zone">
                            <select id="chp_select_province" class="chp-select-products" style="margin-left:0 !important;">
                                <option value="0" selected="selected">--- 所在省 ---</option>
                                @foreach($province_zones as $zone)
                                    <option value="{{$zone['id']}}">{{$zone["name"]}}</option>
                                @endforeach
                            </select>
                            <select id="chp_select_city" class="chp-select-products">
                                <option value="0" selected="selected">-- 所在市 ---</option>
                            </select>
                            <select id="chp_select_district" class="chp-select-products">
                                <option value="0" data-zone="0" selected="selected">--- 所在区 ---</option>
                            </select>
                        </div>
                    </div> 
                    <div class="chp-addressitem-row">
                        <div class="chp-addressitem-cell-gap"><hr /></div>
                        <div class="chp-addressitem-cell-gap"><hr /></div>
                    </div>
                    <div class="chp-addressitem-row">
                        <div class="chp-addressitem-cell-title">详细地址</div>
                        <div style="display:table-cell;">
                            <textarea id="chp_address_details" class="chp-address-input" rows="2" placeholder="街道地址"></textarea>
                        </div>
                    </div>             
                </div>
            </div>
        </div>
        <div class="row">
            <div class="small-12 columns" style="margin:0;padding:0 10px;">
                <input id="chp_address_setdefault" type="checkbox" style="margin:0 0 0 10px;vertical-align:middle;" />
                <label for="chp_address_setdefault" style="vertical-align:middle;margin-left:0.125rem;">设为默认收货地址</label> 
            </div>
        </div>
        <div class="row">
            <div class="small-12 columns text-center" style="margin:30px 10px;">
                <span class="button chp-addressitem-submit" data-action="add" data-addressid="0">确 定</span>          
            </div>
        </div>
    </section>
</div>