<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class MobileRule implements Rule
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
        return preg_match('/^\+[1-9]\d{10,14}$/', $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __("validation.mobile", ['attribute' => __("validation.attributes.mobile")]);
    }
}
