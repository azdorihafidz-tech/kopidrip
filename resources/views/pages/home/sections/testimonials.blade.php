@if ($testimonials->isNotEmpty())
    <section class="section-space bg-cream" aria-labelledby="testimonials-heading">
        <div class="site-container">
            <div class="section-heading-row">
                <x-public.section-heading eyebrow="Cerita pelanggan" title="Kesan dari para penikmat kopi." description="Pendapat pelanggan yang telah memberi izin untuk ditampilkan." />
            </div>
            <div class="mt-9 grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($testimonials as $testimonial)
                    <figure class="flex h-full flex-col rounded-xl bg-paper p-6 shadow-sm sm:p-8">
                        <blockquote class="flex-1 font-display text-xl leading-relaxed">“{{ $testimonial->content }}”</blockquote>
                        <figcaption class="mt-7 border-t border-ink/10 pt-4 text-sm font-semibold">
                            {{ $testimonial->customer_name }}
                            @if ($testimonial->customer_label)<span class="mt-1 block font-normal text-muted">{{ $testimonial->customer_label }}</span>@endif
                        </figcaption>
                    </figure>
                @endforeach
            </div>
        </div>
    </section>
@endif
