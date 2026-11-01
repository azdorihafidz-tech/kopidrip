@props(['product'])

<article class="product-card group">
    <a class="product-image-wrap" href="{{ route('products.show', $product->slug) }}" aria-label="Lihat {{ $product->name }}">
        @if (filled($product->primary_image_path))
            <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($product->primary_image_path) }}" alt="{{ $product->name }}" width="640" height="760" loading="lazy">
        @else
            <span class="product-image-placeholder" aria-hidden="true"><span>{{ $product->name }}</span></span>
        @endif
        <span class="product-arrow" aria-hidden="true">↗</span>
    </a>
    <div class="flex items-start justify-between gap-4 pt-4">
        <div>
            <h3 class="font-display text-xl leading-snug"><a class="hover:text-moss" href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a></h3>
            @if ($product->is_demo)
                <span class="mt-2 inline-flex rounded-full bg-amber-100 px-2.5 py-1 text-[10px] font-semibold uppercase tracking-wider text-amber-950">DEMO — bukan penawaran</span>
            @endif
            @if (filled($product->short_description))
                <p class="mt-2 line-clamp-2 text-sm leading-6 text-muted">{{ $product->short_description }}</p>
            @endif
        </div>
        <div class="shrink-0 text-right">
            @if ($product->is_demo)<span class="block text-[10px] font-semibold uppercase tracking-wider text-amber-800">Harga demo</span>@endif
            <p class="text-sm font-medium">{{ $product->price !== null ? 'Rp '.number_format((float) $product->price, 0, ',', '.') : 'Harga belum tersedia' }}</p>
        </div>
    </div>
</article>
