<?php

namespace App\Rules;

use App\Support\BrandColorContrast;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class AccessibleBrandColor implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! BrandColorContrast::isAccessible($value)) {
            $fail('Gunakan warna HEX enam digit yang memiliki kontras memadai dengan latar terang.');
        }
    }
}
