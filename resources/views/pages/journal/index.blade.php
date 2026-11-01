@extends('layouts.public')

@section('seo_title', 'Jurnal')
@section('seo_description', 'Tulisan dan catatan Kopi Drip Sidikalang yang telah dipublikasikan.')

@section('content')
    <x-public.page-hero eyebrow="Jurnal" title="Catatan seputar kopi." description="Artikel akan hadir setelah materi editorial resmi tersedia." />
    <section class="section-space"><div class="site-container">
        @if ($posts->isNotEmpty())
            <div class="grid gap-9 sm:grid-cols-2 lg:grid-cols-3">@foreach ($posts as $post)<x-public.journal-card :post="$post" />@endforeach</div>
            <div class="mt-12">{{ $posts->links() }}</div>
        @else
            <x-public.empty-state title="Jurnal masih disiapkan" description="Belum ada tulisan yang dipublikasikan." />
        @endif
    </div></section>
@endsection
