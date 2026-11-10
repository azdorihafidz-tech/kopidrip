@php($section = $homeSections->get('featured_products'))
@if ($section)
    <section class="section-space bg-cream">
        <div class="site-container">
            <div class="section-heading-row">
                <x-public.section-heading :eyebrow="$section->eyebrow ?: ''" :title="$section->title ?: ''" :description="$section->description ?: ''" />
                @if ($section->button_text && $section->safeButtonUrl())<a class="text-link hidden sm:inline-flex" href="{{ $section->safeButtonUrl() }}">{{ $section->button_text }} <span aria-hidden="true">→</span></a>@endif
            </div>
            @if ($featuredProducts->isNotEmpty())
                <div class="mt-10 grid gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($featuredProducts as $product)<x-public.product-card :product="$product" />@endforeach
                </div>
            @else
                <div class="mt-10 grid overflow-hidden bg-ink text-white md:grid-cols-2">
                    <div class="flex flex-col justify-center p-7 sm:p-10 lg:p-12">
                        <p class="eyebrow eyebrow-light">Katalog Kopi Drip</p>
                        <h3 class="mt-3 font-display text-3xl leading-tight sm:text-4xl">Sedang mencari kopi untuk dinikmati?</h3>
                        <p class="mt-4 max-w-lg leading-7 text-white/70">Tanyakan pilihan produk, harga, dan ketersediaan terbaru langsung kepada kami.</p>
                        <a class="button button-cream mt-7 self-start" href="{{ route('contact') }}">Tanya melalui kontak <span aria-hidden="true">↗</span></a>
                    </div>
                    <div class="relative min-h-[250px] bg-coffee md:min-h-[360px]">
                        <img class="absolute inset-0 size-full object-cover" src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url('editorial/hero-brewing.webp') }}" alt="Ilustrasi editorial kopi yang diseduh dengan pour over." width="1024" height="1365" loading="lazy">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/45 to-transparent"></div>
                        <p class="absolute bottom-5 left-5 text-[0.62rem] font-semibold uppercase tracking-[0.18em] text-white sm:bottom-7 sm:left-7">Visual editorial · ritual seduh</p>
                    </div>
                </div>
            @endif
            @if ($section->button_text && $section->safeButtonUrl())<a class="text-link mt-8 sm:hidden" href="{{ $section->safeButtonUrl() }}">{{ $section->button_text }}</a>@endif
        </div>
    </section>
@endif
