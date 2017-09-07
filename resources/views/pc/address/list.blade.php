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
            <div class="customer-content-header">收货地址</div>
            @if(isset($addresses) && count($addresses) > 0)
                <div class="customer-address">
                    <table>
                        <thead><tr>
                            <td width="630">地址信息</td>
                            <td width="170">操作</td>
                        </tr></thead>
                        <tbody>
                        @foreach($addresses as $address)
                            <tr>
                                <td width="630">
                                    <div class="address-info">{{ $address["zone"]["province"] }} &nbsp;&nbsp; {{ $address["zone"]["district"] }} &nbsp;&nbsp; {{ $address["details"] }}（{{ $address["consignee_name"] }}收，{{ $address["consignee_phone"] }}）
                                        @if($address['is_default']==1)<span class="address_setdefault">默认地址</span>@endif
                                    </div>
                                </td>
                                <td width="170" style="position: relative;">
                                    <div class="address-action">
                                        <span><a href="/my/address/{{$address["id"]}}">修改</a></span>&nbsp;&nbsp;<span>|</span>&nbsp;&nbsp;<span><a href="javascript:void(0);" class="delete-address-btn" data-id="{{$address["id"]}}">删除</a></span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
            <div class="customer-address-add">
                <a href="/my/address/add">添加地址[+]</a>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript" src="/assets/libs/jquery-confirm/dist/jquery-confirm.min.js"></script>
    <script type="text/javascript" src="/assets/js/pc.customer.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            //删除地址
            $('.delete-address-btn').click(function(){
                var address_id = $(this).data('id');
                $.confirm({
                    content: "您确定要删除该地址吗?",
                    confirm: function() {
                        if(address_id) {
                            $.post("/ajax/deleteAddress",{'address_id':address_id},
                                    function(data){
                                        if(data == 'success') {
                                            window.location.reload();//刷新当前页面.
                                        }
                                    });
                        }
                    }
                });
            });
        })

    </script>
@endsection
