<?php

/*
 * This file is part of the huaxin/wechat.
 *
 * (c) viphuaxin.com <it@viphuaxin.com>
 */

namespace App\Validators;

/**
 * Class TicketValidator.
 */
class TicketValidator
{
    public function validate($attribute, $value, $parameters, $validator)
    {
        if (auth()->check() && \auth()->user()->is_admin) {
            return true;
        }

        $config = config('services.captcha.'.$parameters[0]);
        $query = [
            'aid' => $config['aid'],
            'AppSecretKey' => $config['secret'],
            'Ticket' => $value,
            'Randstr' => \request('randstr'),
            'UserIP' => \request()->ip(),
        ];

        $result = \file_get_contents('https://ssl.captcha.qq.com/ticket/verify?'.\http_build_query($query));

        $result = \json_decode($result, true);

        if (!$passed = ($result['response'] ?? 0) == 1) {
            \Log::error('Captcha verified fail:', \compact('query', 'result'));
        }

        return $passed;
    }
}
