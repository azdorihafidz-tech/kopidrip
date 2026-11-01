<?php

namespace App\Enums;

enum OrderStatus: string
{
    case Pending = 'pending';
    case Confirmed = 'confirmed';
    case Processing = 'processing';
    case Shipped = 'shipped';
    case Completed = 'completed';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Menunggu konfirmasi',
            self::Confirmed => 'Dikonfirmasi',
            self::Processing => 'Diproses',
            self::Shipped => 'Dikirim',
            self::Completed => 'Selesai',
            self::Cancelled => 'Dibatalkan',
        };
    }

    /** @return list<self> */
    public function nextStatuses(PaymentStatus $paymentStatus): array
    {
        $next = match ($this) {
            self::Pending => [self::Confirmed, self::Cancelled],
            self::Confirmed => [self::Processing, self::Cancelled],
            self::Processing => [self::Shipped, self::Cancelled],
            self::Shipped => [self::Completed],
            default => [],
        };

        if ($paymentStatus === PaymentStatus::Paid) {
            return array_values(array_filter($next, fn (self $status) => $status !== self::Cancelled));
        }

        return array_values(array_filter($next, fn (self $status) => ! in_array($status, [self::Processing, self::Shipped, self::Completed], true)));
    }
}
