<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} | Foundation</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-stone-50 text-stone-900 antialiased">
    <main class="mx-auto flex min-h-screen max-w-4xl items-center px-6 py-16">
        <section class="w-full rounded-2xl border border-stone-200 bg-white p-8 shadow-sm sm:p-12" x-data="{ ready: true }">
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-amber-800">Kopi Drip Sidikalang</p>
            <h1 class="mt-4 text-3xl font-semibold tracking-tight sm:text-4xl">Fondasi website siap dikembangkan</h1>
            <p class="mt-4 max-w-2xl leading-7 text-stone-600">Halaman pemeriksaan sederhana untuk memastikan Blade, Vite, Tailwind CSS, dan Alpine.js terhubung.</p>
            <p class="mt-6 inline-flex items-center gap-2 rounded-full bg-emerald-50 px-4 py-2 text-sm font-medium text-emerald-800" x-show="ready">
                <span class="size-2 rounded-full bg-emerald-600"></span> Frontend aktif
            </p>
            <p class="mt-8 text-sm text-stone-500">Panel admin tersedia di <a class="font-medium text-amber-800 underline" href="{{ url('/admin') }}">/admin</a>.</p>
        </section>
    </main>
</body>
</html>