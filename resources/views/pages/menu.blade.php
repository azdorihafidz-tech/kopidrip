@extends('layouts.public')

@section('seo_title', 'Menu')
@section('seo_description', 'Informasi menu Kopi Drip Sidikalang yang telah dipublikasikan.')

@section('content')
    <x-public.page-hero eyebrow="Coffee shop" title="Menu" description="Kategori dan item hanya ditampilkan setelah tersedia dan dipublikasikan." />
    <section class="section-space"><div class="site-container">
        @if ($categories->isNotEmpty())
            <div class="mx-auto max-w-4xl space-y-14">
                @foreach ($categories as $category)
                    <section aria-labelledby="menu-{{ $category->slug }}">
                        <div class="flex items-end justify-between border-b border-ink/20 pb-4"><div><h2 id="menu-{{ $category->slug }}" class="font-display text-3xl">{{ $category->name }}</h2>@if ($category->description)<p class="mt-2 text-sm text-muted">{{ $category->description }}</p>@endif</div></div>
                        <div class="divide-y divide-ink/10">
                            @foreach ($category->items as $item)
                                <article class="flex flex-col gap-4 py-5 sm:flex-row sm:items-start sm:justify-between sm:gap-8">
                                    @if ($item->image_path)<img class="size-24 object-cover" src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($item->image_path) }}" alt="{{ $item->name }}" loading="lazy">@endif
                                    <div class="flex-1"><h3 class="font-medium">{{ $item->name }}</h3>@if (filled($item->description))<p class="mt-1 text-sm leading-6 text-muted">{{ $item->description }}</p>@endif</div>
                                    <p class="shrink-0 text-sm font-medium">{{ $item->price !== null ? 'Rp '.number_format((float) $item->price, 0, ',', '.') : 'Harga belum tersedia' }}</p>
                                </article>
                            @endforeach
                        </div>
                    </section>
                @endforeach
            </div>
        @else
            <x-public.empty-state title="Menu belum tersedia" description="Informasi menu akan ditampilkan setelah dikonfirmasi dan dipublikasikan." />
        @endif
    </div></section>
@endsection
