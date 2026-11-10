@extends('layouts.public')

@section('seo_title', 'Jurnal')
@section('seo_description', 'Tulisan dan catatan Kopi Drip Sidikalang yang telah dipublikasikan.')

@section('content')
    <x-public.page-hero eyebrow="Jurnal" title="Catatan untuk penikmat kopi." description="Panduan ringan untuk mengenal rasa dan mengeksplorasi metode seduh." />
    <section class="section-space"><div class="site-container">
        @if ($posts->isNotEmpty())
            <div class="grid gap-9 sm:grid-cols-2 lg:grid-cols-3">@foreach ($posts as $post)<x-public.journal-card :post="$post" />@endforeach</div>
            <div class="mt-12">{{ $posts->links() }}</div>
        @else
            <x-public.empty-state title="Tulisan baru segera hadir" description="Kunjungi kembali untuk membaca catatan dan panduan seputar kopi." />
        @endif
    </div></section>
@endsection
