<?php

namespace App\Validators;

/**
 * Class UsernameValidator.
 */
class UsernameValidator
{
    public function validate($attribute, $value, $parameters, $validator)
    {
        return \preg_match('/^[a-zA-Z]+[a-zA-Z0-9\-]+$/', $value);
    }
}
