<div class="row">
    <div class="small-12">
        <a href="{{isset($node['localAttributes']['Image Link'])?
            $node['localAttributes']['Image Link']['values']['1']['value']:''}}">
            <img class="chm-banner" 
                src="{{isset($node['localAttributes']['Image Path'])?
                    $node['localAttributes']['Image Path']['values']['1']['value']:''}}"
                alt="{{isset($node['localAttributes']['Image Title'])?
                    $node['localAttributes']['Image Title']['values']['1']['value']:''}}"
                />
        </a> 
    </div>
</div> 