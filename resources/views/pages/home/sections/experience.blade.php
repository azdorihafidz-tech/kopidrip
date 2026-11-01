@php($section = $homeSections->get('coffee_shop'))
@if ($section)
    <section class="section-space bg-ink text-white">
        <div class="site-container grid gap-10 lg:grid-cols-[1fr_0.82fr] lg:items-center">
            <div class="experience-art" aria-hidden="true">
                <div class="experience-ring"></div><div class="experience-cup"><span></span></div>
                <div class="experience-bean experience-bean-one"></div><div class="experience-bean experience-bean-two"></div>
                <p class="experience-art-label">{{ $section->eyebrow }}</p>
            </div>
            <div class="max-w-xl lg:py-10">
                @if ($section->eyebrow)<p class="eyebrow eyebrow-light">{{ $section->eyebrow }}</p>@endif
                <h2 class="section-title mt-4 text-paper">{{ $section->title }}</h2>
                @if ($section->description)<p class="mt-5 max-w-lg whitespace-pre-line leading-7 text-white/65">{{ $section->description }}</p>@endif
                @if ($section->button_text && $section->safeButtonUrl())<a class="button button-outline-light mt-7" href="{{ $section->safeButtonUrl() }}">{{ $section->button_text }} <span aria-hidden="true">→</span></a>@endif
            </div>
        </div>
    </section>
@endif
