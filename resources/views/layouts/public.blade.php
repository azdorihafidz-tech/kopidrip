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
    @if ($websiteSettings->site_is_demo)
        <p class="bg-amber-100 px-4 py-2 text-center text-xs font-semibold tracking-wide text-amber-950">DEMO CONTENT — REPLACE BEFORE PRODUCTION</p>
    @endif
    <x-public.header :brand-name="$brandName" :website-settings="$websiteSettings" />
    @if (session('status'))
        <div role="status" class="site-container py-3 text-sm font-medium text-green-800">{{ session('status') }}</div>
    @endif
    @if ($errors->any())
        <div role="alert" class="site-container py-3 text-sm font-medium text-red-800">{{ $errors->first() }}</div>
    @endif
    <main id="main-content">@yield('content')</main>
    <x-public.footer :brand-name="$brandName" :website-settings="$websiteSettings" />
</body>
</html>
