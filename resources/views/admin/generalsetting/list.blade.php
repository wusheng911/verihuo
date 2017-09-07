@extends('layouts.admin')

@section('title', '系统配置项')

@push('stylesheets')
    <link media="all" type="text/css" rel="stylesheet" href="{{ asset('assets/css/bootstrap-switch.css') }}">
    <link media="all" type="text/css" rel="stylesheet" href="{{ asset('assets/css/bootstrap-multiselect.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('assets/js/bootstrap-switch.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-multiselect.js') }}"></script>
    <script src="{{ asset('assets/admin/js/generalsetting.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
			var path = App.path;
            setTimeout(function(){ $('.alert-success').remove(); }, 3000);
            //多选设置
            $('#setting-email').multiselect({
                nonSelectedText: '请选择',
                allSelectedText: '全部选择'
            })
            $('#setting-email').multiselect('select',{!! isset($email) ? $email : '0' !!});

            //单选按钮设置
            $.fn.bootstrapSwitch.defaults.onText = '开';
            $.fn.bootstrapSwitch.defaults.offText = '关';
            var fashionWeekdefaultValue = {{ isset($FASHION_WEEK) ? $FASHION_WEEK : 'false' }};
            $("[name='fashion-week-checkbox']").bootstrapSwitch('state', fashionWeekdefaultValue, fashionWeekdefaultValue);
            $("[name='fashion-week-checkbox']").on('switchChange.bootstrapSwitch', function(event, state) {
                $('.fashion-week').attr('value',state);
            });

            //当多选select没有任何选择，不post相关name时，提交form前添加该name并且值设为0
            $('#sureAndBack').click(function () {
                var html = "<input type='hidden' name='setting[email][]' value='0'>";
                var obj=$("#setting-email option:selected").length;
                if(obj==0) {
                    $('form').append(html);
                }
                $('form').submit();
            })

            //验证数值类型
            $('.num').each(function () {
                $(this).bind('blur',function () {
                    var value = $.trim($(this).val());
                    var z= /^[0-9]*$/;
                    if((!z.test(value) || value==0) && value!='') {
                        var notice = $(this).attr('placeholder');
                        var id = $(this).attr('id');
                        window.setTimeout(function(){ document.getElementById(id).focus();},0);
                        $("#sureAndBack").attr("disabled", "disabled");
                        alert(notice);
                    } else {
                        $("#sureAndBack").removeAttr("disabled");
                    }
                })
            })
        });
    </script>
@endpush

@section('content')
    @if(Session::has('message'))
        <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {{Session::get('message')}}
        </div>
    @endif
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">常规配置项</h3>
        </div>
        <div class="panel-body">
        {!! Form::open(['url' => action('Admin\GeneralSettingController@save'), 'method' => 'post','class'=>'form-horizontal','role'=>'form']) !!}
            <!-- 可填写配置 -->
            <div class="form-group">
                <label class="col-sm-2 control-label">关于我们</label>
                <div class="col-sm-2">
                    <input type="text" id="about_us" class="form-control num" placeholder="请输入文章ID,例如6" name="setting[about_us]" value="{{ isset($about_us) ? $about_us : '' }}">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">联系我们</label>
                <div class="col-sm-2">
                    <input type="text" id="contact_us" class="form-control num" placeholder="请输入文章ID,例如6" name="setting[contact_us]" value="{{ isset($contact_us) ? $contact_us : '' }}">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">用户协议</label>
                <div class="col-sm-2">
                    <input type="text" id="customer_agreement" class="form-control num" placeholder="请输入文章ID,例如6" name="setting[customer_agreement]" value="{{ isset($customer_agreement) ? $customer_agreement : '' }}">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">分类显示文章行数</label>
                <div class="col-sm-2">
                    <input type="text" id="category_arcticle_row" class="form-control num" placeholder="请输入行数,例如6" name="setting[category_arcticle_row]" value="{{ isset($category_arcticle_row) ? $category_arcticle_row : '' }}">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">文章阅读量更新间隔时间</label>
                <div class="col-sm-3">
                    <input type="text" id="view_count_time" class="form-control num" placeholder="请输入更新间隔时间(分钟),例如10" name="setting[view_count_time]" value="{{ isset($view_count_time) ? $view_count_time : '' }}">
                </div>
            </div>

            <!-- 单选配置 -->
            <div class="form-group">
                <label for="firstname" class="col-sm-2 control-label">首页时装周</label>
                <div class="col-sm-6">
                    <input type="checkbox" name="fashion-week-checkbox" checked>
                </div>
                <input class="fashion-week" type="hidden" name="setting[FASHION_WEEK]" value="{{ isset($FASHION_WEEK) ? $FASHION_WEEK : 'false' }}" />
            </div>
            <!-- 多选配置 -->
            <div class="form-group">
                <label for="firstname" class="col-sm-2 control-label">邮箱设置</label>
                <div class="col-sm-6">
                    <select id="setting-email" multiple="multiple" name="setting[email][]">
                        <option value="通知消息">通知消息</option>
                        <option value="找回密码">找回密码</option>
                        <option value="订单消息">订单消息</option>
                        <option value="商品订阅">商品订阅</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4" ></div>
                <div class="col-md-4" >
                    <button id="sureAndBack" type="button" style="width:100%;" class="btn btn-lg btn-success">保存</button>
                </div>
                <div class="col-md-4" ></div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">资讯首页主kv顺序更换</h3>
	</div>
	<div class="panel-body">
                    <button id="backBtn" type="button" style="width:50%;" class="btn btn-lg btn-success">整体退一位</button>
                    <button id="forwordBtn" type="button" style="width:49%;" class="btn btn-lg btn-success">整体进一位</button>
	</div>
</div>
@endsection
