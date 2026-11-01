<div class="grid gap-6 xl:grid-cols-3">
    <section class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10" aria-labelledby="visitor-countries-heading">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h2 id="visitor-countries-heading" class="text-base font-semibold text-gray-950 dark:text-white">Pengunjung menurut negara</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Visitors unik selama 7 hari terakhir</p>
            </div>
            <span class="grid size-10 shrink-0 place-items-center rounded-full bg-amber-50 text-amber-800 dark:bg-amber-400/10 dark:text-amber-300" aria-hidden="true">
                <x-heroicon-o-globe-asia-australia class="size-5" />
            </span>
        </div>

        @if ($countries->isNotEmpty())
            <ul class="mt-5 divide-y divide-gray-100 dark:divide-white/5">
                @foreach ($countries as $country)
                    <li class="flex items-center justify-between gap-4 py-3 first:pt-0 last:pb-0">
                        <span class="flex min-w-0 items-center gap-3">
                            <span class="grid size-8 shrink-0 place-items-center rounded-full bg-gray-50 text-[0.65rem] font-semibold tracking-wide text-gray-600 dark:bg-white/5 dark:text-gray-300">{{ $country['code'] }}</span>
                            <span class="truncate text-sm font-medium text-gray-800 dark:text-gray-200">{{ $country['name'] }}</span>
                        </span>
                        <span class="shrink-0 text-sm tabular-nums text-gray-500 dark:text-gray-400">{{ number_format($country['visitors'], 0, ',', '.') }} visitors</span>
                    </li>
                @endforeach
            </ul>
        @else
            <div class="mt-5 rounded-lg bg-gray-50 px-4 py-4 text-sm leading-6 text-gray-600 dark:bg-white/5 dark:text-gray-300">
                Data negara akan muncul saat situs menerima header negara dari Cloudflare. Tayangan halaman tetap dihitung tanpa menyimpan alamat IP.
            </div>
        @endif
    </section>

    <section class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10" aria-labelledby="popular-pages-heading">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h2 id="popular-pages-heading" class="text-base font-semibold text-gray-950 dark:text-white">Halaman terpopuler</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Diurutkan berdasarkan page views</p>
            </div>
            <span class="grid size-10 shrink-0 place-items-center rounded-full bg-amber-50 text-amber-800 dark:bg-amber-400/10 dark:text-amber-300" aria-hidden="true">
                <x-heroicon-o-document-chart-bar class="size-5" />
            </span>
        </div>

        @if ($topPages->isNotEmpty())
            <ol class="mt-5 divide-y divide-gray-100 dark:divide-white/5">
                @foreach ($topPages as $index => $page)
                    <li class="flex items-center justify-between gap-4 py-3 first:pt-0 last:pb-0">
                        <span class="flex min-w-0 items-center gap-3">
                            <span class="w-5 shrink-0 text-xs tabular-nums text-gray-400">{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</span>
                            <span class="truncate text-sm font-medium text-gray-800 dark:text-gray-200">{{ $page->path }}</span>
                        </span>
                        <span class="shrink-0 text-sm tabular-nums text-gray-500 dark:text-gray-400">{{ number_format($page->views, 0, ',', '.') }} views</span>
                    </li>
                @endforeach
            </ol>
        @else
            <p class="mt-5 rounded-lg bg-gray-50 px-4 py-4 text-sm text-gray-600 dark:bg-white/5 dark:text-gray-300">Belum ada tayangan halaman dalam periode ini.</p>
        @endif
    </section>

    <section class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10" aria-labelledby="visitor-sources-heading">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h2 id="visitor-sources-heading" class="text-base font-semibold text-gray-950 dark:text-white">Sumber pengunjung</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Situs yang mengarahkan pengunjung</p>
            </div>
            <span class="grid size-10 shrink-0 place-items-center rounded-full bg-amber-50 text-amber-800 dark:bg-amber-400/10 dark:text-amber-300" aria-hidden="true">
                <x-heroicon-o-arrow-trending-up class="size-5" />
            </span>
        </div>

        @if ($topSources->isNotEmpty())
            <ul class="mt-5 divide-y divide-gray-100 dark:divide-white/5">
                @foreach ($topSources as $source)
                    <li class="flex items-center justify-between gap-4 py-3 first:pt-0 last:pb-0">
                        <span class="truncate text-sm font-medium text-gray-800 dark:text-gray-200">{{ $source['name'] }}</span>
                        <span class="shrink-0 text-sm tabular-nums text-gray-500 dark:text-gray-400">{{ number_format($source['visits'], 0, ',', '.') }} visits</span>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="mt-5 rounded-lg bg-gray-50 px-4 py-4 text-sm text-gray-600 dark:bg-white/5 dark:text-gray-300">Sumber pengunjung akan tampil setelah tersedia.</p>
        @endif
    </section>
</div>
