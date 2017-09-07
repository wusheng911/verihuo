<a href="/view/article/{{ isset($node['localAttributes']['Content Id']) ? $node['localAttributes']['Content Id']['values']['1']['value']['id'] : '' }}" target="_blank">
    <img src="{{isset($node['localAttributes']['Content Id']) ? $node['localAttributes']['Content Id']['values']['1']['value']['image_4_3'] : '' }}" style="width:180px;height:135px;">
</a>
<a href="/view/article/{{ isset($node['localAttributes']['Content Id']) ? $node['localAttributes']['Content Id']['values']['1']['value']['id'] : '' }}" target="_blank">
    <span>
        {{ isset($node['localAttributes']['Content Id']) ? $node['localAttributes']['Content Id']['values']['1']['value']['title'] : '' }}
    </span>
</a>
