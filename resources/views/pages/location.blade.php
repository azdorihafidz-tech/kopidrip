@extends('layouts.public')

@section('seo_title', $cmsPage->seo_title ?: $cmsPage->title)
@section('seo_description', $cmsPage->seo_description ?: $cmsPage->excerpt ?: '')
@section('seo_canonical', $cmsPage->canonical_url ?: '')
@section('seo_image', $cmsPage->og_image ?: $cmsPage->featured_image ?: '')

@section('content')
    @php($whatsappUrl = $websiteSettings->whatsappUrl())
    <x-public.page-hero eyebrow="Lokasi" :title="$cmsPage->title" :description="$cmsPage->excerpt" />
    <section class="section-space"><div class="site-container grid gap-12 lg:grid-cols-[1fr_0.8fr]">
        <div>
            @if (filled($safeContent))<div class="prose-copy mb-8">{!! $safeContent !!}</div>@endif
            <p class="eyebrow">Alamat</p>
            @if (filled($websiteSettings->address))<address class="mt-4 max-w-xl font-display text-3xl not-italic leading-relaxed">{{ $websiteSettings->address }}</address>@else<x-public.empty-state title="Alamat belum tersedia" description="Alamat akan ditampilkan setelah dikonfirmasi." />@endif
            @if (filled($websiteSettings->opening_hours))
                <div class="mt-9"><h2 class="font-semibold">Jam operasional</h2><dl class="mt-3 space-y-2 text-sm text-muted">@foreach ((array) $websiteSettings->opening_hours as $day => $hours) @if (is_string($hours) && filled($hours))<div class="flex gap-4"><dt class="w-32 shrink-0">{{ is_string($day) ? ucfirst($day) : 'Jam' }}</dt><dd>{{ $hours }}</dd></div>@endif @endforeach</dl></div>
            @endif
        </div>
        <aside class="contact-panel"><p class="eyebrow">Kontak</p><div class="mt-5 space-y-4">
            @if (filled($websiteSettings->phone))<p><span class="block text-xs uppercase tracking-wider text-muted">Telepon</span><a class="mt-1 inline-block underline" href="tel:{{ preg_replace('/[^+0-9]/', '', $websiteSettings->phone) }}">{{ $websiteSettings->phone }}</a></p>@endif
            @if (filled($websiteSettings->whatsapp))<p><span class="block text-xs uppercase tracking-wider text-muted">WhatsApp</span>@if ($whatsappUrl)<a class="mt-1 inline-block underline" href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer">{{ $websiteSettings->whatsapp }}</a>@else<span class="mt-1 inline-block">{{ $websiteSettings->whatsapp }}</span>@endif</p>@endif
            @if (filled($websiteSettings->email))<p><span class="block text-xs uppercase tracking-wider text-muted">Email</span><a class="mt-1 inline-block underline" href="mailto:{{ $websiteSettings->email }}">{{ $websiteSettings->email }}</a></p>@endif
            @if (!filled($websiteSettings->phone) && !filled($websiteSettings->whatsapp) && !filled($websiteSettings->email))<p class="leading-7 text-muted">Informasi kontak akan tersedia setelah data resminya ditambahkan.</p>@endif
        </div><a class="button button-dark mt-8" href="{{ route('contact') }}">Hubungi kami <span aria-hidden="true">↗</span></a></aside>
        @if ($websiteSettings->safeMapEmbedUrl())
            <div class="mt-12 lg:col-span-2">
                <iframe class="map-frame" src="{{ $websiteSettings->safeMapEmbedUrl() }}" title="Peta lokasi Kopi Drip Sidikalang" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
            </div>
        @endif
    </div></section>
@endsection
