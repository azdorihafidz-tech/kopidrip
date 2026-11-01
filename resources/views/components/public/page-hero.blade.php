@props(['eyebrow', 'title', 'description' => null])

<section class="page-hero">
    <div class="site-container py-16 sm:py-24 lg:py-32">
        <p class="eyebrow eyebrow-light">{{ $eyebrow }}</p>
        <h1 class="mt-5 max-w-4xl font-display text-4xl leading-[1.08] text-paper sm:text-6xl lg:text-7xl">{{ $title }}</h1>
        @if ($description)
            <p class="mt-6 max-w-2xl text-base leading-7 text-white/70 sm:text-lg">{{ $description }}</p>
        @endif
    </div>
</section>
