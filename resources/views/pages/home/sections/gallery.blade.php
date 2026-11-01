@php($section = $homeSections->get('gallery'))
@if ($section)
    <section class="section-space bg-cream">
        <div class="site-container">
            <div class="section-heading-row">
                <x-public.section-heading :eyebrow="$section->eyebrow ?: ''" :title="$section->title ?: ''" :description="$section->description ?: ''" />
                @if ($section->button_text && $section->safeButtonUrl())<a class="text-link hidden sm:inline-flex" href="{{ $section->safeButtonUrl() }}">{{ $section->button_text }} <span aria-hidden="true">→</span></a>@endif
            </div>
            @if ($galleryItems->isNotEmpty())
                <div class="gallery-grid mt-10">
                    @foreach ($galleryItems as $item)
                        <figure class="gallery-tile"><img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($item->image_path) }}" alt="{{ $item->alt_text }}" width="800" height="900" loading="lazy">@if (filled($item->caption))<figcaption>{{ $item->caption }}</figcaption>@endif</figure>
                    @endforeach
                </div>
            @else
                <div class="mt-10"><x-public.empty-state title="Galeri akan segera hadir" description="Belum ada foto yang dipublikasikan." /></div>
            @endif
            @if ($section->button_text && $section->safeButtonUrl())<a class="text-link mt-8 sm:hidden" href="{{ $section->safeButtonUrl() }}">{{ $section->button_text }}</a>@endif
        </div>
    </section>
@endif
