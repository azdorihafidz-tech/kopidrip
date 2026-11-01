@php($section = $homeSections->get('journal'))
@if ($section)
    <section class="section-space">
        <div class="site-container">
            <div class="section-heading-row">
                <x-public.section-heading :eyebrow="$section->eyebrow ?: ''" :title="$section->title ?: ''" :description="$section->description ?: ''" />
                @if ($section->button_text && $section->safeButtonUrl())<a class="text-link hidden sm:inline-flex" href="{{ $section->safeButtonUrl() }}">{{ $section->button_text }} <span aria-hidden="true">→</span></a>@endif
            </div>
            @if ($journalPosts->isNotEmpty())
                <div class="mt-10 grid gap-8 md:grid-cols-2 lg:grid-cols-3">@foreach ($journalPosts as $post)<x-public.journal-card :post="$post" />@endforeach</div>
            @else
                <div class="mt-10"><x-public.empty-state title="Jurnal akan segera hadir" description="Belum ada tulisan yang dipublikasikan." /></div>
            @endif
            @if ($section->button_text && $section->safeButtonUrl())<a class="text-link mt-8 sm:hidden" href="{{ $section->safeButtonUrl() }}">{{ $section->button_text }}</a>@endif
        </div>
    </section>
@endif
