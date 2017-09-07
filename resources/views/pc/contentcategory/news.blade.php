@extends('layouts.app')

@section('title')
@endsection

@section('keywords')
@endsection
@section('description')
@endsection
@section('scripts')
@endsection

@section('content')
@include('pc.nav',['id'=>$id])
<div class="container" style="width:1300px; margin-top:15px;">

    <div class="row">
        @if(!empty($categoryLevel))
            @if($categoryLevel == 1)
            <div class="news-one-ad-top">
                    @foreach ($contentCategoryTops as $adposition)
                        @if ($adposition['adposition_code'] == "PC|News|A1")
                            @include('nodes.index', ['position' => $adposition['adposition_code']])
                        @endif
                        @if ($adposition['adposition_code'] == "PC|News|A2")
                            @include('nodes.index', ['position' => $adposition['adposition_code']])
                        @endif
                        @if ($adposition['adposition_code'] == "PC|News|A3")
                            @include('nodes.index', ['position' => $adposition['adposition_code']])
                        @endif
                        @if ($adposition['adposition_code'] == "PC|News|A4")
                            @include('nodes.index', ['position' => $adposition['adposition_code']])
                        @endif
                    @endforeach
            </div>
            @endif
            @if($categoryLevel == 2)
                <div class="news-two-ad-top">
                    @foreach ($contentCategoryTops as $adposition)
                        @if ($adposition['adposition_code'] == "PC|News|B1")
                            @include('nodes.index', ['position' => $adposition['adposition_code']])
                        @endif
                    @endforeach
                </div>
            @endif
        @endif
        @foreach ($topcats as $cat)
            @include('pc.news', ['category' => $cat])
        @endforeach
    </div>
    @if(isset($articles))
        <div class="row">
            <div id="display-container" class="q-display-container f-s-0 p-r">
            @foreach ($articles as $article)
                    <div id="display-item" class="display-item d-i-b p-r">
                        <div id="display-imgdiv" class="imgdiv">
                            <a href="/view/article/{{ $article->id }}" target="_blank" title=""><img src="{{ $article->image_4_3 }}" alt="{{ $article->seo_keywords }}" width="240px" height="180"></a>
                        </div>
                        <div id="display-data" class="display-data p-r">
                            <div id="data-titlediv" class="data-titlediv t-a-l o-h">
                                <a href="/view/article/{{ $article->id }}">{{ $article->title }}</a>
                            </div>
                            <div id="data-datediv" class="data-datediv t-a-l o-h">
                                <span>{{ $article->post_at }}</span>
                            </div>
                            <div id="data-otherdiv" class="data-otherdiv t-a-l">
                                <span class="span">{{ $article->view_count }}</span>
                                <span>人气 / </span>
                                <span class="span">{{ $article->comment }}</span>
                                <span>评论 / </span>
                                <span class="span">{{ $article->recommend }}</span>
                                <span>推荐</span>
                            </div>
                        </div>
                    </div>
            @endforeach
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
        <script type="text/javascript">
            $(document).ready(function(){
                $('#goPage').click(function(){
                    var page = $('#btn_go_input').val();
                    var url = "/news/{{ $id }}?page=";
                    var lastPage = {{ $articles->lastPage() }};
                    if(!isNaN(page)){
                        if(page>lastPage) page=lastPage;
                        if(page<1) page=1;
                        location.href = url+page;
                    } else {
                        alert('请输入要正确的跳转页数!');
                    }
                });
            });
        </script>
    @endif
</div>
@endsection
