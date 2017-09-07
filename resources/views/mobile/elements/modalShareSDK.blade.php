{{--微信分享弹出引导图片--}}
<div class="chm-sharesdk-container" style="display:none;">
    <img style="width:100%;height:100%;" onclick="javascript:this.parentNode.style.display='none';" src="/assets/img/sharesdk.png"/>
</div>
<input type="hidden" name="art_title" id="article_title" value="{{$article['title']}}"/>
<input type="hidden" name="art_cover" id="article_cover" value="{{$article['image']}}"/>
<input type="hidden" name="art_description" id="article_description" value="{{$article['description']}}"/>