@extends('layouts.public')

@section('seo_title', $cmsPage->seo_title ?: $cmsPage->title)
@section('seo_description', $cmsPage->seo_description ?: $cmsPage->excerpt ?: '')
@section('seo_canonical', $cmsPage->canonical_url ?: '')
@section('seo_image', $cmsPage->og_image ?: $cmsPage->featured_image ?: '')

@section('content')
    <x-public.page-hero eyebrow="Kopi & roastery" :title="$cmsPage->title" :description="$cmsPage->excerpt" />
    @if ($cmsPage->featured_image || filled($safeContent))
        <section class="section-space"><div class="site-container max-w-4xl">
            @if ($cmsPage->featured_image)<img class="journal-feature-image mb-10" src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($cmsPage->featured_image) }}" alt="{{ $cmsPage->title }}" loading="eager">@endif
            @if (filled($safeContent))<div class="prose-copy">{!! $safeContent !!}</div>@endif
        </div></section>
    @endif
    <section class="section-space bg-cream">
        <div class="site-container">
            <x-public.section-heading eyebrow="Produk" title="Pilihan yang dipublikasikan" description="Harga dan detail berasal dari data produk yang dikelola admin." />
            @if ($products->isNotEmpty())
                <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">@foreach ($products as $product)<x-public.product-card :product="$product" />@endforeach</div>
            @else
                <div class="mt-10"><x-public.empty-state title="Belum ada produk untuk ditampilkan" description="Produk akan muncul di sini setelah dipublikasikan." /></div>
            @endif
        </div>
    </section>
@endsection
