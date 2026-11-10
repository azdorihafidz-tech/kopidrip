@extends('layouts.public')
@section('seo_title', 'Checkout')
@section('seo_description', 'Masukkan data penerima untuk membuat pesanan.')
@section('seo_robots', 'noindex,nofollow')
@section('content')
<section class="section-space"><div class="site-container grid max-w-5xl gap-12 lg:grid-cols-2">
    <div><p class="eyebrow">Pemesanan</p><h1 class="mt-3 font-display text-4xl">Checkout</h1><p class="mt-4 text-muted">Pesanan dibuat tanpa pembayaran online. Admin akan mengonfirmasi ongkos kirim, total akhir, dan instruksi pembayaran sebelum Anda membayar.</p>
        <form class="mt-8 space-y-4" method="POST" action="{{ route('checkout.store') }}">@csrf
            @foreach (['name'=>'Nama penerima','email'=>'Email','phone'=>'Nomor telepon','address'=>'Alamat','city'=>'Kota/Kabupaten','province'=>'Provinsi','postal_code'=>'Kode pos'] as $field=>$label)
                <div><label class="mb-1 block text-sm font-medium" for="{{ $field }}">{{ $label }}</label><input class="form-input w-full" id="{{ $field }}" name="{{ $field }}" value="{{ old($field) }}" @if ($field==='email') type="email" @elseif ($field==='phone') type="tel" @else type="text" @endif required maxlength="{{ $field==='address' ? 255 : 160 }}" autocomplete="{{ $field==='name' ? 'name' : ($field==='email' ? 'email' : ($field==='phone' ? 'tel' : 'off')) }}"></div>
            @endforeach
            <div><label class="mb-1 block text-sm font-medium" for="notes">Catatan (opsional)</label><textarea class="form-input w-full" id="notes" name="notes" rows="3" maxlength="2000">{{ old('notes') }}</textarea></div>
            <button class="button button-dark" type="submit">Buat pesanan</button>
        </form>
    </div>
    <aside class="h-fit rounded-xl bg-white p-6"><h2 class="font-semibold">Ringkasan</h2><ul class="mt-4 space-y-3">@foreach ($cart['items'] as $line)<li class="flex justify-between gap-3 text-sm"><span>{{ $line['product']->name }} × {{ $line['quantity'] }}</span><span>{{ \App\Support\Money::formatIdr($line['line_total_minor']) }}</span></li>@endforeach</ul><p class="mt-5 border-t border-ink/10 pt-4">Subtotal <strong>{{ \App\Support\Money::formatIdr($cart['subtotal_minor']) }}</strong></p><p class="mt-2 text-sm text-muted">Ongkir menyusul konfirmasi admin.</p></aside>
</div></section>
@endsection
