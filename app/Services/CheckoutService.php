<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\ShippingStatus;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Support\Money;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CheckoutService
{
    public function place(array $customerData, array $cart): array
    {
        return DB::transaction(function () use ($customerData, $cart): array {
            $lockedLines = [];
            $subtotalMinor = 0;

            foreach ($cart['items'] as $line) {
                $product = Product::query()->lockForUpdate()->find($line['product_id']);
                $variant = $line['variant_id']
                    ? ProductVariant::query()->lockForUpdate()->where('product_id', $line['product_id'])->find($line['variant_id'])
                    : null;

                if (! $product || $product->status !== 'published' || $product->is_demo || ($product->published_at && $product->published_at->isFuture())) {
                    throw ValidationException::withMessages(['cart' => 'Produk sudah tidak tersedia. Muat ulang keranjang dan coba lagi.']);
                }

                if ($line['variant_id'] && (! $variant || ! $variant->is_active)) {
                    throw ValidationException::withMessages(['cart' => 'Varian sudah tidak tersedia. Muat ulang keranjang dan coba lagi.']);
                }

                if (! $line['variant_id'] && $product->variants()->where('is_active', true)->exists()) {
                    throw ValidationException::withMessages(['cart' => 'Pilih varian produk sebelum checkout.']);
                }

                $price = $variant?->price ?? $product->price;
                $stock = $variant?->stock ?? $product->stock;

                if ($price === null || (int) $line['quantity'] < 1 || (int) $line['quantity'] > $stock) {
                    throw ValidationException::withMessages(['cart' => 'Harga atau stok berubah. Periksa keranjang dan coba lagi.']);
                }

                $priceMinor = Money::toMinor((string) $price);
                $subtotalMinor += $priceMinor * (int) $line['quantity'];
                $lockedLines[] = [$product, $variant, (int) $line['quantity'], $priceMinor];
            }

            if ($subtotalMinor > 999999999999) {
                throw ValidationException::withMessages(['cart' => 'Subtotal melebihi batas maksimum pesanan. Hubungi admin untuk bantuan.']);
            }

            $customer = Customer::query()->firstOrNew(['email' => strtolower($customerData['email'])]);
            $customer->fill([
                'name' => $customerData['name'], 'email' => strtolower($customerData['email']), 'phone' => $customerData['phone'],
                'address' => $customerData['address'], 'city' => $customerData['city'], 'province' => $customerData['province'],
                'postal_code' => $customerData['postal_code'],
            ])->save();

            $plainToken = Str::random(64);
            $order = Order::query()->create([
                'customer_id' => $customer->id,
                'order_number' => 'KD-'.now()->format('ymd').'-'.Str::upper(Str::random(8)),
                'access_token_hash' => hash('sha256', $plainToken),
                'customer_name' => $customerData['name'], 'customer_email' => strtolower($customerData['email']),
                'customer_phone' => $customerData['phone'], 'shipping_address' => $customerData['address'],
                'shipping_city' => $customerData['city'], 'shipping_province' => $customerData['province'],
                'shipping_postal_code' => $customerData['postal_code'], 'customer_notes' => $customerData['notes'] ?? null,
                'subtotal' => Money::toDecimal($subtotalMinor), 'shipping_cost' => null, 'discount' => '0.00', 'total' => null,
                'status' => OrderStatus::Pending, 'payment_status' => PaymentStatus::Unpaid,
                'shipping_status' => ShippingStatus::QuotePending, 'stock_reserved' => true, 'placed_at' => now(),
            ]);

            foreach ($lockedLines as [$product, $variant, $quantity, $priceMinor]) {
                if ($variant) {
                    $variant->decrement('stock', $quantity);
                } else {
                    $product->decrement('stock', $quantity);
                }

                OrderItem::query()->create([
                    'order_id' => $order->id, 'product_id' => $product->id, 'product_variant_id' => $variant?->id,
                    'product_name' => $product->name, 'product_sku' => $variant?->sku ?: $product->sku,
                    'variant_name' => $variant?->name, 'quantity' => $quantity,
                    'unit_price' => Money::toDecimal($priceMinor), 'line_total' => Money::toDecimal($priceMinor * $quantity),
                    'weight_grams' => $variant?->weight_grams ?? $product->weight_grams,
                ]);
            }

            return [$order, $plainToken];
        }, 3);
    }
}
