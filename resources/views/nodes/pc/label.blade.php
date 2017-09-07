<div class="spandiv">
    <a href="/view/article/{{isset($node['localAttributes']['Content Id'])?
            $node['localAttributes']['Content Id']['values']['1']['value']['id']:''}}">
        <span class="span">{{isset($node['localAttributes']['Content Id'])?
                    $node['localAttributes']['Content Id']['values']['1']['value']['title']:''}}</span>
    </a>
</div>  