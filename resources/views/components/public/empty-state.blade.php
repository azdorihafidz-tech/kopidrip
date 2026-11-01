@props(['title', 'description'])

<div class="empty-state">
    <span class="empty-state-mark" aria-hidden="true">✳</span>
    <h3>{{ $title }}</h3>
    <p>{{ $description }}</p>
</div>
