var username_empty = '用户名不能为空';
var username_shorter = '用户名长度不能少于3个字符';
var password_empty = '登录密码不能为空';
var password_shorter = '登录密码不能少于6个字符';
var confirm_password_invalid = '两次输入密码不一致';
var email_empty = '邮箱为空';
var email_invalid = '邮箱不是合法的地址';
var agreement = '您没有接受协议';
var msn_invalid = 'msn地址不是一个有效的邮件地址';
var qq_invalid = 'QQ号码不是一个有效的号码';
var home_phone_invalid = '家庭电话不是一个有效号码';
var office_phone_invalid = '办公电话不是一个有效号码';
var mobile_phone_invalid = '手机号码不是一个有效号码';
var mobile_phone_empty = "手机号不能为空";
var msg_un_blank = '帐号不能为空';
var msg_un_shorter = '帐号长度不能少于3个字符';
var msg_un_length = '帐号最长不能超过20个字符';
var msg_un_format = '请输入合法的手机号或邮箱作为您的登陆帐号';
var msg_un_registered = '帐号已经存在,请重新输入';
var msg_can_rg = '可以注册';
var msg_email_blank = '邮件地址不能为空';
var msg_email_registered = '邮箱已存在,请重新输入';
var msg_email_format = '邮件地址不合法';
var msg_blank = '不能为空';
var no_select_question = '您没有完成密码提示问题的操作';
var passwd_blank = '密码中不能包含空格';
var msg_mobilecode_blank = '使用手机号注册请输入短信验证码';
var msg_account_invalid = '没有对应的用户';
//提示弹框
jconfirm.defaults = {
    title: '温馨提示',
    icon: 'fa fa-warning text-warning',
    confirmButton: '确定',
    cancelButton: '取消',
    confirmButtonClass: 'btn-success',
    cancelButtonClass: 'btn-danger',
};
function show_alert(item_short_name, message) {
    if (item_short_name.length > 0) {
        $("#" + item_short_name + '_notice_no').show();
        $("#" + item_short_name + '_notice_yes').hide();
    }
    $("#prompt_alert").removeClass('success').addClass('warning').show().html(message+"!").data("item", item_short_name);
}

function show_info(item_short_name, message) {
    $("#prompt_alert").removeClass('warning').addClass('success').show().html(message+"!").data("item", item_short_name);
}

function hide_alert(item_short_name) {
    if (item_short_name.length > 0) {
        $("#" + item_short_name + '_notice_no').hide();
        $("#" + item_short_name + '_notice_yes').show();
    }
    $("#prompt_alert").html("").hide();
}

function is_alert_on() {
    return $("#prompt_alert").is(":visible") && $("#prompt_alert").hasClass('warning');
}

function get_alert_item() {
    return $("#prompt_alert").data("item");
}

$(document).ready(function() {
    $("#register_form :input").blur(function() {
        var message = "";
        var item = "";
        var submit_disabled = false;
        var value = $(this).val();
        
        //如果其他输入框已经有错误提示,则不提示当前输入框的问题
        if (is_alert_on()) {
            var alert_item = get_alert_item();
            if ((alert_item != '') && (alert_item != $(this).attr("name"))) {
                return;
            }
        }
        
        if ($(this).is("input[name=mobile_number]")) {
            item = "mobile_number";
            if ( value == '' ) {
                message = mobile_phone_empty;
                submit_disabled = true;
            }
            if (!Utils.isTel(value) ) {
                message = mobile_phone_invalid;
                submit_disabled = true;
            }          
        }
        
        if ($(this).is("input[name=user_password]")) {
            item = "user_password";
            if (value.length < 6 ) {
                message = password_shorter;
                submit_disabled = true;
            }         
        }        

        if ($(this).is("input[name=confirm_password]")) {
            item = "confirm_password";
            if (value.length < 6 ) {
                message = confirm_password_invalid;
                submit_disabled = true;
            } else {
                password_str = $("input[name=user_password]").val();
                if (value != password_str) {
                    message = confirm_password_invalid;
                    submit_disabled = true;
                }
            }   
        } 

        if ($(this).is("input[name=user_name]")) {
            item = "user_name";
            if ( value.length < 3 ) { 
                message = username_shorter;
                submit_disabled = true;
            }       
        }
        
        if ($(this).is("input[name=email]")) {
            item = "email";
            //email could be empty
            if ((value.length > 0) && (!Utils.isEmail(value))) { 
                message = email_invalid;
                submit_disabled = true;
            }       
        }        
        
        //if something is invid, than show error and return
        if (submit_disabled) {
            show_alert(item, message);
            document.forms['formCustomer'].elements['Submit'].disabled = 'disabled';
            return;
        }         
        
        if (($(this).is("input[name=mobile_number]") || 
            $(this).is("input[name=user_name]") || 
            $(this).is("input[name=email]")) &&
            value.length > 0) {      
            $.get('/ajax/registeredby/' + value, {}, function(data, textStatus) {
                //'phone' is mapping to 'mobile_number'
                if ((item.length > 0) && ((data == item) || (data == 'phone'))) {
                    message = msg_un_registered;
                    show_alert(item, message);
                    document.forms['formCustomer'].elements['Submit'].disabled = 'disabled';
                } else {
                    hide_alert(item);
                    document.forms['formCustomer'].elements['Submit'].disabled = '';
                }
            });  
        } else {
            document.forms['formCustomer'].elements['Submit'].disabled = '';
            hide_alert(item);                 
        }
    });
    
    $("input[name=zphone]").click(function() {
        value  = Utils.trim($("input[name=mobile_number]").val());
        if (!Utils.isTel(value)) {
          show_alert("mobile_number", mobile_phone_invalid);
          return;
        }
  
        $.post('/ajax/sendsms',
            { "mobile_number": value}, 
            function(data, textStatus){
                if (data.code==2){
                    RemainTime();
                    show_info('', '短信验证码已经发送到您的手机');
                }else{
                    if(result.msg){
                        show_alert('', result.msg);
                    }else{
                        show_alert('', '短信验证码发送失败');
                    }
                }              
            });        
    });
    
    $("#login_form :input").blur(function() {
        var message = "";
        var item = "";
        var submit_disabled = false;
        var value = $(this).val();
              
        if ($(this).is("input[name=login_name]")) {
            if ( value.length < 3 ) { 
                message = msg_un_shorter;
                submit_disabled = true;
            }       
        }
        
        if ($(this).is("input[name=user_password]")) {
            item = "user_password";
            if (value.length < 6 ) {
                message = password_shorter;
                submit_disabled = true;
            }         
        }        
       
        if (submit_disabled) {
            show_alert('', message);
            document.forms['formCustomer'].elements['Submit'].disabled = 'disabled';
            return;
        } else {
            document.forms['formCustomer'].elements['Submit'].disabled = '';
            hide_alert('');
        }      
    });
    
    $("#resetpassword_form :input").blur(function() {
        var message = "";
        var item = "";
        var submit_disabled = false;
        var value = $(this).val();
        
        //如果其他输入框已经有错误提示,则不提示当前输入框的问题
        if (is_alert_on()) {
            if (get_alert_item() != $(this).attr("name")) {
                return;
            }
        }
        
        if ($(this).is("input[name=mobile_number]")) {
            item = "mobile_number";
            if ( value == '' ) {
                message = mobile_phone_empty;
                submit_disabled = true;
            }
            if (!Utils.isTel(value) ) {
                message = mobile_phone_invalid;
                submit_disabled = true;
            }  
            
            if (submit_disabled == false) {
                $.get('/ajax/registeredby/' + value, {}, function(data, textStatus) {
                    //'phone' is mapping to 'mobile_number'
                    if ((item.length > 0) && (data == 'phone')) {
                        hide_alert(item);
                        document.forms['formCustomer'].elements['Submit'].disabled = '';
                    } else {
                        message = msg_account_invalid;
                        show_alert(item, message);
                        document.forms['formCustomer'].elements['Submit'].disabled = 'disabled';                  
                    }
                }); 
                return;
            }    
        }
        
        if ($(this).is("input[name=user_password]")) {
            item = "user_password";
            if (value.length < 6 ) {
                message = password_shorter;
                submit_disabled = true;
            }         
        }        

        if ($(this).is("input[name=confirm_password]")) {
            item = "confirm_password";
            if (value.length < 6 ) {
                message = confirm_password_invalid;
                submit_disabled = true;
            } else {
                password_str = $("input[name=user_password]").val();
                if (value != password_str) {
                    message = confirm_password_invalid;
                    submit_disabled = true;
                }
            }   
        } 

        if (submit_disabled) {
            show_alert(item, message);
            document.forms['formCustomer'].elements['Submit'].disabled = 'disabled';
            return;
        } else {
            hide_alert(item);
            document.forms['formCustomer'].elements['Submit'].disabled = '';         
        }  
    });

    //删除订单
    $('.delete-order-btn').click(function(){
        var orderId = $(this).data('id');
        $.confirm({
            content: "您确定要删除该订单吗?",
            confirm: function() {
                if(orderId) {
                    $.post("/customer/order/ajax/update",{'orderAction':'delete','orderId':orderId},
                        function(data){
                            if(data == 'success') {
                                window.location.reload();//刷新当前页面.
                            }
                    });
                }
            }
        });
    });

    //确认收货
    $('.receipt-order-btn').click(function(){
        var orderId = $(this).data('id');
        $.confirm({
            content: "确定收货?",
            confirm: function() {
                if(orderId) {
                    $.post("/customer/order/ajax/update",{'orderAction':6,'orderId':orderId},
                        function(data){
                            if(data == 'success') {
                                window.location.reload();//刷新当前页面.
                            }
                    });
                }
            }
        });
    });
});



var iTime = 59;
var Account;
function RemainTime(){
  document.getElementById('zphone').disabled = true;
  var iSecond,sSecond="",sTime="";
  if (iTime >= 0){
      iSecond = parseInt(iTime%60);
      if (iSecond >= 0){
          sSecond = iSecond + "秒";
      }
      sTime=sSecond;
      if(iTime==0){
          clearTimeout(Account);
          sTime='获取短信验证码';
          iTime = 59;
          document.getElementById('zphone').disabled = false;
      }else{
          Account = setTimeout("RemainTime()",1000);
          iTime=iTime-1;
      }
  }else{
      sTime='没有倒计时';
  }
  document.getElementById('zphone').value = sTime;
}
        

function doRegister() {
    var mobile_code = Utils.trim($("input[name=mobile_code]").val());  

    if (mobile_code.length == 0) {
        show_alert('', msg_mobilecode_blank);
        return false;
    } else if (is_alert_on()) {
        return false;
    } else {
        return true;
    }
}

function doResetpassword() {
  return doRegister();
}