@extends('layouts.public')

@section('seo_title', $cmsPage->seo_title ?: $cmsPage->title)
@section('seo_description', $cmsPage->seo_description ?: $cmsPage->excerpt)

@section('content')
    <x-public.page-hero eyebrow="Tentang" :title="$cmsPage->title" :description="$cmsPage->excerpt" />
    <section class="section-space">
        <div class="site-container grid gap-8 md:grid-cols-[0.7fr_1.3fr]">
            <p class="eyebrow">Tentang Kopi Drip</p>
            <div class="prose-copy max-w-3xl">{!! $safeContent !!}</div>
        </div>
    </section>
    <section class="section-space-sm bg-cream">
        <div class="site-container flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
            <div><p class="eyebrow">Jelajahi lebih lanjut</p><h2 class="mt-3 font-display text-3xl">Lihat kopi dan produk.</h2></div>
            <a class="button button-dark" href="{{ route('products.index') }}">Jelajahi produk <span aria-hidden="true">→</span></a>
        </div>
    </section>
@endsection
