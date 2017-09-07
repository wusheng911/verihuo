<div id="nav-main" class="nav-main">
	<div id='nav-container' class="nav-container">
		@if(!empty($id))
			<input id='currentId' type="hidden" value='{{$id}}'>
		@else
			<input id='currentId' type="hidden" value='0'>
		@endif
		<div class="nav-icon"><a class="nav-a" href="/"><img src="/assets/img/nav-icon.jpg"></a></div>
		@for($i=1;$i<7;$i++)
            @if($i != 5)
			<a ref="nofollow" href="/news/{{  config("chaohun.art_parent_category_".$i) }}">
					@if((int)$contentNav['topId'] == $i)
						<div id="nav-item-{{$i}}" class="nav-item nav-item-{{$i}} nav-item-default-{{$i}}">
							<span class="nav-a nav-a{{$i}}">{{ config('chaohun.art_parent_category_name_'.$i) }}</span>
						</div>
					@else
						<div id="nav-item-{{$i}}" class="nav-item nav-item-{{$i}}">
							<span class="nav-a nav-a{{$i}}">{{ config('chaohun.art_parent_category_name_'.$i) }}</span>
						</div>
					@endif
			</a>
            @endif

		@endfor
	</div>
</div>
<div id="nav-sub-main" class="nav-sub-main">
	<div id="nav-sub-container" class="nav-sub">
		@if(!empty($contentNav['childs']))
			@foreach($contentNav['childs'] as $childKey => $childValue)
				<div class='nav-sub-item catogory-tag'>
					<a href ={{'/news/'.$childValue['id']}}>{{$childValue['name']}}</a>
				</div>
			@endforeach
		@endif
		@if(!empty($contentNav['childCnt']))
			@if($contentNav['childCnt']>0)
					<div class='nav-sub-item'>|&nbsp&nbsp&nbsp&nbsp热门搜索：</div>
			@endif
		@endif
@if(!empty($contentNav['articleTags']))
			@foreach($contentNav['articleTags'] as $tagKey => $tagValue)
				<div class='nav-sub-item'>
					<a href ={{ action('ViewController@tagSearchForPc',null) }}/{{$tagValue['id']}}>{{$tagValue['name']}}</a>
				</div>
			@endforeach
		@endif

		@if(!empty($contentNav['homeTags']))
			@for($i =0;$i<(int) count($contentNav['homeTags']) + 1;$i++)
				@if(array_key_exists($i, $contentNav['homeTags']))
					<div class='nav-sub-item'>
						<a href ={{$contentNav['homeTags'][$i]['link']}}>{{$contentNav['homeTags'][$i]['name']}}</a>
					</div>
					@endif
				@endfor
			@foreach($contentNav['homeTags'] as $tagKey => $tagValue)

			@endforeach
		@endif
		@if(!empty($contentNav['tags']))
			@foreach($contentNav['tags'] as $tagKey => $tagValue)
				<div class='nav-sub-item'>
					<a href ={{ action('ViewController@tagSearchForPc',null) }}/{{$tagValue['id']}}>{{$tagValue['name']}}</a>
				</div>
			@endforeach
		@endif
	</div>
</div>
