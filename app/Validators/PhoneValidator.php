<?php

/*
 * This file is part of the huaxin/wechat.
 *
 * (c) viphuaxin.com <it@viphuaxin.com>
 */

namespace App\Validators;

/**
 * Class PhoneValidator.
 */
class PhoneValidator
{
    public function validate($attribute, $value, $parameters, $validator)
    {
        return preg_match('/^1(3[0-9]|4[57]|5[0-35-9]|6[6]|7[0135678]|8[0-9])\d{8}$/', $value);
    }
}
