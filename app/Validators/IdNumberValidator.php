<?php

/*
 * This file is part of the huaxin/wechat.
 *
 * (c) viphuaxin.com <it@viphuaxin.com>
 */

namespace App\Validators;

/**
 * Class IdNumberValidator.
 */
class IdNumberValidator
{
    public function validate($attribute, $value, $parameters, $validator)
    {
        return preg_match('/^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$|^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}([0-9]|[Xx])$/', $value);
    }
}
