@extends('layouts.app')

@section('title')
@endsection

@section('keywords')
{{$s_tag}}
@endsection


@section('scripts')
@endsection

@section('content')
    @include('pc.nav')
    <div class="main-content">
        <!--搜索页广告-->
        <div class="s-ads">
            @if(count($nodes))
                @include('nodes.pc.tagsearch_top', ['nodes' => $nodes])
            @endif
        </div>
        <div class="sh-tags">
            <ul>
                <li class="s-tag">{{ $s_tag }} <span>({{ isset($articles['search_total']) ? $articles['search_total'] : $articles->total() }})</span></li>
                @foreach($tags as $key=>$tag)
                    <li><a href="/content/searchbytag/{{ $key }}">{{ $tag }}</a></li>
                @endforeach
            </ul>
        </div>
        <div class="sr-row1">
            <div class="sr-row1-l">
                @if($articles['0'])
                    <div class="sr-a article-info1">
                        <a href="/view/article/{{ $articles['0']->id }}" target="_blank">
                            <div class="a-image"><img src="{{ $articles['0']->image_4_3 }}" width="460" height="345" alt="{{ $articles['0']->seo_keywords }}"></div>
                            <div class="a-content">
                                <h2 limit="20">{{ $articles['0']->title }}</h2>
                                <p limit="150">{{ $articles['0']->description }}</p>
                            </div>
                        </a>
                    </div>
                @endif
                @if($articles['2'])
                    <div class="sr-a article-info3">
                        <a href="/view/article/{{ $articles['2']->id }}" target="_blank">
                            <div class="a-image"><img src="{{ $articles['2']->image_4_3 }}" width="355" height="270" alt="{{ $articles['2']->seo_keywords }}"></div>
                            <div class="a-content">
                                <h2 limit="26">{{ $articles['2']->title }}</h2>
                                <p limit="88">{{ $articles['2']->description }}</p>
                            </div>
                        </a>
                    </div>
                @endif
                @if($articles['3'])
                    <div class="sr-a article-info4">
                        <a href="/view/article/{{ $articles['3']->id }}" target="_blank">
                            <div class="a-image"><img src="{{ $articles['3']->image_4_3 }}" width="355" height="270" alt="{{ $articles['3']->seo_keywords }}"></div>
                            <div class="a-content">
                                <h2 limit="26">{{ $articles['3']->title }}</h2>
                                <p limit="88">{{ $articles['3']->description }}</p>
                            </div>
                        </a>
                    </div>
                @endif
            </div>
            <div class="sr-row1-r">
                @if($articles['1'])
                    <div class="sr-a  article-info2">
                        <a href="/view/article/{{ $articles['1']->id }}" target="_blank">
                            <div class="a-image"><img src="{{ $articles['1']->image_4_3 }}" width="480" height="360" alt="{{ $articles['1']->seo_keywords }}"></div>
                            <div class="a-content">
                                <h2 limit="36">{{ $articles['1']->title }}</h2>
                                <p limit="60">{{ $articles['1']->description }}</p>
                            </div>
                        </a>
                    </div>
                @endif
                @if($articles['4'])
                    <div class="sr-a article-info5">
                        <a href="/view/article/{{ $articles['4']->id }}" target="_blank">
                            <img class="a-image"src="{{ $articles['4']->image_4_3 }}" width="280" height="210" alt="{{ $articles['4']->seo_keywords }}">
                            <h2 limit="13">{{ $articles['4']->title }}</h2>
                            <p limit="150">{{ $articles['4']->description }}</p>
                        </a>
                    </div>
                @endif
            </div>
        </div>
        <div class="sr-row2">
            @if($articles['5'])
                <div class="sr-row2-l sr-a">
                    <a href="/view/article/{{ $articles['5']->id }}" target="_blank">
                        <img src="{{ $articles['5']->image_4_3 }}" width="340" height="255" alt="{{ $articles['5']->seo_keywords }}">
                        <h2 limit="22">{{ $articles['5']->title }}</h2>
                        <p limit="230">{{ $articles['5']->description }}</p>
                    </a>
                </div>
            @endif
            @if($articles['6'])
                <div class="sr-row2-r sr-a">
                    <a href="/view/article/{{ $articles['6']->id }}" target="_blank">
                        <img src="{{ $articles['6']->image_4_3 }}" width="340" height="255" alt="{{ $articles['6']->seo_keywords }}">
                        <h2 limit="22">{{ $articles['6']->title }}</h2>
                        <p limit="240">{{ $articles['6']->description }}</p>
                    </a>
                </div>
            @endif
        </div>
        <div class="sr-row3">
            <div class="sr-row3-l">
                @if($articles['7'])
                    <div class="sr-a  article-info8">
                        <a href="/view/article/{{ $articles['7']->id }}" target="_blank">
                            <div class="a-image"><img src="{{ $articles['7']->image_4_3 }}" width="540" height="405" alt="{{ $articles['7']->seo_keywords }}"></div>
                            <div class="a-content">
                                <h2 limit="40">{{ $articles['7']->title }}</h2>
                                <p limit="100">{{ $articles['7']->description }}</p>
                            </div>
                        </a>
                    </div>
                @endif
                @if($articles['10'])
                    <div class="sr-a  article-info11">
                        <a href="/view/article/{{ $articles['10']->id }}" target="_blank">
                            <img class="a-image" src="{{ $articles['10']->image_4_3 }}" width="280" height="210" alt="{{ $articles['10']->seo_keywords }}">
                            <h2 limit="20">{{ $articles['10']->title }}</h2>
                            <p limit="260">{{ $articles['10']->description }}</p>
                        </a>
                    </div>
                @endif
                @if($articles['11'])
                    <div class="sr-a  article-info12">
                        <a href="/view/article/{{ $articles['11']->id }}" target="_blank">
                            <div class="a-image"><img src="{{ $articles['11']->image_4_3 }}" width="340" height="255" alt="{{ $articles['11']->seo_keywords }}"></div>
                            <div class="a-content">
                                <h2 limit="12">{{ $articles['11']->title }}</h2>
                                <p limit="80">{{ $articles['11']->description }}</p>
                            </div>
                        </a>
                    </div>
                @endif
            </div>
            <div class="sr-row3-r">
                @if($articles['8'])
                    <div class="sr-a  article-info9">
                        <a href="/view/article/{{ $articles['8']->id }}" target="_blank">
                            <div class="a-image"><img src="{{ $articles['8']->image_4_3 }}" width="360" height="270" alt="{{ $articles['8']->seo_keywords }}"></div>
                            <div class="a-content">
                                <h2 limit="24">{{ $articles['8']->title }}</h2>
                                <p limit="150">{{ $articles['8']->description }}</p>
                            </div>
                        </a>
                    </div>
                @endif
                @if($articles['9'])
                    <div class="sr-a article-info10">
                        <a href="/view/article/{{ $articles['9']->id }}" target="_blank">
                            <img class="a-image" src="{{ $articles['9']->image_4_3 }}" width="360" height="270" alt="{{ $articles['9']->seo_keywords }}">
                            <h2 limit="24">{{ $articles['9']->title }}</h2>
                            <p limit="320">{{ $articles['9']->description }}</p>
                        </a>
                    </div>
                @endif
                @if($articles['12'])
                    <div class="sr-a article-info13">
                        <a href="/view/article/{{ $articles['12']->id }}" target="_blank">
                            <div class="a-image"><img src="{{ $articles['12']->image_4_3 }}" width="360" height="270" alt="{{ $articles['12']->seo_keywords }}"></div>
                            <div class="a-content">
                                <h2 limit="28">{{ $articles['12']->title }}</h2>
                                <p limit="110">{{ $articles['12']->description }}</p>
                            </div>
                        </a>
                    </div>
                @endif
                @if($articles['13'])
                    <div class="sr-a article-info14">
                        <a href="/view/article/{{ $articles['13']->id }}" target="_blank">
                            <div class="a-image"><img src="{{ $articles['13']->image_4_3 }}" width="290" height="215" alt="{{ $articles['13']->seo_keywords }}"></div>
                            <div class="a-content">
                                <h2 limit="22">{{ $articles['13']->title }}</h2>
                                <p limit="130">{{ $articles['13']->description }}</p>
                            </div>
                        </a>
                    </div>
                @endif
            </div>    
        </div>

        <!--搜索结果分页-->
        <div class="s-page">
            <ul class="pagination">
                <li class="previous {{ ($articles->currentPage() == 1) ? ' disabled' : '' }}">
                    <a href="@if($articles->currentPage() == 1){{ 'javascript:void(0);' }}@else{{ $articles->url(1) }}@endif"><i class="fa fa-angle-double-left"></i></a>
                </li>
                @if( $articles->currentPage()<6 )
                    <?php $lastPage = ($articles->lastPage()<=5) ? $articles->lastPage() : 5 ;?>
                    @for($i = 1; $i <= $lastPage; $i++)
                        <li class="{{ ($articles->currentPage() == $i) ? ' active' : '' }}">
                            <a href="{{ $articles->url($i) }}">{{ $i }}</a>
                        </li>
                    @endfor
                    @if($articles->lastPage()>=6) <li><a>...</a></li> @endif
                @else
                    <li class=""><a href="{{ $articles->url(1) }}">1</a></li>
                    @if($articles->currentPage() > ($adjacents + 2))
                         <li class=""><a href="">...</a></li>
                    @endif
                    <?php $pmin = ($articles->currentPage() > $adjacents) ? ($articles->currentPage() - $adjacents) : 1; ?>
                    <?php $pmax = ($articles->currentPage() < ($articles->lastPage() - $adjacents)) ? ($articles->currentPage() + $adjacents) : $articles->lastPage(); ?>
                    @for ($i = $pmin; $i <= $pmax; $i++)
                         <li class="{{ ($articles->currentPage() == $i) ? ' active' : '' }}">
                            <a href="{{ $articles->url($i) }}">{{ $i }}</a>
                        </li>
                    @endfor

                    @if($articles->currentPage() < ($articles->lastPage() - $adjacents - 1))
                        <li><a href="">...</a></li>
                    @endif

                    @if ($articles->currentPage() < ($articles->lastPage() - $adjacents))
                        <li class=""><a href="{{ $articles->url($articles->lastPage()) }}">{{ $articles->lastPage() }}</a></li>
                    @endif
                @endif
                @if($articles->currentPage() == $articles->lastPage())
                    <li class="next disabled">
                        <a href="javascript:void(0);">
                @else
                    <li class="next">
                        <a href="{{ $articles->nextPageUrl() }}">
                @endif
                        <i class="fa fa-angle-double-right"></i>
                    </a>
                </li>
                <li><span class="goPageBox">跳转至<span><input id="btn_go_input"  class="form-control" value="" type="text"></span>页<button type="button" class="btn btn-default" id="goPage">Go</button></span></li>
            </ul>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function(){
            $('#goPage').click(function(){
                var page = $('#btn_go_input').val();
                var url = "/content/searchbytag/{{ $s_tag }}?page=";
                var lastPage = {{ $articles->lastPage() }};
                if(!isNaN(page)){
                    if(page>lastPage) page=lastPage;
                    if(page<1) page=1;
                    location.href = url+page;
                } else {
                    alert('请输入要正确的跳转页数!');
                }
            });

            $.fn.limit = function () {
                var s_tag = '{{$s_tag}}';
                var s_tag_l = s_tag.length;
                var self_p = $("p[limit]");
                var self_h = $("h2[limit]");
                self_p.each(function () {
                    var obj = $.trim($(this).text());
                    var limit = $(this).attr("limit");//限制字符串的数量
                    var s_tag_start = obj.indexOf(s_tag);//查找关键字第一次在文章的位置
                    if(s_tag_start>6) {
                        if(s_tag_start>limit){
                            //当关键字出现在文章限制字符之后位置时，从关键字开始往前截取
                            var pos = s_tag_start+s_tag_l-limit;
                            var objString = obj.substring(pos);
                            //console.log(objString);
                        } else {
                            var objString = obj.substring(s_tag_start);
                        }
                    } else {
                        var objString = obj;
                    }
                    var objLength = objString.length;
                    if(objLength > limit) {
                        var strStart = objString.substring(0,limit);
                        var strEnd = objString.substring(limit,objLength);
                        $(this).html(strStart+"...<br/><br/><br/>"+strEnd);
                    }
                })
                self_h.each(function(){
                    var objLength = $(this).text().length;
                    var num = $(this).attr("limit");
                    if(objLength > num) {
                        $(this).css('height','55px');
                    }
                })
            };
            $("[limit]").limit();
        });
    </script>
@endsection
