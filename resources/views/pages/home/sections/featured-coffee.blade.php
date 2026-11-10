@php($section = $homeSections->get('featured_coffee'))
@if ($section)
    <section class="section-space bg-cream">
        <div class="site-container grid gap-8 lg:grid-cols-[1.05fr_0.95fr] lg:items-stretch">
            <figure class="relative min-h-[300px] overflow-hidden bg-coffee sm:min-h-[420px]">
                <img class="absolute inset-0 size-full object-cover" src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url('editorial/coffee-beans-grinder.webp') }}" alt="Ilustrasi editorial biji kopi dan penggiling manual." width="1024" height="768" loading="lazy">
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                <figcaption class="absolute bottom-6 left-6 text-xs font-semibold uppercase tracking-[0.2em] text-white sm:bottom-8 sm:left-8">Eksplorasi kopi · Kopi Drip</figcaption>
            </figure>
            <div class="flex flex-col justify-center bg-paper px-6 py-9 sm:px-10 lg:px-12">
                @if ($section->eyebrow)<p class="eyebrow">{{ $section->eyebrow }}</p>@endif
                <h2 class="mt-3 font-display text-3xl leading-tight sm:text-4xl">{{ $section->title }}</h2>
                @if ($section->description)<p class="mt-4 max-w-2xl whitespace-pre-line leading-7 text-muted">{{ $section->description }}</p>@endif
                <div class="mt-7 grid gap-4 border-y border-ink/10 py-5 sm:grid-cols-3">
                    <div><span class="font-display text-2xl text-moss">01</span><p class="mt-1 text-sm font-medium">Perhatikan aroma</p></div>
                    <div><span class="font-display text-2xl text-moss">02</span><p class="mt-1 text-sm font-medium">Coba cara seduh</p></div>
                    <div><span class="font-display text-2xl text-moss">03</span><p class="mt-1 text-sm font-medium">Catat selera</p></div>
                </div>
                @if ($section->button_text && $section->safeButtonUrl())<a class="button button-dark mt-7 self-start" href="{{ $section->safeButtonUrl() }}">{{ $section->button_text }} <span aria-hidden="true">→</span></a>@endif
            </div>
        </div>
    </section>
@endif
