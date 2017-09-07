<a href="{{isset($node['localAttributes']['Image Link'])?$node['localAttributes']['Image Link']['values'][1]['value']:''}}">
    <img class="@if(stripos($position, 'PC|Home|FashionWeek|B') !== false)
                    {{'fw-a35-2'}}
                @elseif(stripos($position, 'PC|Home|FashionWeek|C') !== false)
                    {{'fw-a35-3'}}
                @elseif(stripos($position, 'PC|Home|FashionWeek|D') !== false)
                    {{'fw-logo'}}
                @endif"
		alt="{{isset($node['localAttributes']['Image Title'])?$node['localAttributes']['Image Title']['values'][1]['value']:''}}"
         src="{{isset($node['localAttributes']['Image Path'])?$node['localAttributes']['Image Path']['values'][1]['value']:''}}">
</a>
