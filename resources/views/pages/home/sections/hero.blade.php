@php($section = $homeSections->get('hero'))
@if ($section)
    <section class="home-hero">
        <div class="site-container home-hero-grid">
            <div class="home-hero-copy">
                @if ($section->eyebrow)<p class="eyebrow eyebrow-light">{{ $section->eyebrow }}</p>@endif
                <h1 class="home-hero-title">{{ $section->title }}@if ($section->subtitle)<br><span>{{ $section->subtitle }}</span>@endif</h1>
                @if ($section->description)<p class="home-hero-intro whitespace-pre-line">{{ $section->description }}</p>@endif
                <div class="mt-8 flex flex-wrap gap-3">
                    @if ($section->button_text && $section->safeButtonUrl())<a class="button button-cream" href="{{ $section->safeButtonUrl() }}">{{ $section->button_text }} <span aria-hidden="true">→</span></a>@endif
                    @if ($section->secondary_button_text && $section->safeButtonUrl('secondary_button_url'))<a class="button button-outline-light" href="{{ $section->safeButtonUrl('secondary_button_url') }}">{{ $section->secondary_button_text }}</a>@endif
                </div>
            </div>
            <div class="home-hero-art">
                @php($heroImage = $section->image_path ?: $websiteSettings->homepage_hero_image)
                @if ($heroImage)
                    <img class="home-hero-photo" src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($heroImage) }}" alt="{{ $section->image_alt ?: 'Gambar utama '.$brandName }}" width="1145" height="1432" fetchpriority="high">
                @else
                    <img class="home-hero-photo" src="{{ asset('images/coffee-editorial.webp') }}" alt="Ilustrasi editorial secangkir kopi, bukan dokumentasi produk atau gerai {{ $brandName }}." width="1145" height="1432" fetchpriority="high">
                    <span class="home-hero-caption">Visual editorial tentang kopi</span>
                @endif
                <span class="home-hero-art-note" aria-hidden="true">Kopi Drip <span>·</span> Sidikalang</span>
            </div>
        </div>
    </section>
@endif
