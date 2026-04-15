<?php

namespace App\Rules;

use App\Support\Layouts\LayoutGrid;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidLayoutGrid implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        foreach (LayoutGrid::validationErrors($value) as $error) {
            $fail($error);
        }
    }
}
