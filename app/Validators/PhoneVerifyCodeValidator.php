<?php

/*
 * This file is part of the huaxin/wechat.
 *
 * (c) viphuaxin.com <it@viphuaxin.com>
 */

namespace App\Validators;

use App\Services\VerificationCode;

/**
 * Class PhoneVerifyCodeValidator.
 */
class PhoneVerifyCodeValidator
{
    public function validate($attribute, $value, $parameters, $validator)
    {
        $phone = request($parameters[0] ?? 'verify_phone');

        if (\is_numeric($parameters[0])) {
            $phone = $parameters[0];
        }

        \Log::debug('phone verify: ', [$parameters, $phone]);

        if (resolve(VerificationCode::class)->validate($phone, $value)) {
            request()->merge(['phone_verified' => true]);

            return true;
        }

        return false;
    }
}
