$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
//for all pages
$(document).ready(function() {
    //使用blur函数可以去链接上的虚线
    $("a").bind('focus', function(){
        $(this).blur();
    });
    
    $("input[type=button]").bind('focus', function(){
        $(this).blur();
    });    
       
    $("#chm_login").click(function() {
        window.location.href = "/customer/login";
    });
    $("#chm_register").click(function() {
        window.location.href = "/customer/register";
    });
    $("#chm_logout").click(function() {
        window.location.href = "/customer/logout";
    });
    $("#chm_account").click(function() {
        window.location.href = "/customer/account";
    });
    //For navigator Top2 (sub-navigator) (资讯二级导航, 我/要/潮/婚4个主题动态显示)
    var active_nav = $(".chm-fixed-navigator").data("active");
    if (!active_nav) {
      active_nav = 'wo';
    }
    $("#nav-" + active_nav + " a").addClass("chm-" + active_nav + "-color");    
    
    if ($("#prompt_alert").is(":visible") && $("#prompt_alert").hasClass('success')) {
        setTimeout(function(){
            $("#prompt_alert").fadeOut();
        }, 5000);
    }
});


/**
 * 更加友好的提示框
 * 
 * @param String message 提示信息
 * @param String link 如果提示信息是可点击的链接, link是点击提示信息后去到的地址
 * @param String btnName 关闭按钮的文字
 * @param String btnLink 点击关闭后可以转到另一个页面
 */
function modalAlert(message, link, btnName, btnLink)
{ 
  var message = arguments[0] ? arguments[0] : '';
  var link = arguments[1] ? arguments[1] : '';//设置参数link的默认值为''
  var btnName = arguments[2] ? arguments[2]: '';
  var btnLink = arguments[3] ? arguments[3] : '';
  
  if (link.length > 0) {
      $("#modalAlert div.small-12").first().html("<a style=\"margin:0 auto;padding-bottom:0.625rem;color:#3990e6;display:block;\" href=\"" + link + "\">" + message + "</a>");
  } else {
      $("#modalAlert div.small-12").first().html("<span style=\"margin:0 auto;padding-bottom:0.625rem;display:block;\">" + message + "</span>");
  }
  
  if (btnName.length > 0) {
      $("#modalAlert").find("#closeAlert").text(btnName);
  }
  
  if (btnLink.length > 0) {
      $("#modalAlert").find("#closeAlert").click(function() {
          $('#modalAlert').foundation('reveal', 'close');
          window.location.href = btnLink;
      });
  }
  
  
  $('#modalAlert').foundation('reveal', 'open');
} 


$('a.chm-modal-close').on('click', function() {
  if ($("#modalAlert").length > 0) {
      $("#modalAlert").foundation('reveal', 'close');
  }
  if ($("#modalShare").length > 0) {
      $("#modalShare").foundation('reveal', 'close');
  }
  if ($("#modalQRCode").length > 0) {
      $("#modalQRCode").foundation('reveal', 'close');
  }  
});

/**********************************************************
 * 确认对话框
 * @param {String} message 显示的内容
 * @param {String} cancel_text 取消按钮的内容
 * @param {String} ok_text 确定按钮的内容
 * @param {String} func_name 回调函数, 作为点击确定Action时的响应,回调函数的参数保存在
 *                 window.func_args 类型是Array
 */
function modalConfirm(message, cancel_text, ok_text, func_name)
{ 
    cancel_text = (cancel_text.length == 0)?"取消":cancel_text;
    ok_text = (ok_text.length == 0)?"确定":ok_text;
    
    $('#modalConfirm').find(".chm-modal-content").text(message);
    $('#modalConfirm').find(".chm-modal-cancel").text(cancel_text);
    $('#modalConfirm').find(".chm-modal-ok").text(ok_text);
      
    $(".chm-modal-cancel").bind("click", function(){
        $('#modalConfirm').foundation('reveal', 'close');
    });
    
    $(".chm-modal-ok").unbind('click').click(function() {
        $('#modalConfirm').foundation('reveal', 'close');
        window[func_name](window.func_args);
        return false;
    });    
    
    if (func_name == 'trashCartItems') {
        $('#modalConfirm').find(".chm-modal-ok").parent("div").css("background-color", "#ff9900");
        $('#modalConfirm').find(".chm-modal-ok").css("color", "white");
    }
    
    $('#modalConfirm').foundation('reveal', 'open');   
}

function modalSelectZone(type, type_id, zone_id)
{
    var type = arguments[0] ? arguments[0] : '';//设置默认值为'' 
    var type_id = arguments[1] ? arguments[1] : ''; //设置默认值为''  
    var zone_id = arguments[2] ? arguments[2] : '';//设置默认值为'' 
    
    //目前当地区选择框弹出时，不自动适配之前已经选中的地区， 需要的话再加
    switch (type) {
        case "province":
            break;
        case "city":
            break;
        case "district":
            break;
        default:
            ;
    } 
    
    $.get("/m/ajax/getzones", {
        type: "province"
    },function(data, textStatus){
        if (data) {
            $(".chm-modalzone-list").prepend(data);
        } 
    });        

    $('#modalZone').foundation('reveal', 'open');
}

function getPlantform() {
    ua = navigator.userAgent;
    if ((ua.indexOf("iPhone") > -1 || ua.indexOf("iPod") > -1)) {
        return "iPhone"
    }
    return "Android"
};

/***********************************
 * Mobile Offcanvas Menu
 * 
 ***********************************/

$(document).foundation({
    offcanvas : {
      // Sets method in which offcanvas opens.
      // [ move | overlap_single | overlap ]
      open_method: 'move', 
      // Should the menu close when a menu link is clicked?
      // [ true | false ]
      close_on_click : false
    }         
  });

$("#register").click(function(){
    window.location.href='/';
}).mouseover(function() {
    $(this).css('text-decoration', 'underline');
}).mouseout(function() {
    $(this).css('text-decoration', 'none');
});

$("#login").click(function(){
    window.location.href='/';
}).mouseover(function() {
    $(this).css('text-decoration', 'underline');
}).mouseout(function() {
    $(this).css('text-decoration', 'none');
});

$("#menu-closer").click(function() {
    $("#menu-block").removeClass('offcanvas-overlap');
});


/***************************************
 * For navigator Top2 (sub-navigator) 
 * (资讯二级导航, 我/要/潮/婚4个主题动态显示)
 ****************************************/

//Action for navigatorTop2 go back button
function go_back() 
{
    var pathname = window.location.pathname;
    
    //如果是评论页直接返回正文
    if (pathname.indexOf('/view/artcomments') == 0) {
        window.location.href = pathname.replace(/artcomments/g, 'article');
    } else {
        window.history.back();
    }
    
    return true;
}