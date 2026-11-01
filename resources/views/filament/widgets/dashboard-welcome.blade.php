<section class="relative isolate overflow-hidden rounded-2xl bg-stone-950 px-6 py-7 text-white shadow-sm sm:px-8 sm:py-9" aria-labelledby="admin-welcome-heading">
    <div class="pointer-events-none absolute -right-16 -top-28 -z-10 size-80 rounded-full border border-amber-100/10"></div>
    <div class="pointer-events-none absolute -right-6 -top-16 -z-10 size-56 rounded-full border border-amber-100/10"></div>
    <div class="flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
        <div class="max-w-2xl">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-amber-200">Ruang pengelola · {{ $today }}</p>
            <h2 id="admin-welcome-heading" class="mt-3 font-serif text-2xl font-semibold tracking-tight sm:text-3xl">Selamat datang{{ $adminName ? ', '.$adminName : '' }}</h2>
            <p class="mt-3 max-w-xl text-sm leading-6 text-stone-300">Pantau perjalanan pengunjung dan kelola konten Kopi Drip dari satu tempat.</p>
        </div>
        <a href="{{ route('home') }}" target="_blank" rel="noopener noreferrer" class="inline-flex min-h-11 shrink-0 items-center justify-center gap-2 rounded-lg bg-amber-100 px-4 py-2.5 text-sm font-semibold text-stone-950 transition hover:bg-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-200">
            Lihat website <x-heroicon-m-arrow-top-right-on-square class="size-4" />
        </a>
    </div>
</section>
