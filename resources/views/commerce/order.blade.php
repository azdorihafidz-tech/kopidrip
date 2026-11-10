@extends('layouts.public')
@section('seo_title', 'Status pesanan')
@section('seo_description', 'Ringkasan dan status pesanan Kopi Drip Sidikalang.')
@section('seo_robots', 'noindex,nofollow')
@section('content')
<section class="section-space"><div class="site-container max-w-3xl">
    <p class="eyebrow">Pesanan dibuat</p><h1 class="mt-3 font-display text-4xl">{{ $order->order_number }}</h1>
    <p class="mt-5">Terima kasih, {{ $order->customer_name }}. Pesanan Anda menunggu konfirmasi admin.</p>
    <div class="mt-6 rounded-xl bg-amber-50 p-5 text-amber-950"><h2 class="font-semibold">Langkah selanjutnya</h2><p class="mt-2 text-sm leading-6">Admin akan memeriksa pesanan, mengonfirmasi ongkos kirim, dan memberi tahu total akhir serta instruksi pembayaran. Jangan lakukan pembayaran sebelum menerima konfirmasi.</p></div>
    <dl class="mt-7 grid gap-4 sm:grid-cols-2"><div><dt class="text-sm text-muted">Status pesanan</dt><dd>{{ $order->status->label() }}</dd></div><div><dt class="text-sm text-muted">Status pembayaran</dt><dd>{{ ucfirst($order->payment_status->value) }} — menunggu konfirmasi admin.</dd></div><div><dt class="text-sm text-muted">Status ongkir</dt><dd>{{ $order->shipping_status->value === 'quote_pending' ? 'Menunggu penawaran ongkir' : ucfirst(str_replace('_', ' ', $order->shipping_status->value)) }}</dd></div><div><dt class="text-sm text-muted">Subtotal</dt><dd>{{ \App\Support\Money::formatIdr($order->subtotal_minor) }}</dd></div><div><dt class="text-sm text-muted">Biaya kirim</dt><dd>{{ $order->shipping_cost === null ? 'Menunggu konfirmasi admin' : \App\Support\Money::formatIdr(\App\Support\Money::toMinor((string) $order->shipping_cost)) }}</dd></div><div><dt class="text-sm text-muted">Total akhir</dt><dd>{{ $order->total_minor === null ? 'Menunggu konfirmasi ongkir' : \App\Support\Money::formatIdr($order->total_minor) }}</dd></div>
        @if ($order->courier)<div><dt class="text-sm text-muted">Kurir</dt><dd>{{ $order->courier }}{{ $order->shipping_service ? ' · '.$order->shipping_service : '' }}</dd></div>@endif
        @if ($order->tracking_number)<div><dt class="text-sm text-muted">Nomor resi</dt><dd>{{ $order->tracking_number }}</dd></div>@endif
    </dl>
    <h2 class="mt-9 font-semibold">Item pesanan</h2><ul class="mt-3 divide-y divide-ink/10 border-y border-ink/10">@foreach ($order->items as $item)<li class="flex justify-between gap-4 py-3"><span>{{ $item->product_name }}{{ $item->variant_name ? ' — '.$item->variant_name : '' }} × {{ $item->quantity }}</span><strong>{{ \App\Support\Money::formatIdr(\App\Support\Money::toMinor((string) $item->line_total)) }}</strong></li>@endforeach</ul>
    @if (session('order_access_token'))<p class="mt-6 text-sm text-muted">Simpan tautan halaman ini untuk melihat status pesanan.</p>@endif
</div></section>
@endsection
