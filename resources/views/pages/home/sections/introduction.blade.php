@php($section = $homeSections->get('introduction'))
@if ($section)
    <section class="section-space">
        <div class="site-container grid gap-8 md:grid-cols-[0.7fr_1.3fr] md:items-end">
            <x-public.section-heading :eyebrow="$section->eyebrow ?: ''" :title="$section->title ?: ''" />
            <div class="max-w-2xl md:justify-self-end">
                @if ($section->description)<p class="whitespace-pre-line font-display text-2xl leading-relaxed text-ink sm:text-3xl">{{ $section->description }}</p>@endif
                @if ($section->button_text && $section->safeButtonUrl())<a class="text-link mt-6" href="{{ $section->safeButtonUrl() }}">{{ $section->button_text }} <span aria-hidden="true">→</span></a>@endif
            </div>
        </div>
    </section>
@endif
