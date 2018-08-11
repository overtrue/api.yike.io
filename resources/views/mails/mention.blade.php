@component('mail::message')

[{{ '@' . $causer->username }}]({{ $causer->url }})  在 [《{{ $content->contentable->commentable->title }}》]({{ $content->contentable->commentable->url }}) 的评论中提及了您：

@component('mail::blockquote')
{!! $content->activity_log_content !!}
@endcomponent

Thanks.

{{ config('app.name') }}
@endcomponent
