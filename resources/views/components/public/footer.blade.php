@props(['brandName', 'websiteSettings'])

<footer class="site-footer">
    <div class="site-container grid gap-12 py-14 md:grid-cols-2 lg:grid-cols-4 lg:py-20">
        <div class="lg:col-span-1">
            <a class="footer-brand" href="{{ route('home') }}">
                @if (filled($websiteSettings->logo_white ?: $websiteSettings->logo))
                    <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($websiteSettings->logo_white ?: $websiteSettings->logo) }}" alt="{{ $brandName }}" width="172" height="52" loading="lazy">
                @else
                    <img class="brand-logo-default" src="{{ asset('images/kopidrip.png') }}" alt="" width="48" height="48" loading="lazy">
                    <span>{{ $brandName }}</span>
                @endif
            </a>
            @if (filled($websiteSettings->tagline))
                <p class="mt-5 max-w-xs text-sm leading-6 text-white/65">{{ $websiteSettings->tagline }}</p>
            @endif
        </div>

        <div>
            <h2 class="footer-heading">Jelajahi</h2>
            <ul class="footer-links">
                <li><a href="{{ route('about') }}">Cerita kami</a></li>
                <li><a href="{{ route('coffee') }}">Kopi & roastery</a></li>
                <li><a href="{{ route('products.index') }}">Produk</a></li>
                <li><a href="{{ route('journal.index') }}">Jurnal</a></li>
                <li><a href="{{ route('faq') }}">FAQ</a></li>
            </ul>
        </div>

        <div>
            <h2 class="footer-heading">Kunjungi & hubungi</h2>
            <ul class="footer-links">
                @if (filled($websiteSettings->address)) <li>{{ $websiteSettings->address }}</li> @endif
                @if (filled($websiteSettings->phone)) <li><a href="tel:{{ preg_replace('/[^+0-9]/', '', $websiteSettings->phone) }}">{{ $websiteSettings->phone }}</a></li> @endif
                @if (filled($websiteSettings->email)) <li><a href="mailto:{{ $websiteSettings->email }}">{{ $websiteSettings->email }}</a></li> @endif
                @if (filled($websiteSettings->opening_hours))
                    @foreach ((array) $websiteSettings->opening_hours as $day => $hours)
                        @if (is_string($hours) && filled($hours))
                            <li>{{ is_string($day) ? ucfirst($day).' · ' : '' }}{{ $hours }}</li>
                        @endif
                    @endforeach
                @endif
                <li><a href="{{ route('location') }}">Detail lokasi</a></li>
            </ul>
        </div>

        @php
            $socialLinks = collect([
                'Instagram' => $websiteSettings->instagram,
                'Facebook' => $websiteSettings->facebook,
                'TikTok' => $websiteSettings->tiktok,
                'YouTube' => $websiteSettings->youtube,
            ])->filter(fn ($url) => is_string($url) && str_starts_with($url, 'https://'));
        @endphp
        @if ($socialLinks->isNotEmpty())
            <div>
                <h2 class="footer-heading">Ikuti</h2>
                <ul class="footer-links">
                    @foreach ($socialLinks as $label => $url)
                        <li><a href="{{ $url }}" target="_blank" rel="noopener noreferrer">{{ $label }} <span aria-hidden="true">↗</span></a></li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
    <div class="border-t border-white/15">
        <div class="site-container flex flex-col gap-3 py-5 text-xs text-white/55 sm:flex-row sm:items-center sm:justify-between">
            <p>© {{ now()->year }} {{ $brandName }}</p>
            <p>Sidikalang · Indonesia</p>
        </div>
    </div>
</footer>
