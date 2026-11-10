@php($section = $homeSections->get('cta'))
@if ($section)
    <section class="section-space-sm">
        <div class="site-container">
            <div class="mx-auto max-w-[1280px] rounded-3xl bg-[#f0a46e] px-6 py-12 text-center sm:px-10 sm:py-16 lg:px-20 lg:py-20">
                @if ($section->eyebrow)<p class="eyebrow">{{ $section->eyebrow }}</p>@endif
                <h2 class="mx-auto mt-2 max-w-3xl font-sans text-3xl font-black leading-tight tracking-[-0.06em] text-ink sm:text-5xl">{{ $section->title }}</h2>
                @if ($section->description)<p class="mx-auto mt-4 max-w-2xl whitespace-pre-line leading-7 text-ink/75">{{ $section->description }}</p>@endif
                @if ($section->button_text && $section->safeButtonUrl())<a class="button button-dark mt-7" href="{{ $section->safeButtonUrl() }}">{{ $section->button_text }} <span aria-hidden="true">↗</span></a>@endif
            </div>
        </div>
    </section>
@endif
