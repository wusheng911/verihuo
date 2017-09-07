@extends('layouts.shopapp')

@section('meta')
    <link href="/all" type="application/rss+xml" rel="alternate" title="robots" />
@endsection

@section('title')
@endsection

@section('styles')
    <link href="{{ asset('assets/libs/jquery-confirm/css/jquery-confirm.css') }}" media="all" type="text/css" rel="stylesheet">
@endsection

@section('content')
    <div class="customer container">
        @include('pc.customer.accountleft')
        <div class="pull-right customer-content">
            <div class="customer-content-header">{{isset($address) ? "编辑地址" : "新增地址"}}</div>
            @if(isset($addresses) && count($addresses) > 0)
                <div class="customer-address">
                </div>
            @endif
            @if(isset($address))
                {!! Form::open(['url' => action('AddressController@update', ['id'=>$address['id']]),'method' => 'PUT', 'class'=>'form-horizontal addressitem-container','role'=>'form']) !!}
            @else
                {!! Form::open(['url' => action('AddressController@store'), 'method' => 'POST', 'class'=>'form-horizontal addressitem-container','role'=>'form'])  !!}
            @endif
                <div class="form-group">
                    <label class="col-sm-2 control-label">所在地区</label>
                    <div class="col-sm-10">
                        <select required @if(isset($address)) {{ "disabled='disabled'" }} @endif id="province" class="form-control" name="province"><option value="">---- 所在省 ----</option></select><!-- province -->
                        <select required id="city" class="form-control" name="city" style="display: none;"><option value="">---- 所在市 ----</option></select><!-- city -->
                        <select required id="district" class="form-control" name="district" style="display: none;"><option value="">---- 所在区 ----</option></select><!-- district -->
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">详细地址</label>
                    <div class="col-sm-8">
                        <textarea required id="details" class="form-control" name="details" placeholder="请输入街道地址">{{isset($address)?$address["details"]:""}}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">收货人姓名</label>
                    <div class="col-sm-3">
                        <input required id="consignee_name" type="text" class="form-control" name="consignee_name" placeholder="请输入收货人姓名" value="{{isset($address)?$address["consignee_name"]:""}}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">联系电话</label>
                    <div class="col-sm-3">
                        <input required id="consignee_phone" type="text" class="form-control" name="consignee_phone" placeholder="请输入联系人电话" value="{{isset($address)?$address["consignee_phone"]:""}}">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <div class="checkbox">
                            <label><input type="checkbox" name="is_default"  @if(isset($address)) {{ ($address['is_default'] == 1) ? "checked='checked'" : '' }} @else {{ "checked='checked'" }} @endif>设置为默认收货地址</label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-default address-submit">提交</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript" src="/assets/libs/jquery-confirm/dist/jquery-confirm.min.js"></script>
    <script type="text/javascript" src="/assets/js/pc.customer.js"></script>
    <script type="text/javascript" src="/assets/js/jquery.chained.min.js"></script>
    <script type="text/javascript" src="/assets/libs/jquery.validate.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            @if(isset($address))
                var zoneId = "{{ $address['zone']['id'] }}";
                $.ajax({
                    type:'get',
                    url: "/ajax/getZonesForPC",
                    data: {'zone_id':zoneId},
                    async: false,
                    success : function(data) {
                        if (data) {
                            $('#province').append(data.province);
                            $('#city').append(data.city);
                            $('#district').append(data.district);
                            $("#city").chained("#province");
                            $("#district").chained("#city");
                            $("#province").removeAttr("disabled");
                        }
                    }
                });
            @else
                $.ajax({
                    type:'get',
                    url: "/ajax/getZonesForPC",
                    async: false,
                    success : function(data) {
                        if (data) {
                            $('#province').append(data.province);
                            $('#city').append(data.city);
                            $('#district').append(data.district);
                            $("#city").chained("#province");
                            $("#district").chained("#city");
                        }
                    }
                });
            @endif
            $(".addressitem-container").validate({
                ignore: ":hidden",//不验证隐藏的的元素
                messages: {
                    province: {
                        required: "请选择省份！"
                    },
                    city: {
                        required: "请选择城市!"
                    },
                    district: {
                        required: "请选择区!"
                    },
                    details: {
                        required: "请输入街道地址！"
                    },
                    consignee_name: {
                        required: "请输入收货人姓名！"
                    },
                    consignee_phone: {
                        required: "请输入联系人电话！"
                    }
                }
            });
        });
    </script>
@endsection
