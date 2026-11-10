<?php

namespace Database\Seeders;

use App\Models\CmsPage;
use App\Models\GalleryItem;
use App\Models\HomepageSection;
use App\Models\JournalPost;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\NavigationItem;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\WebsiteSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DemoContentSeeder extends Seeder
{
    public function run(): void
    {
        WebsiteSetting::query()->firstOrCreate([], ['site_is_demo' => true]);
        $this->seedHomepage();
        $this->seedPages();
        $this->seedNavigation();
        $this->seedProducts();
        $this->seedMenu();
        $this->seedGallery();
        $this->seedJournal();
    }

    private function seedHomepage(): void
    {
        $demoSections = [
            'hero' => [
                'eyebrow' => 'DEMO CONTENT — REPLACE BEFORE PRODUCTION',
                'title' => 'Discover the Character of',
                'subtitle' => 'Sidikalang Coffee.',
                'description' => 'Konsep website untuk mengenalkan kopi dengan karakter dan cerita. Narasi demonstrasi ini bukan klaim tentang asal bahan, proses, atau produk aktual.',
                'button_text' => 'Jelajahi kopi', 'button_url' => '/products',
                'secondary_button_text' => 'Kenali ceritanya', 'secondary_button_url' => '/about',
            ],
            'introduction' => [
                'eyebrow' => 'Coffee with a sense of place', 'title' => 'Ruang untuk mengenal karakter kopi.',
                'description' => 'Setiap cangkir dapat menjadi awal percakapan tentang aroma, rasa, dan cara seduh. Ini adalah konsep editorial untuk demo; detail bisnis dan produk menunggu informasi terverifikasi.',
                'button_text' => 'Kenali Kopi Drip', 'button_url' => '/about',
            ],
            'featured_coffee' => [
                'eyebrow' => 'Coffee character', 'title' => 'A character worth discovering.',
                'description' => 'Aroma · Sweetness · Body · Acidity · Finish\nKerangka pencatatan demo, bukan profil rasa produk aktual.',
                'button_text' => 'Jelajahi kopi', 'button_url' => '/coffee',
            ],
            'featured_products' => [
                'eyebrow' => 'Demo collection', 'title' => 'Meet the coffee.',
                'description' => 'Pilihan berikut adalah data demonstrasi dan tidak tersedia untuk dibeli. Ganti dengan katalog serta harga yang telah disetujui sebelum produksi.',
                'button_text' => 'Semua produk', 'button_url' => '/products',
            ],
            'coffee_shop' => [
                'eyebrow' => 'Brew, at your own pace', 'title' => 'Better coffee, one brew at a time.',
                'description' => 'Drip coffee · Manual brew · Everyday coffee\nContoh tema menu dan pengalaman; bukan daftar layanan atau menu outlet yang telah dikonfirmasi.',
                'button_text' => 'Lihat menu demo', 'button_url' => '/menu',
            ],
            'our_story' => [
                'eyebrow' => 'A sense of place', 'title' => 'Coffee, character, and the moments between.',
                'description' => 'Ruang editorial ini disiapkan untuk cerita brand yang terverifikasi. Belum ada riwayat, praktik sourcing, atau klaim produk yang dinyatakan sebagai fakta.',
                'button_text' => 'Tentang demo', 'button_url' => '/about',
                'secondary_button_text' => 'Jelajahi kopi', 'secondary_button_url' => '/coffee',
            ],
            'gallery' => [
                'eyebrow' => 'Visual study', 'title' => 'A look at the coffee ritual.',
                'description' => 'Ilustrasi editorial untuk pratinjau. Bukan dokumentasi outlet, produk, atau kegiatan aktual.',
                'button_text' => 'Buka galeri demo', 'button_url' => '/gallery',
            ],
            'journal' => [
                'eyebrow' => 'From the journal', 'title' => 'Notes for curious coffee drinkers.',
                'description' => 'Artikel demo untuk meninjau tata letak jurnal; periksa dan ganti materi sebelum publikasi.',
                'button_text' => 'Baca jurnal demo', 'button_url' => '/journal',
            ],
            'location' => [
                'eyebrow' => 'Find us', 'title' => 'Plan a visit when details are confirmed.',
                'description' => 'Alamat dan jam operasional hanya ditampilkan setelah pemilik mengisi Website Settings.',
                'button_text' => 'Detail lokasi', 'button_url' => '/location',
            ],
            'cta' => [
                'eyebrow' => 'Questions', 'title' => 'Let’s start a conversation.',
                'description' => 'Informasi kontak resmi akan ditampilkan setelah Website Settings dilengkapi.',
                'button_text' => 'Hubungi kami', 'button_url' => '/contact',
            ],
        ];

        $knownDefaultTitles = [
            'hero' => ['Kopi Drip'], 'introduction' => ['Sebuah ruang untuk kopi.', 'Ruang untuk mengenal kopi.'],
            'featured_coffee' => ['Kenali kopi dari Kopi Drip.'], 'featured_products' => ['Dari roastery.'],
            'coffee_shop' => ['Singgah, pilih, nikmati.'], 'our_story' => ['Cerita Kopi Drip Sidikalang akan hadir di sini.'],
            'gallery' => ['Kopi Drip, dalam gambar.'], 'journal' => ['Catatan seputar kopi.'],
            'location' => ['Informasi kunjungan akan diperbarui di sini.'], 'cta' => ['Ada yang ingin ditanyakan?'],
        ];

        foreach ($demoSections as $key => $content) {
            $section = HomepageSection::query()->where('section_key', $key)->first();
            if ($section && in_array($section->title, $knownDefaultTitles[$key] ?? [], true)) {
                $section->update($content);
            }
        }
    }

    private function seedPages(): void
    {
        $pages = [
            'about' => [
                'title' => 'DEMO — About Kopi Drip Sidikalang',
                'content' => '<p><strong>Demo content — replace before production.</strong></p><p>Bagian ini menunjukkan bagaimana cerita dan profil brand dapat ditampilkan. Tambahkan hanya informasi yang telah dikonfirmasi pemilik.</p>',
            ],
            'coffee' => [
                'title' => 'DEMO — Coffee & Roastery',
                'content' => '<p><strong>Demo content — replace before production.</strong></p><p>Halaman ini menjadi contoh untuk mengenalkan kopi dan pilihan produk. Informasi origin, proses, dan karakter produk harus diisi berdasarkan data resmi.</p>',
            ],
            'contact' => [
                'title' => 'DEMO — Contact',
                'content' => '<p>Informasi kontak di halaman ini berasal dari Website Settings. Data yang belum tersedia akan tetap kosong sampai pemilik mengisinya.</p>',
            ],
            'location' => [
                'title' => 'DEMO — Location',
                'content' => '<p>Alamat, jam operasional, dan peta hanya tampil setelah informasi resminya diatur di Website Settings.</p>',
            ],
        ];

        foreach ($pages as $slug => $demo) {
            $page = CmsPage::query()->where('slug', $slug)->first();

            if ($page && in_array($page->title, [
                'Kopi Drip Sidikalang', 'Kenali kopi dari Kopi Drip.', 'Mari terhubung.', 'Kunjungi Kopi Drip.',
            ], true)) {
                $page->update([
                    'title' => $demo['title'],
                    'content' => $demo['content'],
                    'excerpt' => 'Demo content — replace before production.',
                ]);
            }
        }
    }

    private function seedNavigation(): void
    {
        $items = [
            ['label' => 'Beranda', 'url' => '/', 'sort_order' => 10],
            ['label' => 'Tentang', 'url' => '/about', 'sort_order' => 20],
            ['label' => 'Kopi', 'url' => '/coffee', 'sort_order' => 30],
            ['label' => 'Belanja', 'url' => '/products', 'sort_order' => 40],
            ['label' => 'Menu', 'url' => '/menu', 'sort_order' => 50],
            ['label' => 'Jurnal', 'url' => '/journal', 'sort_order' => 60],
            ['label' => 'Galeri', 'url' => '/gallery', 'sort_order' => 70],
            ['label' => 'Promo', 'url' => '/promotions', 'sort_order' => 75],
            ['label' => 'FAQ', 'url' => '/faq', 'sort_order' => 78],
            ['label' => 'Kontak', 'url' => '/contact', 'sort_order' => 80],
            ['label' => 'Belanja kopi', 'url' => '/products', 'sort_order' => 90, 'is_cta' => true],
        ];

        foreach ($items as $item) {
            $navigation = NavigationItem::query()->firstOrCreate(
                ['label' => $item['label'], 'url' => $item['url']],
                ['sort_order' => $item['sort_order'], 'is_active' => true, 'is_cta' => $item['is_cta'] ?? false],
            );

            $aliases = match ($item['url']) {
                '/about' => ['Cerita', 'About'],
                '/coffee' => ['Coffee'],
                '/journal' => ['Journal'],
                '/products' => ['Produk', 'Products', 'DEMO — Order Now'],
                '/gallery' => ['Gallery'],
                '/promotions' => ['Promosi', 'Promotions'],
                '/faq' => ['Pertanyaan umum', 'FAQs'],
                '/contact' => ['Contact'],
                '/' => ['Home'],
                default => [],
            };

            if ($aliases) {
                NavigationItem::query()->where('url', $item['url'])->whereIn('label', $aliases)->update(['is_active' => false]);
            }

            NavigationItem::query()->where('url', $item['url'])->where('label', $item['label'])
                ->where('id', '!=', $navigation->id)->update(['is_active' => false]);
        }

        NavigationItem::query()->where('url', '/products')->where('label', 'Belanja')->update(['is_cta' => false]);
        NavigationItem::query()->where('url', '/products')->where('label', 'Belanja kopi')->update(['is_cta' => true]);
    }

    private function seedProducts(): void
    {
        $categories = [
            'demo-coffee-beans' => 'DEMO — Coffee Beans',
            'demo-drip-coffee' => 'DEMO — Drip Coffee',
            'demo-accessories' => 'DEMO — Accessories',
        ];

        $categoryModels = [];

        foreach ($categories as $slug => $name) {
            $categoryModels[$slug] = ProductCategory::query()->firstOrCreate(
                ['slug' => $slug],
                ['name' => $name, 'description' => 'DEMO category — replace before production.', 'is_active' => true],
            );
        }

        $products = [
            ['arabica-sidikalang', 'DEMO — Arabica Sidikalang', 'demo-coffee-beans', 'DEMO-COF-001', '145000.00', 24, true, '100g' => ['25000.00', 20], '200g' => ['45000.00', 16], '500g' => ['99000.00', 10]],
            ['arabica-sidikalang-premium', 'DEMO — Arabica Sidikalang Premium', 'demo-coffee-beans', 'DEMO-COF-002', '185000.00', 12, true, '100g' => ['35000.00', 15], '200g' => ['65000.00', 12], '500g' => ['125000.00', 8]],
            ['house-blend', 'DEMO — House Blend', 'demo-coffee-beans', 'DEMO-COF-003', '99000.00', 32, true, '200g' => ['39000.00', 20], '500g' => ['85000.00', 12], '1kg' => ['155000.00', 6]],
            ['robusta-selection', 'DEMO — Robusta Selection', 'demo-coffee-beans', 'DEMO-COF-004', '89000.00', 18, false, '200g' => ['35000.00', 14], '500g' => ['79000.00', 8]],
            ['signature-drip-coffee', 'DEMO — Signature Drip Coffee', 'demo-drip-coffee', 'DEMO-DRP-001', '65000.00', 40, true, '5 sachets' => ['39000.00', 30], '10 sachets' => ['69000.00', 20]],
            ['drip-coffee-discovery-box', 'DEMO — Drip Coffee Discovery Box', 'demo-drip-coffee', 'DEMO-DRP-002', '89000.00', 16, true, '5 sachets' => ['59000.00', 12], '10 sachets' => ['99000.00', 8]],
            ['kopi-drip-tumbler', 'DEMO — Kopi Drip Tumbler', 'demo-accessories', 'DEMO-ACC-001', '125000.00', 9, false],
            ['coffee-starter-set', 'DEMO — Coffee Starter Set', 'demo-accessories', 'DEMO-ACC-002', '225000.00', 7, true],
        ];

        foreach ($products as $productData) {
            [$slug, $name, $categorySlug, $sku, $price, $stock, $featured] = array_slice($productData, 0, 7);
            $product = Product::query()->firstOrCreate(
                ['slug' => $slug],
                [
                    'product_category_id' => $categoryModels[$categorySlug]->id,
                    'name' => $name,
                    'sku' => $sku,
                    'short_description' => 'DEMO — deskripsi dan harga contoh, belum merupakan informasi produk atau penawaran resmi.',
                    'description' => 'Demo content — replace before production. Nama, detail, harga, stok, dan ketersediaan produk ini hanya contoh untuk pengujian website.',
                    'price' => $price,
                    'stock' => $stock,
                    'weight_grams' => 200,
                    'status' => 'published',
                    'published_at' => now()->subDay(),
                    'featured' => $featured,
                    'is_demo' => true,
                    'seo_title' => $name.' | Kopi Drip Sidikalang',
                    'seo_description' => 'Konten demonstrasi. Produk ini bukan penawaran resmi dan perlu diganti sebelum website digunakan.',
                ],
            );

            $imagePath = $this->ensureImage('demo/products/'.$slug.'.webp');
            ProductImage::query()->firstOrCreate(
                ['product_id' => $product->id, 'path' => $imagePath],
                ['alt_text' => 'DEMO — ilustrasi kopi, bukan foto produk Kopi Drip.', 'sort_order' => 0, 'is_primary' => true],
            );

            foreach (array_slice($productData, 7, null, true) as $variantName => [$variantPrice, $variantStock]) {
                ProductVariant::query()->firstOrCreate(
                    ['sku' => $sku.'-'.strtoupper(preg_replace('/[^A-Za-z0-9]+/', '-', $variantName))],
                    [
                        'product_id' => $product->id,
                        'attribute_name' => str_contains(strtolower($variantName), 'sachet') ? 'Pack' : 'Size',
                        'name' => $variantName,
                        'price' => $variantPrice,
                        'stock' => $variantStock,
                        'weight_grams' => str_contains($variantName, 'kg') ? 1000 : 200,
                        'is_active' => true,
                    ],
                );
            }
        }
    }

    private function seedMenu(): void
    {
        $menu = [
            ['slug' => 'demo-coffee', 'name' => 'DEMO — Coffee', 'items' => ['DEMO — Espresso', 'DEMO — Americano', 'DEMO — Cappuccino', 'DEMO — Latte', 'DEMO — Filter Coffee', 'DEMO — Iced Coffee', 'DEMO — Cold Brew']],
            ['slug' => 'demo-non-coffee', 'name' => 'DEMO — Non Coffee', 'items' => ['DEMO — Chocolate', 'DEMO — Matcha', 'DEMO — Tea', 'DEMO — Lemon Tea']],
            ['slug' => 'demo-food', 'name' => 'DEMO — Food', 'items' => ['DEMO — Butter Croissant', 'DEMO — Banana Bread', 'DEMO — Toast', 'DEMO — Cookies']],
            ['slug' => 'demo-signature', 'name' => 'DEMO — Signature', 'items' => ['DEMO — Signature Milk Coffee', 'DEMO — Seasonal Pour Over', 'DEMO — Drip Coffee Flight', 'DEMO — House Creation']],
        ];

        foreach ($menu as $index => $categoryData) {
            $category = MenuCategory::query()->firstOrCreate(
                ['slug' => $categoryData['slug']],
                ['name' => $categoryData['name'], 'description' => 'DEMO menu — contoh, belum tentu tersedia di outlet.', 'is_active' => true, 'sort_order' => ($index + 1) * 10],
            );

            foreach ($categoryData['items'] as $itemIndex => $name) {
                MenuItem::query()->firstOrCreate(
                    ['menu_category_id' => $category->id, 'name' => $name],
                    [
                        'description' => 'DEMO — deskripsi dan ketersediaan contoh; konfirmasi menu resmi sebelum publikasi.',
                        'price' => null,
                        'image_path' => null,
                        'is_available' => true,
                        'is_featured' => $itemIndex === 0,
                        'sort_order' => ($itemIndex + 1) * 10,
                    ],
                );
            }
        }
    }

    private function seedGallery(): void
    {
        $image = $this->ensureImage('demo/gallery/coffee-editorial.webp');
        $items = [
            ['DEMO — Coffee Still Life', 'demo', 10],
            ['DEMO — Coffee Brewing Illustration', 'coffee', 20],
            ['DEMO — Coffee Product Placeholder', 'product', 30],
            ['DEMO — Roastery Placeholder', 'roastery', 40],
            ['DEMO — Cafe Placeholder', 'cafe', 50],
            ['DEMO — Event Placeholder', 'event', 60],
        ];

        foreach ($items as [$title, $category, $sort]) {
            GalleryItem::query()->firstOrCreate(
                ['title' => $title],
                [
                    'image_path' => $image,
                    'alt_text' => 'DEMO — ilustrasi editorial kopi; bukan foto bisnis, outlet, atau produk aktual.',
                    'caption' => 'Ilustrasi demo — ganti dengan foto milik Kopi Drip.',
                    'description' => 'Aset editorial lokal untuk meninjau tampilan galeri. Bukan dokumentasi bisnis.',
                    'category' => $category,
                    'status' => 'published',
                    'sort_order' => $sort,
                ],
            );
        }
    }

    private function seedJournal(): void
    {
        $posts = [
            ['mengenal-karakter-kopi-sidikalang', 'DEMO — Mengenal Karakter Kopi Sidikalang', 'Panduan untuk mencatat karakter kopi tanpa menganggapnya sebagai profil resmi produk.', '<p><strong>Artikel demo — tinjau dan lengkapi sebelum publikasi.</strong></p><p>Catatan rasa dapat berubah menurut bahan, proses, penyimpanan, dan cara seduh. Gunakan lembar cupping atau informasi pemasok untuk menuliskan deskripsi produk yang akurat.</p>'],
            ['panduan-menyeduh-kopi-di-rumah', 'DEMO — Panduan Menyeduh Kopi di Rumah', 'Contoh struktur panduan seduh yang dapat disesuaikan dengan metode dan produk sebenarnya.', '<p><strong>Artikel demo — tinjau dan lengkapi sebelum publikasi.</strong></p><p>Siapkan alat bersih, gunakan air yang layak minum, lalu catat takaran, waktu, dan hasil seduhan. Ubah satu variabel setiap percobaan agar catatan mudah dibandingkan.</p>'],
            ['mengenal-perbedaan-arabica-dan-robusta', 'DEMO — Mengenal Arabica dan Robusta', 'Contoh artikel pengantar yang perlu ditinjau editor sebelum diterbitkan.', '<p><strong>Artikel demo — tinjau dan lengkapi sebelum publikasi.</strong></p><p>Nama varietas atau spesies saja tidak cukup untuk menjelaskan rasa sebuah kopi. Cara pengolahan, sangrai, penyimpanan, dan penyeduhan juga memengaruhi hasil di cangkir.</p>'],
            ['tips-menikmati-drip-coffee', 'DEMO — Tips Menikmati Drip Coffee', 'Contoh konten editorial tentang persiapan kopi drip.', '<p><strong>Artikel demo — tinjau dan lengkapi sebelum publikasi.</strong></p><p>Ikuti petunjuk pada kemasan yang telah diverifikasi, tuang air dengan hati-hati, dan sesuaikan kekuatan seduhan menurut selera. Jangan menganggap petunjuk contoh sebagai spesifikasi produk aktual.</p>'],
            ['coffee-brewing-basics', 'DEMO — Coffee Brewing Basics', 'A sample editorial outline for future brewing education.', '<p><strong>Demo article — review and replace before publication.</strong></p><p>Keep a simple brew log: method, dose, water, time, and taste. Treat recipes as starting points and verify any product-specific advice with the product owner.</p>'],
        ];

        foreach ($posts as $index => [$slug, $title, $excerpt, $content]) {
            JournalPost::query()->firstOrCreate(
                ['slug' => $slug],
                [
                    'title' => $title,
                    'excerpt' => $excerpt,
                    'content' => $content,
                    'author_name' => 'DEMO — Editorial',
                    'is_demo' => true,
                    'image_path' => $this->ensureImage('demo/journal/'.$slug.'.webp'),
                    'status' => 'published',
                    'published_at' => now()->subDays($index + 1),
                    'seo_title' => $title.' | Kopi Drip Sidikalang',
                    'seo_description' => 'Konten demonstrasi yang perlu ditinjau sebelum publikasi resmi.',
                ],
            );
        }

        JournalPost::query()
            ->whereIn('slug', array_column($posts, 0))
            ->where('title', 'like', 'DEMO%')
            ->where('is_demo', false)
            ->update(['is_demo' => true]);
    }

    private function ensureImage(string $path): string
    {
        $disk = Storage::disk('public');

        if (! $disk->exists($path)) {
            $source = public_path('images/coffee-editorial.webp');

            if (is_file($source)) {
                $disk->put($path, file_get_contents($source));
            }
        }

        return $path;
    }
}
