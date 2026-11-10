@extends('layouts.public')

@section('seo_title', 'Promo Kopi Drip Sidikalang')
@section('seo_description', 'Lihat promo dan penawaran yang sedang berlangsung dari Kopi Drip Sidikalang.')

@section('content')
    <x-public.page-hero eyebrow="Penawaran" title="Promo Kopi Drip" description="Temukan penawaran yang sedang berlangsung." />
    <section class="section-space">
        <div class="site-container">
            @if ($promotions->isNotEmpty())
                <div class="grid gap-7 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($promotions as $promotion)
                        <article class="overflow-hidden rounded-xl border border-ink/10 bg-paper shadow-sm">
                            @if ($promotion->image_path)<a href="{{ route('promotions.show', $promotion->slug) }}"><img class="aspect-[4/3] w-full object-cover" src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($promotion->image_path) }}" alt="{{ $promotion->title }}" loading="lazy"></a>@endif
                            <div class="p-6">
                                <p class="eyebrow">Promo berlangsung</p>
                                <h2 class="mt-3 font-display text-2xl leading-snug"><a href="{{ route('promotions.show', $promotion->slug) }}">{{ $promotion->title }}</a></h2>
                                @if ($promotion->summary)<p class="mt-3 text-sm leading-6 text-muted">{{ $promotion->summary }}</p>@endif
                                <a class="text-link mt-5 inline-flex" href="{{ route('promotions.show', $promotion->slug) }}">Lihat detail <span aria-hidden="true">↗</span></a>
                            </div>
                        </article>
                    @endforeach
                </div>
                <div class="mt-10">{{ $promotions->links() }}</div>
            @else
                <x-public.empty-state title="Belum ada promo aktif" description="Promo baru akan ditampilkan di sini setelah diterbitkan melalui admin." />
            @endif
        </div>
    </section>
@endsection
