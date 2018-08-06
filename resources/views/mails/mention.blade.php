@component('mail::message')

    <a href="{{ $causer->url }}">@{{ $causer->username }}</a> 在《{{ $content->contentable->title }}》的评论中提及了您：

    <quote>
        {{ $content }}
    </quote>

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
