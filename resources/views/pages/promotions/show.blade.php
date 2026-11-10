@extends('layouts.public')

@section('seo_title', $promotion->title)
@section('seo_description', $promotion->summary ?: 'Detail promo Kopi Drip Sidikalang.')
@section('seo_image', $promotion->image_path ?: '')

@section('content')
    @php($promoWhatsappUrl = $websiteSettings->whatsappUrl('Halo Kopi Drip, saya ingin bertanya tentang promo '.$promotion->title.': '.route('promotions.show', $promotion->slug)))
    <section class="section-space">
        <article class="site-container max-w-4xl">
            <a class="text-link mb-8 inline-flex" href="{{ route('promotions.index') }}"><span aria-hidden="true">←</span> Semua promo</a>
            @if ($promotion->image_path)<img class="mb-9 max-h-[560px] w-full rounded-xl object-cover" src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($promotion->image_path) }}" alt="{{ $promotion->title }}" fetchpriority="high">@endif
            <p class="eyebrow">Penawaran Kopi Drip</p>
            <h1 class="mt-3 font-display text-4xl leading-tight sm:text-5xl">{{ $promotion->title }}</h1>
            @if ($promotion->summary)<p class="mt-5 text-lg leading-8 text-muted">{{ $promotion->summary }}</p>@endif
            @if ($safeDescription)<div class="prose-copy mt-8">{!! $safeDescription !!}</div>@endif
            @if ($promotion->terms)<section class="mt-9 rounded-xl bg-cream p-6"><h2 class="font-semibold">Syarat dan ketentuan</h2><p class="mt-3 whitespace-pre-line text-sm leading-7 text-muted">{{ $promotion->terms }}</p></section>@endif
            @if ($promoWhatsappUrl)<a class="button button-whatsapp mt-9" href="{{ $promoWhatsappUrl }}" target="_blank" rel="noopener noreferrer">Tanyakan promo ini via WhatsApp <span aria-hidden="true">↗</span></a>@endif
        </article>
    </section>
@endsection
