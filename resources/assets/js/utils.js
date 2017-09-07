/* utils.js */

var Browser = new Object();

Browser.isMozilla = (typeof document.implementation != 'undefined') && (typeof document.implementation.createDocument != 'undefined') && (typeof HTMLDocument != 'undefined');
Browser.isIE = window.ActiveXObject ? true : false;
Browser.isFirefox = (navigator.userAgent.toLowerCase().indexOf("firefox") != - 1);
Browser.isSafari = (navigator.userAgent.toLowerCase().indexOf("safari") != - 1);
Browser.isOpera = (navigator.userAgent.toLowerCase().indexOf("opera") != - 1);

var Utils = new Object();

Utils.htmlEncode = function(text)
{
  return text.replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
}

Utils.trim = function( text )
{
  if (typeof(text) == "string")
  {
    return text.replace(/^\s*|\s*$/g, "");
  }
  else
  {
    return text;
  }
}

Utils.isEmpty = function( val )
{
  switch (typeof(val))
  {
    case 'string':
      return Utils.trim(val).length == 0 ? true : false;
      break;
    case 'number':
      return val == 0;
      break;
    case 'object':
      return val == null;
      break;
    case 'array':
      return val.length == 0;
      break;
    default:
      return true;
  }
}

Utils.isNumber = function(val)
{
  var reg = /^[\d|\.|,]+$/;
  return reg.test(val);
}

Utils.isInt = function(val)
{
  if (val == "")
  {
    return false;
  }
  var reg = /\D+/;
  return !reg.test(val);
}

Utils.isEmail = function( email )
{
  var reg1 = /([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)/;

  return reg1.test( email );
}

Utils.isTel = function ( tel )
{
  var reg = /^[\d|\-|\s|\_]+$/; //只允许使用数字-空格等

  return reg.test( tel );
}

Utils.fixEvent = function(e)
{
  var evt = (typeof e == "undefined") ? window.event : e;
  return evt;
}

Utils.srcElement = function(e)
{
  if (typeof e == "undefined") e = window.event;
  var src = document.all ? e.srcElement : e.target;

  return src;
}

Utils.isTime = function(val)
{
  var reg = /^\d{4}-\d{2}-\d{2}\s\d{2}:\d{2}$/;

  return reg.test(val);
}

Utils.x = function(e)
{ //当前鼠标X坐标
    return Browser.isIE?event.x + document.documentElement.scrollLeft - 2:e.pageX;
}

Utils.y = function(e)
{ //当前鼠标Y坐标
    return Browser.isIE?event.y + document.documentElement.scrollTop - 2:e.pageY;
}

Utils.request = function(url, item)
{
	var sValue=url.match(new RegExp("[\?\&]"+item+"=([^\&]*)(\&?)","i"));
	return sValue?sValue[1]:sValue;
}

Utils.$ = function(name)
{
    return document.getElementById(name);
}

function rowindex(tr)
{
  if (Browser.isIE)
  {
    return tr.rowIndex;
  }
  else
  {
    table = tr.parentNode.parentNode;
    for (i = 0; i < table.rows.length; i ++ )
    {
      if (table.rows[i] == tr)
      {
        return i;
      }
    }
  }
}

document.getCookie = function(sName)
{
  // cookies are separated by semicolons
  var aCookie = document.cookie.split("; ");
  for (var i=0; i < aCookie.length; i++)
  {
    // a name/value pair (a crumb) is separated by an equal sign
    var aCrumb = aCookie[i].split("=");
    if (sName == aCrumb[0])
      return decodeURIComponent(aCrumb[1]);
  }

  // a cookie with the requested name does not exist
  return null;
}

document.setCookie = function(sName, sValue, sExpires)
{
  var sCookie = sName + "=" + encodeURIComponent(sValue);
  if (sExpires != null)
  {
    sCookie += "; expires=" + sExpires;
  }

  document.cookie = sCookie;
}

document.removeCookie = function(sName,sValue)
{
  document.cookie = sName + "=; expires=Fri, 31 Dec 1999 23:59:59 GMT;";
}

function getPosition(o)
{
    var t = o.offsetTop;
    var l = o.offsetLeft;
    while(o = o.offsetParent)
    {
        t += o.offsetTop;
        l += o.offsetLeft;
    }
    var pos = {top:t,left:l};
    return pos;
}

function cleanWhitespace(element)
{
  var element = element;
  for (var i = 0; i < element.childNodes.length; i++) {
   var node = element.childNodes[i];
   if (node.nodeType == 3 && !/\S/.test(node.nodeValue))
     element.removeChild(node);
   }
}




/****************
 * 数组处理函数
 ****************/
///集合取交集  
Array.intersect = function () {  
    var result = new Array();  
    var obj = {};  
    for (var i = 0; i < arguments.length; i++) {  
        for (var j = 0; j < arguments[i].length; j++) {  
            var str = arguments[i][j];  
            if (!obj[str]) {  
                obj[str] = 1;  
            }  
            else {  
                obj[str]++;  
                if (obj[str] == arguments.length)  
                {  
                    result.push(str);  
                }  
            }//end else  
        }//end for j  
    }//end for i  
    return result;  
}  
  
//集合去掉重复  
Array.prototype.uniquelize = function () {  
    var tmp = {},  
        ret = [];  
    for (var i = 0, j = this.length; i < j; i++) {  
        if (!tmp[this[i]]) {  
            tmp[this[i]] = 1;  
            ret.push(this[i]);  
        }  
    }  
  
    return ret;  
}  
//并集  
Array.union = function () {  
    var arr = new Array();  
    var obj = {};  
    for (var i = 0; i < arguments.length; i++) {  
        for (var j = 0; j < arguments[i].length; j++)  
        {  
            var str=arguments[i][j];  
            if (!obj[str])  
            {  
                obj[str] = 1;  
                arr.push(str);  
            }  
        }//end for j  
    }//end for i  
    return arr;  
}  
  
//2个集合的差集 在arr不存在  
Array.prototype.minus = function (arr) {  
    var result = new Array();  
    var obj = {};  
    for (var i = 0; i < arr.length; i++) {  
        obj[arr[i]] = 1;  
    }  
    for (var j = 0; j < this.length; j++) {  
        if (!obj[this[j]])  
        {  
            obj[this[j]] = 1;  
            result.push(this[j]);  
        }  
    }  
    return result;  
};  

//去除重复的值
Array.prototype.getUnique = function(){
   var u = {}, a = [];
   for(var i = 0, l = this.length; i < l; ++i){
      if(u.hasOwnProperty(this[i])) {
         continue;
      }
      a.push(this[i]);
      u[this[i]] = 1;
   }
   return a;
}


/**  
 * 将数值四舍五入(保留2位小数)后格式化成金额形式  
 *  
 * @param num 数值(Number或者String)  
 * @return 金额格式的字符串,如'1,234,567.45'  
 * @type String  
 */    
function formatCurrency(num) {    
    num = num.toString().replace(/\$|\,/g,'');    
    if(isNaN(num))    
    num = "0";    
    sign = (num == (num = Math.abs(num)));    
    num = Math.floor(num*100+0.50000000001);    
    cents = num%100;    
    num = Math.floor(num/100).toString();    
    if(cents<10)    
    cents = "0" + cents;    
    for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)    
    num = num.substring(0,num.length-(4*i+3))+','+    
    num.substring(num.length-(4*i+3));  
    
    result = ((sign)?'':'-') + num;
    
    if (cents !== "00") {
        result = result + '.' + cents;
    }
   
    return result; 
}    
     
/**  
 * 将数值四舍五入(保留1位小数)后格式化成金额形式  
 *  
 * @param num 数值(Number或者String)  
 * @return 金额格式的字符串,如'1,234,567.4'  
 * @type String  
 */    
function formatCurrencyTenThou(num) {    
    num = num.toString().replace(/\$|\,/g,'');    
    if(isNaN(num))    
    num = "0";    
    sign = (num == (num = Math.abs(num)));    
    num = Math.floor(num*10+0.50000000001);    
    cents = num%10;    
    num = Math.floor(num/10).toString();    
    for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)    
    num = num.substring(0,num.length-(4*i+3))+','+    
    num.substring(num.length-(4*i+3));    
    return (((sign)?'':'-') + num + '.' + cents);    
}    
    
// 添加金额格式化    
jQuery.extend({    
    formatFloat:function(src, pos){    
        var num = parseFloat(src).toFixed(pos);    
        num = num.toString().replace(/\$|\,/g,'');    
        if(isNaN(num)) num = "0";    
        sign = (num == (num = Math.abs(num)));    
        num = Math.floor(num*100+0.50000000001);    
        cents = num%100;    
        num = Math.floor(num/100).toString();    
        if(cents<10) cents = "0" + cents;    
        for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)    
        num = num.substring(0,num.length-(4*i+3))+','+num.substring(num.length-(4*i+3));    
        return (((sign)?'':'-') + num + '.' + cents);    
    }    
});   

//获取url中的参数
function getUrlParam(name, url) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)"); //构造一个含有目标参数的正则表达式对象
    
    var str_params = (url.indexOf("?") !== -1)?url.substr(url.indexOf("?")+1, url.length):'';
    
    if (str_params.length > 0) {
        var r = str_params.match(reg);  //匹配目标参数
        if (r != null) return unescape(r[2]); return null; //返回参数值      
    } else {
        return null;
    }
}

/**
 * 友好的弹出框, 2秒后自动消失
 * 
 * @param String 提示内容
 * @param Integer 自定义消失延迟时间（微秒）
 */
function modalFadeAlert(message, fadeOutTime)
{
  var message = arguments[0] ? arguments[0] : '';
  var fadeOutTime = arguments[1] ? arguments[1] : 2000;
  
  $("#modalFadeAlert").find("span").text(message);
  $("#modalFadeAlert").show().fadeOut(parseInt(fadeOutTime));
}

/**
 * 用于解析API错误信息的函数, 返回API错误信息字符串
 * 
 * @param JSON responseText ajax调用接收到的JSON响应字符串
 * @returns String 错误信息
 */
function getResponseError(responseText) 
{
    var response_text = $.parseJSON(responseText);
    return (typeof(response_text.error) != "undefined" && response_text.error.length > 0)?response_text.error:"";
}
