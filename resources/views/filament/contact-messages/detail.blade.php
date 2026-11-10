<dl class="space-y-4 text-sm">
    <div>
        <dt class="font-semibold">Nama</dt>
        <dd class="mt-1">{{ $contactMessage->name }}</dd>
    </div>
    <div>
        <dt class="font-semibold">Email</dt>
        <dd class="mt-1"><a class="text-primary-600 underline" href="mailto:{{ $contactMessage->email }}">{{ $contactMessage->email }}</a></dd>
    </div>
    <div>
        <dt class="font-semibold">Diterima</dt>
        <dd class="mt-1">{{ $contactMessage->created_at?->format('d M Y, H:i') }}</dd>
    </div>
    <div>
        <dt class="font-semibold">Pesan</dt>
        <dd class="mt-1 whitespace-pre-wrap leading-7">{{ $contactMessage->message }}</dd>
    </div>
</dl>
