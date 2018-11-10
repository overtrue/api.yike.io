<?php

namespace App\Validators;

/**
 * Class UserUniqueContentValidator.
 *
 * @author overtrue <i@overtrue.me>
 */
class UserUniqueContentValidator
{
    public function validate($attribute, $value, $parameters, $validator)
    {
        \Log::info($value, $parameters);

        return !\request()->user()->{$parameters[0]}()
            ->where($parameters[1] ?? $attribute, $value)
            ->where('id', '!=', $parameters[2] ?? 0)
            ->exists();
    }
}
