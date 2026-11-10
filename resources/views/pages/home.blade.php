@extends('layouts.public')

@section('seo_title', $homeSections->get('hero')?->title ?: '')
@section('seo_description', $homeSections->get('introduction')?->description ?: $homeSections->get('hero')?->description ?: '')

@section('content')
    @php($brandName = filled($websiteSettings->business_name) ? $websiteSettings->business_name : config('app.name', 'Kopi Drip Sidikalang'))
    @php($ctaSection = $homeSections->get('cta'))
    @foreach ($homeSections->except('cta') as $key => $section)
        @php($partial = ['coffee_shop' => 'experience', 'our_story' => 'story', 'location' => 'visit', 'cta' => 'call-to-action'][$key] ?? $key)
        @includeIf('pages.home.sections.'.$partial)
    @endforeach
    @include('pages.home.sections.promotion-highlight')
    @include('pages.home.sections.testimonials')
    @include('pages.home.sections.faqs')
    @if ($ctaSection)
        @php($section = $ctaSection)
        @include('pages.home.sections.call-to-action')
    @endif
@endsection
