@component('mail::message')

[{{ '@' . $comment->user->username }}]({{ $comment->user->url }})  评论了您的文章 [《{{ $comment->commentable->title }}》]({{ $comment->commentable->url .'#comment-'.$comment->id}}) ：

@component('mail::blockquote')
 {!! $comment->content->activity_log_content !!}
@endcomponent


Thanks.

{{ config('app.name') }}
@endcomponent
