<?php

namespace App\Support;

use InvalidArgumentException;

final class Money
{
    public static function toMinor(string|int $amount): int
    {
        $amount = trim((string) $amount);

        if (! preg_match('/^(-?)(\d{1,10})(?:\.(\d{1,2}))?$/', $amount, $matches)) {
            throw new InvalidArgumentException('Amount must be a decimal value with at most two fractional digits.');
        }

        $minor = ((int) $matches[2] * 100) + (int) str_pad($matches[3] ?? '', 2, '0');

        return ($matches[1] ?? '') === '-' ? -$minor : $minor;
    }

    public static function toDecimal(int $minor): string
    {
        $negative = $minor < 0;
        $minor = abs($minor);
        $amount = intdiv($minor, 100).'.'.str_pad((string) ($minor % 100), 2, '0', STR_PAD_LEFT);

        return $negative ? '-'.$amount : $amount;
    }

    public static function formatIdr(int $minor): string
    {
        $negative = $minor < 0 ? '-' : '';
        $minor = abs($minor);
        $whole = number_format(intdiv($minor, 100), 0, ',', '.');
        $fraction = $minor % 100;

        return 'Rp '.$negative.$whole.($fraction === 0 ? '' : ','.str_pad((string) $fraction, 2, '0', STR_PAD_LEFT));
    }
}
