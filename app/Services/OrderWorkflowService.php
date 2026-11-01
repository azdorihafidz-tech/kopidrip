<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\ShippingStatus;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Support\Money;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderWorkflowService
{
    public function setShippingQuote(Order $order, int $shippingMinor, string $courier, string $service): Order
    {
        if ($shippingMinor < 0 || $shippingMinor > 999999999999) {
            throw ValidationException::withMessages(['shipping_cost' => 'Biaya kirim tidak valid.']);
        }

        return DB::transaction(function () use ($order, $shippingMinor, $courier, $service): Order {
            $order = Order::query()->lockForUpdate()->findOrFail($order->id);
            if ($order->status === OrderStatus::Cancelled || $order->payment_status === PaymentStatus::Paid) {
                throw ValidationException::withMessages(['order' => 'Pesanan batal atau sudah dibayar tidak dapat diubah ongkirnya.']);
            }

            $order->shipping_cost = Money::toDecimal($shippingMinor);
            $order->total = Money::toDecimal($order->subtotal_minor + $shippingMinor - Money::toMinor((string) $order->discount));
            $order->courier = $courier;
            $order->shipping_service = $service;
            $order->shipping_status = ShippingStatus::Quoted;
            $order->save();

            return $order;
        }, 3);
    }

    public function transition(Order $order, OrderStatus $next, ?string $trackingNumber = null): Order
    {
        return DB::transaction(function () use ($order, $next, $trackingNumber): Order {
            $order = Order::query()->with('items')->lockForUpdate()->findOrFail($order->id);
            $allowed = $order->status->nextStatuses($order->payment_status);

            if (! in_array($next, $allowed, true)) {
                throw ValidationException::withMessages(['status' => 'Perubahan status pesanan tidak diizinkan.']);
            }

            if ($next === OrderStatus::Confirmed && $order->shipping_cost === null) {
                throw ValidationException::withMessages(['status' => 'Tetapkan ongkir sebelum mengonfirmasi pesanan.']);
            }

            if ($next === OrderStatus::Cancelled && $order->payment_status === PaymentStatus::Paid) {
                throw ValidationException::withMessages(['status' => 'Pesanan yang sudah dibayar harus melalui proses refund.']);
            }

            if ($next === OrderStatus::Cancelled && $order->stock_reserved) {
                foreach ($order->items as $item) {
                    if ($item->product_variant_id) {
                        ProductVariant::query()->whereKey($item->product_variant_id)->increment('stock', $item->quantity);
                    } elseif ($item->product_id) {
                        Product::query()->whereKey($item->product_id)->increment('stock', $item->quantity);
                    }
                }
                $order->stock_reserved = false;
                $order->cancelled_at = now();
                $order->shipping_status = ShippingStatus::Cancelled;
            }

            if ($next === OrderStatus::Confirmed) {
                $order->confirmed_at = now();
            }
            if ($next === OrderStatus::Shipped) {
                $order->shipped_at = now();
                $order->shipping_status = ShippingStatus::Shipped;
                $order->tracking_number = $trackingNumber;
            }
            if ($next === OrderStatus::Completed) {
                $order->completed_at = now();
                $order->shipping_status = ShippingStatus::Delivered;
            }
            $order->status = $next;
            $order->save();

            return $order;
        }, 3);
    }
}
