<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Support\Money;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class CartService
{
    private const SESSION_KEY = 'commerce.cart';

    public function add(int $productId, ?int $variantId, int $quantity): void
    {
        $line = $this->resolvePurchasable($productId, $variantId);
        $cart = $this->rawItems();
        $key = $this->key($productId, $variantId);
        $nextQuantity = ($cart[$key]['quantity'] ?? 0) + $quantity;

        if (! isset($cart[$key]) && count($cart) >= 50 || $nextQuantity > 20) {
            throw ValidationException::withMessages(['quantity' => 'Keranjang dibatasi maksimal 20 item per baris dan 50 jenis item.']);
        }

        if ($nextQuantity > $line['stock']) {
            throw ValidationException::withMessages(['quantity' => 'Jumlah melebihi stok yang tersedia.']);
        }

        $cart[$key] = ['product_id' => $productId, 'variant_id' => $variantId, 'quantity' => $nextQuantity];
        Session::put(self::SESSION_KEY, $cart);
    }

    public function update(int $productId, ?int $variantId, int $quantity): void
    {
        $line = $this->resolvePurchasable($productId, $variantId);

        if ($quantity > $line['stock']) {
            throw ValidationException::withMessages(['quantity' => 'Jumlah melebihi stok yang tersedia.']);
        }

        $cart = $this->rawItems();
        $key = $this->key($productId, $variantId);

        if (! isset($cart[$key])) {
            throw ValidationException::withMessages(['cart' => 'Item tidak ditemukan di keranjang.']);
        }

        $cart[$key]['quantity'] = $quantity;
        Session::put(self::SESSION_KEY, $cart);
    }

    public function remove(int $productId, ?int $variantId): void
    {
        $cart = $this->rawItems();
        unset($cart[$this->key($productId, $variantId)]);
        Session::put(self::SESSION_KEY, $cart);
    }

    public function clear(): void
    {
        Session::forget(self::SESSION_KEY);
    }

    public function quantity(): int
    {
        return array_sum(array_map(fn (array $line): int => max(0, (int) ($line['quantity'] ?? 0)), $this->rawItems()));
    }

    public function summary(): array
    {
        $raw = $this->rawItems();
        $productIds = collect($raw)->pluck('product_id')->filter()->unique()->all();
        $variantIds = collect($raw)->pluck('variant_id')->filter()->unique()->all();
        $products = Product::query()->whereIn('id', $productIds)
            ->with(['images', 'primaryImage', 'variants' => fn ($query) => $query->where('is_active', true)])
            ->get()->keyBy('id');
        $variants = ProductVariant::query()->whereIn('id', $variantIds)->where('is_active', true)->get()->keyBy('id');
        $lines = [];
        $subtotalMinor = 0;
        $hasUnavailable = false;

        foreach ($raw as $key => $stored) {
            $productId = (int) ($stored['product_id'] ?? 0);
            $variantId = isset($stored['variant_id']) ? (int) $stored['variant_id'] : null;
            $quantity = (int) ($stored['quantity'] ?? 0);
            $product = $products->get($productId);
            $variant = $variantId ? $variants->get($variantId) : null;
            $error = $this->unavailableReason($product, $variant, $variantId);
            $priceMinor = null;
            $stock = 0;

            if ($error === null) {
                $price = $variant?->price ?? $product->price;

                if ($price === null) {
                    $error = 'Harga belum tersedia.';
                } else {
                    $priceMinor = Money::toMinor((string) $price);
                    $stock = $variant?->stock ?? $product->stock;

                    if ($quantity < 1 || $quantity > $stock) {
                        $error = 'Jumlah item melebihi stok terkini.';
                    }
                }
            }

            $lineTotal = $error === null ? $priceMinor * $quantity : null;
            $hasUnavailable = $hasUnavailable || $error !== null;
            $subtotalMinor += $lineTotal ?? 0;
            $lines[] = [
                'key' => $key,
                'product_id' => $productId,
                'variant_id' => $variantId,
                'product' => $product,
                'variant' => $variant,
                'quantity' => $quantity,
                'price_minor' => $priceMinor,
                'line_total_minor' => $lineTotal,
                'stock' => $stock,
                'available' => $error === null,
                'error' => $error,
            ];
        }

        return [
            'items' => $lines,
            'quantity' => $this->quantity(),
            'subtotal_minor' => $subtotalMinor,
            'can_checkout' => $lines !== [] && ! $hasUnavailable,
        ];
    }

    public function assertCheckoutReady(): array
    {
        $summary = $this->summary();

        if ($summary['items'] === []) {
            throw ValidationException::withMessages(['cart' => 'Keranjang masih kosong.']);
        }

        if (! $summary['can_checkout']) {
            throw ValidationException::withMessages(['cart' => 'Periksa item yang tidak tersedia atau jumlah yang melebihi stok.']);
        }

        return $summary;
    }

    private function resolvePurchasable(int $productId, ?int $variantId): array
    {
        $product = Product::query()->with(['variants' => fn ($query) => $query->where('is_active', true)])->find($productId);
        $variant = $variantId ? ProductVariant::query()->where('product_id', $productId)->where('is_active', true)->find($variantId) : null;
        $error = $this->unavailableReason($product, $variant, $variantId);

        if ($error !== null) {
            throw ValidationException::withMessages(['product_id' => $error]);
        }

        $price = $variant?->price ?? $product->price;

        if ($price === null) {
            throw ValidationException::withMessages(['product_id' => 'Harga produk belum tersedia.']);
        }

        return ['stock' => $variant?->stock ?? $product->stock, 'price_minor' => Money::toMinor((string) $price)];
    }

    private function unavailableReason(?Product $product, ?ProductVariant $variant, ?int $variantId): ?string
    {
        if (! $product || $product->status !== 'published' || ($product->published_at && $product->published_at->isFuture())) {
            return 'Produk sudah tidak tersedia.';
        }

        if ($product->is_demo) {
            return 'Produk ini belum tersedia untuk dipesan.';
        }

        if ($variantId && (! $variant || (int) $variant->product_id !== (int) $product->id)) {
            return 'Varian produk sudah tidak tersedia.';
        }

        if (! $variantId && $product->variants->isNotEmpty()) {
            return 'Pilih salah satu varian produk terlebih dahulu.';
        }

        return null;
    }

    private function rawItems(): array
    {
        $cart = Session::get(self::SESSION_KEY, []);

        return is_array($cart) ? $cart : [];
    }

    private function key(int $productId, ?int $variantId): string
    {
        return $productId.':'.($variantId ?? 0);
    }
}
