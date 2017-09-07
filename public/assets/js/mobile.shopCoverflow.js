var car2 = {
 /*************************
 *  = 对象变量，判断函数
 *************************/
	_events 		: {},                           // 自定义事件---this._execEvent('scrollStart');
    
    _pageHeight     : $(window).height() - 80,
    _topPageHeight  : $(window).width()/($(window).height() - 80),
    _midPageHeight  : ($(window).height() - 80 - $(window).width())/(2*($(window).height() - 80)),
    //滑动距离
    _moveLength     : function() {
                        return Math.round(this._pageHeight*this._midPageHeight)+2;
    },
    
	_page 			: $('.chm-coverflow-container').children("div"),							// 模版页面切换的页面集合
	_pageNow		: 0,									// 页面当前的index数
	_pageNext		: null,									// 页面下一个的index数

	_touchStartValY	: 0,									// 触摸开始获取的第一个值
	_touchDeltaY	: 0,									// 滑动的距离(< 0 表示 move up, > 0 表示 move down)

	_moveStart		: true,									// 触摸移动是否开始
	_movePosition	: null,									// 触摸移动的方向（上、下） ('up' or 'down')
	_movePosition_c	: null,									// 触摸移动的方向的控制
	_mouseDown		: false,								// 判断鼠标是否按下
	_moveFirst		: true,
	_moveInit		: false,

	_firstChange	: false,
	
	_elementStyle	: document.createElement('div').style,	// css属性保存对象

/***********************
 *  = gobal通用函数
 ***********************/

	// 判断浏览器内核类型
	_vendor			: function () {
						var vendors = ['t', 'webkitT', 'MozT', 'msT', 'OT'],
							transform,
							i = 0,
							l = vendors.length;
				
						for ( ; i < l; i++ ) {
							transform = vendors[i] + 'ransform';
							if ( transform in this._elementStyle ) return vendors[i].substr(0, vendors[i].length-1);
						}
						return false;
					},
	// 判断浏览器来适配css属性值
	_prefixStyle	: function (style) {
						if ( this._vendor() === false ) return false;
						if ( this._vendor() === '' ) return style;
						return this._vendor() + style.charAt(0).toUpperCase() + style.substr(1);
					},

	// 判断属性支持是否
	_injectStyles 	: function( rule, callback, nodes, testnames ) {
						var style, ret, node, docOverflow,
							div = document.createElement('div'),
							body = document.body,
							fakeBody = body || document.createElement('body'),
							mod = 'modernizr';

						if ( parseInt(nodes, 10) ) {
							while ( nodes-- ) {
								node = document.createElement('div');
								node.id = testnames ? testnames[nodes] : mod + (nodes + 1);
								div.appendChild(node);
								}
						}

						style = ['&#173;','<style id="s', mod, '">', rule, '</style>'].join('');
						div.id = mod;
						(body ? div : fakeBody).innerHTML += style;
						fakeBody.appendChild(div);
						if ( !body ) {
							fakeBody.style.background = '';
							fakeBody.style.overflow = 'hidden';
							docOverflow = docElement.style.overflow;
							docElement.style.overflow = 'hidden';
							docElement.appendChild(fakeBody);
						}

						ret = callback(div, rule);
						if ( !body ) {
							fakeBody.parentNode.removeChild(fakeBody);
							docElement.style.overflow = docOverflow;
						} else {
							div.parentNode.removeChild(div);
						}

						return !!ret;
					},

	//禁止滚动条
	_scrollStop		: function(){
						//禁止滚动
						$(window).on('touchmove.scroll',this._scrollControl);
						$(window).on('scroll.scroll',this._scrollControl);
					},
	//启动滚动条
	_scrollStart 	: function(){		
						//开启屏幕禁止
						$(window).off('touchmove.scroll');
						$(window).off('scroll.scroll');
					},
	//滚动条控制事件
	_scrollControl	: function(e){e.preventDefault();},

/**************************************************************************************************************/
/*  关联处理函数
***************************************************************************************************************/
/**
 *  单页面-m-page 切换的函数处理
 *  -->绑定事件
 *  -->事件处理函数
 *  -->事件回调函数
 *  -->事件关联函数【
 */
 	// 页面切换开始
 	page_start		: function(){
 		car2._page.on('touchstart mousedown',car2.page_touch_start);
 		car2._page.on('touchmove mousemove',car2.page_touch_move);
 		car2._page.on('touchend mouseup',car2.page_touch_end);
 	},

 	// 页面切换停止
 	page_stop		: function(){
		car2._page.off('touchstart mousedown');
 		car2._page.off('touchmove mousemove');
 		car2._page.off('touchend mouseup');
 	},

 	// page触摸移动start
 	page_touch_start: function(e){
 		if(!car2._moveStart) return;

 		if(e.type == "touchstart"){
        	car2._touchStartValY = window.event.touches[0].pageY;
        }else{
        	car2._touchStartValY = e.pageY||e.y;
        	car2._mouseDown = true;
        }

        car2._moveInit = true;
 	},

 	// page触摸移动move
 	page_touch_move : function(e){
 		e.preventDefault();

		if(!car2._moveStart) return;
		if(!car2._moveInit) return;

		// 设置变量值
 		var moveP;

 		// 获取移动的值
 		if(e.type == "touchmove"){
        	moveP = window.event.touches[0].pageY;
        }else{
            if(car2._mouseDown){
                moveP = e.pageY||e.y;
            }else return;
        }

		// 获取下次活动的page
    car2.page_position(e,moveP);
		// page页面移动 		
 		car2.page_translate();	
 	},

 	// page触摸移动判断方向
 	page_position	: function(e,moveP)
    { 		
 		// 设置移动的距离
 		if(moveP!=='undefined') {
            car2._touchDeltaY = moveP - car2._touchStartValY;
            //console.log('car2._touchDeltaY:' + car2._touchDeltaY);
        }
        
        // 设置移动方向
        car2._movePosition = (moveP - car2._touchStartValY >0) ? 'down' : 'up';
        //如果移动方向变换, 则重置_moveFirst属性
        if(car2._movePosition !== car2._movePosition_c){
            car2._moveFirst = true;
            car2._movePosition_c = car2._movePosition;
        }else{
            car2._moveFirst = false;
        }

 		// move阶段根据方向设置页面的初始化位置--执行一次
 		if(car2._moveFirst) {
            init_next();
        }

        function init_next()
        {              
            // 设置下一页面的显示和位置        
            if(car2._movePosition == 'up'){
                $(".chm-coverflow-page-next").css(car2._prefixStyle('transform'), 'translate(0,'+car2._pageHeight+'px)');
                
                $(".chm-coverflow-page-next").attr('data-translate',car2._pageHeight);               
                $(".chm-coverflow-page-top").attr('data-translate',0);
                $(".chm-coverflow-page-middle").attr('data-translate',0);
                $(".chm-coverflow-page-bottom").attr('data-translate',0);
            }
 		}
 	},

 	// page触摸移动设置函数
 	page_translate	: function(){		
    //手指的滑动距离
 		var y = car2._touchDeltaY;
        l = car2._pageHeight;
        m_p3 = car2._moveLength() * -1;                   
        y_move = y/3;
                       
        //移动距离超过bottom块的应移动距离， 则忽略多余的移动
        //下面代码中的+0.2和-1, -2都是为了防止图片移动时，图片之间会产生细小间隙
        if (car2._movePosition == 'up') {
            if (y_move*1.2 >= m_p3) {
                top_height = car2._topPageHeight*100 - (car2._topPageHeight - car2._midPageHeight)*100*(y_move/m_p3)*1.2;            
                middle_height = car2._midPageHeight*100+(car2._topPageHeight - car2._midPageHeight)*100*(y_move/m_p3)*1.2+0.5;             

                //top页面的移动          
                $(".chm-coverflow-page-top").css(car2._prefixStyle('transform'), 'translate(0,'+y_move+'px)'); 
                $(".chm-coverflow-page-top").css("height",  top_height+"%");

                //middle页面的移动
                $(".chm-coverflow-page-middle").css(car2._prefixStyle('transform'), 'translate(0,'+(y_move)+'px)');
                $(".chm-coverflow-page-middle").css("height",  middle_height+"%");
                //console.log($(".chm-coverflow-page-middle").css("height"));
                //bottom页面的移动
                $(".chm-coverflow-page-bottom").css(car2._prefixStyle('transform'), 'translate(0,'+(y_move-2)+'px)');            
                //next页面的移动
                $(".chm-coverflow-page-next").css(car2._prefixStyle('transform'), 'translate(0,'+(y_move-3)+'px)');            
            }
        }
 	},

 	// page触摸移动end
 	page_touch_end	: function(e){
 		car2._moveInit = false;
 		car2._mouseDown = false;
 		if(!car2._moveStart) {
            return;
        }
 		car2._moveStart = false;
    
        clicked_item = null;
        
        console.log(car2._touchDeltaY);
    
		// 页面切换
 		if(Math.abs(car2._touchDeltaY)>=10){		// 切换成功
 			car2.page_success();
 		}else{                                  // 没有切换
        //点击事件, 判断点击了哪个区域
        if (car2._touchDeltaY === 0) {
            if (car2._touchStartValY <= car2._pageHeight * car2._topPageHeight) {
                clicked_item = "top";
            } else if (car2._touchDeltaY >= (car2._pageHeight - car2._pageHeight*car2._midPageHeight)) {
                clicked_item = "bottom";
            } else {
                clicked_item = "middle";
            }
        }   
        car2.page_fail();
 		}

        // 注销控制值
 		car2._movePosition = null;
 		car2._movePosition_c = null;
 		car2._touchStartValY = 0;
    
    //处理点击事件
    switch(clicked_item) {
        case 'top':
            window.location.href = "/view/product/" + $(".chm-coverflow-page-top .chm-coverflow-pageitem").data("productid");
            break;
        case 'middle':
           // window.location.href = "/view/product/" + $(".chm-coverflow-page-middle .chm-coverflow-pageitem").data("productid");
            break;
        case "bottom":
           // window.location.href = "/view/product/" + $(".chm-coverflow-page-bottom .chm-coverflow-pageitem").data("productid");
            break;
    }
 	},

 	// 切换成功
 	page_success	: function(){
        l = car2._pageHeight;
        m_p3 = car2._moveLength() * -1; 
        duration = 100;
        
        prev_origin = $(".chm-coverflow-page-prev");
        top_origin = $(".chm-coverflow-page-top");
        middle_origin = $(".chm-coverflow-page-middle");
        bottom_origin = $(".chm-coverflow-page-bottom");
        next_origin = $(".chm-coverflow-page-next");        
        
        if (car2._movePosition == 'up') {
            bottom_origin.transition({
                    "translate":["0", m_p3+"px"]
                },
                duration
            );                

            next_origin.transition({
                    "translate":["0", m_p3+"px"]
                },
                duration
            );

            middle_origin.transition({
                    "height": car2._topPageHeight*100+"%",
                    "translate":["0", m_p3+"px"]
                },
                duration
            );

            top_origin.transition({
                    "height": car2._midPageHeight*100+"%",
                    "translate":["0", m_p3+"px"]
                },
                duration,
                'in',
                function() {
                    top_origin.removeAttr("style");
                    top_origin.addClass('chm-coverflow-page-next');
                    top_origin.removeClass('chm-coverflow-page-top');

                    middle_origin.removeAttr("style");
                    middle_origin.addClass('chm-coverflow-page-top');
                    middle_origin.removeClass('chm-coverflow-page-middle');

                    bottom_origin.removeAttr("style");
                    bottom_origin.addClass('chm-coverflow-page-middle');
                    bottom_origin.removeClass('chm-coverflow-page-bottom');

                    next_origin.removeAttr("style");
                    next_origin.addClass('chm-coverflow-page-bottom');
                    next_origin.removeClass('chm-coverflow-page-next');

                    $(".chm-coverflow-page-top").css(car2._prefixStyle('transform'), '');
                    $(".chm-coverflow-page-middle").css(car2._prefixStyle('transform'), '');
                    $(".chm-coverflow-page-bottom").css(car2._prefixStyle('transform'), '');
                    $(".chm-coverflow-page-next").css(car2._prefixStyle('transform'), '');

                    $(".chm-coverflow-page-top").css("height", car2._topPageHeight*100+"%");

                    $(".chm-coverflow-page-middle").css("bottom", car2._midPageHeight*100+"%");
                    $(".chm-coverflow-page-middle").css("height", car2._midPageHeight*100+"%");

                    $(".chm-coverflow-page-bottom").css("height", car2._midPageHeight*100+"%");

                    $(".chm-coverflow-page-next").css("height", car2._midPageHeight*100+"%");
                    $(".chm-coverflow-page-next").css("bottom", -1*car2._midPageHeight*100+"%");

                    $(".chm-coverflow-page-top").show();
                    $(".chm-coverflow-page-middle").show();
                    $(".chm-coverflow-page-bottom").show();     
                    $(".chm-coverflow-page-next").show();                

                    car2._moveStart = true;
                    car2._moveFirst = true;
                    car2._pageNext = null;
                    car2._touchDeltaY = 0;

                    //后台准备下一轮播页的数据
                    setTimeout(function(){     
                        //设置下一个chm-coverflow-data-nextnext
                        current_page_content = $(".chm-hidden-coverflow").filter(".chm-coverflow-data-nextnext");
                        next_page_content = (current_page_content.attr("name") == $(".chm-hidden-coverflow:last").attr("name"))?
                            $(".chm-hidden-coverflow:first"):$(".chm-hidden-coverflow.chm-coverflow-data-nextnext").next();

                        current_values = current_page_content.val();
                        current_arr = new Array();
                        current_arr = current_values.split('|');

                        //设置下一个页面的标题,价格,图片
                        $(".chm-coverflow-page-next .chm-coverflow-pageitem-title").text(current_arr[1]);
                        $(".chm-coverflow-page-next .chm-coverflow-pageitem-info").text(current_arr[2]);
                        $(".chm-coverflow-page-next .chm-coverflow-pageitem-price").text(current_arr[3]);
                        $(".chm-coverflow-page-next .chm-coverflow-pageitem").css("background-image", "url("+current_arr[4]+")");
                        $(".chm-coverflow-page-next .chm-coverflow-pageitem").data("productid", current_arr[0]);

                        current_page_content.removeClass("chm-coverflow-data-nextnext");
                        next_page_content.addClass("chm-coverflow-data-nextnext"); 
                        
                        
                        //设置下一个chm-coverflow-data-prev
                        current_page_content = $(".chm-hidden-coverflow").filter(".chm-coverflow-data-prev");
                        prev_page_content = (current_page_content.attr("name") == $(".chm-hidden-coverflow:last").attr("name"))?
                            $(".chm-hidden-coverflow:first"):$(".chm-hidden-coverflow.chm-coverflow-data-prev").next(); 
                            
                        current_page_content.removeClass("chm-coverflow-data-prev");
                        prev_page_content.addClass("chm-coverflow-data-prev"); 
                        
                        prev_values = prev_page_content.val();
                        prev_arr = new Array();
                        prev_arr = prev_values.split('|');                        
                        
                        
                        //设置前一个页面的标题,价格,图片
                        $(".chm-coverflow-page-prev .chm-coverflow-pageitem-title").text(prev_arr[1]);
                        $(".chm-coverflow-page-prev .chm-coverflow-pageitem-info").text(prev_arr[2]);
                        $(".chm-coverflow-page-prev .chm-coverflow-pageitem-price").text(prev_arr[3]);
                        $(".chm-coverflow-page-prev .chm-coverflow-pageitem").css("background-image", "url("+prev_arr[4]+")");
                        $(".chm-coverflow-page-prev .chm-coverflow-pageitem").data("productid", prev_arr[0]);                        
                        
                    },50);                
                }            
            );
        } 
        
        if (car2._movePosition == 'down') {  
            prev_origin.removeAttr("style");
            prev_origin.addClass('chm-coverflow-page-top');
            prev_origin.removeClass('chm-coverflow-page-prev');            
                        
            top_origin.removeAttr("style");
            top_origin.addClass('chm-coverflow-page-middle');
            top_origin.removeClass('chm-coverflow-page-top');

            middle_origin.removeAttr("style");
            middle_origin.addClass('chm-coverflow-page-bottom');
            middle_origin.removeClass('chm-coverflow-page-middle');

            bottom_origin.removeAttr("style");
            bottom_origin.addClass('chm-coverflow-page-next');
            bottom_origin.removeClass('chm-coverflow-page-bottom');
            
            next_origin.removeAttr("style");
            next_origin.addClass('chm-coverflow-page-prev');
            next_origin.removeClass('chm-coverflow-page-next');

            $(".chm-coverflow-page-top").css(car2._prefixStyle('transform'), '');
            $(".chm-coverflow-page-middle").css(car2._prefixStyle('transform'), '');
            $(".chm-coverflow-page-bottom").css(car2._prefixStyle('transform'), '');
            $(".chm-coverflow-page-next").css(car2._prefixStyle('transform'), '');

            $(".chm-coverflow-page-top").css("height", car2._topPageHeight*100+"%");

            $(".chm-coverflow-page-middle").css("bottom", car2._midPageHeight*100+"%");
            $(".chm-coverflow-page-middle").css("height", car2._midPageHeight*100+"%");

            $(".chm-coverflow-page-bottom").css("height", car2._midPageHeight*100+"%");

            $(".chm-coverflow-page-next").css("height", car2._midPageHeight*100+"%");
            $(".chm-coverflow-page-next").css("bottom", -1*car2._midPageHeight*100+"%");

            $(".chm-coverflow-page-top").show();
            $(".chm-coverflow-page-middle").show();
            $(".chm-coverflow-page-bottom").show();     
            //$(".chm-coverflow-page-next").show();                

            car2._moveStart = true;
            car2._moveFirst = true;
            car2._pageNext = null;
            car2._touchDeltaY = 0; 

            //准备下一轮播页的数据
     
            //设置下一个chm-coverflow-data-nextnext
            current_page_content = $(".chm-hidden-coverflow").filter(".chm-coverflow-data-nextnext");
            next_page_content = (current_page_content.attr("name") == $(".chm-hidden-coverflow:first").attr("name"))?
                $(".chm-hidden-coverflow:last"):$(".chm-hidden-coverflow.chm-coverflow-data-nextnext").prev();

            current_values = current_page_content.val();
            current_arr = new Array();
            current_arr = current_values.split('|');

            //设置下一个页面的标题,价格,图片
            $(".chm-coverflow-page-next .chm-coverflow-pageitem-title").text(current_arr[1]);
            $(".chm-coverflow-page-next .chm-coverflow-pageitem-info").text(current_arr[2]);
            $(".chm-coverflow-page-next .chm-coverflow-pageitem-price").text(current_arr[3]);
            $(".chm-coverflow-page-next .chm-coverflow-pageitem").css("background-image", "url("+current_arr[4]+")");
            $(".chm-coverflow-page-next .chm-coverflow-pageitem").data("productid", current_arr[0]);

            current_page_content.removeClass("chm-coverflow-data-nextnext");
            next_page_content.addClass("chm-coverflow-data-nextnext"); 


            //设置下一个chm-coverflow-data-prev
            current_page_content = $(".chm-hidden-coverflow").filter(".chm-coverflow-data-prev");
            prev_page_content = (current_page_content.attr("name") == $(".chm-hidden-coverflow:first").attr("name"))?
                $(".chm-hidden-coverflow:last"):$(".chm-hidden-coverflow.chm-coverflow-data-prev").prev(); 

            current_page_content.removeClass("chm-coverflow-data-prev");
            prev_page_content.addClass("chm-coverflow-data-prev"); 

            prev_values = prev_page_content.val();
            prev_arr = new Array();
            prev_arr = prev_values.split('|');                        


            //设置前一个页面的标题,价格,图片
            $(".chm-coverflow-page-prev .chm-coverflow-pageitem-title").text(prev_arr[1]);
            $(".chm-coverflow-page-prev .chm-coverflow-pageitem-info").text(prev_arr[2]);
            $(".chm-coverflow-page-prev .chm-coverflow-pageitem-price").text(prev_arr[3]);
            $(".chm-coverflow-page-prev .chm-coverflow-pageitem").css("background-image", "url("+prev_arr[4]+")");
            $(".chm-coverflow-page-prev .chm-coverflow-pageitem").data("productid", prev_arr[0]);                        



        }
        
 	},

 	// 切换失败
 	page_fail	: function(){

        car2._moveStart = true;
        car2._moveFirst = true;
        car2._pageNext = null;
        car2._touchDeltaY = 0;
        
        $(".chm-coverflow-page-top").removeAttr("style");
        $(".chm-coverflow-page-middle").removeAttr("style");
        $(".chm-coverflow-page-bottom").removeAttr("style");
        $(".chm-coverflow-page-next").removeAttr("style");
                
        $(".chm-coverflow-page-top").css(car2._prefixStyle('transform'), '');
        $(".chm-coverflow-page-middle").css(car2._prefixStyle('transform'), '');
        $(".chm-coverflow-page-bottom").css(car2._prefixStyle('transform'), '');
        $(".chm-coverflow-page-next").css(car2._prefixStyle('transform'), '');

        $(".chm-coverflow-page-top").css("height", car2._topPageHeight*100+"%");

        $(".chm-coverflow-page-middle").css("bottom", car2._midPageHeight*100+"%");
        $(".chm-coverflow-page-middle").css("height", car2._midPageHeight*100+"%");

        $(".chm-coverflow-page-bottom").css("height", car2._midPageHeight*100+"%");

        $(".chm-coverflow-page-next").css("height", car2._midPageHeight*100+"%");
        $(".chm-coverflow-page-next").css("bottom", -1*car2._midPageHeight*100+"%");

        $(".chm-coverflow-page-top").show();
        $(".chm-coverflow-page-middle").show();
        $(".chm-coverflow-page-bottom").show();     
        $(".chm-coverflow-page-next").show();      
 	},


/**
 * app初始化
 */
	// 样式适配
	styleInit : function(){
		// 禁止文版被拖动
		document.body.style.userSelect = 'none';
		document.body.style.mozUserSelect = 'none';
		document.body.style.webkitUserSelect = 'none';
	},

	// 对象初始化
	init : function(){
		// 样式，标签的渲染
		// 对象操作事件处理
		this.styleInit();
		
		$(window).on('load',function(){
            //console.log(car2._topPageHeight);
            $(".chm-coverflow-page-top").attr('data-translate','');
            $(".chm-coverflow-page-middle").attr('data-translate','');
            $(".chm-coverflow-page-bottom").attr('data-translate','');
            $(".chm-coverflow-page-next").attr('data-translate','');
            
            $(".chm-coverflow-page-top").css(car2._prefixStyle('transform'), '');
            $(".chm-coverflow-page-middle").css(car2._prefixStyle('transform'), '');
            $(".chm-coverflow-page-bottom").css(car2._prefixStyle('transform'), '');
            $(".chm-coverflow-page-next").css(car2._prefixStyle('transform'), '');
            
            $(".chm-coverflow-page-top").css("height", car2._topPageHeight*100+"%");
            
            $(".chm-coverflow-page-middle").css("bottom", car2._midPageHeight*100+"%");
            $(".chm-coverflow-page-middle").css("height", car2._midPageHeight*100+"%");
            
            $(".chm-coverflow-page-bottom").css("height", car2._midPageHeight*100+"%");
            
            $(".chm-coverflow-page-next").css("height", car2._midPageHeight*100+"%");
            $(".chm-coverflow-page-next").css("bottom", -1*car2._midPageHeight*100+"%");
            
            $(".chm-coverflow-page-top").show();
            $(".chm-coverflow-page-middle").show();
            $(".chm-coverflow-page-bottom").show();     
            $(".chm-coverflow-page-next").show(); 
            
            
            // 开启window的滚动
            car2._scrollStart();
            // 开启页面切换
            car2.page_start();	

		})
	}
};

/*初始化对象函数*/
car2.init();
