@extends('layouts.public')

@section('seo_title', $post->seo_title ?: $post->title)
@section('seo_description', $post->seo_description ?: $post->excerpt ?: 'Jurnal Kopi Drip Sidikalang.')
@section('seo_image', $post->og_image ?: $post->image_path ?: '')
@section('og_type', 'article')
@section('seo_robots', $post->is_demo ? 'noindex,nofollow' : 'index,follow')

@section('content')
    <article class="section-space"><div class="site-container max-w-4xl">
        <a class="text-link mb-8 inline-flex" href="{{ route('journal.index') }}"><span aria-hidden="true">←</span> Semua tulisan</a>
        <header class="mb-10"><p class="eyebrow">{{ $post->published_at?->format('d/m/Y') }}</p><h1 class="mt-4 font-display text-4xl leading-tight sm:text-6xl">{{ $post->title }}</h1>@if (filled($post->excerpt))<p class="mt-6 max-w-3xl text-lg leading-8 text-muted">{{ $post->excerpt }}</p>@endif</header>
        @if (filled($post->image_path))<img class="journal-feature-image" src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($post->image_path) }}" alt="{{ $post->title }}" width="1200" height="760" fetchpriority="high">@endif
        @if (filled($safeContent))<div class="prose-copy mx-auto mt-10">{!! $safeContent !!}</div>@endif
    </div></article>
@endsection

@push('head')
    @unless ($post->is_demo)
        <script type="application/ld+json">{!! json_encode([
            '@context' => 'https://schema.org', '@type' => 'Article', 'headline' => $post->title,
            'description' => $post->excerpt, 'datePublished' => $post->published_at?->toIso8601String(),
            'dateModified' => $post->updated_at?->toIso8601String(), 'mainEntityOfPage' => route('journal.show', $post->slug),
            'image' => $post->image_path ? \Illuminate\Support\Facades\Storage::disk('public')->url($post->image_path) : null,
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!}</script>
    @endunless
@endpush
