/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$('.chp-slick-productslider').on('click', 'div.slick-active img', function() {
    $("#product_thumbnail").attr("src", $(this).attr("src"));
    //$(".slick-current").removeClass("slick-current");
    //$(this).parent("div").addClass("slick-current");
});

/**
 * On Click slider next or prev, change the thumnail image at the same time
 * 
 */        
$('.chp-slick-productslider').on('afterChange', function(event, slick, currentSlide, nextSlide){
    var next = $(".slick-active").find("img").attr("src");
    $("#product_thumbnail").attr("src", next);
});

/*************************************
 * 点击商品属性事件
 * 
 */
$("span.chp-productattr-value").click(function()
{
    //商品属性取消选中  
    if ($(this).hasClass("selected")) {
        $(this).removeClass("selected");
        
        var selected_skuids = getSelectedSkuIds('union');       
        //把与剩余选中属性相同sku_ids的属性显示出来
        $("li.chm-productattr-value").each(function() {
            if (!$(this).is(":visible")) {
                if (selected_skuids.length == 0) {
                    $(this).show();
                    
                } else {
                    sku_ids = $(this).data("skuids").toString().split(",");
                    for(i=0; i < skuids.length; i++) {
                        if (selected_sku.indexOf(sku_ids[i]) != -1) {
                            $(this).removeClass("selected");
                            $(this).show();
                        }
                    }
                }
            }
        });
        
        //重新设置价格
        if (selected_skuids.length == 0) {
            $(".chp-product-price").text($(".chp-product-price").data("price"));
        } else {
            var prices = getPricesBySkuIds(getSelectedSkuIds('intersect'), window.skus);
            $(".chp-product-price").text(prices[0]);
        }
   
    } else {  //如果选择了一个商品属性值
        //相同属性下的值只能单选
        $(this).siblings(".selected").removeClass("selected");
        $(this).addClass("selected");
        
        //不同sku的属性需要隐藏
        current_attr = $(this).parent("p");
        attr_value_id = $(this).data("attrvalue");
        current_skuids = $(this).data("skuids").toString().split(",");
        //console.log("attrvalue:"+attr_value_id);
        //console.log("skuids:"+current_skuids);
        
        //检查其他属性, 留下相同货号的属性, 隐藏不同货号的属性
        $("p.chp-product-attr").each(function(){
            //如果是其他属性才进入判断
            if ($(this)[0].innerHTML != current_attr[0].innerHTML) {
                $(this).children("span.chp-productattr-value").each(function(){
                    hide_attrvalue = true;
                    skuids = $(this).data("skuids").toString().split(",");
                    for(i=0; i < skuids.length; i++) {
                        if (current_skuids.indexOf(skuids[i]) != -1) {
                            hide_attrvalue = false;
                            break;
                        }
                    }
                    
                    if (hide_attrvalue) {
                        $(this).hide();
                        $(this).removeClass("selected"); 
                    } else {
                        if (!$(this).is(":visible")) {
                            $(this).show();
                            $(this).removeClass("selected");                        
                        }
                    }
                });                
            }
        });
        
        //根据选中属性的货号, 修改价格
        var prices = getPricesBySkuIds(getSelectedSkuIds('intersect'), window.skus);
        $(".chp-product-price").text(prices[0]);  
    }
    
    display_attrs = getDisplayAttributes(window.shop_attrs, getSelectedSkuIds('intersect'));
    //console.log('display_attrs:');
    //console.log(display_attrs);
    $("#chp_product_attributes").html(renderProductAttributes(display_attrs));  
});


$(".chp-quantity-edit-addbtn").click(function(){
    $(".chp-edit-quantity").val(parseInt($(".chp-edit-quantity").val())+1);
});

$(".chp-quantity-edit-subtractbtn").click(function() {
    if (parseInt($(".chp-edit-quantity").val()) > 1) {
        $(".chp-edit-quantity").val(parseInt($(".chp-edit-quantity").val())-1);
    }
});


/***************************
 * 点击加入购物车事件
 */
$("#chp_addto_cart").click(function() {
    var user_id = $("input[name=global_user_id]").val();
    var skus = window.skus;
                
    if (typeof(skus) == "undefined") {
        modalFadeAlert('商品缺货!', '');
        return;
    }
    
    //判断用户是否登录
    if (user_id.length == 0) {
        window.location.href = '/customer/login';
        return;
    }
    
    sku_id = 0;
    //如果货号多于1, 则需要选择一个颜色属性
    if (skus.length > 1) {
        sku_ids = getSelectedSkuIds('intersect');
        
        if (sku_ids.length != 1) {
            modalFadeAlert('请选择属性!');
            return;            
        } else {
            sku_id = sku_ids.pop();
        }
    } else if (skus.length == 1) {
        sku_id = skus[0].id;
    } else {
        modalFadeAlert('商品缺货!');
        return;         
    }
    
    if (!Utils.isInt($(".chp-edit-quantity").val())) {
        modalFadeAlert('无效的商品数量!');
        return;         
    }
    
    if (parseInt($(".chp-edit-quantity").val()) <= 0) {
        modalFadeAlert('无效的商品数量!');
        return;         
    }
    
    if (parseInt($(".chp-edit-quantity").val()) > parseInt($("#chp_sku_count").text())) {
        modalFadeAlert('购买数量超出了库存!');
        return;          
    }
    
    //如果选择了合适的货品
    if (sku_id > 0) { 
        //防止重复点击
        if (window.disableAddCart == true) {
            return;
        } else {
            window.disableAddCart = true;
        }        
               
        $.ajax({
            type: "POST",
            url: "/ajax/addtocart",
            data: {
                sku_id: sku_id,
                user_id: user_id,
                quantity: parseInt($(".chp-edit-quantity").val()),
                product_name: $("#product_thumbnail").attr("alt"),
                product_image: $("#product_thumbnail").attr("src")                
            },
            dataType: "json",
            async: true,
            success: function(data) {
                window.disableAddCart = false;
                //加入购物车成功
                if (data) {
                    console.log(data);
                    //更新购物车logo上显示的商品数量
                    //$(".chm-cart-logo-number").show().text(data[0].total_quantity);
                    modalFadeAlert("添加成功！");                        
                }
            },
            error: function(jqXHR) {
                window.disableAddCart = false;
                error_message = getResponseError(jqXHR.responseText);
                error_message = (error_message.length > 0)?error_message:"出错了。";
                modalFadeAlert(error_message + "请联系客服！");
            },
            complete: function() {
                window.disableAddCart = false;
            }
        });         
    }
});

$(".chp-modal-close").click(function(){
    $(this).parent().parent("div").foundation('reveal', 'close');
    
    if ($(this).parent().parent("div").attr("id") == "modalAddressItem") {
        $('#modalAddressList').foundation('reveal', 'open');
    }
});

$(document).on("click", ".chp-addresslist-cell-content", function(){
    if (!$(this).hasClass("chp-address-cell-selected")) {
        $(".chp-address-cell-selected").removeClass("chp-address-cell-selected");
        $(this).addClass("chp-address-cell-selected");
        $(this).next("div.chp-addresslist-cell-edit").addClass("chp-address-cell-selected");
    }
});

$(document).on("click", ".chp-address-edit", function() {
    var address_container = $(this).parents(".chp-address-row");
    
    setAddressItem(address_container.data("consignee"),
        address_container.data("phone"),
        address_container.data("province"),
        address_container.data("city"),
        address_container.data("district"),
        address_container.data("details"),
        (address_container.data("default") === 1)?true:false);
    $('.chp-addressitem-submit').data("action", "update").data("addressid", address_container.data("addressid")); 
    $('#modalAddressItem').foundation('reveal', 'open'); 
});

$(document).on("click", ".chp-address-delete", function() {
    var address_id = $(this).parents(".chp-address-row").data("addressid");
    submitAddress('delete', address_id);
});

$(".chp-addresslist-submit").click(function(){
    var selected_address = $(".chp-address-cell-selected").parent("div").data("addressid");
    
    if (selected_address  > 0) {
        var address_path = $(".chp-addresslist-cell-content.chp-address-cell-selected").children("p:last").children("span:last").text();
        var consignee_name = $(".chp-addresslist-cell-content.chp-address-cell-selected").children("p:first").children("span:eq(2)").text();
        var consignee_phone = $(".chp-addresslist-cell-content.chp-address-cell-selected").children("p:first").children("span:last").text();
        
        console.log(address_path);
        
        $(".chp-order-addressitem").html(address_path + "（ " + consignee_name + " 收，&nbsp;" + consignee_phone + "） " +
            "<i class=\"fa fa-edit fa-lg chp-order-address-edit\" data-addressid=\""+selected_address+"\"></i>").removeClass("chp-hide");
        $(".chp-order-address-add").addClass("chp-hide");
    } else {
        $(".chp-order-addressitem").html("").addClass("chp-hide");
        $(".chp-order-address-add").html("添加地址[+]").removeClass("chp-hide");
    }
    
    $('#modalAddressList').foundation('reveal', 'close');  
});


$(".chp-addresslist-cancel").click(function(){
    $('#modalAddressList').foundation('reveal', 'close');  
});

/**
 * 打开地址列表对话框
 * 
 * @param {type} address_id 如果已有选中的地址id, 如果没有=0
 * @returns {undefined}
 */
function modalAddressList(address_id)
{   
    $(".chp-address-cell-selected").removeClass("chp-address-cell-selected");
    
    if (address_id == 0) {
        $(".chp-address-row").each(function() {
            if ($(this).data("default") == 1) {
                $(this).children("div.chp-addresslist-cell-content").addClass("chp-address-cell-selected");
                $(this).children("div.chp-addresslist-cell-edit").addClass("chp-address-cell-selected");
            }            
        });
    } else {
        $(".chp-address-row").each(function() {
            if ($(this).data("addressid") == address_id) {
                $(this).children("div.chp-addresslist-cell-content").addClass("chp-address-cell-selected");
                $(this).children("div.chp-addresslist-cell-edit").addClass("chp-address-cell-selected");
            }
        });
    }
    
    
    $('#modalAddressList').foundation('reveal', 'open');   
}

$("#chp_add_address").click(function() {
    setAddressItem("", "", 0, 0, 0, "", false);
    $('.chp-addressitem-submit').data("action", "add");
    $('#modalAddressItem').foundation('reveal', 'open');  
});

function setAddressItem(consignee_name, consignee_phone, zone_province, zone_city, zone_district, address_detail, is_default)
{
    $("input[name=chp_address_consignee_name]").val(consignee_name);
    $("input[name=chp_address_consignee_phone]").val(consignee_phone);
    $("#chp_address_details").val(address_detail);
    $("#chp_address_setdefault").prop("checked", is_default);
       
    $("#chp_select_province").find("option:selected").prop("selected", false);
    if (zone_province > 0) {
        $("#chp_select_province option[value='" + zone_province + "']").prop("selected", "selected");
        
        //防止重复点击
        if (window.disableSetAddressItem == true) {
            return;
        } else {
            window.disableSetAddressItem = true;
        }
        
        var ajax_url = "/ajax/getzones?province=" + zone_province;
        
        if (zone_city > 0) {
            ajax_url = ajax_url + "&city="+zone_city;
        }
        
        $.ajax({
            type: "GET",
            url: ajax_url,
            async: false,        
            success: function(data){
                if (data) {
                    console.log(data);

                    $("#chp_select_city option[value!=0]").remove();
                    if (data.city.length > 0) {
                        var cities = data.city;
                        for(i=0; i<cities.length; i++) {
                            $("#chp_select_city").append("<option value='"+cities[i].id+"'>"+cities[i].name+"</option>");
                        }
                    }
                    
                    $("#chp_select_city").find("option:selected").prop("selected", false);
                    if (zone_city > 0) {
                        $("#chp_select_city option[value=" + zone_city + "]").prop("selected", "selected");
                    } else {
                        $("#chp_select_city option[value=0]").prop("selected", "selected");
                    }

                    $("#chp_select_district option[value!=0]").remove();
                    if (data.district.length > 0) {
                        var districts = data.district;
                        for(i=0; i<districts.length; i++) {
                            $("#chp_select_district").append("<option value='"+districts[i].id+"' data-zone='"+districts[i].zone_id+"'>"+districts[i].name+"</option>");
                        }                    
                    }

                    $("#chp_select_district").find("option:selected").prop("selected", false);
                    if (zone_district > 0) {
                        $("#chp_select_district option[value=" + zone_district + "]").prop("selected", "selected");
                    } else {
                        $("#chp_select_district option[value=0]").prop("selected", "selected");
                    }
                } 
            },
            complete: function() {
                window.disableSetAddressItem = false;
            }            
        });          
    } else {
        $("#chp_select_province option[value='0']").prop("selected", false);
        
        $("#chp_select_city option[value!='0']").remove();
        $("#chp_select_city option[value='0']").prop("selected", "selected"); 

        $("#chp_select_district option[value!='0']").remove();
        $("#chp_select_district option[value='0']").prop("selected", "selected");
    }
}

$("#chp_select_province").change(function() {
    $("#chp_select_city option[value!=0]").remove();
    $("#chp_select_city option[value=0]").prop("selected", "selected");
    $("#chp_select_district option[value!=0]").remove();
    $("#chp_select_district option[value=0]").prop("selected", "selected");
    
    var province = $(this).val();
    
    if (province != 0) {
        $.ajax({
            type: "GET",
            url: "/ajax/getzones?province=" + province,
            async: false,        
            success: function(data){
                if (data) {
                    console.log(data);
                    if (data.city.length > 0) {
                        var cities = data.city;
                        for(i=0; i<cities.length; i++) {
                            $("#chp_select_city").append("<option value='"+cities[i].id+"'>"+cities[i].name+"</option>");
                        }
                    }   
                }
            }
        });
    } 
});

$("#chp_select_city").change(function() {
    $("#chp_select_district option[value!=0]").remove();
    $("#chp_select_district option[value=0]").prop("selected", "selected");
    
    var city = $(this).val();
    
    if (city != 0) {
        $.ajax({
            type: "GET",
            url: "/ajax/getzones?city=" + city,
            async: false,        
            success: function(data){
                if (data) {
                    console.log(data);
                    if (data.district.length > 0) {
                        var districts = data.district;
                        for(i=0; i<districts.length; i++) {
                            $("#chp_select_district").append("<option value="+districts[i].id+" data-zone="+districts[i].zone_id+">"+districts[i].name+"</option>");
                        }   
                    }   
                }
            }
        });
    } 
});

$(document).on("click", ".chp-addressitem-submit", function() {
    var action = $('.chp-addressitem-submit').data("action");
    var address_id = $('.chp-addressitem-submit').data("addressid");
    submitAddress(action, address_id);
});

/*******************************
 * 添加，修改，删除地址
 * @action string "add", "update", "delete"
 * @address_id integer for add action it's 0
 */
function submitAddress(action, address_id) 
{
    var url = "/ajax/addaddress";
    
    if (action == 'update') {
        url = "/ajax/updateaddress/" + address_id;
    } else if (action == 'delete') {
        url = '/ajax/deleteaddress';
    }
            
    user_id = $("input[name=global_user_id]").val();

        
    //判断用户是否登录
    if (user_id.length == 0) {
        window.location.href = '/customer/login';
        return;
    }  
                
    if (action == 'update' || action == 'add') {
        
        consignee_name = $("input[name=chp_address_consignee_name]").val();
        consignee_phone = $("input[name=chp_address_consignee_phone]").val();
        details = $("#chp_address_details").val();
        is_default = $("#chp_address_setdefault").prop("checked")?"1":"0";    
        zone_id = $("#chp_select_district").find("option:selected").data("zone"); 
        
        if (zone_id == 0) {
            modalFadeAlert('请选择所在地区');
            return;
        }  

        if (consignee_name.length == 0) {
            modalFadeAlert('请填写收件人');
            $("input[name=chp_address_consignee_name]").focus();
            return;
        } 

        if (!Utils.isTel(consignee_phone)) {
            modalFadeAlert('请填写有效的收件人电话');
            $("input[name=chp_address_consignee_phone]").focus();
            return;
        } 

        if (details.length == 0) {
            modalFadeAlert('请填写详细地址');
            $("#chp_address_details").focus();
            return;
        } 
        
        //防止重复点击
        if (window.disableSubmitAddress == true) {
            return;
        } else {
            window.disableSubmitAddress = true;
        }         

        //添加/更新 地址
        $.ajax({
            type: "POST",
            url: url,
            data: {
                zone_id: zone_id,
                user_id: user_id,
                consignee_name: consignee_name,
                consignee_phone: consignee_phone,
                details:  details,
                is_default: is_default            
            },
            dataType: "html",
            async: false,
            success: function(data) {   
                console.log(data);
                //添加地址成功
                if (data) { 
                    $(".chp-addresslist-container").html(data);
                    $('#modalAddressItem').foundation('reveal', 'close'); 
                    $('#modalAddressList').foundation('reveal', 'open');  
                    
                } 
                window.disableSubmitAddress = false;
            },
            error:function(XMLResponse){
                window.disableSubmitAddress = false;
                console.log(XMLResponse.responseText);
            }
        }); 
    }
    
    if (action == 'delete') {
        //删除地址
        $.ajax({
            type: "POST",
            url: "/ajax/deleteaddresses/" + address_id,
            dataType: "json",
            async: false,
            success: function(data) {   
                console.log(data);
                //删除地址成功
                if (data) {
                    $(".chp-address-row[data-addressid='"+address_id+"']").next("div.chp-address-row").remove();
                    $(".chp-address-row[data-addressid='"+address_id+"']").remove(); 
                } 
            },
            error:function(XMLResponse){
                console.log(XMLResponse.responseText);
            }
        });        
    }
};

$(document).on("click", ".chp-order-address-add", function(){
    modalAddressList(0);
});

$(document).on("click", ".chp-order-address-edit", function(){
    modalAddressList($(this).data("addressid"));
});

$("#chp_order_button").click(function() {

    var user_id = $("input[name=global_user_id]").val();   
    //判断用户是否登录
    if (user_id.length == 0) {
        window.location.href = '/customer/login';
        return;
    }
    
    if ($(".chp-order-addressitem").hasClass("chp-hide")) {
        modalFadeAlert("请添加收货地址！");
        return;
    }
    
    var address_id = $(".chp-order-address-edit").data("addressid");
    if (parseInt(address_id) <= 0) {
        modalFadeAlert("请添加收货地址！");
        return;
    }    
    
    //如果未完成交易，后退至此页面，用户可以再次点击下单按钮，但是此时订单已生成，购物车已清空，
    //因为每次单击确认订单时，都检查购物车是否还有数据，如果数据不存在则表示订单已生成，将用户
    //转到订单页
    cart_ids = new Array();        
    $(".chp-order-cartitem-container").each(function(){
        cart_id = $(this).data("cartid");
        if (cart_id > 0) {
            cart_ids.push(cart_id);
        }
    });        

    if (cart_ids.length > 0) {
        //防止重复点击
        if (window.disableGoPay == true) {
            return;
        } else {
            window.disableGoPay = true;
        }        
        
        $.ajax({
            type: "GET",
            url: "/ajax/iscartexist",
            data: {
                ids: cart_ids.join(","),
                user_id: $("input[name=global_user_id]").val()
            },
            async: false,
            success: function(data){            
                if (data == "1") {
                    var remarks = new Array();
                    $(".chp-order-remark").each(function(){
                        remark = new Object();
                        remark.merchant_id = $(this).data("merchantid");
                        remark.remark = $(this).val();       
                        remarks.push(remark);
                    });

                    var params = {};
                    params.remarks = remarks;
                    params.address_id = address_id;
                    params.pay_method = $("input[name=chp_pay_method]:checked").val();
                    $.ajax({
                        type: "POST",
                        url: "/shop/createorder",
                        data: JSON.stringify(params),
                        dataType: "json",
                        async: true,
                        success: function(data){                  
                            if (data) {
                                //启动交易付款
                                order_ids = data.order_ids;                                
                                if (order_ids.length > 0) {
                                    ids = order_ids.join(",");
                                    //弹出提示框， 防止用户再次回到此页
                                    //modalOrderPay(ids, user_id); 
                                    //转到支付页
                                    window.disableGoPay = false;
                                    window.location.href= "/pay/orders/" + ids;
                                } else {
                                    window.disableGoPay = false;
                                    modalFadeAlert("出错了, 请联系在线客服！");
                                }

                            } else {
                                window.disableGoPay = false;
                                modalAlert("出错了, 请联系在线客服！");
                            }
                        },
                        error: function(jqXHR) {
                            window.disableGoPay = false;
                            error_message = getResponseError(jqXHR.responseText);
                            error_message = (error_message.length > 0)?error_message:"出错了。";
                            modalFadeAlert(error_message + "请联系在线客服！");
                        }
                    });                   
                } else {
                    window.disableGoPay = false;
                    modalFadeAlert("您有未完成的订单，请到个人中心查看！");
                    return;
                }
            },
            error: function(jqXHR) {
                window.disableGoPay = false;
                error_message = getResponseError(jqXHR.responseText);
                error_message = (error_message.length > 0)?error_message:"出错了。";
                modalFadeAlert(error_message + "请联系在线客服！");
                return;
                
            }
        });            
    } else {
        window.disableGoPay = false;
        modalFadeAlert("您有未完成的订单，请到个人中心查看！");
        return;
    }        
});
