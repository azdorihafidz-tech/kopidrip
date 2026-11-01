@props(['brandName', 'websiteSettings'])

<header class="sticky top-0 z-40 border-b border-ink/10 bg-paper/95 backdrop-blur-md">
    <nav class="site-container flex min-h-20 items-center justify-between gap-5" aria-label="Navigasi utama" x-data="{ menuOpen: false }" @keydown.escape.window="menuOpen = false" @click.outside="menuOpen = false">
        <a class="brand-lockup shrink-0" href="{{ route('home') }}" aria-label="{{ $brandName }} — Beranda">
            @if (filled($websiteSettings->logo))
                <img class="max-h-11 w-auto max-w-40 object-contain" src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($websiteSettings->logo) }}" alt="{{ $brandName }}" width="160" height="44">
            @else
                <img class="brand-logo-default" src="{{ asset('images/kopidrip.png') }}" alt="" width="44" height="44">
                <span class="brand-name">{{ $brandName }}</span>
            @endif
        </a>

        <div class="hidden items-center gap-4 xl:flex">
            @foreach ($navigationItems as $item)
                <a class="{{ $item->is_cta ? 'button button-dark button-small' : 'nav-link' }}" href="{{ $item->publicUrl() }}" @if ($item->open_new_tab) target="_blank" rel="noopener noreferrer" @endif>
                    {{ $item->label }}@if ($item->is_cta) <span aria-hidden="true">→</span>@endif
                </a>
            @endforeach
            <a class="nav-link" href="{{ route('cart.index') }}" aria-label="Keranjang, {{ app(\App\Services\CartService::class)->quantity() }} item">Keranjang ({{ app(\App\Services\CartService::class)->quantity() }})</a>
        </div>

        <button class="mobile-menu-button xl:hidden" type="button" aria-controls="mobile-navigation" :aria-expanded="menuOpen.toString()" @click="menuOpen = !menuOpen">
            <span class="sr-only" x-text="menuOpen ? 'Tutup menu' : 'Buka menu'"></span>
            <svg x-show="!menuOpen" aria-hidden="true" viewBox="0 0 24 24" fill="none" class="size-6"><path d="M4 7h16M4 12h16M4 17h16" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg>
            <svg x-cloak x-show="menuOpen" aria-hidden="true" viewBox="0 0 24 24" fill="none" class="size-6"><path d="m6 6 12 12M18 6 6 18" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg>
        </button>

        <div id="mobile-navigation" class="mobile-navigation xl:hidden" x-cloak x-show="menuOpen" x-transition.origin.top>
            @foreach ($navigationItems as $item)
                <a class="{{ $item->is_cta ? 'button button-dark mt-2 justify-center' : '' }}" href="{{ $item->publicUrl() }}" @if ($item->open_new_tab) target="_blank" rel="noopener noreferrer" @endif @click="menuOpen = false">{{ $item->label }}</a>
            @endforeach
            <a href="{{ route('cart.index') }}" @click="menuOpen = false">Keranjang ({{ app(\App\Services\CartService::class)->quantity() }})</a>
        </div>
    </nav>
</header>
