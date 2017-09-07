@if($position == 'PC|Home|FashionWeek|A')
    <a href="{{isset($node['localAttributes']['Image Link'])?$node['localAttributes']['Image Link']['values'][1]['value']:''}}">
        <img src="{{isset($node['localAttributes']['Image Path'])?$node['localAttributes']['Image Path']['values'][1]['value']:''}}">
    </a>
    <div class="fw-txt-a35">
        <a href="/view/article/{{isset($node['localAttributes']['Content Id'])?$node['localAttributes']['Content Id']['values'][1]['value']['id']:''}}">
            <p class="fw-a35-txt-1">{{isset($node['localAttributes']['Content Id'])?$node['localAttributes']['Content Id']['values'][1]['value']['title']:''}}</p>
        </a>
            <div class="fw-splite"></div>
        <a href="/view/article/{{isset($node['localAttributes']['Content Id'])?$node['localAttributes']['Content Id']['values'][1]['value']['id']:''}}">
            <p class="fw-a35-txt-2">{{isset($node['localAttributes']['Content Id'])?$node['localAttributes']['Content Id']['values'][1]['value']['description']:''}}</p>
        </a>
    </div>
@elseif($position == 'PC|Home|FashionWeek|E')
    <a href="{{isset($node['localAttributes']['Image Link'])?$node['localAttributes']['Image Link']['values'][1]['value']:''}}">
        <img src="{{isset($node['localAttributes']['Image Path'])?$node['localAttributes']['Image Path']['values'][1]['value']:''}}">
    </a>
    <div class="fw-a35-4-txt">
        <a href="/view/article/{{isset($node['localAttributes']['Content Id'])?$node['localAttributes']['Content Id']['values'][1]['value']['id']:''}}">
            <p>{{isset($node['localAttributes']['Content Id'])?$node['localAttributes']['Content Id']['values'][1]['value']['title']:''}}</p>
        </a>
    </div>
@else
    <div id="info-left" class="d-i-b info-left">
        <a href="{{isset($node['localAttributes']['Content Id'])?'/view/article/'.$node['localAttributes']['Content Id']['values'][1]['value']['id']:''}}"><img id="info-left-img" alt="{{isset($node['localAttributes']['Content Id'])?$node['localAttributes']['Content Id']['values'][1]['value']['seo_keywords']:''}}" class="info-left-img t-a-l" src="{{isset($node['localAttributes']['Image Path'])?
                        $node['localAttributes']['Image Path']['values'][1]['value']:''}}"></a>
        <img src="/assets/img/PC-content/shadow-bar-icon.png">
        <div id="info-left-div" class="info-left-div">
            <div class="left-one-href">
                <a href="/view/article/{{isset($node['localAttributes']['Content Id'])?$node['localAttributes']['Content Id']['values'][1]['value']['id']:''}}" class="info-left-span-1 keep-color-golden d-b t-a-l">{{isset($node['localAttributes']['Content Id'])?$node['localAttributes']['Content Id']['values'][1]['value']['title']:''}}</a>
            </div>
            <div class="info-excerpt-div">
                <a href="/view/article/{{isset($node['localAttributes']['Content Id'])?$node['localAttributes']['Content Id']['values'][1]['value']['id']:''}}" class="info-left-span-2 keep-color-a d-b t-a-l">{{isset($node['localAttributes']['Content Id'])?$node['localAttributes']['Content Id']['values'][1]['value']['description']:''}}</a>
            </div>
        </div>
    </div>
@endif



