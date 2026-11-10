@extends('layouts.public')

@section('seo_title', 'Galeri')
@section('seo_description', 'Galeri foto resmi Kopi Drip Sidikalang.')

@section('content')
    <x-public.page-hero eyebrow="Galeri" title="Ruang visual Kopi Drip." description="Ilustrasi editorial seputar biji kopi dan metode seduh." />
    <section class="section-space"><div class="site-container">
        @if ($galleryItems->isNotEmpty())
            <div class="gallery-grid">
                @foreach ($galleryItems as $item)
                    <figure class="gallery-tile"><img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($item->image_path) }}" alt="{{ $item->alt_text }}" width="800" height="900" loading="lazy">@if (filled($item->title) || filled($item->caption))<figcaption>{{ $item->title ?: $item->caption }}@if ($item->description)<span class="mt-1 block text-xs">{{ $item->description }}</span>@endif</figcaption>@endif</figure>
                @endforeach
            </div>
            <div class="mt-12">{{ $galleryItems->links() }}</div>
        @else
            <x-public.empty-state title="Galeri akan segera hadir" description="Belum ada foto yang dipublikasikan." />
        @endif
    </div></section>
@endsection
