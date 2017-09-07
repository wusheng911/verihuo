/****************************************
 * View Product Page
 * 
 ****************************************/


var link_customer_service = 'http://cs.ecqun.com/mobile/rand?id=1678907';


function sumPriceQuantityByCarts(carts, skus) 
{
    var price_quantity = new Object();
    price_quantity.price = 0;
    price_quantity.quantity = 0;
    
    for(var i=0; i<carts.length; i++) {
        sku = getSkuById(carts[i].sku_id, skus);
        price_quantity.quantity += parseInt(carts[i].quantity);
        price_quantity.price += parseInt(carts[i].quantity) * sku.price;
    }
    return price_quantity;
}

function renderPriceQuantityByCarts(carts, skus)
{
    price_quantity = sumPriceQuantityByCarts(carts, skus);
                
    if (price_quantity.price > 0 && price_quantity.quantity > 0) {
        $("#cat_count").text("数量:"+price_quantity.quantity);
        $("#cat_total_price").text("总计:"+price_quantity.price+"元");
        $("#cat_details").show();
    }
}

/*************************************
 * 点击商品属性事件
 * 
 */
$("li.chm-productattr-value").click(function()
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
            $("#navigator_product_price").text($("#navigator_product_price").data("price"));
        } else {
            var prices = getPricesBySkuIds(getSelectedSkuIds('intersect'), window.skus);
            $("#navigator_product_price").text(prices[0]);
        }
   
    } else {  //如果选择了一个商品属性值
        //相同属性下的值只能单选
        $(this).siblings(".selected").removeClass("selected");
        $(this).addClass("selected");
        
        //不同sku的属性需要隐藏
        current_attr = $(this).parent("ul");
        attr_value_id = $(this).data("attrvalue");
        current_skuids = $(this).data("skuids").toString().split(",");
        //console.log("attrvalue:"+attr_value_id);
        //console.log("skuids:"+current_skuids);
        
        //检查其他属性, 留下相同货号的属性, 隐藏不同货号的属性
        $("ul.chm-product-attr").each(function(){
            //如果是其他属性才进入判断
            if ($(this)[0].innerHTML != current_attr[0].innerHTML) {
                $(this).children("li.chm-productattr-value").each(function(){
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
        $("#navigator_product_price").text(prices[0]);  
    }
        
    display_attrs = getDisplayAttributes(window.shop_attrs, getSelectedSkuIds('intersect'));
    console.log(display_attrs);
    $("#product-attributes").children("div").html(renderProductAttributes(display_attrs));  
});


/***************************
 * 点击加入购物车事件
 */
$("#chm_addto_cart").click(function() {
    var user_id = $("input[name=global_user_id]").val();
    var skus = window.skus;
        
    if (typeof(skus) == "undefined") {
        modalAlert('这个商品缺货!', '');
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
            modalAlert('请选择商品属性!');
            return;            
        } else {
            sku_id = sku_ids.pop();
        }
    } else if (skus.length == 1) {
        sku_id = skus[0].id;
    } else {
        modalAlert('这个商品缺货!');
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
                quantity: 1,
                product_name: $("#product_thumbnail").attr("alt"),
                product_image: $("#product_thumbnail").attr("src")                
            },
            dataType: "json",
            async: true,
            success: function(data) {                 
                //加入购物车成功
                if (data) {
                    //如果滚动条的滚动距离超过了头图的高度, 就不飞啦
                    if ($("#main-section").scrollTop() < ($('.slick-active').height() - 50)) {
                        //飞入购物车效果
                        var flyElm = $('.slick-active').find('img').clone().css('opacity','0.7');
                        flyElm.css({
                            'z-index': 9000,
                            'display': 'block',
                            'position': 'absolute',
                            'top': $('.slick-active').offset().top + 'px',
                            'left': $('.slick-active').offset().left +'px',
                            'width': $('.slick-active').width() +'px',
                            'height': $('.slick-active').height() +'px'
                        });
                        $('body').append(flyElm);
                        flyElm.animate({
                            top:19,
                            left:$(window).width()-19,
                            width:0,
                            height:0,
                        },'slow', function() {
                            //更新购物车logo上显示的商品数量
                            $(".chm-cart-logo-number").show().text(data[0].total_quantity);
                            modalFadeAlert("添加成功！");
                        });
                    } else {
                        //更新购物车logo上显示的商品数量
                        $(".chm-cart-logo-number").show().text(data[0].total_quantity);
                        modalFadeAlert("添加成功！");                        
                    }
                }
            },
            error: function(jqXHR) {
                error_message = getResponseError(jqXHR.responseText);
                error_message = (error_message.length > 0)?error_message:"出错了。";
                modalAlert(error_message + "点这里联系在线客服！", link_customer_service);
            },
            complete: function() {
                window.disableAddCart = false;
            }
        });         
    }
});

//点击购物车logo
$(".chm-cart-logo-container").click(function(){
    window.location.href = "/shop/cart";
});

/*****************************************
 * 购物车页面
 ****************************************/

//单击购物车某一商品，触发选中或反选这个商品的事件
$(".chm-cartitem-content-container").click(function(event) {
    if (event.target.tagName !== 'A' && event.target.tagName !== 'I' 
        && !$(event.target).hasClass("chm-cartitem-edit-addbtn")  
        && !$(event.target).hasClass("chm-cartitem-edit-subtractbtn")  
        && !$(event.target).hasClass("chm-cartitem-edit-spandiv") 
        && !$(event.target).hasClass("chm-cartitem-edit-quantity")) {
        current_checkbox = $(this).prev("div").children(":checkbox");
        current_checkbox.prop("checked", !current_checkbox.prop("checked")).trigger("change");        
    } 
});

//选中数量发生变化时重新计算总价
$(".chm-cartitem-checkbox").change(function() {
    displayTotalPrice();
});

function displayTotalPrice()
{
    var total_price = 0;   
    $(".chm-cartitem-checkbox:checked").each(function(){
        total_price = total_price + parseInt($(this).parent("div").next("div").find(".chm-cartitem-edit-quantity").text()) *
            parseFloat($(this).parent("div").next("div").find(".chm-cartitem-pricevalue").data("pricevalue"));
    });
    
    if (total_price > 0) {
        $(".navigator-cart-price").text("总计:" + formatCurrency(total_price)+"元");
        $("#chm_cart_order").addClass("chm-cart-order-enable");
    } else {
        $(".navigator-cart-price").text("");
        $("#chm_cart_order").removeClass("chm-cart-order-enable");
    }
}

//全选
$("#chm_cart_selectall").change(function(){
    var is_select_all = $(this).prop("checked");
    $(".chm-cartitem-checkbox").each(function(){
        $(this).prop("checked", is_select_all);
    });
    displayTotalPrice();
});

$(".chm-cartitem-trash").click(function() {
    cart_id = $(this).parents(".chm-cartitem-row").data("cartid");
    window.func_args = [cart_id];
    modalConfirm("是否删除该产品？", "取消", "取定", "trashCartItems");
});

//确认收货
$(".chm-account-order-receipt").click(function() {
    orderId = $(this).data("orderid");
    orderAction = 6;
    window.func_args = [orderAction,orderId];
    modalConfirm("确认收货", "取消", "确认", "updateOrder");
});

//删除订单
$(".chm-account-delete-order").click(function() {
    orderId = $(this).data("orderid");
    orderAction = 'delete';
    window.func_args = [orderAction,orderId];
    modalConfirm("删除订单", "取消", "确认", "updateOrder");
});

function updateOrder(order) {
    if (order) {
        $.post("/customer/order/ajax/update",{'orderAction':order[0],'orderId':order[1]},
            function(data){
                if(data == 'success') {
                    window.location.reload();//刷新当前页面.
                }
        });
    }
}

$(".chm-cart-trash").click(function() {
    cart_ids = [];
    
    $(".chm-cartitem-row").each(function() {
        if ($(this).find(".chm-cartitem-checkbox").prop("checked")) {
            cart_ids.push($(this).data("cartid"));
        }
    });
    
    if (cart_ids.length > 0) {
        window.func_args = cart_ids;
        modalConfirm("是否删除该产品？", "取消", "确定", "trashCartItems", cart_ids);
    } 
});

/**********************
 * 回调函数, 执行删除购物车物品条目的实际操作
 * @param {Array} cart_ids 需要删除的条目的cart id
 */
function trashCartItems(cart_ids) {
    if (cart_ids.length > 0) {
        $.ajax({
            type: "POST",
            url: "/m/ajax/deletecartitems/" + encodeURIComponent(cart_ids.join(",")),
            async: false,
            success: function(data){        
                //删除购物车, data返回更新后的购物车货品数量
                if (data) {
                    console.log(data);
                    $(".chm-cartitem-row").each(function(){
                        if (cart_ids.indexOf($(this).data("cartid")) !== -1) {
                            $(this).remove();
                        }
                    });
                    //更新购物车logo上显示的商品数量
                    if (data > 0) {
                        $(".chm-cart-logo-number").show().text(data);
                    } else {
                        $(".chm-cart-logo-number").hide();
                    } 
                    //重新计算总价
                    displayTotalPrice();
                }
            }
        });              
    }
}

/****************************************
 * 增加商品数量函数
 */
$(".chm-cartitem-edit-addbtn").click(function(){
    changeCartQuantity($(this), true);
});

/****************************************
 * 减少商品数量函数
 */
$(".chm-cartitem-edit-subtractbtn").click(function(){
    changeCartQuantity($(this), false);
});

/**
 * 修改购物车中的商品数量
 * @param Object btn JQuery按钮对象 (+ , -)
 * @param Boolean isadd 增加为true, 减少为false
 */
function changeCartQuantity(btn, isadd)
{
    if (btn.hasClass("chm-btn-disabled")) {
        return;
    }
        
    quantity_edit_container = btn.parent("div").parent("div");
    current_quantity_label = quantity_edit_container.find(".chm-cartitem-edit-quantity");
    quantity = parseInt(current_quantity_label.text().trim());
    //减少商品数量
    if (isadd == false && quantity <= 1) {
        ModalAlert("不能再减啦, 请删我吧!");
        return;
    }
    
    cart_id = btn.parents(".chm-cartitem-row").data('cartid');
    quantity_updateto = (isadd == false)?(quantity-1):(quantity+1);
    console.log("updated to: " + quantity_updateto);
    
    //防止重复点击
    if (window.disableChangeQuantity == true) {
        return;
    } else {
        window.disableChangeQuantity = true;
    }     
    
    $.ajax({
        type: "POST",
        url: "/m/ajax/changecartquantity/"+cart_id,
        data: "quantity="+quantity_updateto,
        async: true,
        success: function(data){
            if(parseInt(data) > 0){     
                console.log(data);
                current_quantity_label.text(quantity_updateto);
                if (quantity_updateto == 1) {
                    quantity_edit_container.find('.chm-cartitem-edit-subtractbtn').addClass("chm-btn-disabled").prop("disabled", true);
                } else {
                    quantity_edit_container.find('.chm-cartitem-edit-subtractbtn').removeClass("chm-btn-disabled").prop("disabled", false);
                }
                //更新购物车logo上显示的商品数量
                if (data > 0) {
                    $(".chm-cart-logo-number").show().text(data);
                } else {
                    $(".chm-cart-logo-number").hide();
                } 
                //重新计算总价
                displayTotalPrice();
            }
        },
        error: function(jqXHR) {
            error_message = getResponseError(jqXHR.responseText);
            error_message = (error_message.length > 0)?error_message:"出错了。";
            modalAlert(error_message + "点这里联系在线客服！", link_customer_service);
        },
        complete:function() {
            window.disableChangeQuantity = false;
        }
    });  
}

//进入下单页
$("#chm_cart_order").click(function(){
    if ($(this).hasClass("chm-cart-order-enable")) {
                
        cart_ids = new Array();        
        $(".chm-cartitem-checkbox:checked").each(function(){
            cart_id = $(this).parent("div").parent("div").data("cartid");
            if (cart_id > 0) {
                cart_ids.push(cart_id);
            }
        });        
        
        if (cart_ids.length > 0) {
            //防止重复点击
            if (window.disableStartOrder == true) {
                return;
            } else {
                window.disableStartOrder = true;
            } 
            
            $.ajax({
                type: "POST",
                url: "/shop/order",
                data: "cart_ids="+cart_ids.join(","),
                async: true,
                success: function(data){            
                    //下单成功
                    if (data) {
                        window.location.href = "/shop/order";
                    } else {
                        window.disableStartOrder = false;
                    }
                },
                error: function(jqXHR) {
                    error_message = getResponseError(jqXHR.responseText);
                    error_message = (error_message.length > 0)?error_message:"出错了。";
                    modalAlert(error_message + "点这里联系在线客服！", link_customer_service);
                    
                    window.disableStartOrder = false;
                }
            });            
        }
    } 
});


/********************************************
 * 地区(Zone)列表弹出框
 ********************************************/


/**************************
 * 选中一个地区后: 如果选择了省/市则需要继续选择县区, 如果选择了县区,则返回zone_id
 */
$(".chm-modalzone-list").on("click", "li", function() {
    $(this).siblings(".selected").removeClass("selected");
    $(this).addClass("selected");
    
    zone_type = $(this).data("type");
    type_id = $(this).data("id");
    zone_content = $(this).text();
    
    if (zone_type == "district") {
        zone_id = $(this).data("zoneid");
        zone_content = $("#chm_modalzone_province").text() + $("#chm_modalzone_city").text() + $(this).text();        
        $(".chm-addressitem-cell-zone").children("span").text(zone_content);
        $(".chm-addressitem-cell-zone").data("zoneid", zone_id); 
        $('#modalZone').foundation('reveal', 'close');
        
        $(".chm-modalzone-list").children("li").remove();
        $("#chm_modalzone_province").text("").data("id", "").data("type","").hide();
        $("#chm_modalzone_city").text("").data("id", "").data("type","").hide();
    } else {
        $.get("/m/ajax/getzones", {
            type: (zone_type == "province")?"city":"district",
            parent_id: type_id
        },function(data, textStatus){
            if (data) {
                $(".chm-modalzone-list").children("li").remove();
                $(".chm-modalzone-list").prepend(data);
                $("#chm_modalzone_" + zone_type).text(zone_content)
                    .data("id", type_id)
                    .data("type", zone_type).show();
                $(".chm-modalzone-toselect").addClass("selected");
                $(".chm-modalzone-toselect").siblings(".selected").removeClass("selected");
                if (zone_type == "province") {
                    $("#chm_modalzone_city").text("").data("id", "").data("type","").hide();
                }
            } 
        });        
        
    }
});

/**********************
 * 在地区(Zone)选择弹出框中点击了已选中的省/市
 */
$("#chm_modalzone_province, #chm_modalzone_city").click(function(){
    zone_type = $(this).data("type");
    zone_id = $(this).data("id"); 
    var current_node = $(this);
    $.get("/m/ajax/getzones", {
        type: zone_type,
        parent_id: (zone_type == "province")?"":$("#chm_modalzone_province").data("id")
    },function(data, textStatus){
        if (data) {
            $(".chm-modalzone-list").children("li").remove();
            current_node.siblings(".selected").removeClass("selected");
            current_node.addClass("selected");            
            $(".chm-modalzone-list").prepend(data);
            $(".chm-modalzone-list li").each(function() {
                if ($(this).data("id") == zone_id) {
                    $(this).addClass("selected");
                }
            });
        } 
    });    
});

/**********************
 * 在地区(Zone)选择弹出框中点击了"请选择"按钮
 */
$(".chm-modalzone-toselect").click(function() {
    if (!$(this).is(".selected")) {
        zone_type = "city";
        parent_id = $("#chm_modalzone_province").data("id");
        if ($("#chm_modalzone_city").is(":visible")) {
            zone_type = "district";
            parent_id = $("#chm_modalzone_city").data("id");
        }
        
        var current_node = $(this);
        $.get("/m/ajax/getzones", {
            type: zone_type,
            parent_id: parent_id
        },function(data, textStatus){
            if (data) {
                $(".chm-modalzone-list").children("li").remove();
                current_node.siblings(".selected").removeClass("selected");
                current_node.addClass("selected");            
                $(".chm-modalzone-list").prepend(data);
            } 
        });        
        
    }
});

/*****************************************
 * 地址详情页
 *****************************************/

/***************************************
 * 地址详情页,点击地区,弹出地区(Zone)列表弹出框
 */
$(".chm-addressitem-cell-zone").click(function(){
    modalSelectZone();
});

/********************************************
 * 地区详情页,点击确定按钮(新增或修改地址)
 */
$(".chm-addressitem-submit").click(function() {         
    url = "/m/ajax/addaddress";
    address_id = $(".chm-addressitem-container").data("addressid").toString();
    zone_id = $(".chm-addressitem-cell-zone").data("zoneid").toString();
    user_id = $("input[name=global_user_id]").val();
    consignee_name = $("input[name=chm_address_consignee_name]").val();
    consignee_phone = $("input[name=chm_address_consignee_phone]").val();
    details = $("input[name=chm_address_details]").val();
    is_default = $("#chm_address_setdefault").prop("checked")?"1":"0";
    
    //判断用户是否登录
    if (user_id.length == 0) {
        window.location.href = '/customer/login';
        return;
    }  
    
    if (zone_id.length == 0) {
        modalAlert('请选择所在地区。');
        return;
    }  
    
    if (consignee_name.length == 0) {
        modalAlert('请填写收件人。');
        $("input[name=chm_address_consignee_name]").focus();
        return;
    } 
    
    if (!Utils.isTel(consignee_phone)) {
        modalAlert('请填写有效的收件人电话。');
        $("input[name=chm_address_consignee_phone]").focus();
        return;
    } 
    
    if (details.length == 0) {
        modalAlert('请填写详细地址。');
        $("input[name=chm_address_details]").focus();
        return;
    }     
    
    //如果是更新地址
    if (address_id.length > 0) {
        url = "/m/ajax/updateaddress/" + address_id;
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
        dataType: "json",
        async: true,
        success: function(data){      
            //添加地址成功
            if (data) {
                //如果带有selected_id参数,说明操作源头来自下单页, 这将决定返回地址列表页后点击地址项的行为(是再跳回到下单页)
                selected_id = getUrlParam("selected_id", document.referrer);
                str_params = (selected_id != null)?"?selected_id="+selected_id:"";
                window.location.href = "/my/address" + str_params;
            } else {
                window.disableSubmitAddress = false;
            }
        },
        error: function() {
            window.disableSubmitAddress = false;
        }
    });          
});

/********************************************
 * 地区详情页,点击删除按钮(删除地址)
 */
$(".chm-address-trash").click(function(){
    address_id = $(".chm-addressitem-container").data("addressid");
    user_id = $("input[name=global_user_id]").val();
    
    //判断用户是否登录
    if (user_id.length == 0) {
        window.location.href = '/customer/login';
        return;
    }  
    window.func_args = address_id;
    modalConfirm("删除地址？", "取消", "确定", "trashAddressItems", address_id);
});

/************************************
 * 回调函数,
 * @type type 完成删除地址的操作
 * 
 * @param String address_ids a string list of address id seperated by ","
 */
function trashAddressItems(address_ids)
{
    if (address_ids.toString().length > 0) {
        console.log(document.referrer);
        selected_id = getUrlParam("selected_id", document.referrer);
                    str_params = (selected_id != null)?"?selected_id="+selected_id:""; 
        console.log(str_params);
        //删除地址
        $.ajax({
            type: "POST",
            url: "/m/ajax/deleteaddresses/" + address_id,
            async: true,
            success: function(data){ 
                console.log(data);
                //删除地址成功
                if (data) {
                    console.log(document.referrer);
                    selected_id = getUrlParam("selected_id", document.referrer);
                    str_params = (selected_id != null)?"?selected_id="+selected_id:"";                    
                    window.location.href = "/my/address" + str_params;
                }
            }
        });        
    }
}

/*****************************************
 * 发货地址管理(地址列表)页
 *****************************************/

/****************************************
 * 地址列表页,点击新增按钮(增加新地址)
 */
$(".chm-address-create").click(function(){
    window.location.href = "/my/address/add";
    return;
});

/****************************************
 * 地址列表页,点击编辑按钮(编辑地址)
 */
$(".chm-address-cell-edit").click(function(){
    address_id = $(this).data("addressid");
    window.location.href = "/my/address/" + address_id;
    return;
});

//在地址列表中点击一个条目
$(".chm-address-list-cell-content").click(function() { 
    
    $(this).parent("div").siblings().each(function() {
        $(this).children("div").removeClass("chm-address-cell-selected");
    });
    
    $(this).addClass("chm-address-cell-selected");
    $(this).next("div").addClass("chm-address-cell-selected");
    
    $selected_id = $(".chm-address-list-container").data("selectedid").toString();
    //有$selected_id，说明是从下单页进入的地址页，选中一个地址条目时，应该返回下单页
    if($selected_id.length > 0) {
        //防止重复点击
        if (window.disablePickAddress == true) {
            return;
        } else {
            window.disablePickAddress = true;
        }         
               
        //下单时设置地址
        $.ajax({
            type: "POST",
            url: "/m/ajax/pickaddress",
            data: "id=" + $(this).data("addressid"),
            async: true,
            success: function(data){         
                //设置地址成功
                if (data) {
                    window.location.href = "/shop/order";
                } else {
                    window.disablePickAddress = false;
                    modalAlert("您有未完成的订单， 请点这里查看！", '/customer/order/search/current');
                }
            },
            error: function() {
                window.disablePickAddress = false;
            }
        });         
    }
});

/*****************************************
 * 下单（订单确认）页
 *****************************************/

/**
 * 选择收货地址
 */
$(".chm-order-address").click(function(){   
    address_id = $(this).data("addressid").toString();
    window.location.href = "/my/address?selected_id=" + ((address_id.length == 0)?"0":address_id);
    return;
});

/**
 * 确认订单（生成订单），进入付款流程
 */
$("#chm_go_pay").click(function() {    
    var user_id = $("input[name=global_user_id]").val();   
    //判断用户是否登录
    if (user_id.length == 0) {
        window.location.href = '/customer/login';
        return;
    }
    
    //如果未完成交易，后退至此页面，用户可以再次点击下单按钮，但是此时订单已生成，购物车已清空，
    //因为每次单击确认订单时，都检查购物车是否还有数据，如果数据不存在则表示订单已生成，将用户
    //转到订单页
    cart_ids = new Array();        
    $(".chm-order-cartitem-container").each(function(){
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
            async: true,
            success: function(data){            
                if (data == "1") {
                    ;
                } else {
                    modalAlert("您有未完成的订单，点这里查看！", '/customer/order/search/current');
                }
            },
            error: function(jqXHR) {
                error_message = getResponseError(jqXHR.responseText);
                error_message = (error_message.length > 0)?error_message:"出错了。";
                modalAlert(error_message + "点这里联系在线客服！", link_customer_service);
            }
        });            
    } else {
        window.disableGoPay = false;
        modalAlert("您有未完成的订单，点这里查看！", '/customer/order/search/current');
        return;
    }    
    
    
    var remarks = new Array();
    $(".chm-order-remark").each(function(){
        remark = new Object();
        remark.merchant_id = $(this).data("merchantid");
        remark.remark = $(this).val();       
        remarks.push(remark);
    });
    
    if ($(this).hasClass("chm-cart-order-enable")) {
        var params = {};
        params.remarks = remarks;
        $.ajax({
            type: "POST",
            url: "/shop/createorder",
            data: JSON.stringify(params),
            dataType: "json",
            async: true,
            success: function(data){                  
                if (data) {
                    //更新购物车logo上显示的商品数量
                    cart_quantity = parseInt(data.cart_quantity);                
                    if (cart_quantity > 0) {
                        $(".chm-cart-logo-number").show().text(cart_quantity);
                    } else {
                        $(".chm-cart-logo-number").hide();  
                    }

                    //启动交易付款
                    order_ids = data.order_ids;                                
                    if (order_ids.length > 0) {
                        ids = order_ids.join(",");
                        //弹出提示框， 防止用户再次回到此页
                        //modalOrderPay(ids, user_id); 
                        //转到支付页
                        window.location.href= "/pay/orders/" + ids;
                    } else {
                        modalAlert("出错了, 点这里联系在线客服！", link_customer_service);
                    }
                    window.disableGoPay = false;
                } else {
                    window.disableGoPay = false;
                    modalAlert("出错了, 点这里联系在线客服！", link_customer_service);
                }
            },
            error: function(jqXHR) {
                error_message = getResponseError(jqXHR.responseText);
                error_message = (error_message.length > 0)?error_message:"出错了。";
                modalAlert(error_message + "点这里联系在线客服！", link_customer_service);
                
                window.disableGoPay = false;
            }
        });
    } else {
        ModalAlert("请选择收货地址和支付方式!");
        window.disableGoPay = false;
    }
});
