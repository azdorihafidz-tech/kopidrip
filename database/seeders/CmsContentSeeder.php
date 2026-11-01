<?php

namespace Database\Seeders;

use App\Models\CmsPage;
use App\Models\HomepageSection;
use App\Models\NavigationItem;
use Illuminate\Database\Seeder;

class CmsContentSeeder extends Seeder
{
    public function run(): void
    {
        $sections = [
            ['section_key' => 'hero', 'label' => 'Hero', 'eyebrow' => 'Sidikalang · Indonesia', 'title' => 'Kopi Drip', 'subtitle' => 'Sidikalang', 'description' => "Coffee shop, coffee roastery, dan online store.\nSatu ruang untuk mengenal Kopi Drip—mulai dari kopi, cerita, hingga pilihan yang tersedia.", 'button_text' => 'Jelajahi produk', 'button_url' => '/products', 'secondary_button_text' => 'Tentang kami', 'secondary_button_url' => '/about'],
            ['section_key' => 'introduction', 'label' => 'Introduction', 'eyebrow' => 'Selamat datang', 'title' => 'Sebuah ruang untuk kopi.', 'description' => 'Kopi Drip Sidikalang bergerak di ranah coffee shop, coffee roastery, dan online store.', 'button_text' => 'Kenali Kopi Drip', 'button_url' => '/about'],
            ['section_key' => 'featured_coffee', 'label' => 'Featured coffee', 'eyebrow' => 'Kopi pilihan', 'title' => 'Kenali kopi dari Kopi Drip.', 'description' => 'Detail proses, kategori, dan pilihan kopi akan ditambahkan setelah informasi resmi tersedia.', 'button_text' => 'Jelajahi kopi', 'button_url' => '/coffee'],
            ['section_key' => 'featured_products', 'label' => 'Featured products', 'eyebrow' => 'Pilihan kopi', 'title' => 'Dari roastery.', 'description' => 'Pilihan produk yang telah dipublikasikan akan hadir di sini.', 'button_text' => 'Semua produk', 'button_url' => '/products'],
            ['section_key' => 'coffee_shop', 'label' => 'Coffee shop', 'eyebrow' => 'Coffee shop', 'title' => 'Singgah, pilih, nikmati.', 'description' => 'Informasi pengalaman, menu, dan suasana akan diperbarui melalui konten resmi Kopi Drip Sidikalang.', 'button_text' => 'Lihat menu', 'button_url' => '/menu'],
            ['section_key' => 'our_story', 'label' => 'Our story', 'eyebrow' => 'Tentang kami', 'title' => 'Cerita Kopi Drip Sidikalang akan hadir di sini.', 'button_text' => 'Baca cerita', 'button_url' => '/about', 'secondary_button_text' => 'Jelajahi kopi', 'secondary_button_url' => '/coffee'],
            ['section_key' => 'gallery', 'label' => 'Gallery', 'eyebrow' => 'Ruang visual', 'title' => 'Kopi Drip, dalam gambar.', 'description' => 'Foto resmi akan ditampilkan setelah tersedia.', 'button_text' => 'Buka galeri', 'button_url' => '/gallery'],
            ['section_key' => 'journal', 'label' => 'Journal', 'eyebrow' => 'Jurnal', 'title' => 'Catatan seputar kopi.', 'description' => 'Tulisan yang telah dipublikasikan akan muncul di sini.', 'button_text' => 'Semua tulisan', 'button_url' => '/journal'],
            ['section_key' => 'location', 'label' => 'Location', 'eyebrow' => 'Lokasi & kontak', 'title' => 'Informasi kunjungan akan diperbarui di sini.', 'button_text' => 'Detail lokasi', 'button_url' => '/location'],
            ['section_key' => 'cta', 'label' => 'Call to action', 'eyebrow' => 'Kopi Drip Sidikalang', 'title' => 'Ada yang ingin ditanyakan?', 'button_text' => 'Hubungi kami', 'button_url' => '/contact'],
        ];

        foreach ($sections as $index => $section) {
            HomepageSection::query()->firstOrCreate(
                ['section_key' => $section['section_key']],
                $section + ['is_visible' => true, 'sort_order' => ($index + 1) * 10],
            );
        }

        $pages = [
            ['slug' => 'about', 'title' => 'Kopi Drip Sidikalang', 'excerpt' => 'Coffee shop · Coffee roastery · Online store', 'content' => 'Cerita, filosofi, dan perjalanan brand akan disampaikan di sini. Informasi ini menunggu materi resmi dari pemilik.'],
            ['slug' => 'coffee', 'title' => 'Kenali kopi dari Kopi Drip.', 'excerpt' => 'Halaman ini menjadi rumah bagi informasi kopi dan produk roastery yang telah disiapkan untuk dipublikasikan.', 'content' => 'Detail proses, kategori, dan pilihan kopi akan ditambahkan setelah informasi resmi tersedia.'],
            ['slug' => 'contact', 'title' => 'Mari terhubung.', 'excerpt' => 'Informasi kontak resmi Kopi Drip Sidikalang ditampilkan berdasarkan data Website Settings.', 'content' => 'Informasi kontak akan muncul setelah diatur pada Website Settings.'],
            ['slug' => 'location', 'title' => 'Kunjungi Kopi Drip.', 'excerpt' => 'Informasi lokasi dan kunjungan mengikuti data resmi Website Settings.', 'content' => 'Alamat dan jam operasional akan ditampilkan setelah dikonfirmasi pemilik.'],
        ];

        foreach ($pages as $page) {
            CmsPage::query()->firstOrCreate(
                ['slug' => $page['slug']],
                $page + ['status' => 'published', 'published_at' => now()],
            );
        }

        $navigation = [
            ['label' => 'Tentang', 'url' => '/about'],
            ['label' => 'Kopi', 'url' => '/coffee'],
            ['label' => 'Belanja', 'url' => '/products'],
            ['label' => 'Menu', 'url' => '/menu'],
            ['label' => 'Jurnal', 'url' => '/journal'],
            ['label' => 'Galeri', 'url' => '/gallery'],
            ['label' => 'Kontak', 'url' => '/contact'],
        ];

        foreach ($navigation as $index => $item) {
            NavigationItem::query()->firstOrCreate(
                ['label' => $item['label'], 'url' => $item['url']],
                $item + ['sort_order' => ($index + 1) * 10, 'is_active' => true],
            );
        }
    }
}
