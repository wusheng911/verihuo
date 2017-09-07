<a target="_blank" href="{{isset($node['localAttributes']['Image Link'])?
    $node['localAttributes']['Image Link']['values']['1']['value']:''}}">
    <img src="{{isset($node['localAttributes']['Image Path'])?
          $node['localAttributes']['Image Path']['values']['1']['value']:''}}"
		alt="{{isset($node['localAttributes']['Image Title'])?$node['localAttributes']['Image Title']['values'][1]['value']:''}}"
 />
</a>


