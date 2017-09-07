@extends('layouts.app')




@section('script-file-bottom')
    <script type="text/javascript">
     var once = false;
     $(window).focus(function(){
         if(once) {
             return;
         }
         once = true;
         var order_id = {{ $order->id}};
         $.ajax({
             method: "GET",
             url: "/pay/orders/{{ $order->id }}/ispaid",
             dataType: "json",
             success: function(data){
                 console.log(data);
                 if(data.is_paid == 0) {
                     alert("未支付成功");
                 } else {
                     alert("支付成功");
                     window.location.href = "/customer/order/{{ $order->id }}";
                 }
             }});
     });
    </script>
    
@endsection

@section('content')
<div>
    <div class="text-center">
        <br>
        <br>
        <h1 class="text-center">订单号: {{ $order->sn}}</h1>
        <h1 class="text-center">待支付: {{ $order->total}} 元</h1>
        {!! QrCode::size(300)->generate($url); !!}
        <h1 class="text-center">请使用微信扫码支付</h1>
        <br>
        <br>
    </div>
</div>
@endsection
