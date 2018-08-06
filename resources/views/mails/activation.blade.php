@component('mail::message')

请点击此按钮以激活您的账户：

@component('mail::button', ['url' => $user->getActivationLink()])
    立即激活
@endcomponent

<small>本邮件有效期为 60 天，请在过期前激活。</small>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
