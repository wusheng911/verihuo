@extends('layouts.admin')
@section('title', '创建新商品')

@push('stylesheets')
<link media="all" type="text/css" rel="stylesheet" href="{{ asset('assets/libs/datetimepicker-2.3.7/build/jquery.datetimepicker.min.css') }}">
<link media="all" type="text/css" rel="stylesheet" href="{{ asset('assets/libs/jquerycolorbox/colorbox.css') }}">
<link href="{{ asset('assets/admin/css/product_edit.css') }}" rel="stylesheet" type="text/css">
@endpush

@push('scripts')
<script src="{{ asset('assets/libs/datetimepicker-2.3.7/build/jquery.datetimepicker.full.min.js') }}"></script>
<script src="{{ asset('assets/libs/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('assets/libs/jquerycolorbox/jquery.colorbox-min.js') }}"></script>
<script src="{{ asset('assets/libs/elfinder/standalonepopup.js') }}"></script>
<script src="{{ asset('assets/admin/js/shop/product_edit.js') }}"> </script>
<script src="{{ asset('assets/admin/js/shop/jquery.chained.min.js') }}"> </script>
<script type="text/javascript">
    //在页面加载完之后加载jquery
    $(document).ready(function() {
        //编辑商品属性规格
        if(App.productCategory == "invalid"){

        } else {
            if(!App.productCategory) {
                $("#category2").chained("#category1");
            }
            var category_2 = $("#category2").find("option").length;
            var category_3 = $("#category3").find("option").length;

            if (category_2 > 0) {
                if(!App.productCategory) $("#category2").hide();
            } else {
                $("#category2").remove();
            }
            if (category_3 > 0) {
                if(!App.productCategory) {
                    $("#category3").hide();
                    $("#category3").chainedTo("#category2");
                }
            } else {
                $("#category3").remove();
            }
        }
        //商品详细描述编辑器配置
        CKEDITOR.replace( 'product_html_info', {
            language: 'zh-cn',
            toolbar: 'Basic',
            height: 300,
            toolbarGroups: [
                {"name":"basicstyles","groups":["basicstyles"]},
                {"name":"links","groups":["links"]},
                {"name":"paragraph","groups":["list","blocks"]},
                {"name":"document","groups":["mode"]},
                {"name":"insert","groups":["insert"]},
                {"name":"styles","groups":["styles"]},
                {"name":"about","groups":["about"]}
            ],
            // Remove the redundant buttons from toolbar groups defined above.
            removeButtons: 'Underline,Strike,Subscript,Superscript,Anchor,Styles,Specialchar'
        });

        $("#sortable").sortable({
            revert:true,
            cursor: "move",
            items: "li",
            opacity: 0.6
        });
        $("#sortable").disableSelection();
    });

    //表单验证
    $('form').validate({
        /*rules : {
         category:[],
         },*/
        //自定义错误消息放到哪里
        errorPlacement : function(error, element) {
            element.next().remove();//删除显示图标
            if(element.attr('id') != 'category1' && !element.hasClass('l-text')) element.after('<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>');
            element.closest('.form-group').append(error);//显示错误消息提示
            element.closest('#createTable').find('label').remove();
            element.closest('#createTable').append(error);
        },
        //给未通过验证的元素进行处理
        highlight : function(element) {
            $(element).closest('.form-group').addClass('has-error has-feedback');
            $(element).closest('td').addClass('has-error has-feedback');
        },
        unhighlight: function(element, errorClass) {
            $(element).closest('td').removeClass('has-error has-feedback');
        },
        //验证通过的处理
        success : function(label) {
            var el_input=label.closest('.form-group').find("input");
            el_input.next().remove();//与errorPlacement相似
            el_input.after('<span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>');
            label.closest('.form-group').removeClass('has-error').addClass("has-feedback has-success");
            label.remove();
        },
    });
    $.extend($.validator.messages, {
        required: "这是必填字段",
        number: "请输入有效的数字",
        digits: "库存只能为整数",
    });

    function getBaseAttributes(id) {
        $('.product-attributes-content,.base-attributes-content').children().remove();
        $('.sell-attributes,.base-attributes').hide();
        var html = '';
        $.ajax({
            type: 'GET',
            cache:false,
            url:  '/'+'{{ Config('chaohun.admin_prefix') }}'+'/product/getcategoryattributes/'+id,
            success: function (data) {
                //显示商品基本属性
                if(data.is_base != '') {
                    $.each(data.is_base,function(index, item){
                        if(item.required == 1) {
                            var required = '<span class="required">*</span>';
                        } else {
                            var required = '';
                        }
                        html += '<div class="form-group"><label class="col-sm-2 control-label">'+item.show_name+required+'</label>';

                        $.each(item.level,function(key,val) {
                            if(val != ''){
                                html += '<div class="col-sm-2"><select name="product[base_attribute]['+item.id+']['+key+']" class="form-control base-attribute-level'+index+key+'">';
                                $.each(val,function (i,v) {
                                    html += '<option value="'+v.id+'" class="'+v.parent_id+'">'+v.value+'</option>';
                                });
                                html += '</select></div>';
                            }
                        });

                        html += '</div>';
                    })
                    $('.base-attributes-content').html(html).show();
                    $('.base-attributes-content .form-group').each(function (index,item) {
                        $(this).find('div.col-sm-2').each(function (i,v) {
                            if(i==1) $(".base-attribute-level"+index+"1").chained(".base-attribute-level"+index+"0");
                            if(i==2) $(".base-attribute-level"+index+"2").chained(".base-attribute-level"+index+"1");
                            if(i==3) $(".base-attribute-level"+index+"3").chained(".base-attribute-level"+index+"2");
                        })
                    });
                    html = '';
                }

                //显示商品销售属性
                if(data.is_sell!='') {
                    $.each(data.is_sell,function(index, item){
                        if(item.value != ''){
                            var input_name = item.name;
                            html += '<div class="form-group"><label class="control-label col-sm-2 Father_Title">'+item.show_name+'</label><div class="col-sm-8 Father_Item'+index+' list-group">';
                            $.each(item.value,function(i,val){
                                html +='<div class="checkbox checkbox-primary"><input id="checkbox'+val.id+'" name="'+input_name+'['+val.id+']'+'" type="checkbox" class="chcBox_Width" value="'+val.value+'" data-id="'+val.id+'"><label for="checkbox'+val.id+'"> '+val.value+' </label></div>';
                            });
                            html += '</div></div>';
                        }
                    });
                    $('.product-attributes-content').html(html);
                    $('.sell-attributes').show();
                    //$('.base-attributes').show();

                    $(".chcBox_Width").on("change", function () {
                        step.Creat_Table();
                    });
                }
            }
        });
    }

    //创建新商品时
    $('.product-category1,.product-category2,.product-category3').change(function(){
        if($(this).hasClass('product-category1')){
            //console.log('一级分类id：'+$(this).val());
            var id1 = $(this).val();
            if(id1) {
                getBaseAttributes(id1);
            }
        }
        if($(this).hasClass('product-category2')) {
            var id2 = $(this).val();
            if(id2) {
                //console.log('二级分类id：'+$(this).val());
                getBaseAttributes(id2);
            } else {
                id1 = $('.product-category1').val();
                getBaseAttributes(id1);
            }
        }
        if($(this).hasClass('product-category3')) {
            var id3 = $(this).val();
            if (id3) {
                //console.log('三级分类id：' + $(this).val());
                getBaseAttributes(id3);
            } else {
                id2 = $('.product-category2').val();
                getBaseAttributes(id2);
            }
        }
    });

    //编辑商品属性规格
    if(App.productCategory){
        var html = '';
        $.ajax({
            type: 'GET',
            cache:false,
            url: '/'+'{{ Config('chaohun.admin_prefix') }}'+'/product/getcategoryattributes/'+App.productCategory,
            success: function (data) {
                //显示商品基本属性
                if(data.is_base != '') {
                    $.each(data.is_base,function(index, item){
                        if(item.required == 1) {
                            var required = '<span class="required">*</span>';
                        } else {
                            var required = '';
                        }
                        html += '<div class="form-group"><label class="col-sm-2 control-label">'+item.show_name+required+'</label>';

                        $.each(item.level,function(key,val) {
                            if(val != ''){
                                html += '<div class="col-sm-2"><select name="product[base_attribute]['+item.id+']['+key+']" class="form-control base-attribute-level'+index+key+'">';
                                $.each(val,function (i,v) {
                                    if($.inArray(v.id,App.base_attributes) > -1 ){
                                        var selected = 'selected="selected"';
                                    } else {
                                        var selected = '';
                                    }
                                    html += '<option value="'+v.id+'" class="'+v.parent_id+'" '+selected+'>'+v.value+'</option>';
                                });
                                html += '</select></div>';
                            }
                        });

                        html += '</div>';
                    });
                    $('.base-attributes-content').html(html).show();
                    $('.base-attributes-content .form-group').each(function (index,item) {
                        $(this).find('select').each(function (i,v) {
                            if(i==1) $(".base-attribute-level"+index+"1").chained(".base-attribute-level"+index+"0");
                            if(i==2) $(".base-attribute-level"+index+"2").chained(".base-attribute-level"+index+"1");
                            if(i==3) $(".base-attribute-level"+index+"3").chained(".base-attribute-level"+index+"2");
                        })
                    });
                    html = '';
                }

                //商品销售属性
                if(data.is_sell != '') {
                    $.each(data.is_sell,function(index, item){
                        if(item.value != '') {
                            var input_name = item.name;
                            html += '<div class="form-group"><label class="control-label col-sm-2 Father_Title">' + item.show_name + '</label><div class="col-sm-8 Father_Item' + index + ' list-group">';
                            $.each(item.value, function (i, val) {
                                if (_.indexOf(App.productAttributes, val.id + '') > -1) {
                                    var checked = 'checked="checked"';
                                } else {
                                    var checked = '';
                                }
                                html += '<div class="checkbox checkbox-primary"><input id="checkbox' + val.id + '" name="' + input_name + '[' + val.id + ']' + '" type="checkbox" class="chcBox_Width" value="' + val.value + '" data-id="' + val.id + '" ' + checked + '><label for="checkbox' + val.id + '"> ' + val.value + ' </label></div>';
                            });
                            html += '</div></div>';
                        }
                    });
                    $('.product-attributes-content').html(html);
                    $('.sell-attributes').show();
                    step.Creat_Table();
                    $(".chcBox_Width").on("change", function () {
                        step.Creat_Table();
                    });
                }
            }
        });
    }
    var skuDatas = new Array();
    var step = {

        //SKU信息组合
        Creat_Table: function () {
            step.hebingFunction();
            var SKUObj = $(".Father_Title");
            //var skuCount = SKUObj.length;
            var arrayTile = new Array();//标题组数
            var arrayInfor = new Array();//盛放每组选中的CheckBox值的对象
            var arrayColumn = new Array();//指定列，用来合并哪些列
            var dataId = new Array();
            var bCheck = true;//是否全选
            var columnIndex = 0;
            $("#createTable tbody tr").each(function(){
                var skuId = $(this).data('id');
                skuDatas[skuId] = new Array();
                $(this).find('input').each(function(){
                    var skuTag = $(this).data('tag');
                    skuDatas[skuId][skuTag] = $(this).val();
                });
            });
            $.each(SKUObj, function (i, item){
                arrayColumn.push(columnIndex);
                columnIndex++;
                arrayTile.push(SKUObj.eq(i).html());
                var itemName = "Father_Item" + i;
                //选中的CHeckBox取值
                var order = new Array();
                var data_id = new Array();
                $("." + itemName + " input[type=checkbox]:checked").each(function (){
                    order.push($(this).val());
                    data_id.push($(this).data('id'));
                });
                arrayInfor.push(order);
                dataId.push(data_id);
                if (order.join() == ""){
                    bCheck = false;
                }
            });
            //开始创建Table表
            if (bCheck == true) {
                var RowsCount = 0;
                $("#createTable").html("");
                var table = $("<table id=\"process\" class=\"table table-bordered\"></table>");
                table.appendTo($("#createTable"));
                var thead = $("<thead></thead>");
                thead.appendTo(table);
                var trHead = $("<tr></tr>");
                trHead.appendTo(thead);
                //创建表头
                $.each(arrayTile, function (index, item) {
                    var td = $("<th>" + item + "</th>");
                    td.appendTo(trHead);
                });
                var itemColumHead = $("<th  style=\"width:100px;\">价格</th><th style=\"width:100px;\">库存</th><th style=\"width:200px;\">SN号</th>");
                itemColumHead.appendTo(trHead);
                var tbody = $("<tbody></tbody>");
                tbody.appendTo(table);
                //生成组合
                var zuheDate = step.doExchange(arrayInfor);
                var zuheDataId = step.doExchange(dataId);
                if (zuheDate.length > 0) {
                    //创建行
                    $.each(zuheDate, function (index, item) {
                        var td_array = item.split(",");
                        if(arrayTile.length > 1) {
                            var attributeId =_.sortBy(zuheDataId[index].split(","),function(a){ return parseInt(a); });
                        } else {
                            var attributeId = zuheDataId[index];
                        }
                        var tr = $("<tr data-id="+attributeId+"></tr>");
                        tr.appendTo(tbody);
                        $.each(td_array, function (i, values) {
                            var td = $("<td>" + values + "</td>");
                            td.appendTo(tr);
                        });
                        //console.log(skuDatas);
                        if(typeof(App.sku[attributeId]) != "undefined") {
                            var sku_price= App.sku[attributeId].price;
                            var sku_quantity= App.sku[attributeId].quantity;
                            var sku_sn= App.sku[attributeId].sn;
                            var sku_id= App.sku[attributeId].id;
                        } else if(typeof(skuDatas[attributeId]) != 'undefined') {
                            var sku_price = (typeof(skuDatas[attributeId]['price']) != 'undefined') ? skuDatas[attributeId]['price'] : '';
                            var sku_quantity = (typeof(skuDatas[attributeId]['quantity']) != 'undefined') ? skuDatas[attributeId]['quantity'] : '';
                            var sku_sn = (typeof(skuDatas[attributeId]['sn']) != 'undefined') ? skuDatas[attributeId]['sn'] : '';
                            var sku_id= '0';
                        } else {
                            var sku_price = '';
                            var sku_quantity = '';
                            var sku_sn = '';
                            var sku_id= '0';
                        }
                        var td1 = $('<td><input required number="true" name="sku['+attributeId+'][price]['+sku_id+']" class="l-text form-control " type="text" value="'+sku_price+'" data-tag="price"></td>');
                        td1.appendTo(tr);
                        var td2 = $('<td><input required digits="true" name="sku['+attributeId+'][quantity]['+sku_id+']" class="l-text form-control" type="text" value="'+sku_quantity+'" data-tag="quantity"></td>');
                        td2.appendTo(tr);
                        var td3 = $('<td><input name="sku['+attributeId+'][sn]['+sku_id+']" class="l-text form-control" type="text" value="'+sku_sn+'"  data-tag="sn"></td>');
                        td3.appendTo(tr);
                    });
                }
                //结束创建Table表
                arrayColumn.pop();//删除数组中最后一项
                //合并单元格
                $(table).mergeCell({
                    // 目前只有cols这么一个配置项, 用数组表示列的索引,从0开始
                    cols: arrayColumn
                });
            } else{
                //未全选中,清除表格
                document.getElementById('createTable').innerHTML="";
            }
        },//合并行
        hebingFunction: function () {
            $.fn.mergeCell = function (options) {
                return this.each(function () {
                    var cols = options.cols;
                    for (var i = cols.length - 1; cols[i] != undefined; i--) {
                        // fixbug console调试
                        // console.debug(cols[i]);
                        mergeCell($(this), cols[i]);
                    }
                    dispose($(this));
                });
            };
            function mergeCell($table, colIndex) {
                $table.data('col-content', ''); // 存放单元格内容
                $table.data('col-rowspan', 1); // 存放计算的rowspan值 默认为1
                $table.data('col-td', $()); //
                // 存放发现的第一个与前一行比较结果不同td(jQuery封装过的), 默认一个"空"的jquery对象
                $table.data('trNum', $('tbody tr', $table).length); // 要处理表格的总行数, 用于最后一行做特殊处理时进行判断之用
                $('tbody tr', $table).each(function (index) {
                    var $td = $('td:eq(' + colIndex + ')', this);
                    var currentContent = $td.html();
                    if ($table.data('col-content') == '') {
                        $table.data('col-content', currentContent);
                        $table.data('col-td', $td);
                    } else {
                        if ($table.data('col-content') == currentContent) {
                            var rowspan = $table.data('col-rowspan') + 1;
                            $table.data('col-rowspan', rowspan);
                            $td.hide();
                            if (++index == $table.data('trNum'))
                                $table.data('col-td').attr('rowspan', $table.data('col-rowspan'));
                        } else {
                            if ($table.data('col-rowspan') != 1) {
                                $table.data('col-td').attr('rowspan', $table.data('col-rowspan'));
                            }
                            $table.data('col-td', $td);
                            $table.data('col-content', $td.html());
                            $table.data('col-rowspan', 1);
                        }
                    }
                });
            }
            // 同样是个private函数 清理内存之用
            function dispose($table) {
                $table.removeData();
            }
        },
        //组合数组
        doExchange: function (doubleArrays) {
            var len = doubleArrays.length;
            if (len >= 2) {
                var arr1 = doubleArrays[0];
                var arr2 = doubleArrays[1];
                var len1 = doubleArrays[0].length;
                var len2 = doubleArrays[1].length;
                var newlen = len1 * len2;
                var temp = new Array(newlen);
                var index = 0;
                for (var i = 0; i < len1; i++) {
                    for (var j = 0; j < len2; j++) {
                        temp[index] = arr1[i] + "," + arr2[j];
                        index++;
                    }
                }
                var newArray = new Array(len - 1);
                newArray[0] = temp;
                if (len > 2) {
                    var _count = 1;
                    for (var i = 2; i < len; i++) {
                        newArray[_count] = doubleArrays[i];
                        _count++;
                    }
                }
                return step.doExchange(newArray);
            }
            else {
                return doubleArrays[0];
            }
        }
    }
</script>
@endpush

@section('content')
    @if(!empty($product->id))
        <div class="" style="margin-bottom: 20px;overflow: hidden;">
            <a href="{{ action('Admin\Shop\ProductController@copyProduct',['id'=>$product->id]) }}" class="btn btn-lg btn-success col-sm-2 pull-right" target="_blank">复制商品</a>
        </div>
    @endif
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">
                @if(empty($product->id))添加新商品@else编辑商品@endif
            </h3>
        </div>
        <div class="panel-body">
            @if(empty($product->id))
                {!! Form::open(['url' => action('Admin\Shop\ProductController@store'), 'method' => 'POST', 'class'=>'form-horizontal']) !!}
            @else
                {!! Form::open(['url' => action('Admin\Shop\ProductController@update', ['id'=>$product->id]),'method' => 'PUT', 'class'=>'form-horizontal']) !!}
            @endif
            <fieldset>
                <legend>基本属性</legend>
                <div class="form-group">
                    <label class="col-sm-2 control-label">商家</label>
                    <div class="col-sm-6">
                        <select class="form-control" name="product[merchant]" disabled="disabled">
                            <option value="1" selected="selected">verihuo</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="name" class="col-sm-2 control-label">商品名称<span class="required">*</span></label>
                    <div class="col-sm-6"><input type="text" name="product[name]" class="form-control" id="name" placeholder="请输入商品名称" value="{{ empty($product->name) ? "" : "$product->name" }}" required></div>
                </div>
                <div class="form-group" id="product_description">
                    <label for="description" class="col-sm-2 control-label">商品描述</label>
                    <div class="col-sm-6"><textarea id="product_html_info" name="product[html_info]" class="form-control" rows="3" placeholder="请输入商品描述">{{ empty($product->html_info) ? "" : "$product->html_info" }}</textarea></div>
                </div>
                <div class="form-group">
                    <label for="description" class="col-sm-2 control-label">商品简单说明</label>
                    <div class="col-sm-6"><textarea name="product[info]" class="form-control" rows="3" placeholder="请输入商品描述">{{ empty($product->info) ? "" : "$product->info" }}</textarea></div>
                </div>
                <div class="form-group">
                    <label for="show_price" class="col-sm-2 control-label">销售价</label>
                    <div class="col-sm-6">
                        <input type="text" name="product[show_price]" class="form-control" id="show_price" placeholder="请输入商品销售价" value="{{ empty($product->show_price) ? "" : "$product->show_price" }}">
                    </div>
                    <label class="control-label notes">（商品显示价格，非商品真实价格）</label>
                </div>
                <div class="form-group">
                    <label for="show_min_price" class="col-sm-2 control-label">最低销售价</label>
                    <div class="col-sm-6">
                        <input type="text" name="product[show_min_price]" class="form-control" id="show_min_price" placeholder="请输入商品最低销售价" value="{{ empty($product->show_min_price) ? "" : "$product->show_min_price" }}">
                    </div>
                    <label class="control-label notes">（商品显示价格，非商品真实价格）</label>
                </div>
                <div class="form-group">
                    <label for="show_max_price" class="col-sm-2 control-label">最高销售价</label>
                    <div class="col-sm-6">
                        <input type="text" name="product[show_max_price]" class="form-control" id="show_max_price" placeholder="请输入商品最高销售价" value="{{ empty($product->show_max_price) ? "" : "$product->show_max_price" }}">
                    </div>
                    <label class="control-label notes">（商品显示价格，非商品真实价格）</label>
                </div>
                <div class="form-group">
                    <label for="sn" class="col-sm-2 control-label">商品SN码</label>
                    <div class="col-sm-6"><input type="text" name="product[sn]" class="form-control" id="sn" placeholder="请输入商品SN码" value="{{ empty($product->sn) ? "" : "$product->sn" }}"></div>
                </div>
                <div class="form-group">
                    <label for="area" class="col-sm-2 control-label">商品产地</label>
                    <div class="col-sm-6"><input type="text" name="product[area]" class="form-control" id="area" placeholder="请输入商品产地" value="{{ empty($product->area) ? "" : "$product->area" }}"></div>
                </div>
                <div class="form-group">
                    <label for="package" class="col-sm-2 control-label">商品包装</label>
                    <div class="col-sm-6"><input type="text" name="product[package]" class="form-control" id="package" placeholder="请输入商品包装" value="{{ empty($product->package) ? "" : "$product->package" }}"></div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">商品图片</label>
                    <div class="col-sm-10 product_image">
                        <ul id="sortable">
                            <li id="1" class="ui-state-default col-sm-4">
                                <a href="" class="popup_selector" data-inputid="feature_image_1">
                                    <img id="img_feature_image_1" for="feature_image_1" alt="" src="{{ $productImages[1] or asset('assets/img/article_icon_1_1.jpg') }}">
                                </a>
                                <input type="hidden" id="feature_image_1" name="product[image][mobile][]" value="@if(!empty($productImages[1])){{ $productImages[1] }}@endif"/>
                            </li>
                            <li id="2" class="ui-state-default col-sm-4">
                                <a href="" class="popup_selector" data-inputid="feature_image_2">
                                    <img id="img_feature_image_2" for="feature_image_2" alt="" src="{{ $productImages[2] or asset('assets/img/article_icon_1_1.jpg') }}">
                                </a>
                                <input type="hidden" id="feature_image_2" name="product[image][mobile][]" value="@if(!empty($productImages[2])){{ $productImages[2] }}@endif"/>
                            </li>
                            <li id="3" class="ui-state-default col-sm-4">
                                <a href="" class="popup_selector" data-inputid="feature_image_3">
                                    <img id="img_feature_image_3" for="feature_image_3" alt="" src="{{ $productImages[3] or asset('assets/img/article_icon_1_1.jpg') }}">
                                </a>
                                <input type="hidden" id="feature_image_3" name="product[image][mobile][]" value="@if(!empty($productImages[3])){{ $productImages[3] }}@endif"/>
                            </li>
                            <li id="4" class="ui-state-default col-sm-4">
                                <a href="" class="popup_selector" data-inputid="feature_image_4">
                                    <img id="img_feature_image_4" for="feature_image_4" alt="" src="{{ $productImages[4] or asset('assets/img/article_icon_1_1.jpg') }}">
                                </a>
                                <input type="hidden" id="feature_image_4" name="product[image][mobile][]" value="@if(!empty($productImages[4])){{ $productImages[4] }}@endif"/>
                            </li>
                            <li id="5" class="ui-state-default col-sm-4">
                                <a href="" class="popup_selector" data-inputid="feature_image_5">
                                    <img id="img_feature_image_5" for="feature_image_5" alt="" src="{{ $productImages[5] or asset('assets/img/article_icon_1_1.jpg') }}">
                                </a>
                                <input type="hidden" id="feature_image_5" name="product[image][mobile][]" value="@if(!empty($productImages[5])){{ $productImages[5] }}@endif"/>
                            </li>
                            <!--<input type="hidden" id="img_position" name="product[img_position]" value=""/>-->
                        </ul>
                    </div>
                </div>
                <div class="form-group">
                    <label for="categories" class="col-sm-2 control-label">商品状态</label>
                    <div class="col-sm-2">
                        <select class="form-control" name="product[status]">
                            <option value="0" @if($product->status == 0){!! 'selected="selected"' !!} @endif>无效商品</option>
                            <option value="1" @if($product->status == 1){!! 'selected="selected"' !!} @endif>未审核</option>
                            <option value="2" @if($product->status == 2){!! 'selected="selected"' !!} @endif>已审核</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="categories" class="col-sm-2 control-label">是否上架</label>
                    <div class="col-sm-2">
                        <select class="form-control" name="product[is_available]">
                            <option value="1" @if($product->is_available == 1){!! 'selected="selected"' !!} @endif>上架</option>
                            <option value="2" @if($product->is_available == 2){!! 'selected="selected"' !!} @endif>下架</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="categories" class="col-sm-2 control-label">商品所属分类<span class="required">*</span></label>
                    @if($productCategories == "invalid")
                        <div class="col-sm-2">
                            <select class="form-control" disabled="disabled"><option>无效分类</option></select>
                        </div>
                        <div class="col-sm-4">
                            <label class="control-label notes">（可复制该商品重新选择分类创建）</label>
                        </div>

                    @else
                        <div class="col-sm-2">
                            <select required id="category1" class="form-control product-category1" name="product[category][]" @if(!empty($product->id)){!! 'disabled="disabled"' !!}@endif>
                                @if(empty($product->id))
                                    <option value="">请选择分类</option>
                                    @foreach($productCategories as $key=>$productCategory)
                                        @if($productCategory->level == 0)
                                            <option value="{{ $productCategory->id }}">{{ $productCategory->name }}</option>
                                        @endif
                                    @endforeach
                                @elseif(!empty($productCategory[0]))
                                    <option value="{{ $productCategory[0]->id }}" selected="selected">{{ $productCategory[0]->name }}</option>
                                @endif
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <select id="category2" class="col-sm-2 form-control product-category2" name="product[category][]" @if(!empty($product->id)){!! 'disabled="disabled"' !!}@endif>
                                @if(empty($product->id))
                                    <option value="">请选择分类</option>
                                    @foreach($productCategories as $key=>$productCategory)
                                        @if($productCategory->level == 1)
                                            <option value="{{ $productCategory->id }}" class="{{$productCategory->pid}}">{{ $productCategory->name }}</option>
                                        @endif
                                    @endforeach
                                @elseif(!empty($productCategory[1]))
                                    <option value="{{ $productCategory[1]->id }}" selected="selected">{{ $productCategory[1]->name }}</option>
                                @endif
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <select id="category3" class="col-sm-2 form-control product-category3" name="product[category][]" @if(!empty($product->id)){!! 'disabled="disabled"' !!}@endif>
                                @if(empty($product->id))
                                    <option value="">请选择分类</option>
                                    @foreach($productCategories as $key=>$productCategory)
                                        @if($productCategory->level == 2)
                                            <option value="{{ $productCategory->id }}" class="{{$productCategory->pid}}">{{ $productCategory->name }}</option>
                                        @endif
                                    @endforeach
                                @elseif(!empty($productCategory[2]))
                                    <option value="{{ $productCategory[2]->id }}" selected="selected">{{ $productCategory[2]->name }}</option>
                                @endif
                            </select>
                        </div>
                    @endif
                </div>
                <div class="base-attributes-content" style="display: none;"></div>
            </fieldset>
            <div class="sell-attributes" style="display: none;">
                <fieldset>
                    <legend>商品规格<span class="required">*</span></legend>
                    <div class="product-attributes-content"></div>
                    <div class="" id="createTable"></div>
                </fieldset>
            </div>

            <div class="row">
                @if($productCategories == "invalid")
                    <div class="col-md-8"></div>
                    <div class="col-md-4">
                        <a href="{{ action('Admin\Shop\ProductController@copyProduct',['id'=>$product->id]) }}" class="btn btn-lg btn-success" style="width:100%;" target="_blank">复制商品</a>
                    </div>
                @else
                    <div class="col-md-4"></div>
                    <div class="col-md-4">
                        <input id="sureAndBackList" type="submit" style="width:100%;" class="btn btn-lg btn-success" name="backlist" value="保存并返回商品列表">
                    </div>
                    <div class="col-md-4">
                        <input type="submit" style="width:100%;" class="btn btn-lg btn-success" value="保存">
                    </div>
                @endif
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
