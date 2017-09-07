/**
 * Created by Rambo on 2017/2/21.
 */
$(document).ready(function () {
    //购物车商品数量减少
    $('.cart-product-quantity-sub').click(function () {
        var cart_product_quantity = parseInt($(this).next().attr("value"));
        if(cart_product_quantity>1) {
            cart_product_quantity--;
            updateCartProductQuantity($(this),cart_product_quantity);
        } else {
            $(this).attr('disabled',true);
            return false;
        }
    });

    //购物车商品数量增加
    $('.cart-product-quantity-add').click(function () {
        var cart_product_quantity = parseInt($(this).prev().attr("value"));
        if(cart_product_quantity<100) {
            cart_product_quantity++;
            updateCartProductQuantity($(this),cart_product_quantity);
        } else {
            $(this).attr('disabled',true);
            return false;
        }
    });

    //更新购物车产品数量
    function updateCartProductQuantity(btn,quantity) {
        var cart_id = btn.closest('td').find('input').data('cartid');
        var product_price = btn.closest('td').find('input').data('price');
        if(btn.hasClass('cart-product-quantity-sub')) {
            var action = 'sub';
        } else {
            var action = 'add';
        }
        $.ajax({
            type: 'post',
            url: "/ajax/updateCartQuantity/"+cart_id,
            data: "quantity="+quantity+"&action="+action,
            async: false,
            success: function (data) {
                if (data == 1) {
                    btn.closest('td').find('input').attr("value",quantity);
                    btn.prop("disabled", false);
                    //重新计算商品小计
                    var cart_product_total = quantity*product_price;
                    btn.closest('td').next().find('.cart-product-total').html(cart_product_total);

                    //重新计算购物车内商品总数量和总计
                    updateCartTotal();
                } else {
                    btn.removeClass("cart-btn-disabled").prop("disabled", false);
                    if(btn.hasClass('cart-product-quantity-add')) {
                        if (data == 3) {
                            btn.attr('disabled',true);
                            $.confirm({
                                content: "抱歉，您购买的数量已经超出商品库存",
                                cancelButton:false,
                            });
                        }
                    }
                }
            }
        });
    }

    //批量删除购物车商品
    $('.delete-cart-products').click(function () {
        var cartIds = $('.cart-submit').data('cartids');
        if(cartIds != '') {
            $.confirm({
                content: "确定删除这些商品?",
                confirm: function () {
                    if (cartIds) {
                        $.post("/ajax/deleteCartProduct", {'cartIds': cartIds},
                            function (data) {
                                if (data == 1) {
                                    window.location.reload();//刷新当前页面.
                                }
                            });
                    }
                }
            });
        } else {
            $.confirm({
                content: "请勾选您要删除的商品！"
            });
        }
    });

    //单组全选，取消
    $('.select-product-group').click(function () {
        if(this.checked) {
            $(this).closest(".cart-merchants").next().find('.cart-product-select').prop("checked",true);
            //$('.cart-select-all').prop("checked",true);
        } else {
            $(this).closest(".cart-merchants").next().find('.cart-product-select').prop("checked",false);
            $('.cart-select-all').prop("checked",false);
        }
    })

    //全选，取消按钮
    $('.cart-select-all').click(function () {
        if(this.checked) {
            $("input[type='checkbox']").prop("checked",true);
        } else {
            $("input[type='checkbox']").prop("checked",false);
        }
    });

    //每次勾选后重新计算购物车总数量和总计
    $("input[type='checkbox']").click(function () {
        if($(this).hasClass('cart-product-select')){
            //购物车分组选中的商品数
            var cart_group_product_checked = $(this).closest('.cart-product').find('input[type=checkbox]:checked').length;
            //购物车分组商品总数
            var cart_group_products = $(this).closest('.cart-product').find('.cart-product-select').length;
            if(cart_group_product_checked == cart_group_products) {
                $(this).closest('li').find('.select-product-group').prop("checked",true);
            } else {
                $(this).closest('li').find('.select-product-group').prop("checked",false);
            }
        }

        //购物车总选中的商品数
        var cart_product_checked = $(".cart-product input[type=checkbox]:checked").length;
        //购物车总商品总数
        var cart_products = $(".cart-product input[type=checkbox]").length;

        $('.cart-product-select').each(function () {
            if(!this.checked) {
                $('.cart-select-all').prop("checked",false);
                $(this).closest('.cart-merchants input[type=checkbox]').prop("checked",false);
            }
        });
        if(cart_product_checked == cart_products) {
            $('.cart-select-all').prop("checked",true);
            $(this).closest('.cart-merchants input[type=checkbox]').prop("checked",true);
        }

        //重新计算购物车内商品总数量和总计
        updateCartTotal();
    });

    function updateCartTotal(){
        var quantity_total = 0;
        var price_total = 0;
        var cart_ids = new Array();

        $(".cart-product input[type=checkbox]").each(function () {
            if(this.checked) {
                price_total+=+$(this).closest('tr').find('.cart-product-total').text();
                quantity_total+=+$(this).closest('tr').find('.cart-product-quantity').attr("value");
                var cart_id = $(this).closest('tr').find('.cart-product-quantity').data("cartid")
                cart_ids.push(cart_id);
            }
        });

        if(cart_ids.length>0) {
            var cartIds = cart_ids.join(",");
            $('.cart-submit').removeClass('cart-submit-disabled').removeAttr("disabled");
        } else {
            var cartIds = '';
            $('.cart-submit').addClass('cart-submit-disabled').attr("disabled","disabled");
        }

        $('.quantity-total').text(quantity_total);
        $('.price-total').text('￥'+price_total);
        $('.cart-submit').data('cartids',cartIds);
    }

    //删除购物车商品
    $('.cart-product-delete').click(function () {
        var cartId = $(this).data('id');
        $.confirm({
            content: "确定删除该商品?",
            confirm: function() {
                if(cartId) {
                    $.post("/ajax/deleteCartProduct",{'cartId':cartId},
                        function(data){
                            if(data == 1) {
                                window.location.reload();//刷新当前页面.
                            } else {
                                alert('删除失败');
                            }
                        });
                }
            }
        });
    });

    //购物车结算
    $('.cart-submit').click(function() {
        var cartIds = $(this).data('cartids');
        $("input[name='cart_ids']").val(cartIds);
        $('form').submit();
        //console.log(cartIds);
        //location.href = "/shop/order?cart_ids="+cartIds;
        //return false;
    });
})