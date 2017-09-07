<div class="chm-parentartcomment-container">
    @if (isset($comment['parent']))
        @include('mobile.elements.articleParentComment', ['comment' => $comment['parent']])
    @endif  
    <div class="chm-username-container">
        <span>{{$comment['user_name']}}</span> 说:
    </div>      
    <div>
        <p class="chm-parentartcomment-body">{{$comment['body']}}</p>
    </div>
</div>