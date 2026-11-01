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
                <div class="mt-10"><x-public.empty-state title="Pilihan kopi segera menyusul" description="Belum ada produk yang dipublikasikan." /></div>
            @endif
            @if ($section->button_text && $section->safeButtonUrl())<a class="text-link mt-8 sm:hidden" href="{{ $section->safeButtonUrl() }}">{{ $section->button_text }}</a>@endif
        </div>
    </section>
@endif
