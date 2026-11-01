@props(['post'])

<article class="journal-card group">
    <a class="journal-image-wrap" href="{{ route('journal.show', $post->slug) }}" aria-label="Baca {{ $post->title }}">
        @if (filled($post->image_path))
            <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($post->image_path) }}" alt="{{ $post->title }}" width="720" height="480" loading="lazy">
        @else
            <span class="journal-image-placeholder" aria-hidden="true"></span>
        @endif
    </a>
    <p class="eyebrow mt-5">{{ $post->published_at?->format('d/m/Y') }}@if ($post->author_name) · {{ $post->author_name }}@endif</p>
    <h3 class="mt-2 font-display text-2xl leading-snug"><a class="hover:text-moss" href="{{ route('journal.show', $post->slug) }}">{{ $post->title }}</a></h3>
    @if (filled($post->excerpt))
        <p class="mt-3 line-clamp-3 text-sm leading-6 text-muted">{{ $post->excerpt }}</p>
    @endif
    <a class="text-link mt-5" href="{{ route('journal.show', $post->slug) }}">Baca cerita <span aria-hidden="true">↗</span></a>
</article>
