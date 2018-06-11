@component('mail::message')

请点击此按钮以重置您的账户密码：

@component('mail::button', ['url' => $link])
    重置密码
@endcomponent

<small>本邮件有效期为 60 天，请在过期前激活。</small>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
