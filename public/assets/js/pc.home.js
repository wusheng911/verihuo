$(document).ready(function(){
	//console.log(123);
	//$.backstretch([
			//'http://api.asilu.com/cdn/img/bg/444.jpg',
			//'http://api.asilu.com/cdn/img/bg/445.jpg',
			//'http://api.asilu.com/cdn/img/bg/446.jpg',
			//'http://api.asilu.com/cdn/img/bg/447.jpg',
			//'http://api.asilu.com/cdn/img/bg/448.jpg'
		//], {
			//fade : 1000, // 动画时长
			//duration : 2000 // 切换延时
	//});
	 $(document).ready(function(){
            $('.aniview').AniView();
        });
	 $.goup({
                trigger: 100,
                bottomOffset: 150,
                locationOffset: 100,
                title: '返回顶部',
                titleAsText: true
            });
			var Page = (function() {
				
				//给图片添加点击事件
				var imgs = $('#sb-slider').find('img');
				imgs.each(function(i){
					$(imgs.get(i)).css('cursor','pointer');
					$(imgs.get(i)).on('click',function(e){
					var screenWidth = $(document).width();
					var halfScreenWidth = screenWidth / 2;
					if(e.clientX > halfScreenWidth){
						slicebox.next();
					}else{
						slicebox.previous();
					}
						//slicebox.play();
				});
				});

				var $navArrows = $( '#nav-arrows' ).hide(),
					$shadow = $( '#shadow' ).hide(),
					slicebox = $( '#sb-slider' ).slicebox( {
						onReady : function() {

							$navArrows.show();
							$shadow.show();

						},
						orientation : 'r',
						cuboidsRandom : true
					} ),
					
					init = function() {

						initEvents();
						//slicebox.play();
						
					},
					initEvents = function() {

						// add navigation events
						$navArrows.children( ':first' ).on( 'click', function() {

							slicebox.next();
							return false;

						} );

						$navArrows.children( ':last' ).on( 'click', function() {
							
							slicebox.previous();
							return false;

						} );

					};

					return { init : init };

			})();

			Page.init();
});
