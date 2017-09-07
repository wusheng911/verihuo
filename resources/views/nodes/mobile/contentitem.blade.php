@if (isset($node['localAttributes']['Content Id']) && (count($node['localAttributes']['Content Id']) > 0)) 
    <div class="row">
        <div class="small-12 columns">
          <div class="chm-article-rec">
            <a href="{{'/view/article/' . $node['localAttributes']['Content Id']['values']['1']['value']['id']}}">
                <img class="chm-article-rec-coverimage" 
                    src="{{$node['localAttributes']['Content Id']['values']['1']['value']['image_4_3']}}"
                    alt="{{$node['localAttributes']['Content Id']['values']['1']['value']['title']}}"
                    />
            </a>
            <div class="chm-article-rec-articleblock">
              <div class="chm-article-rec-articlecontainer">
                <h5>
                    <a href="{{'/view/article/' . $node['localAttributes']['Content Id']['values']['1']['value']['id']}}">
                        {{$node['localAttributes']['Content Id']['values']['1']['value']['title']}}
                    </a>
                </h5>
                <span>
                    <a href="{{'/view/article/' . $node['localAttributes']['Content Id']['values']['1']['value']['id']}}">
                        {{$node['localAttributes']['Content Id']['values']['1']['value']['description']}}
                    </a>
                </span>
              </div>
            </div>
          </div>
        </div>
    </div> 
@endif
