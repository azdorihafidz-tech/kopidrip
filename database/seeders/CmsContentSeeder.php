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
            ['section_key' => 'hero', 'label' => 'Hero', 'eyebrow' => 'Kopi Sidikalang · Indonesia', 'title' => 'Secangkir kopi,', 'subtitle' => 'diracik dengan cerita.', 'description' => 'Kenali kopi, jelajahi cara seduh, dan ikuti kabar terbaru dari Kopi Drip Sidikalang.', 'button_text' => 'Jelajahi kopi', 'button_url' => '/coffee', 'secondary_button_text' => 'Hubungi kami', 'secondary_button_url' => '/contact'],
            ['section_key' => 'introduction', 'label' => 'Tentang', 'eyebrow' => 'Tentang Kopi Drip', 'title' => 'Ruang untuk menikmati kopi dengan cara Anda.', 'description' => 'Temukan inspirasi seduh, catatan seputar kopi, dan informasi terbaru dari Kopi Drip Sidikalang.', 'button_text' => 'Baca jurnal', 'button_url' => '/journal'],
            ['section_key' => 'featured_coffee', 'label' => 'Eksplorasi kopi', 'eyebrow' => 'Eksplorasi kopi', 'title' => 'Mulai dari rasa ingin tahu.', 'description' => 'Pelajari biji kopi, sangrai, dan metode seduh. Informasi setiap produk ditampilkan setelah detail resminya tersedia.', 'button_text' => 'Kenali kopi', 'button_url' => '/coffee'],
            ['section_key' => 'featured_products', 'label' => 'Produk pilihan', 'eyebrow' => 'Pilihan Kopi Drip', 'title' => 'Temukan kopi untuk menemani hari.', 'description' => 'Tanyakan pilihan produk, harga, dan ketersediaan melalui halaman kontak.', 'button_text' => 'Tanyakan produk', 'button_url' => '/contact'],
            ['section_key' => 'coffee_shop', 'label' => 'Ritual kopi', 'eyebrow' => 'Ritual sehari-hari', 'title' => 'Nikmati kopi dengan ritme sendiri.', 'description' => 'Dari seduhan harian hingga waktu jeda, temukan cara menikmati kopi yang paling Anda sukai.', 'button_text' => 'Hubungi kami', 'button_url' => '/contact'],
            ['section_key' => 'our_story', 'label' => 'Tentang Kopi Drip', 'eyebrow' => 'Kopi Drip Sidikalang', 'title' => 'Kopi, cerita, dan ruang untuk terus mengeksplorasi.', 'description' => 'Kenali dunia kopi melalui panduan seduh, cerita, dan informasi terbaru dari Kopi Drip Sidikalang.', 'button_text' => 'Baca jurnal', 'button_url' => '/journal', 'secondary_button_text' => 'Hubungi kami', 'secondary_button_url' => '/contact'],
            ['section_key' => 'gallery', 'label' => 'Galeri editorial', 'eyebrow' => 'Galeri editorial', 'title' => 'Momen di sekitar kopi.', 'description' => 'Lihat visual editorial tentang biji kopi dan cara menyeduh.', 'button_text' => 'Lihat galeri', 'button_url' => '/gallery'],
            ['section_key' => 'journal', 'label' => 'Jurnal', 'eyebrow' => 'Jurnal', 'title' => 'Catatan untuk penikmat kopi.', 'description' => 'Panduan ringan untuk mengenal rasa dan mengeksplorasi metode seduh.', 'button_text' => 'Semua tulisan', 'button_url' => '/journal'],
            ['section_key' => 'location', 'label' => 'Kunjungi & hubungi', 'eyebrow' => 'Kunjungi & hubungi', 'title' => 'Cari informasi kunjungan.', 'description' => 'Untuk informasi terbaru tentang kunjungan, silakan hubungi Kopi Drip.', 'button_text' => 'Lihat kontak', 'button_url' => '/contact'],
            ['section_key' => 'cta', 'label' => 'Hubungi kami', 'eyebrow' => 'Kopi Drip Sidikalang', 'title' => 'Ada yang ingin ditanyakan?', 'description' => 'Hubungi kami untuk bertanya tentang kopi dan informasi terbaru.', 'button_text' => 'Hubungi kami', 'button_url' => '/contact'],
        ];

        foreach ($sections as $index => $section) {
            HomepageSection::query()->firstOrCreate(
                ['section_key' => $section['section_key']],
                $section + ['is_visible' => true, 'sort_order' => ($index + 1) * 10],
            );
        }

        $pages = [
            ['slug' => 'about', 'title' => 'Tentang Kopi Drip Sidikalang', 'excerpt' => 'Kopi, cerita, dan inspirasi untuk menikmati seduhan.', 'content' => '<p>Kopi Drip Sidikalang adalah ruang untuk mengenal kopi, mengeksplorasi cara seduh, dan mengikuti kabar terbaru seputar Kopi Drip.</p><p>Temukan catatan dan panduan umum tentang kopi. Informasi spesifik mengenai produk, menu, dan kunjungan ditampilkan setelah dikonfirmasi.</p>'],
            ['slug' => 'coffee', 'title' => 'Kopi & cara seduh', 'excerpt' => 'Jelajahi pengetahuan dasar tentang kopi dan cara menyeduhnya.', 'content' => '<h2>Mulai mengenal kopi</h2><p>Rasa dalam cangkir dipengaruhi oleh banyak hal, termasuk biji, proses pengolahan, tingkat sangrai, penyimpanan, air, dan metode seduh. Mengubah satu hal dalam satu waktu dapat membantu Anda memahami perbedaannya.</p><h2>Informasi produk</h2><p>Produk, harga, dan ketersediaan akan ditampilkan di katalog setelah detailnya dikonfirmasi.</p>'],
            ['slug' => 'contact', 'title' => 'Hubungi Kopi Drip', 'excerpt' => 'Kirim pertanyaan melalui formulir kontak atau WhatsApp yang tercantum.', 'content' => '<p>Kirim pesan melalui formulir di halaman ini. Jika nomor WhatsApp resmi telah dicantumkan, Anda juga dapat menghubungi kami lewat WhatsApp.</p>'],
            ['slug' => 'location', 'title' => 'Informasi kunjungan', 'excerpt' => 'Hubungi kami untuk informasi kunjungan terbaru.', 'content' => '<p>Untuk informasi kunjungan, alamat, dan jam operasional terbaru, silakan kirim pertanyaan melalui halaman kontak.</p>'],
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
            ['label' => 'Produk', 'url' => '/products'],
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
