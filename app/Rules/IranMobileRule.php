<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\Rule;

class IranMobileRule implements Rule
{
    /** 
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */

    public function passes($attribute, $value)
    {
        return (bool)preg_match('/^(((98)|(\+98)|(0098)|0)(9){1}[0-9]{9})+$/', $value) || (bool)preg_match('/^(9){1}[0-9]{9}+$/', $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return ':attribute is not a valid mobile number.';
    }
}
