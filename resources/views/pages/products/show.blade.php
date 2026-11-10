@extends('layouts.public')

@section('seo_title', $product->seo_title ?: $product->name)
@section('seo_description', $product->seo_description ?: $product->short_description ?: 'Detail produk Kopi Drip Sidikalang.')
@section('seo_image', $product->og_image ?: $product->primary_image_path ?: '')
@section('og_type', 'product')
@section('seo_robots', $product->is_demo ? 'noindex,nofollow' : 'index,follow')

@section('content')
    @php($productWhatsappUrl = ! $product->is_demo ? $websiteSettings->whatsappUrl('Halo Kopi Drip, saya tertarik dengan '.$product->name.': '.route('products.show', $product->slug)) : null)
    <section class="section-space">
        <div class="site-container">
            <a class="text-link mb-8 inline-flex" href="{{ route('products.index') }}"><span aria-hidden="true">←</span> Semua produk</a>
            <div class="grid gap-10 lg:grid-cols-2 lg:gap-16">
                <div class="detail-image-wrap">
                    @if (filled($product->primary_image_path))
                        <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($product->primary_image_path) }}" alt="{{ $product->name }}" width="900" height="1050" fetchpriority="high">
                    @else
                        <div class="product-image-placeholder product-image-placeholder-large" role="img" aria-label="Gambar {{ $product->name }} belum tersedia"><span>{{ $product->name }}</span></div>
                    @endif
                </div>

                @if ($product->images->count() > 1)
                    <div class="mt-4 grid grid-cols-4 gap-3 lg:col-start-1">
                        @foreach ($product->images as $image)
                            <img class="aspect-square w-full object-cover" src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($image->path) }}" alt="{{ $image->alt_text ?: $product->name }}" loading="lazy">
                        @endforeach
                    </div>
                @endif

                <div class="lg:py-10">
                    <p class="eyebrow">Kopi Drip · Produk</p>
                    <h1 class="mt-4 font-display text-4xl leading-tight sm:text-5xl">{{ $product->name }}</h1>
                    @if ($product->is_demo)
                        <p class="mt-4 border-l-4 border-amber-500 bg-amber-50 p-4 text-sm leading-6 text-amber-950"><strong>Konten demonstrasi.</strong> Nama, spesifikasi, harga, dan stok ini hanya contoh dan bukan penawaran untuk dibeli.</p>
                    @endif
                    <p class="mt-5 text-lg font-medium">
                        @if ($product->is_demo)<span class="mb-1 block text-xs font-semibold uppercase tracking-wider text-amber-800">Harga demo</span>@endif
                        @if ($product->price !== null)
                            Rp {{ number_format((float) $product->price, 0, ',', '.') }}
                        @else
                            Harga belum tersedia
                        @endif
                        @if ($product->compare_price !== null && $product->price !== null && (float) $product->compare_price > (float) $product->price)
                            <span class="ml-2 text-sm text-muted line-through">Rp {{ number_format((float) $product->compare_price, 0, ',', '.') }}</span>
                        @endif
                    </p>
                    @if (filled($product->short_description))
                        <p class="mt-6 text-lg leading-8 text-muted">{{ $product->short_description }}</p>
                    @endif
                    @if (filled($product->description))
                        <div class="prose-copy mt-8">{{ $product->description }}</div>
                    @endif

                    <dl class="product-facts mt-9">
                        @if ($product->category)
                            <div><dt>Kategori</dt><dd>{{ $product->category->name }}</dd></div>
                        @endif
                        @if (filled($product->sku))
                            <div><dt>SKU</dt><dd>{{ $product->sku }}</dd></div>
                        @endif
                        @if ($product->weight_grams)
                            <div><dt>Berat</dt><dd>{{ number_format($product->weight_grams) }} g</dd></div>
                        @endif
                        <div><dt>Status</dt><dd>Dipublikasikan</dd></div>
                    </dl>

                    @if ($product->variants->isNotEmpty())
                        <section class="mt-8" aria-labelledby="product-variants-title">
                            <h2 id="product-variants-title" class="font-semibold">Pilihan varian</h2>
                            <ul class="mt-3 divide-y divide-ink/10 border-y border-ink/10">
                                @foreach ($product->variants as $variant)
                                    <li class="flex flex-wrap justify-between gap-3 py-3 text-sm">
                                        <span>{{ $variant->attribute_name }}: {{ $variant->name }}</span>
                                        <span>{{ $variant->price !== null ? 'Rp '.number_format((float) $variant->price, 0, ',', '.') : 'Harga belum tersedia' }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </section>
                    @endif

                    @if ($productWhatsappUrl && $product->price === null && $product->variants->isEmpty())
                        <a class="button button-whatsapp mt-8" href="{{ $productWhatsappUrl }}" target="_blank" rel="noopener noreferrer">Tanyakan produk ini via WhatsApp <span aria-hidden="true">↗</span></a>
                    @endif
                    @if (! $product->is_demo && ($product->price !== null || $product->variants->where('is_active', true)->isNotEmpty()) && $product->status === 'published')
                        <form class="mt-8 space-y-4" method="POST" action="{{ route('cart.store') }}">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            @if ($product->variants->where('is_active', true)->isNotEmpty())
                                <label class="block text-sm font-medium" for="variant_id">Pilih varian</label>
                                <select class="form-input" id="variant_id" name="variant_id" required>
                                    <option value="">Pilih varian</option>
                                    @foreach ($product->variants->where('is_active', true) as $variant)
                                        @if ($variant->price !== null && $variant->stock > 0)
                                            <option value="{{ $variant->id }}">{{ $variant->attribute_name }}: {{ $variant->name }} — Rp {{ number_format((float) $variant->price, 0, ',', '.') }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            @elseif ($product->stock > 0)
                                <label class="block text-sm font-medium" for="quantity">Jumlah</label>
                                <input class="form-input w-24" id="quantity" type="number" name="quantity" min="1" max="20" value="1" required>
                            @endif
                            @if ($product->variants->where('is_active', true)->isNotEmpty())<input type="hidden" name="quantity" value="1">@endif
                            <button class="button button-dark" type="submit" @if ($product->variants->isEmpty() && $product->stock < 1) disabled @endif>Tambah ke keranjang</button>
                        </form>
                        @if ($productWhatsappUrl)
                            <a class="button button-whatsapp mt-4" href="{{ $productWhatsappUrl }}" target="_blank" rel="noopener noreferrer">Tanya produk ini via WhatsApp <span aria-hidden="true">↗</span></a>
                        @endif
                    @else
                        <a class="button button-dark mt-9" href="{{ route('contact') }}">Tanyakan produk ini <span aria-hidden="true">↗</span></a>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection

@push('head')
    @unless ($product->is_demo)
        <script type="application/ld+json">{!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $product->name,
            'description' => $product->short_description ?: $product->seo_description,
            'image' => $product->primary_image_path ? \Illuminate\Support\Facades\Storage::disk('public')->url($product->primary_image_path) : null,
            'sku' => $product->sku,
            'offers' => $product->price !== null ? [
                '@type' => 'Offer', 'priceCurrency' => 'IDR', 'price' => $product->price,
                'availability' => $product->stock > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                'url' => route('products.show', $product->slug),
            ] : null,
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!}</script>
    @endunless
@endpush
