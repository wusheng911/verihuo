@if (isset($content) && is_array($content))
<div class="row">
    <div class="small-12 columns">
      <div class="chm-article-rec">
        <a href="{{isset($content['id'])?'/view/article/'.$content['id']:''}}">
            <img class="chm-article-rec-coverimage" src="{{isset($content['image_4_3'])?$content['image_4_3']:''}}" />
        </a>
        <div class="chm-article-rec-articleblock">
          <div class="chm-article-rec-articlecontainer">
            <h5><a href="{{isset($content['id'])?'/view/article/'.$content['id']:''}}">{{isset($content['title'])?$content['title']:''}}</a></h5>
            <span><a href="{{isset($content['id'])?'/view/article/'.$content['id']:''}}">{{isset($content['description'])?$content['description']:''}}</a></span>
          </div>
        </div>
      </div>
    </div>
</div>
@endif