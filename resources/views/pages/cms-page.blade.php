@extends('layouts.public')

@section('seo_title', $cmsPage->seo_title ?: $cmsPage->title)
@section('seo_description', $cmsPage->seo_description ?: $cmsPage->excerpt ?: '')
@section('seo_canonical', $cmsPage->canonical_url ?: '')
@section('seo_image', $cmsPage->og_image ?: $cmsPage->featured_image ?: '')

@section('content')
    <x-public.page-hero eyebrow="Halaman" :title="$cmsPage->title" :description="$cmsPage->excerpt" />
    <article class="section-space">
        <div class="site-container max-w-4xl">
            @if ($cmsPage->featured_image)
                <img class="journal-feature-image mb-10" src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($cmsPage->featured_image) }}" alt="{{ $cmsPage->title }}" loading="eager">
            @endif
            @if (filled($safeContent))
                <div class="prose-copy mx-auto">{!! $safeContent !!}</div>
            @endif
        </div>
    </article>
@endsection
