@php($section = $homeSections->get('our_story'))
@if ($section)
    <section class="section-space">
        <div class="site-container grid gap-9 md:grid-cols-[0.5fr_1fr] md:items-start">
            @if ($section->eyebrow)<p class="eyebrow pt-2">{{ $section->eyebrow }} <span aria-hidden="true">—</span></p>@endif
            <div class="max-w-3xl">
                <h2 class="font-display text-3xl leading-tight sm:text-5xl">{{ $section->title }}</h2>
                @if ($section->description)<p class="mt-6 max-w-2xl whitespace-pre-line leading-7 text-muted">{{ $section->description }}</p>@endif
                <div class="mt-7 flex flex-wrap gap-3">
                    @if ($section->button_text && $section->safeButtonUrl())<a class="button button-dark" href="{{ $section->safeButtonUrl() }}">{{ $section->button_text }} <span aria-hidden="true">→</span></a>@endif
                    @if ($section->secondary_button_text && $section->safeButtonUrl('secondary_button_url'))<a class="button button-outline-dark" href="{{ $section->safeButtonUrl('secondary_button_url') }}">{{ $section->secondary_button_text }}</a>@endif
                </div>
            </div>
        </div>
    </section>
@endif
