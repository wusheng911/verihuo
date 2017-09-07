<div class="chm-artcomment-container {{(isset($comment['is_last']) && $comment['is_last'])?'chm-lastartcomment-container':''}}">
    <div class="chm-username-container">
        <span>{{$comment['user_name']}}</span> 说:
    </div>
    <!-- parent comments -->
    @if (isset($comment['parent']))
        @include('mobile.elements.articleParentComment', ['comment' => $comment['parent']])
    @endif 
    <div>
        <p class="chm-comment-body">{{$comment['body']}}</p>
    </div>
    <div>
        <span>{{$comment['created_at']}}</span>
        <div class="chm-artcomment-hf-container" style="{{(Session::has('Customer') && (Session::get('Customer.id') == $comment['user_id']))?'display:none;':''}}">
            <a class="chm-artcomment-thumbup" href="javascript:;" data-comment="{{$comment['id']}}">
                <i class="fa fa-thumbs-o-up"></i>
                <span>{{(isset($comment['votes']) && intval($comment['votes']) > 0)?$comment['votes']:''}}</span>
            </a>
            <a class="chm-artcomment-hf" href="javascript:;">回复</a>
        </div>
    </div>                    
    <div class="chm-artcomment-hfbox-container">
        <div>
            <textarea placeholder="我来说两句..." name="content" style="height:100%;width:100%;"></textarea>
        </div>
        <div style="height:2.6rem;line-height:2.6rem;">
            <!-- <span style="float:left;">王老五</span> -->
            <a href="javascript:;" class="button chm-btn-artcomment-pl chm-btn-artcomment-hf" 
                data-comment="{{$comment['id']}}" data-article="{{$comment['content_id']}}">发表评论</a>
        </div>
    </div>
</div>
