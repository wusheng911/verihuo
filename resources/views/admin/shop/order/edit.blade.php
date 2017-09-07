@extends('layouts.admin')
@section('title')
@endsection
@push('stylesheets')
<style>
 h2{text-align:center;}
 .print{display:none;}
 .customer-row{margin-top: -15px;}
 fieldset {min-height: 146px; padding: 0px 10px 0px 10px; margin-left: 20px; margin-bottom: 10px; border: 1px solid black;}
 # fieldset.op-st{height: 146px;}
 .content-wrapper{background-color: white;}
 legend{padding: 0px 10px 0px 10px;margin-bottom: 6px; border:1px; width:auto;}

 .btn-lgg{height: 70px;}

 /* print media */
 @media print {

     @page { margin: 0; size: A4; margin-left: -.5cm; margin-right: -.5cm;}

 *{ transition: none !important; font-weight: 0;font-size: .6cm;}
 body {margin: 1.6cm;}

 .print {display: block;}
 .no-print {display: none;}
 .table{ border: 1px solid gray; width:100%; border-collapse: collapse;}
 table tr th {font-size: 16pt; font-weight: normal; font-family:'Times New Roman',Times,serif; border: 1px solid gray;}
 table tr td {text-align: center;height: 40pt; font-size: 14pt; font-family:'Times New Roman',Times,serif; border: 1pt solid gray;}
 .col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8,
 .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12 {float: left; padding-left: .2cm; padding-right: .2cm}
 .col-sm-12 {width: 100%;}
 .col-sm-11 {width: 91.66666667%;}
 .col-sm-10 {width: 83.33333333%;}
 .col-sm-9 {width: 75%;}
 .col-sm-8 {width: 66.66666667%;}
 .col-sm-7 {width: 58.33333333%;}
 .col-sm-6 {width: 50%;}
 .col-sm-5 {width: 41.66666667%;}
 .col-sm-4 {width: 33.33333333%;}
 .col-sm-3 {width: 25%;}
 .col-sm-2 {width: 16.66666667%;}
 .col-sm-1 {width: 8.33333333%;}
 .text-left {text-align: left;}
 .text-center {text-align: center;}
 .text-right {text-align: right;}

 }
 /* print media  end*/
</style>
@endpush

@push('scripts')
<script type="text/javascript" src="/assets/admin/js/shop/order.js" ></script>
<script>
 getListUrl = "{{ action('Admin\Shop\OrderController@listJson') }}";
 $(document).ready(function(){
     $("#print-btn").click(function(){
         $("#order-page").print({
             globalStyles: false,
             mediaPrint: true,
             stylesheet: null,
             noPrintSelector: ".no-print",
             iframe: false,
             append: null,
             prepend: "HI",
             manuallyCopyFormValues: true,
             deferred: $.Deferred(),
             timeout: 750,
             title: "发货单",
             doctype: '<!doctype html>'
         });
     });
 });
</script>
@endpush

@section('content')
    <div class="row">
        <!-- menu -->
        <div class="oo-menu panel panel-default col-lg-1">
            <div class="text-center list-group">
                <a href="\{{config('chaohun.admin_prefix')}}\shop\order" class="list-group-item ">订单总览</a>
                <a href="" class="list-group-item list-group-item-info">处理订单</a>
            </div>
        </div>
        <!-- body -->
        <div class="container col-lg-offset-1" style="background-color:white;height:100%">
            <div>
                <div id="order-page" class="row" >
                    <div class="col-lg-10" > <fieldset>
                        <legend class="">详情</legend>
                        <h2>订单</h2>
                        <div class="row">
                            <label class="col-sm-3 text-right">订单号: </label>
                            <span class=" label label-default">{{ $order->sn }}</span>
                        </div>
                        <br>
                        <div class="row customer-row">
                            <label class="col-sm-3 text-right">客户姓名: </label>
                            @if(!empty($order->customer))
                                <span class="">{{ $order->customer->user_name }}</span>
                            @else
                                <span class="" style="display:none;">!!非顾客下单!!</span>
                            @endif
                        </div>
                        <br>
                        <div class="row customer-row">
                            <label class="col-sm-3 text-right">收货地址: </label>
                            <span class="">{{ $order->consignee_address }}</span>
                        </div>
                        <br>
                        <div class="row customer-row">
                            <label class="col-sm-3 text-right">收货人: </label>
                            <span class="">{{ $order->consignee_name}} </span>
                        </div>
                        <br>
                        <div class="row customer-row">
                            <label class="col-sm-3 text-right">电话: </label>
                            <span class=""> {{ $order->consignee_phone}}</span>
                        </div>
                        @if(!empty($order->remark))
                        <br>
                        <div class="row customer-row">
                            <label class="col-sm-3 text-right">备注: </label>
                            <span class=""> {{ $order->remark}}</span>
                        </div>
                        @endif
                        <hr>
                        <br class="print">
                        <table class="table table-bordered">
                            <tr>
                                <th style="width: 20%;">商品号</th>
                                <th style="width: 23%;">商品名称</th>
                                <th style="width: 20%">规格</th>
                                <th style="width: 7%">数量</th>
                                <th style="width: 10%">价格</th>
                                <th class="print" >分拣</th>
                            </tr>
                            @foreach($order->orderSkus as $os)
                                <tr>
                                    <td>{{ !empty($os->sku->sn) ? $os->sku->sn : '' }}</td>
                                    <td>{{ !empty($os->sku->product_name) ? $os->sku->product_name : '' }}</td>
                                    <td>{{ $os->sku->saleAttrStr() }}</td>
                                    <td>{{ $os->quantity }}</td>
                                    <td>{{ $os->total }}</td>
                                    <td class="print"></td>
                                </tr>
                            @endforeach
                        </table>
                        <hr>
                        <div class="row" >
                            <div class="col-lg-5" >
                                商品总数：{{ $order->orderSkus->reduce(function($c, $a){return $c + $a->quantity;}, 0) }}件，
                                商品总价格：{{ $order->orderSkus->reduce(function($c, $a){return $c + $a->total;}, 0.0) }}元，
                                应付: {{ $order->total }}元
                            </div>
                            <div class="col-lg-offset-10" >
                                <button id="print-btn" type="button" class="no-print btn btn-small btn-xs btn-primary"> 打印一张发货详单 </button>
                            </div>
                        </div>
                        <br class="print">
                        <hr>
                        <div class="print">
                            <div >
                                操作员:____
                            </div>
                            <br >
                            <div>
                                商城: 
                                <img src="/assets/img/frontend/logo.png">
                            </div>

                        </div>

                    </fieldset> </div>
                </div>
                <!-- 订单 状态 操作部分 -->
                <div class="row">
                    @if(\App\Models\Shop\OrderStatus::Shipping == $order->status)
                        <!-- status -->
                        <div class="col-lg-10"> 
                            <fieldset class="op-st">
                                <legend class="">状态</legend>
                                <div class="row">
                                    <div class="col-lg-offset-0">
                                        <h2> {{ $order->orderStatus->description }} </h2>
                                    </div>
                                    <div class="col-lg-offset-1" >
                                        快递公司: {{ !empty($order->logistic->name) ? $order->logistic->name : '' }}
                                        , 运单号: <span class="text-success">{{$order->logistic_no }}</span>
                                        <a href="javascript:void(0)" class="text-danger  show-logistic-detail" oid="{{$order->id}}">
                                            点击物流号，查询详细信息
                                        </a>
                                        <div class="logistic-detail text-success" style="display:none;"> </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    @else 
                        <!-- status -->
                        <div class="col-lg-4"> 
                            <fieldset class="op-st">
                                <legend class="">状态</legend>
                                <div class="row">
                                    @if($order->status == \App\Models\Shop\OrderStatus::Preparing)
                                        <h4 class="text-center">订单已经打印完成</h4>
                                    @endif
                                    <div class="col-lg-offset-0">
                                        <h2> {{ $order->orderStatus->description }} </h2>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <!-- operate -->
                        <div class="col-lg-6" >
                            <fieldset class="op-st">
                                <legend class="">操作</legend>
                                @if($order->status == \App\Models\Shop\OrderStatus::Raw)
                                    @if($order->orderSkus->reduce(function($c, $a){return $c + $a->quantity;}, 0) > 0)
                                        <h4 class="text-danger text-center" style="margin-top:-10px;">请检查订单无误后确认订单</h4>
                                        <div class="row" >
                                            <div class="col-lg-5">
                                                {!! Form::open(['url' => action('Admin\Shop\OrderController@update', ['id'=>$order->id]), 'method' => 'put']) !!}
                                                <input type="hidden" name="step" value="mod" />
                                                <div class="row" ><div class="{{ count($errors) > 0 ? 'has-error' : '' }} col-lg-10 col-lg-offset-1 input-group" >
                                                    <span class="input-group-addon">新价格</span>
                                                    <?php $errortips = ''; ?>
                                                    @if( count($errors) > 0)
                                                        @foreach($errors->all() as $error)
                                                            <?php $errortips .= $error; ?>
                                                        @endforeach
                                                    @endif
                                                        <input type="text" title="{{ $errortips or '' }}" class="form-control" name="order[price]" placeholder="0.00"
                                                               {{ $order->paid_status == 1 ? 'disabled' : '' }} >
                                                        @if(count($errors) > 0)
                                                        <span class="glyphicon glyphicon-remove form-control-feedback"></span>
                                                        @endif
                                                        <span class="input-group-addon">￥</span>
                                                </div></div> 
                                                <div class="row" style="margin-top:2px;"><div class="col-lg-12 col-lg-offset-0">
                                                    <input type="button" id="changeTotal" class="col-lg-12 btn btn-danger" value="修改价格"
                                                           {{ $order->paid_status == 1 ? 'disabled' : '' }} />
                                                </div> </div>
                                                {!! Form::close() !!}
                                            </div>
                                            <div class="col-lg-3">
                                                {!! Form::open(['url' => action('Admin\Shop\OrderController@update', ['id'=>$order->id]), 'method' => 'put']) !!}
                                                <input type="hidden" name="step" value="paid" />
                                                <br>
                                                <input type="button" id="changeToPaid" class="col-lg-12 btn btn-primary" style ="margin-top:16px;" value="设为已付款"
                                                       {{ $order->paid_status == 1 ? 'disabled' : '' }} />
                                                {!! Form::close() !!}
                                            </div>
                                            <div class="col-lg-4">
                                                {!! Form::open(['url' => action('Admin\Shop\OrderController@update', ['id'=>$order->id]), 'method' => 'put']) !!}
                                                <input type="hidden" name="step" value="{{\App\Models\Shop\OrderStatus::Confirmed}}" />
                                                <input type="submit" class="col-lg-12 btn btn-lgg btn-success" value="确认订单"
                                                       {{ $order->paid_status == 1 ? '' : 'disabled' }} />
                                                {!! Form::close() !!}
                                            </div>
                                        </div>
                                    @else
                                        <div class="row" >
                                            <h4 class="col-lg-offset-1 text-danger">订单异常, 无法确认订单</h4>
                                        </div>
                                    @endif
                                @endif
                                <div class="row">
                                    {!! Form::open(['url' => action('Admin\Shop\OrderController@update', ['id'=>$order->id]), 'method' => 'put']) !!}
                                    <input type="hidden" name="step" value="{{\App\Models\Shop\OrderStatus::Preparing}}" />
                                    @if($order->status == \App\Models\Shop\OrderStatus::Confirmed)
                                        <div class="row" > 
                                            <br>
                                            <input type="submit" class="col-lg-8 col-lg-offset-2 btn btn-lgg btn-success" value="打印完成, 去备货" />
                                        </div>
                                    @endif
                                    {!! Form::close() !!}
                                </div>
                                <div class="row">
                                    @if($order->status == \App\Models\Shop\OrderStatus::Preparing)
                                        <h5 class="col-lg-11 col-lg-offset-1 text-warning">
                                            备货后<span class="text-danger">填写物流运单号</span>发货!
                                        </h5>
                                        {!! Form::open(['url' => action('Admin\Shop\OrderController@update', ['id'=>$order->id]), 'method' => 'put']) !!}
                                        <input type="hidden" name="step" value="{{\App\Models\Shop\OrderStatus::Shipping}}" />
                                        <div class="col-lg-4 col-lg-offset-" >
                                            <select id="logistic-select" class="selectpicker show-tick" data-size="7" data-live-search="true"
                                                    name="order[logistic_id]">
                                                <optgroup label="四通一达风">
                                                    <?php $sitongyidafeng = ['SF','STO','YD','YTO','ZTO','BTWL'] ?>
                                                    @foreach($logistics as $l)
                                                        @if(in_array($l->code, $sitongyidafeng))
                                                            <option data-icon="fa fa-leaf"
                                                                    {{ $l->id == $order->logistic_id ? 'selected="selected"' : "" }}
                                                                    value="{{ $l->id }}">{{ $l->name }}</option>
                                                        @endif
                                                    @endforeach
                                                </optgroup>
                                                <optgroup label="其他">
                                                    @foreach($logistics as $l)
                                                        @if(!in_array($l->code, $sitongyidafeng))
                                                            <option data-icon="fa fa-leaf" value="{{ $l->id }}">{{ $l->name }}</option>
                                                        @endif
                                                    @endforeach
                                                </optgroup>
                                            </select>
                                        </div>
                                        <div class="col-lg-4 col-lg-offset">
                                            <input class="form-control-static" name="order[logistic_no]" type="text"
                                                   value="{{$order->logistic_no}}" placeholder="运单号" aria-describedby="basic-addon1">
                                        </div>
                                        <input type="button" id="setLogistic" class="col-lg-2 col-lg-offset-1 btn btn-success" value="确认" />
                                        {!! Form::close() !!}
                                    @endif
                                </div>
                            </fieldset>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

@endsection
