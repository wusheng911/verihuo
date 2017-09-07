<div id="info-title" class="m-info-title">
        <div class="m-title-a-div">
        	@if(array_key_exists('img',$category))
            	<a href="/news/{{$category['id']}}"><img src="{{$category['img']}}"></a>
            @else
                <a href="/news/{{$category['id']}}" style="font-size:24px;color:#333333">{{$category['name']}}</a>
            @endif
            
            @foreach ($category['ads'] as $key=>$adposition)
                @if ($adposition['adposition_code'] == "PC|News|".$category['id']."|T")
                    @include('nodes.index', ['position' => $adposition['adposition_code'],'hasImg'=>isset($category['img']) ? 'true' : 'false'])
                                @php
                                    unset($category['ads'][$key]);
                                @endphp
                                @break
                @endif
            @endforeach
        </div>
        @if(array_key_exists('img',$category))
        <a href="/news/{{$category['id']}}" class="m-more keep-color-title" style="margin-top:10px;">more</a>
        @else
        	<a href="/news/{{$category['id']}}" class="m-more keep-color-title" >more</a>
        @endif
    
</div>            
<div class="{{$cat['type']}}">

            @foreach ($category['ads'] as $key=>$adposition)
                        @if ($adposition['adposition_code'] == "PC|News|".$category['id']."|A")
                    		@include('nodes.index', ['position' => $adposition['adposition_code']])
                                @php
                                    unset($category['ads'][$key]);
                                @endphp
                                @break
                    	@endif
            @endforeach
    <div id="info-right" class="d-i-b v-a-t info-right">
        <div id="info-top" class="info-top t-a-l">
            <div class="d-i-bes" id="info-top-img-1" class="info-top-img info-top-img-1">
            @foreach ($category['ads'] as $key=>$adposition)
                        @if ($adposition['adposition_code'] == "PC|News|".$category['id']."|B")
                    		@include('nodes.index', ['position' => $adposition['adposition_code']])
                                @php
                                    unset($category['ads'][$key]);
                                @endphp
                                @break
                    	@endif
            @endforeach
            
            	</div>            
            <div class="d-i-bs" id="info-top-img-1" class="info-top-img info-top-img-1">
            @foreach ($category['ads'] as $key=>$adposition)
                        @if ($adposition['adposition_code'] == "PC|News|".$category['id']."|C")
                    		@include('nodes.index', ['position' => $adposition['adposition_code']])
                                @php
                                    unset($category['ads'][$key]);
                                @endphp
                                @break
                    	@endif
            @endforeach
            </div>
        </div>
        <div id="info-bottom">				
            <div id="info-bottom-div" class="info-bottom-div f-l">
            @foreach ($category['ads'] as $key=>$adposition)
                        @if ($adposition['adposition_code'] == "PC|News|".$category['id']."|D1")
                    		@include('nodes.index', ['position' => $adposition['adposition_code']])
                                @php
                                    unset($category['ads'][$key]);
                                @endphp
                                @break
                    	@endif
            @endforeach
                <div id="info-bottom-uldiv" class="info-bottom-uldiv d-i-b t-a-l">
                    <ul id="info-bottom-ul" type="square">	
            @foreach ($category['ads'] as $key=>$adposition)
                        @if ($adposition['adposition_code'] == "PC|News|".$category['id']."|E1")
                    		@include('nodes.index', ['position' => $adposition['adposition_code']])
                                @php
                                    unset($category['ads'][$key]);
                                @endphp
                                @break
                    	@endif
            @endforeach					 								 		
                    </ul>
                </div>
            </div>
            <div id="info-bottom-div" class="info-bottom-div1 f-l">
            @foreach ($category['ads'] as $key=>$adposition)
                        @if ($adposition['adposition_code'] == "PC|News|".$category['id']."|D2")
                    		@include('nodes.index', ['position' => $adposition['adposition_code']])
                                @php
                                    unset($category['ads'][$key]);
                                @endphp
                                @break
                    	@endif
            @endforeach
                <div id="info-bottom-uldiv" class="info-bottom-uldiv d-i-b t-a-l">
                    <ul id="info-bottom-ul" type="square">		
            @foreach ($category['ads'] as $key=>$adposition)
                        @if ($adposition['adposition_code'] == "PC|News|".$category['id']."|E2")
                    		@include('nodes.index', ['position' => $adposition['adposition_code']])
                                @php
                                    unset($category['ads'][$key]);
                                @endphp
                                @break
                    	@endif
            @endforeach						 								 		
                    </ul>
                </div>
            </div>				
        </div>
    </div>
</div>
