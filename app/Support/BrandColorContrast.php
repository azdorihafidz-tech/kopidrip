<?php

namespace App\Support;

final class BrandColorContrast
{
    public static function isAccessible(mixed $color): bool
    {
        if (! is_string($color) || ! preg_match('/^#[0-9a-fA-F]{6}$/', $color)) {
            return false;
        }

        return self::contrastRatio($color, '#f5f2e9') >= 4.5;
    }

    public static function safeColor(mixed $color, string $fallback): string
    {
        return self::isAccessible($color) ? $color : $fallback;
    }

    private static function contrastRatio(string $first, string $second): float
    {
        $luminances = [self::luminance($first), self::luminance($second)];
        rsort($luminances);

        return ($luminances[0] + 0.05) / ($luminances[1] + 0.05);
    }

    private static function luminance(string $color): float
    {
        $channels = array_map(static function (string $channel): float {
            $value = hexdec($channel) / 255;

            return $value <= 0.04045 ? $value / 12.92 : (($value + 0.055) / 1.055) ** 2.4;
        }, str_split(ltrim($color, '#'), 2));

        return 0.2126 * $channels[0] + 0.7152 * $channels[1] + 0.0722 * $channels[2];
    }
}
