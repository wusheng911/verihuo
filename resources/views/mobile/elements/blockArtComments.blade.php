@include('mobile.elements.blockHeaderInArticle', ['blockHeader' => '热门评论'])

<div id="add_art_comment" class="row collapse columns">
    <div class="small-9 columns" style="height:4.375rem;">        
        <textarea placeholder="我来说两句..." name="content" style="height:100%;width:100%;"></textarea>
    </div>
  <div class="small-3 columns" style="height:4.375rem;">
        <a href="javascript:;" class="button chm-btn-artcomment-pl chm-btn-artcomment-newpl" data-article="{{$article_id}}">发表评论</a>
    </div>
</div>

<hr class="chm-divider-transparent-4"/>


<div class="row">
    <div class="small-12 columns">
        <div class="chm-artcomment-rec {{isset($comments) && count($comments)>0?"":"chm-hide"}}">
            <div id="art_comments">
                @if (isset($comments) && is_array($comments)) 
                    @foreach ($comments as $comment)
                        @include('mobile.elements.artComment', ['comment' => $comment])
                    @endforeach

                @endif
            </div>
            @if(isset($comments) && count($comments) > 0 && 
            !(isset(end($comments)['is_last']) && end($comments)['is_last']))
                <div class="chm-artloadcomments-container">
                    <a id="artLoadComments" href="javascript:;" data-article="{{$article_id}}">查看更多评论</a>
                </div>
            @endif

        </div>
    </div>
</div>

