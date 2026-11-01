<div class="space-y-6 text-sm">
    <section class="grid gap-4 sm:grid-cols-2">
        <div><h3 class="font-semibold">Penerima</h3><p>{{ $order->customer_name }}</p><p>{{ $order->customer_email }}</p><p>{{ $order->customer_phone }}</p></div>
        <div><h3 class="font-semibold">Alamat</h3><p>{{ $order->shipping_address }}</p><p>{{ $order->shipping_city }}, {{ $order->shipping_province }} {{ $order->shipping_postal_code }}</p></div>
    </section>
    @if ($order->customer_notes)<section><h3 class="font-semibold">Catatan pelanggan</h3><p class="whitespace-pre-wrap">{{ $order->customer_notes }}</p></section>@endif
    <section><h3 class="font-semibold">Item pesanan</h3><ul class="mt-2 divide-y divide-gray-200">@foreach ($order->items as $item)<li class="flex justify-between gap-4 py-2"><span>{{ $item->product_name }}{{ $item->variant_name ? ' · '.$item->variant_name : '' }} × {{ $item->quantity }}</span><span>{{ \App\Support\Money::formatIdr(\App\Support\Money::toMinor((string) $item->line_total)) }}</span></li>@endforeach</ul></section>
    <section class="grid gap-2 sm:grid-cols-2"><p>Subtotal: <strong>{{ \App\Support\Money::formatIdr($order->subtotal_minor) }}</strong></p><p>Ongkir: <strong>{{ $order->shipping_cost === null ? 'Belum ditetapkan' : \App\Support\Money::formatIdr(\App\Support\Money::toMinor((string) $order->shipping_cost)) }}</strong></p><p>Total: <strong>{{ $order->total_minor === null ? 'Belum ditetapkan' : \App\Support\Money::formatIdr($order->total_minor) }}</strong></p><p>Status pembayaran: <strong>{{ $order->payment_status->value }}</strong></p></section>
    @if ($order->admin_notes)<section><h3 class="font-semibold">Catatan internal</h3><p class="whitespace-pre-wrap">{{ $order->admin_notes }}</p></section>@endif
</div>
