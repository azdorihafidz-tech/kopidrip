<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\ShippingStatus;
use App\Support\Money;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'customer_id', 'order_number', 'access_token_hash', 'customer_name', 'customer_email', 'customer_phone',
        'shipping_address', 'shipping_city', 'shipping_province', 'shipping_postal_code', 'customer_notes',
        'subtotal', 'shipping_cost', 'discount', 'total', 'currency', 'status', 'payment_status', 'shipping_status',
        'courier', 'shipping_service', 'tracking_number', 'stock_reserved', 'admin_notes', 'placed_at',
        'confirmed_at', 'shipped_at', 'completed_at', 'cancelled_at',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'shipping_cost' => 'decimal:2',
            'discount' => 'decimal:2',
            'total' => 'decimal:2',
            'stock_reserved' => 'boolean',
            'status' => OrderStatus::class,
            'payment_status' => PaymentStatus::class,
            'shipping_status' => ShippingStatus::class,
            'placed_at' => 'datetime',
            'confirmed_at' => 'datetime',
            'shipped_at' => 'datetime',
            'completed_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function scopeLatestFirst(Builder $query): Builder
    {
        return $query->orderByDesc('created_at');
    }

    public function getSubtotalMinorAttribute(): int
    {
        return Money::toMinor((string) $this->subtotal);
    }

    public function getTotalMinorAttribute(): ?int
    {
        return $this->total === null ? null : Money::toMinor((string) $this->total);
    }
}
