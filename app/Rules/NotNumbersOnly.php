<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NotNumbersOnly implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // not numbers only
        if (preg_match('/^[0-9]+$/', $value)) {
            $fail('The :attribute must not be numbers only.');
        }
    }
}
