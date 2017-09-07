$(document).ready(function() {
    var article_id = $("#artLoadComments").data("article");
    //加载更多文章
    $(".chm-article-loadmore").on('click', function(){
        var article_id = $("#article_content").data("article");
        $.get("/m/ajax/article/"+article_id, {
            pos:$(this).data("pos")
        }, function(data, textStatus){
            if (data) {
                $("#article_content").children("div").append(data.content);
                $(".chm-article-loadmore").parent("div").hide();
            }
        });
    });
    
    //回到页头
    $("#main-section").on('scroll',function(){
        //console.log($("#main-section").scrollTop());
        if ($("#main-section").scrollTop() > 500) {
            if (!$(".chm-back-to-top").is(":visible")) {
                $(".chm-back-to-top").show();
            }
        } else {
            if ($(".chm-back-to-top").is(":visible")) {
                $(".chm-back-to-top").hide();
            }          
        }
    });
    
    $(".chm-back-to-top").on('click',function(){
        $("#main-section").scrollTop(0);
    });
    
    //加载更多评论   
    $("#artLoadComments").on('click', function() {
        $.get("/m/ajax/loadcomments/" + article_id, {
            offset: $("#art_comments").children("div").length
        },function(data, textStatus){
            if (data) {
                $("#art_comments").append(data);
                border_width = $("#art_comments").children("div:last-child").css("border-bottom-width");
                if (border_width == "0px") {
                    $("#art_comments").children("div:last-child").css("border-bottom", "none");
                    $("#artLoadComments").parent("div").hide();                    
                }
            } else {
                $("#art_comments").children("div:last-child").css("border-bottom", "none");
                $("#artLoadComments").parent("div").hide();
            }
        });
    });  
    //回复/取消回复       
    $("#art_comments").on("click", ".chm-artcomment-hf", function(){
        if ($(this).parent("div").parent("div").next("div").is(":visible")) {
            $(this).text("回复");
            $(this).parent("div").parent("div").next("div").hide(); 
        } else {
            var user_id = $("input[name=global_user_id]").val(); 
            if (user_id.length > 0) {
                $(this).text("取消");
                $(this).parent("div").parent("div").next("div").show();
            } else {
                modalAlert('文明理性发言，请点击此链接登录。', '/customer/login');
            }
        }          
    });
    
    //点击评论框
    $("#art_comments, #add_art_comment").on("click", "textarea", function(){
        var user_id = $("input[name=global_user_id]").val();

        //判断用户是否登录
        if (user_id.length == 0) {
            modalAlert('文明理性发言，请点击此链接登录。', '/customer/login');
            return;
        }        
    });

    //发表评论
    $("#art_comments, #add_art_comment").on("click", ".chm-btn-artcomment-pl", function(){        
        var pid = $(this).data("comment");
        var article_id = $(this).data("article");
        var user_id = $("input[name=global_user_id]").val();

        //判断用户是否登录
        if (user_id.length == 0) {
            modalAlert('文明理性发言，请点击此链接登录。', '/customer/login');
            return;
        }

        if (pid) { //发表回复
            var content = $(this).parent("div").prev("div").children("textarea").val();                
            var input_pl = $(this).parent("div").prev("div").children("textarea");
            var div_pl = $(this);

            if (content.length < 6) {
                modalAlert('评论内容不能少于6个字符!');
            } else {
                //防止重复点击
                if (window.disableSubmitComment == true) {
                    return;
                } else {
                    window.disableSubmitComment = true;
                }                
                
                $.post("/m/ajax/addcomment/" + article_id,
                    {
                        pid: pid,
                        content: content,
                        user_id: user_id                          
                    },
                    function(data, textStatus){                
                        //添加评论成功后, 清空输入框, 并隐藏评论区.
                        input_pl.val("");
                        div_pl.parent("div").parent("div").hide();
                        div_pl.parent("div").parent("div").prev("div").find("a.chm-artcomment-hf").text("回复");

                        if (data) {
                            $("#art_comments").prepend(data);
                        }
                        window.disableSubmitComment = false;
                    }
                );                    
            }          
        } else {   //发表新评论
            var content = $(this).parent("div").prev("div").children("textarea").val();                
            var input_pl = $(this).parent("div").prev("div").children("textarea");

            if (content.length < 6) {
                modalAlert('评论内容不能少于6个字符!');
            } else {  
                //防止重复点击
                if (window.disableSubmitComment == true) {
                    return;
                } else {
                    window.disableSubmitComment = true;
                }                
                
                $.post('/m/ajax/addcomment/' + article_id,
                    {
                        content: content,
                        user_id: user_id 
                    },
                    function(data, textStatus) {                 
                        //添加评论成功后, 清空输入框.
                        input_pl.val("");

                        if (data) {
                            $("div.chm-artcomment-rec").removeClass("chm-hide");
                            $("#art_comments").prepend(data);
                        }
                        window.disableSubmitComment = false;
                    }
                );                    
            }               
        }         
    });
    
    //对评论点赞
    $("#art_comments").on("click", ".chm-artcomment-thumbup", function(){
        var art_comment_thumbup = $(this);
        var comment_id = $(this).data("comment");
        var current_votes = $(this).children("span").text();        
        current_votes = (current_votes.length > 0)?current_votes:'0';
        
        //当前用户已经点赞过的评论ID以字符串(,分割)的形式记录在cookie中, 读取cookie来判断是否重复点赞
        var cookie_art_comment_thumbup = $.cookie("art_comment_thumbup");
        var thumbup_comments = [];
        if (cookie_art_comment_thumbup) {
            thumbup_comments = cookie_art_comment_thumbup.split(",");
            if (thumbup_comments.indexOf(String(comment_id)) >= 0) {
                modalAlert("您已点赞了这条评论!");
                return;
            }
        } 
        
        $.post("/m/ajax/updateartcomment/" + comment_id, {
            votes: parseInt(current_votes) + 1
        },function(data, textStatus){            
            if (data) {
                art_comment_thumbup.children("span").text(data.votes);
                thumbup_comments.push(comment_id);
                $.cookie("art_comment_thumbup", thumbup_comments.toString());
            }
        });                 
    });
    
    //触发工具栏评论按钮后, 屏幕滚动到评论区
    $("#artbar_add_comment").click(function(e) {  
        e.preventDefault();  
        $('#main-section').animate({
            scrollTop: $("#add_art_comment").offset().top - $('#main-section').offset().top + $('#main-section').scrollTop() - 120
        }, 'slow', function() {
            $("#add_art_comment textarea:first").select();
        });        
    });
    
    //初始化分享
    var config = {
        url:encodeURIComponent(window.location.href),// 分享的网页链接
        title:encodeURIComponent(document.getElementById("article_title").innerText),// 标题
        desc:encodeURIComponent(document.getElementById("article_description").value),// 描述
        img:encodeURIComponent('http://' + window.location.host + document.getElementById("article_cover").value),// 图片
        img_title:encodeURIComponent(document.getElementById("article_title").innerText),// 图片标题
        from:encodeURIComponent('verihuo') // 来源
    };
    var share_obj = new nativeShare('nativeShare',config);   

});

