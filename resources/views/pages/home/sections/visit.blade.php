@php($section = $homeSections->get('location'))
@if ($section)
    <section class="section-space-sm border-y border-ink/10">
        <div class="site-container grid gap-6 sm:grid-cols-[1fr_auto] sm:items-center">
            <div>
                @if ($section->eyebrow)<p class="eyebrow">{{ $section->eyebrow }}</p>@endif
                @if (filled($websiteSettings->address))
                    <h2 class="mt-3 font-display text-2xl">{{ $websiteSettings->address }}</h2>
                @elseif ($section->title)
                    <h2 class="mt-3 font-display text-2xl">{{ $section->title }}</h2>
                @endif
                @if (filled($websiteSettings->phone))<p class="mt-2 text-sm text-muted">{{ $websiteSettings->phone }}</p>@endif
                @if ($section->description)<p class="mt-2 whitespace-pre-line text-sm leading-6 text-muted">{{ $section->description }}</p>@endif
            </div>
            @if ($section->button_text && $section->safeButtonUrl())<a class="button button-dark" href="{{ $section->safeButtonUrl() }}">{{ $section->button_text }} <span aria-hidden="true">→</span></a>@endif
        </div>
    </section>
@endif
