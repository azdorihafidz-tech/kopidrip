@extends('layouts.public')

@section('seo_title', isset($category) ? ($category->seo_title ?: $category->name) : 'Produk Kopi')
@section('seo_description', isset($category) ? ($category->seo_description ?: $category->description ?: '') : 'Jelajahi produk Kopi Drip Sidikalang yang telah dipublikasikan.')

@section('content')
    <x-public.page-hero eyebrow="Produk" :title="isset($category) ? $category->name : 'Pilihan dari Kopi Drip.'" :description="isset($category) ? $category->description : 'Daftar produk akan menampilkan informasi dan harga yang telah dipublikasikan.'" />
    <section class="section-space"><div class="site-container">
        @if ($categories->isNotEmpty())
            <nav class="mb-8 flex flex-wrap gap-3" aria-label="Kategori produk">
                @foreach ($categories as $productCategory)
                    <a class="button button-outline-dark button-small" href="{{ route('products.category', $productCategory->slug) }}">{{ $productCategory->name }}</a>
                @endforeach
            </nav>
        @endif
        @if ($products->isNotEmpty())
            <div class="grid gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($products as $product)<x-public.product-card :product="$product" />@endforeach
            </div>
            <div class="mt-12">{{ $products->links() }}</div>
        @else
            <x-public.empty-state title="Belum ada produk untuk ditampilkan" description="Produk akan muncul di sini setelah dipublikasikan." />
        @endif
    </div></section>
@endsection
