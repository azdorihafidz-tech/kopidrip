@if ($featuredPromotion)
    <section class="section-space">
        <div class="site-container">
            <article class="grid overflow-hidden rounded-2xl bg-cream lg:grid-cols-2">
                @if ($featuredPromotion->image_path)
                    <img class="h-full min-h-64 w-full object-cover" src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($featuredPromotion->image_path) }}" alt="{{ $featuredPromotion->title }}" loading="lazy">
                @endif
                <div class="flex flex-col items-start justify-center p-7 sm:p-10 lg:p-14">
                    <p class="eyebrow">Promo berlangsung</p>
                    <h2 class="mt-3 font-display text-3xl leading-tight sm:text-4xl">{{ $featuredPromotion->title }}</h2>
                    @if ($featuredPromotion->summary)<p class="mt-4 max-w-xl leading-7 text-muted">{{ $featuredPromotion->summary }}</p>@endif
                    <a class="button button-dark mt-7" href="{{ route('promotions.show', $featuredPromotion->slug) }}">Lihat detail promo <span aria-hidden="true">↗</span></a>
                </div>
            </article>
        </div>
    </section>
@endif
