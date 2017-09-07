@extends('layouts.mobile', ['subnav' => true, 'is_shop' => true])

@section('meta')
    <meta name="description" content="微信支付" />
    <meta name="keywords" content="婚礼策划,时尚,娱乐,明星,生活,潮流,资讯,策划培训" />
@endsection

@section('title','微信支付')


@section('navigatorTop')
    @include('mobile.elements.navigatorTop2', ['title' => isset($category["name"])?$category["name"]:"", 'hide_divider' => true])
@endsection

@section('script-file-bottom')
    <script type="text/javascript">
     data = <?php echo json_encode($data); ?>;

     function onBridgeReady(){
         WeixinJSBridge.invoke(
             'getBrandWCPayRequest', {
                 "appId": data.appId,     //公众号名称，由商户传入
                 "timeStamp": ""+data.timeStamp,         //时间戳，自1970年以来的秒数
                 "nonceStr": data.nonceStr, //随机串
                 "package":  data.package,
                 "signType": data.signType,         //微信签名方式:
                 "paySign": data.paySign    //微信签名
                 },
             function(res){
                 //todo ok and cancel
                 if(res.err_msg == "get_brand_wcpay_request：ok" ) {
                     @if(isset($rt))
                     var url = "<?php echo $rt; ?>";
                     window.location.href = url;
                     @endif
                     //window.location.href = "/customer/order/"+<?php echo $order->id; ?>;
                 }     // 使用以上方式判断前端返回,微信团队郑重提示：res.err_msg将在用户支付成功后返回    ok，但并不保证它绝对可靠。 

                 @if(isset($rt))
                 var url = "<?php echo $rt; ?>";
                 window.location.href = url;
                 @endif
                 //window.location.href = "/customer/order/"+<?php echo $order->id; ?>;
             }
         ); 
         return false;
     }
     
     if (typeof WeixinJSBridge == "undefined"){
         if( document.addEventListener ){
             document.addEventListener('WeixinJSBridgeReady', onBridgeReady, false);
         }else if (document.attachEvent){
             document.attachEvent('WeixinJSBridgeReady', onBridgeReady); 
             document.attachEvent('onWeixinJSBridgeReady', onBridgeReady);
         }
     }else{
         onBridgeReady();
     }
    </script>
@endsection

@section('content')
<div>
    <div class="text-center">
        <br>
        <h2>订单号: {{ $order->sn}}</h2>
        <h2>待支付: {{ $order->total}} 元</h2>
        <h3 class="text-center">正在支付中...</h3>
    </div>
</div>
@endsection
