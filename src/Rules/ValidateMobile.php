<?php

namespace Rayiumir\Vordia\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class ValidateMobile implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param Closure(string, ?string=): PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        preg_match('/^09[0-9]{9}$/', $value, $matches);

        if (!$matches) {
            $fail('validation.mobile')->translate();
        }
    }
}
