@extends('layouts.public')

@section('seo_title', $cmsPage->seo_title ?: $cmsPage->title)
@section('seo_description', $cmsPage->seo_description ?: $cmsPage->excerpt ?: '')
@section('seo_canonical', $cmsPage->canonical_url ?: '')
@section('seo_image', $cmsPage->og_image ?: $cmsPage->featured_image ?: '')

@section('content')
    @php($whatsappUrl = $websiteSettings->whatsappUrl('Halo Kopi Drip, saya ingin bertanya.'))
    <x-public.page-hero eyebrow="Kontak" :title="$cmsPage->title" :description="$cmsPage->excerpt" />
    <section class="section-space"><div class="site-container grid gap-12 lg:grid-cols-[0.8fr_1fr]">
        <div>
            @if (filled($safeContent))<div class="prose-copy mb-8">{!! $safeContent !!}</div>@endif
            <x-public.section-heading eyebrow="Kontak resmi" title="Informasi yang tersedia." description="Data kontak ditampilkan dari Website Settings." />
            <div class="mt-8 space-y-5">
                @if (filled($websiteSettings->email))<p><span class="eyebrow">Email</span><a class="mt-1 block text-lg underline" href="mailto:{{ $websiteSettings->email }}">{{ $websiteSettings->email }}</a></p>@endif
                @if (filled($websiteSettings->phone))<p><span class="eyebrow">Telepon</span><a class="mt-1 block text-lg underline" href="tel:{{ preg_replace('/[^+0-9]/', '', $websiteSettings->phone) }}">{{ $websiteSettings->phone }}</a></p>@endif
                @if (filled($websiteSettings->whatsapp))<p><span class="eyebrow">WhatsApp</span>@if ($whatsappUrl)<a class="mt-1 block text-lg underline" href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer">{{ $websiteSettings->whatsapp }}</a>@else<span class="mt-1 block text-lg">{{ $websiteSettings->whatsapp }}</span>@endif</p>@endif
                @if (filled($websiteSettings->address))<p><span class="eyebrow">Alamat</span><span class="mt-1 block leading-7">{{ $websiteSettings->address }}</span></p>@endif
                @if (!filled($websiteSettings->email) && !filled($websiteSettings->phone) && !filled($websiteSettings->whatsapp) && !filled($websiteSettings->address))<p class="leading-7 text-muted">Belum ada informasi kontak yang diatur.</p>@endif
            </div>
            @if ($whatsappUrl)
                <a class="button button-whatsapp mt-8 w-full justify-center sm:w-auto" href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer">
                    <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" class="size-5"><path d="M20 11.7a8.1 8.1 0 0 1-11.95 7.1L4 20l1.25-3.82A8.1 8.1 0 1 1 20 11.7Z" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/><path d="M8.9 8.5c.2-.45.4-.46.7-.47h.5c.17 0 .35.06.45.34l.68 1.63c.08.2.04.38-.1.55l-.5.62c-.15.18-.3.33-.13.62.17.29.75 1.24 1.63 2 .98.86 1.8 1.12 2.12 1.25.27.12.44.1.61-.1l.75-.88c.18-.21.36-.25.6-.15l1.52.73c.23.11.39.17.45.27.06.1.06.57-.14 1.1-.2.52-1.14 1.02-1.57 1.06-.4.04-.9.06-1.46-.12-.34-.11-.78-.25-1.34-.5-2.36-1.03-3.9-3.47-4.02-3.63-.12-.16-.95-1.26-.95-2.4 0-1.14.6-1.7.8-1.92Z" fill="currentColor"/></svg>
                    Chat lewat WhatsApp
                    <span aria-hidden="true">↗</span>
                </a>
            @endif
        </div>
        <div class="contact-form-wrap">
            <h2 class="font-display text-3xl">Kirim pertanyaan</h2>
            @if (session('contact-status'))<p class="form-notice mt-5" role="status">{{ session('contact-status') }}</p>@endif
            <p class="mt-3 text-sm leading-6 text-muted">Pesan akan tersimpan di admin agar tim Kopi Drip dapat menindaklanjutinya. Jangan kirim informasi pembayaran atau kata sandi.</p>
            <form class="mt-7 space-y-5" action="{{ route('contact.store') }}" method="post">
                @csrf
                <div><label class="form-label" for="name">Nama</label><input class="form-input" id="name" name="name" value="{{ old('name') }}" autocomplete="name" required maxlength="120" @error('name') aria-invalid="true" aria-describedby="name-error" @enderror>@error('name')<p class="form-error" id="name-error">{{ $message }}</p>@enderror</div>
                <div><label class="form-label" for="email">Email</label><input class="form-input" id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required maxlength="255" @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>@error('email')<p class="form-error" id="email-error">{{ $message }}</p>@enderror</div>
                <div><label class="form-label" for="subject">Subjek</label><input class="form-input" id="subject" name="subject" value="{{ old('subject') }}" required maxlength="150" @error('subject') aria-invalid="true" aria-describedby="subject-error" @enderror>@error('subject')<p class="form-error" id="subject-error">{{ $message }}</p>@enderror</div>
                <div><label class="form-label" for="message">Pesan</label><textarea class="form-input min-h-36 resize-y" id="message" name="message" required maxlength="3000" @error('message') aria-invalid="true" aria-describedby="message-error" @enderror>{{ old('message') }}</textarea>@error('message')<p class="form-error" id="message-error">{{ $message }}</p>@enderror</div>
                <button class="button button-dark w-full justify-center sm:w-auto" type="submit">Kirim pesan <span aria-hidden="true">↗</span></button>
            </form>
        </div>
    </div></section>
@endsection
