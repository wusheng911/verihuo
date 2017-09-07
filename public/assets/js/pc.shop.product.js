$(onReady);
var currentSelectImgIndex =0;
var imgs;
var list;
var max;
function onReady(){
    $(document).on('scroll',onScroll);
    $('#product-arrow-left').on('click',onClickImgProductLeft);
    $('#product-arrow-right').on('click',onClickImgProductRight)
    list = $('#products-img-list').children('div');
    max = list.size();
    list.each(function(e){
        $(this).on('click',onDivClick)
    })
    console.log(list);
}
function onDivClick(e){
    currentSelectImgIndex = $(this).attr('data-slick-index');
}

function onClickImgProductLeft(e){
    if(currentSelectImgIndex>0){
        currentSelectImgIndex--;
    }
    var div= list.get(currentSelectImgIndex);
    img = $(div).children('img:first');
    img.click();

}
function onClickImgProductRight(){
    if(currentSelectImgIndex<max-1){
        currentSelectImgIndex++;
    }
    var div= list.get(currentSelectImgIndex);
    img = $(div).children('img:first');
    img.click();
}
function onScroll(e){
    var sp = $('#chp-shop-product-blockright');
    //窗口高度
    var docHeight = document.body.clientHeight;
    //控件高度
    var height = sp.height();
    //页面总高度
    var topHeight = $(document).outerHeight();
    //控件原始位置
    var originPosition = sp.offset();
    // 滚动条位置
    var scroll = $(document).scrollTop();
    if(height<docHeight){
        if(scroll>100){
            if(scroll + height < topHeight -182){
                sp.css('top',scroll-100);
            }else{
                sp.css('top',topHeight - 282 - height);
            }
        }else{
            sp.css('top',0);
        }
    }else{
        if(scroll + docHeight >height + 100){
            if(scroll + docHeight>topHeight -182){
                sp.css('top',topHeight - 282 -height);
            }else{
                sp.css('top',scroll+docHeight-height-100)
            }
        }
        if(scroll <100){
            sp.css('top',0);
        }
    }
}
