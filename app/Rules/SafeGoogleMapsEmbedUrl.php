<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class SafeGoogleMapsEmbedUrl implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value !== null && $value !== '' && ! self::isValid($value)) {
            $fail('Gunakan URL embed resmi Google Maps atau kosongkan kolom ini.');
        }
    }

    public static function isValid(mixed $value): bool
    {
        if (! is_string($value) || preg_match('/[\x00-\x20\\\\]/', $value)) {
            return false;
        }

        $parts = parse_url($value);

        return strtolower($parts['scheme'] ?? '') === 'https'
            && strtolower($parts['host'] ?? '') === 'www.google.com'
            && in_array($parts['path'] ?? '', ['/maps/embed', '/maps/embed/'], true)
            && ! isset($parts['user'])
            && ! isset($parts['pass'])
            && ! isset($parts['port'])
            && filter_var($value, FILTER_VALIDATE_URL) !== false;
    }
}
