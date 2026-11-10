@props(['eyebrow', 'title', 'description' => null])

<section class="page-hero">
    <div class="site-container py-16 sm:py-24 lg:py-32">
        <p class="eyebrow">{{ $eyebrow }}</p>
        <h1 class="mt-4 max-w-4xl font-sans text-4xl font-black leading-[1.02] tracking-[-0.065em] text-ink sm:text-6xl lg:text-7xl">{{ $title }}</h1>
        @if ($description)
            <p class="mt-6 max-w-2xl text-base leading-7 text-white/70 sm:text-lg">{{ $description }}</p>
        @endif
    </div>
</section>
