@extends('layouts.mobile', ['subnav' => true, 'is_shop' => true])

@section('meta')
    @include('elements.metaSEO')
@endsection

@section('title', 'veriHuo- College application essays and advice')

@section('navigatorTop')
    @include('mobile.elements.navigatorTop3', ['title' => '开票信息'])
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
        <div class="row">
            <div class="small-12 columns" style="margin:0;padding:10px;">
                <div class="chm-addressitem-container" data-addressid="{{isset($address)?$address["id"]:""}}">
                    <div class="chm-addressitem-row">
                        <div class="chm-addressitem-cell-title">收货人</div>
                        <div style="display:table-cell;">
                            <input type="text" name="chm_address_consignee_name" class="chm-input" 
                                style="width:10rem !important;" placeholder="姓名" 
                                value="{{isset($address)?$address["consignee_name"]:""}}"/>
                        </div>
                    </div>
                    <div class="chm-addressitem-row">
                        <div class="chm-addressitem-cell-gap"><hr /></div>
                        <div class="chm-addressitem-cell-gap"><hr /></div>
                    </div>
                    <div class="chm-addressitem-row">
                        <div class="chm-addressitem-cell-title">联系电话</div>
                        <div style="display:table-cell;">
                            <input type="text" name="chm_address_consignee_phone" class="chm-input" 
                                style="width:10rem !important;" placeholder="电话" 
                                value="{{isset($address)?$address["consignee_phone"]:""}}"/>
                        </div>
                    </div>   
                    <div class="chm-addressitem-row">
                        <div class="chm-addressitem-cell-gap"><hr /></div>
                        <div class="chm-addressitem-cell-gap"><hr /></div>
                    </div>
                    <div class="chm-addressitem-row">
                        <div class="chm-addressitem-cell-title">所在地区</div>
                        <div class="chm-addressitem-cell-zone" data-type="" data-typeid="" 
                            data-zoneid="{{isset($address)?$address["zone_id"]:""}}">
                            <span>{{isset($address["zone"])?$address["full_path"]:"请选择"}}</span>
                            <i class="fa fa-angle-right fa-lg"></i>
                      </div>
                    </div> 
                    <div class="chm-addressitem-row">
                        <div class="chm-addressitem-cell-gap"><hr /></div>
                        <div class="chm-addressitem-cell-gap"><hr /></div>
                    </div>
                    <div class="chm-addressitem-row">
                        <div class="chm-addressitem-cell-title">详细地址</div>
                        <div style="display:table-cell;">
                        <input type="text" name="chm_address_details" class="chm-input" placeholder="街道地址" 
                            value="{{isset($address)?$address["details"]:""}}"/>
                      </div>
                    </div>             
                </div>
            </div>
        </div>
        <div class="row">
            <div class="small-12 columns" style="margin:0;padding:0 10px;font-size: 0.875rem;">
                <input id="chm_address_setdefault" type="checkbox" style="margin:0 0 0 10px;vertical-align:middle;" 
                    {{(isset($address) && ($address["is_default"] == "1"))?"checked=\"checked\"":""}}/>
                <label for="chm_address_setdefault" style="vertical-align:middle;margin-left:0.125rem;">设为默认收货地址</label> 
            </div>
        </div>
        <div class="row">
            <div class="small-12 columns text-center" style="margin:0;padding:30px 10px 0;">
                <span class="chm-addressitem-submit" class="button">确 定</span>          
            </div>
        </div>
    </section>
    
    @include('mobile.elements.modalZone')
    @include('mobile.elements.modalConfirm')
@endsection

@section('script-file-bottom')
    <script type="text/javascript">
        $("input[name=chm_address_consignee_name]").focus();
    </script>
@endsection
