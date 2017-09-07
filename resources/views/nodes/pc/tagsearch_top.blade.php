<ul>
    @foreach($nodes as $node)
        <li>
            <a target="_blank" href="{{isset($node['localAttributes']['Image Link'])?$node['localAttributes']['Image Link']['values']['1']['value']:''}}">
                <img src="{{ isset($node['localAttributes']['Image Path'])?$node['localAttributes']['Image Path']['values']['1']['value']:''}}" width="325" height="200"/>
            </a>
        </li>
    @endforeach
</ul>
