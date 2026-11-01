<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class SafeNavigationUrl implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! self::isValid($value)) {
            $fail('Masukkan tautan internal yang diawali / atau URL eksternal HTTPS yang valid.');
        }
    }

    public static function isValid(mixed $value): bool
    {
        if (! is_string($value) || $value === '' || preg_match('/[\x00-\x20\\\\]/', $value)) {
            return false;
        }

        if (preg_match('/^#[A-Za-z][A-Za-z0-9_-]{0,100}$/', $value)) {
            return true;
        }

        $parts = parse_url($value);

        if ($parts === false) {
            return false;
        }

        if (str_starts_with($value, '/')) {
            $path = $parts['path'] ?? '';

            return ! str_starts_with($value, '//')
                && ! isset($parts['scheme'], $parts['host'])
                && $path !== ''
                && ! preg_match('#(?:^|/)\.\.?(/|$)#', $path)
                && ! preg_match('#^/admin(?:/|$)#i', $path);
        }

        return strtolower($parts['scheme'] ?? '') === 'https'
            && filled($parts['host'] ?? null)
            && ! isset($parts['user'])
            && ! isset($parts['pass'])
            && filter_var($value, FILTER_VALIDATE_URL) !== false;
    }
}
