@if ($faqs->isNotEmpty())
    <section class="section-space" aria-labelledby="home-faq-heading">
        <div class="site-container grid gap-9 lg:grid-cols-[0.7fr_1.3fr]">
            <div>
                <p class="eyebrow">Ada pertanyaan?</p>
                <h2 id="home-faq-heading" class="mt-3 font-display text-3xl leading-tight sm:text-4xl">Kami bantu jawab.</h2>
                <p class="mt-4 max-w-md leading-7 text-muted">Informasi pemesanan dan produk yang sering ditanyakan.</p>
                <a class="text-link mt-5 inline-flex" href="{{ route('faq') }}">Lihat semua FAQ <span aria-hidden="true">↗</span></a>
            </div>
            <div class="divide-y divide-ink/15 border-y border-ink/15">
                @foreach ($faqs as $faq)
                    <details class="group py-5">
                        <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-semibold marker:hidden">
                            <span>{{ $faq->question }}</span>
                            <span class="font-normal text-muted transition group-open:rotate-45" aria-hidden="true">+</span>
                        </summary>
                        <p class="mt-4 whitespace-pre-line pr-8 text-sm leading-7 text-muted">{{ $faq->answer }}</p>
                    </details>
                @endforeach
            </div>
        </div>
    </section>
@endif
