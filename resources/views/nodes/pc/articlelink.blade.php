@if(!empty($node))
		@foreach ($node['localAttributes']['ArticleList']['values'][1]['value'] as $a)
			<li class="info-bottom-li" type="initial"><a class="keep-color-a" href="/view/article/{{isset($a['id'])?$a['id']:''}}">{{isset($a['title'])?$a['title']:''}}</a></li>
		@endforeach
@endif
