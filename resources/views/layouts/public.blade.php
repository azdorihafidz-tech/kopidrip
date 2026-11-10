<!doctype html>
<html lang="id">
@php
    $brandName = filled($websiteSettings->business_name)
        ? $websiteSettings->business_name
        : config('app.name', 'Kopi Drip Sidikalang');
    $seoTitle = trim(strip_tags($__env->yieldContent('seo_title'))) ?: $brandName;
    $seoDescription = trim(strip_tags($__env->yieldContent('seo_description')))
        ?: 'Situs resmi Kopi Drip Sidikalang.';
    $canonicalUrl = trim(strip_tags($__env->yieldContent('seo_canonical'))) ?: url()->current();
    $seoImagePath = trim($__env->yieldContent('seo_image'))
        ?: ($websiteSettings->homepage_hero_image ?: $websiteSettings->logo);
    $seoImageUrl = $seoImagePath
        ? (str_starts_with($seoImagePath, 'https://')
            ? $seoImagePath
            : \Illuminate\Support\Facades\Storage::disk('public')->url($seoImagePath))
        : null;
    $faviconUrl = filled($websiteSettings->favicon)
        ? \Illuminate\Support\Facades\Storage::disk('public')->url($websiteSettings->favicon)
        : asset('images/kopidrip.png');
    $brandColors = $websiteSettings->brandColors();
@endphp
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{ $seoDescription }}">
    <meta name="robots" content="{{ $websiteSettings->site_is_demo ? 'noindex,nofollow' : (trim(strip_tags($__env->yieldContent('seo_robots'))) ?: 'index,follow') }}">
    <link rel="canonical" href="{{ trim(strip_tags($__env->yieldContent('seo_canonical'))) ?: $canonicalUrl }}">
    <meta property="og:type" content="{{ trim($__env->yieldContent('og_type')) ?: 'website' }}">
    <meta property="og:site_name" content="{{ $brandName }}">
    <meta property="og:title" content="{{ $seoTitle }}">
    <meta property="og:description" content="{{ $seoDescription }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta name="twitter:card" content="{{ $seoImageUrl ? 'summary_large_image' : 'summary' }}">
    <meta name="twitter:title" content="{{ $seoTitle }}">
    <meta name="twitter:description" content="{{ $seoDescription }}">
    @if ($seoImageUrl)
        <meta property="og:image" content="{{ $seoImageUrl }}">
        <meta name="twitter:image" content="{{ $seoImageUrl }}">
    @endif
    @if ($faviconUrl)
        <link rel="icon" href="{{ $faviconUrl }}">
        <link rel="apple-touch-icon" href="{{ $faviconUrl }}">
    @endif
    @if (filled(config('services.google_analytics.measurement_id')) && ! request()->routeIs('orders.show', 'checkout.*', 'cart.*'))
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ rawurlencode(config('services.google_analytics.measurement_id')) }}"></script>
        <script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments)}gtag('js',new Date());gtag('config',{{ Illuminate\Support\Js::from(config('services.google_analytics.measurement_id')) }},{'anonymize_ip':true});</script>
    @endif
    <title>{{ $seoTitle }}@unless ($seoTitle === $brandName) · {{ $brandName }}@endunless</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>:root { --brand-primary: {{ $brandColors['primary'] }}; --brand-secondary: {{ $brandColors['secondary'] }}; --brand-accent: {{ $brandColors['accent'] }}; }</style>
    @stack('head')
</head>
<body class="min-h-screen bg-paper font-sans text-ink antialiased">
    <a class="skip-link" href="#main-content">Lewati ke konten</a>
    <x-public.header :brand-name="$brandName" :website-settings="$websiteSettings" />
    @if (session('status'))
        <div role="status" class="site-container py-3 text-sm font-medium text-green-800">{{ session('status') }}</div>
    @endif
    @if ($errors->any())
        <div role="alert" class="site-container py-3 text-sm font-medium text-red-800">{{ $errors->first() }}</div>
    @endif
    <main id="main-content">@yield('content')</main>
    <x-public.footer :brand-name="$brandName" :website-settings="$websiteSettings" />
    @php($floatingWhatsappUrl = $websiteSettings->whatsappUrl('Halo Kopi Drip, saya ingin bertanya.'))
    @if ($floatingWhatsappUrl)
        <a class="whatsapp-float" href="{{ $floatingWhatsappUrl }}" target="_blank" rel="noopener noreferrer" aria-label="Chat dengan Kopi Drip melalui WhatsApp">
            <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" class="size-6"><path d="M20 11.7a8.1 8.1 0 0 1-11.95 7.1L4 20l1.25-3.82A8.1 8.1 0 1 1 20 11.7Z" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/><path d="M8.9 8.5c.2-.45.4-.46.7-.47h.5c.17 0 .35.06.45.34l.68 1.63c.08.2.04.38-.1.55l-.5.62c-.15.18-.3.33-.13.62.17.29.75 1.24 1.63 2 .98.86 1.8 1.12 2.12 1.25.27.12.44.1.61-.1l.75-.88c.18-.21.36-.25.6-.15l1.52.73c.23.11.39.17.45.27.06.1.06.57-.14 1.1-.2.52-1.14 1.02-1.57 1.06-.4.04-.9.06-1.46-.12-.34-.11-.78-.25-1.34-.5-2.36-1.03-3.9-3.47-4.02-3.63-.12-.16-.95-1.26-.95-2.4 0-1.14.6-1.7.8-1.92Z" fill="currentColor"/></svg>
            <span>Chat WhatsApp</span>
        </a>
    @endif
</body>
</html>
