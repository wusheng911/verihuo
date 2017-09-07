@foreach($addresses as $address)
    <div class="chp-address-row" data-addressid="{{$address["id"]}}"
    accesskey=""data-consignee="{{$address["consignee_name"]}}" data-phone="{{$address["consignee_phone"]}}" 
    data-details="{{$address["details"]}}" data-province="{{$address["zone"]["province_id"]}}" 
    data-city="{{isset($address["zone"]["city_id"])?$address["zone"]["city_id"]:0}}" 
    data-district="{{isset($address["zone"]["district_id"])?$address["zone"]["district_id"]:0}}"
    data-default="{{$address["is_default"]}}">
        <div data-addressid="{{$address["id"]}}" class="chp-addresslist-cell-content">
            <p>
                <span class="chp-span-normal-title">收件人:</span>
                <span style="margin-right:1rem;">{{$address["consignee_name"]}}</span>
                <span class="chp-span-normal-title">电话:</span>
                <span>{{$address["consignee_phone"]}}</span>
            </p>
            <p>
                <span class="chp-span-normal-title">地址:</span>
                <span>{{$address["full_path"] . $address["details"]}}</span>
            </p> 
        </div>
        <div class="chp-addresslist-cell-edit" data-addressid="{{$address["id"]}}">
            @if($address["is_default"] == "1")
                <p>默认地址</p>
            @endif
            <p>
                <i class="fa fa-edit fa-lg chp-address-edit"></i>
                <i class="fa fa-trash fa-lg chp-address-delete"></i>
            </p>
        </div>

    </div> 
    <div class="chp-address-row">
        <div style="display:table-cell;height:10px;"></div>
        <div style="display:table-cell;height:10px;"></div>
    </div>
@endforeach


