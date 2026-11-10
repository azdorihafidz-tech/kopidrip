@extends('layouts.public')

@section('seo_title', 'Pertanyaan Umum')
@section('seo_description', 'Jawaban atas pertanyaan tentang produk dan layanan Kopi Drip Sidikalang.')

@section('content')
    <x-public.page-hero eyebrow="Bantuan" title="Pertanyaan umum" description="Informasi tentang produk dan layanan Kopi Drip Sidikalang." />
    <section class="section-space">
        <div class="site-container max-w-4xl">
            @if ($faqs->isNotEmpty())
                <div class="divide-y divide-ink/15 border-y border-ink/15">
                    @foreach ($faqs as $faq)
                        <details class="group py-6">
                            <summary class="flex cursor-pointer list-none items-center justify-between gap-5 font-display text-xl marker:hidden">
                                <span>{{ $faq->question }}</span>
                                <span class="font-sans text-muted transition group-open:rotate-45" aria-hidden="true">+</span>
                            </summary>
                            <p class="mt-4 max-w-3xl whitespace-pre-line leading-7 text-muted">{{ $faq->answer }}</p>
                        </details>
                    @endforeach
                </div>
                <div class="mt-8">{{ $faqs->links() }}</div>
            @else
                <x-public.empty-state title="Informasi FAQ segera hadir" description="Pertanyaan umum akan ditampilkan setelah jawaban resmi ditambahkan di admin." />
            @endif
        </div>
    </section>
@endsection
