@php($section = $homeSections->get('cta'))
@if ($section)
    <section class="cta-band">
        <div class="site-container flex flex-col gap-7 py-14 sm:flex-row sm:items-center sm:justify-between sm:py-20">
            <div class="max-w-2xl">
                @if ($section->eyebrow)<p class="eyebrow eyebrow-light">{{ $section->eyebrow }}</p>@endif
                <h2 class="mt-3 font-display text-3xl leading-tight text-white sm:text-4xl">{{ $section->title }}</h2>
                @if ($section->description)<p class="mt-4 whitespace-pre-line leading-7 text-white/75">{{ $section->description }}</p>@endif
            </div>
            @if ($section->button_text && $section->safeButtonUrl())<a class="button button-cream" href="{{ $section->safeButtonUrl() }}">{{ $section->button_text }} <span aria-hidden="true">→</span></a>@endif
        </div>
    </section>
@endif
