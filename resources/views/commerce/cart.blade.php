@extends('layouts.public')
@section('seo_title', 'Keranjang')
@section('seo_description', 'Periksa keranjang belanja Kopi Drip Sidikalang.')
@section('seo_robots', 'noindex,nofollow')
@section('content')
<section class="section-space"><div class="site-container max-w-4xl">
    <p class="eyebrow">Belanja</p><h1 class="mt-3 font-display text-4xl">Keranjang</h1>
    @if ($cart['items'] === [])
        <p class="mt-8">Keranjang Anda masih kosong.</p><a class="button button-dark mt-5" href="{{ route('products.index') }}">Lihat produk</a>
    @else
        <div class="mt-8 divide-y divide-ink/10 border-y border-ink/10">
            @foreach ($cart['items'] as $line)
                <div class="flex flex-wrap items-center justify-between gap-5 py-5">
                    <div><h2 class="font-semibold">{{ $line['product']?->name ?? 'Produk tidak tersedia' }}</h2>
                        @if ($line['variant'])<p class="text-sm text-muted">{{ $line['variant']->attribute_name }}: {{ $line['variant']->name }}</p>@endif
                        @if ($line['available'])<p class="mt-1 text-sm">{{ \App\Support\Money::formatIdr($line['price_minor']) }} / item</p>@else<p role="alert" class="mt-1 text-sm text-red-700">{{ $line['error'] }}</p>@endif
                    </div>
                    @if ($line['available'])
                        <div class="flex items-center gap-4"><form class="flex items-center gap-2" method="POST" action="{{ route('cart.update') }}">@csrf @method('PATCH')
                            <input type="hidden" name="product_id" value="{{ $line['product_id'] }}"><input type="hidden" name="variant_id" value="{{ $line['variant_id'] }}">
                            <label class="sr-only" for="quantity-{{ $loop->index }}">Jumlah {{ $line['product']->name }}</label><input class="form-input w-20" id="quantity-{{ $loop->index }}" type="number" name="quantity" min="1" max="20" value="{{ $line['quantity'] }}">
                            <button class="text-link" type="submit">Perbarui</button></form>
                            <span class="font-semibold">{{ \App\Support\Money::formatIdr($line['line_total_minor']) }}</span>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('cart.destroy') }}">@csrf @method('DELETE')<input type="hidden" name="product_id" value="{{ $line['product_id'] }}"><input type="hidden" name="variant_id" value="{{ $line['variant_id'] }}"><input type="hidden" name="quantity" value="1"><button class="text-link" type="submit">Hapus</button></form>
                </div>
            @endforeach
        </div>
        <div class="mt-7 flex flex-wrap items-center justify-between gap-5"><p>Subtotal <strong>{{ \App\Support\Money::formatIdr($cart['subtotal_minor']) }}</strong><br><span class="text-sm text-muted">Ongkir akan dikonfirmasi admin sebelum pembayaran.</span></p>
            <div class="flex gap-3"><form method="POST" action="{{ route('cart.clear') }}">@csrf @method('DELETE')<button class="text-link" type="submit">Kosongkan</button></form>@if ($cart['can_checkout'])<a class="button button-dark" href="{{ route('checkout.create') }}">Lanjut checkout</a>@endif</div>
        </div>
    @endif
</div></section>
@endsection
