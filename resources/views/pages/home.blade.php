@extends('layouts.public')

@section('seo_title', $homeSections->get('hero')?->title ?: '')
@section('seo_description', $homeSections->get('introduction')?->description ?: $homeSections->get('hero')?->description ?: '')

@section('content')
    @php($brandName = filled($websiteSettings->business_name) ? $websiteSettings->business_name : config('app.name', 'Kopi Drip Sidikalang'))
    @foreach ($homeSections as $key => $section)
        @php($partial = ['coffee_shop' => 'experience', 'our_story' => 'story', 'cta' => 'call-to-action'][$key] ?? $key)
        @includeIf('pages.home.sections.'.$partial)
    @endforeach
@endsection
