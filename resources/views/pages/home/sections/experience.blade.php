@php($section = $homeSections->get('coffee_shop'))
@if ($section)
    <section class="section-space bg-ink text-white">
        <div class="site-container grid gap-10 lg:grid-cols-[1fr_0.82fr] lg:items-center">
            <div class="experience-art">
                <img class="absolute inset-0 size-full object-cover" src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url('editorial/pour-over.webp') }}" alt="Ilustrasi editorial air panas dituangkan ke alat pour over." width="1024" height="768" loading="lazy">
                <div class="absolute inset-0 bg-gradient-to-t from-black/65 via-black/5 to-transparent"></div>
                <p class="experience-art-label">Ilustrasi editorial · metode seduh</p>
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
