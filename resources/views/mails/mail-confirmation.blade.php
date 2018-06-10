@component('mail::message')

请点击此按钮以确定修改账户邮箱：

@component('mail::button', ['url' => $user->getUpdateMailLink($email)])
    确定修改
@endcomponent

<small>本邮件有效期为 60 天，请在过期前激活。</small>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
