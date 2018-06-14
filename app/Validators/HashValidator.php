<?php

namespace App\Validators;

use Illuminate\Support\Facades\Hash;

/**
 * Class HashValidator.
 */
class HashValidator
{
    public function validate($attribute, $value, $parameters, $validator)
    {
        return Hash::check($value, $parameters[0]);
    }
}
