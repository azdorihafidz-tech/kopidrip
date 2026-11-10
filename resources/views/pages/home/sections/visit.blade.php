@php($section = $homeSections->get('location'))
@if ($section)
    <section class="section-space-sm">
        <div class="site-container">
            <div class="grid overflow-hidden rounded-3xl bg-[#f4ece4] md:grid-cols-[1.15fr_0.85fr]">
                <div class="flex flex-col items-start justify-center p-7 sm:p-10 lg:p-14">
                    @if ($section->eyebrow)<p class="eyebrow">{{ $section->eyebrow }}</p>@endif
                    @if (filled($websiteSettings->address))
                        <h2 class="mt-3 font-sans text-3xl font-black tracking-[-0.05em] text-ink sm:text-4xl">{{ $websiteSettings->address }}</h2>
                    @elseif ($section->title)
                        <h2 class="mt-3 font-sans text-3xl font-black tracking-[-0.05em] text-ink sm:text-4xl">{{ $section->title }}</h2>
                    @endif
                    @if ($section->description)<p class="mt-4 max-w-xl whitespace-pre-line leading-7 text-muted">{{ $section->description }}</p>@endif
                    @if (filled($websiteSettings->phone))<p class="mt-3 text-sm font-semibold text-ink">{{ $websiteSettings->phone }}</p>@endif
                    @if ($section->button_text && $section->safeButtonUrl())<a class="button button-dark mt-6" href="{{ $section->safeButtonUrl() }}">{{ $section->button_text }} <span aria-hidden="true">→</span></a>@endif
                </div>
                <div class="relative min-h-[250px] bg-[#d9bea6] md:min-h-[340px]">
                    <img class="absolute inset-0 size-full object-cover" src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url('editorial/coffee-beans-grinder.webp') }}" alt="Ilustrasi editorial biji kopi dan penggiling manual." width="1024" height="768" loading="lazy">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/45 to-transparent"></div>
                    <p class="absolute bottom-5 left-5 text-[0.62rem] font-semibold uppercase tracking-[0.18em] text-white sm:bottom-7 sm:left-7">Kopi Drip · Sidikalang</p>
                </div>
            </div>
        </div>
    </section>
@endif
