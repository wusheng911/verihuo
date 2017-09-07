<div id="info-bottom-imgdiv" class="info-bottom-imgdiv d-i-b">
    <a href="{{isset($node['localAttributes']['Content Id'])?'/view/article/'.$node['localAttributes']['Content Id']['values'][1]['value']['id']:''}}"><img id="info-bottom-img" class="info-bottom-imgdiv info-bottom-img" alt="{{isset($node['localAttributes']['Content Id'])?$node['localAttributes']['Content Id']['values'][1]['value']['seo_keywords']:''}}" src="{{isset($node['localAttributes']['Content Id'])?$node['localAttributes']['Content Id']['values'][1]['value']['image_4_3']:''}}"></img></a>
</div>
<div id="info-bottom-mixdiv" class="info-bottom-mixdiv d-i-b v-a-t">
    <div id="info-bottom-hrefdiv" class="info-bottom-hrefdiv t-a-l">
        <a href="/view/article/{{isset($node['localAttributes']['Content Id'])?$node['localAttributes']['Content Id']['values'][1]['value']['id']:''}}" class="a">{{isset($node['localAttributes']['Content Id'])?$node['localAttributes']['Content Id']['values'][1]['value']['title']:''}}</a>
    </div>
    <hr class="info-hr" size=1 color=#ddc49c align=center noshade>
    <div id="info-bottom-spandiv" class="info-bottom-spandiv p-r o-h t-a-l">
        <span id="info-bottom-span" class="excerpt-span">{{isset($node['localAttributes']['Content Id'])?$node['localAttributes']['Content Id']['values'][1]['value']['description']:''}}</span>
    </div>
</div>
