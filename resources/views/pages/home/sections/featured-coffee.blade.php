@php($section = $homeSections->get('featured_coffee'))
@if ($section)
    <section class="section-space-sm">
        <div class="site-container">
            <div class="editorial-card grid gap-6 sm:grid-cols-[1fr_auto] sm:items-center">
                <div>
                    @if ($section->eyebrow)<p class="eyebrow">{{ $section->eyebrow }}</p>@endif
                    <h2 class="mt-3 font-display text-3xl">{{ $section->title }}</h2>
                    @if ($section->description)<p class="mt-3 max-w-2xl whitespace-pre-line leading-7 text-muted">{{ $section->description }}</p>@endif
                </div>
                @if ($section->button_text && $section->safeButtonUrl())<a class="button button-outline-dark" href="{{ $section->safeButtonUrl() }}">{{ $section->button_text }} <span aria-hidden="true">→</span></a>@endif
            </div>
        </div>
    </section>
@endif
