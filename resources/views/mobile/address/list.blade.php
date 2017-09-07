@extends('layouts.mobile', ['subnav' => true])

@section('meta')
    @include('elements.metaSEO')
@endsection


@section('navigatorTop')
    @include('mobile.elements.navigatorTop3', ['title' => "收件地址", 
        'type'=>'address', 'show_trash' => false])
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
        @if(isset($addresses) && count($addresses) > 0)
            <div class="chm-address-container chm-address-list-container" data-selectedid="{{$selected_id}}">
                @foreach($addresses as $address)
                    <div style="display:table-row;">
                        <div data-addressid="{{$address["id"]}}" 
                            class="chm-address-cell-content chm-address-list-cell-content {{isset($selected_id) && ($selected_id == $address["id"])?"chm-address-cell-selected":""}}">
                            <p>
                                <span class="chm-span-normal-title">收件人:</span>
                                <span style="margin-right:1rem;">{{$address["consignee_name"]}}</span>
                                <span class="chm-span-normal-title">电话:</span>
                                <span>{{$address["consignee_phone"]}}</span>
                            </p>
                            <p>
                                <span class="chm-span-normal-title">地址:</span>
                                <span>{{$address["full_path"] . $address["details"]}}</span>
                            </p> 
                        </div>
                        <div class="chm-address-cell-edit {{isset($selected_id) && ($selected_id == $address["id"])?"chm-address-cell-selected":""}}" data-addressid="{{$address["id"]}}">
                            @if($address["is_default"] == "1")
                                <p>默认</p>
                            @endif
                            <p><i class="fa fa-edit fa-lg"></i></p>
                        </div>
                      
                    </div> 
                    <div class="chm-address-row" style="display:table-row;">
                        <div style="display:table-cell;height:10px;"></div>
                        <div style="display:table-cell;height:10px;"></div>
                    </div>
                @endforeach
            </div>
        @endif
        <div class="row">        
            <div class="small-12 columns" style="margin:0;padding:10px 10px;">
                <div class="chm-address-create">
                    <span style="font-size:1.5rem;">+</span>&nbsp;<span>新增地址</span>
                </div>
            </div>
        </div>

    </section>
    
    @include('mobile.elements.modalZone')
@endsection

@section('script-file-bottom')
    <script type="text/javascript" src="/assets/js/mobile.shop.js"></script>
@endsection
