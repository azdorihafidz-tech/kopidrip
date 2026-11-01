@props(['eyebrow', 'title', 'description' => null, 'align' => 'left'])

<div @class(['max-w-2xl', 'mx-auto text-center' => $align === 'center'])>
    <p class="eyebrow">{{ $eyebrow }}</p>
    <h2 class="section-title mt-3">{{ $title }}</h2>
    @if ($description)
        <p class="mt-4 text-base leading-7 text-muted">{{ $description }}</p>
    @endif
</div>
