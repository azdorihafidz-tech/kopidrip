<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->refreshWebsiteSettings();
        $this->refreshHomepage();
        $this->refreshPages();
        $this->hideUnverifiedCatalog();
        $this->publishEditorialGallery();
        $this->publishJournal();
        $this->publishFaqs();
    }

    private function refreshWebsiteSettings(): void
    {
        $settings = DB::table('website_settings')->first();

        if (! $settings) {
            DB::table('website_settings')->insert([
                'business_name' => 'Kopi Drip Sidikalang',
                'tagline' => 'Kopi, cerita, dan cara seduh.',
                'homepage_hero_image' => 'editorial/hero-brewing.webp',
                'site_is_demo' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return;
        }

        $updates = ['site_is_demo' => false, 'updated_at' => now()];

        if (blank($settings->business_name)) {
            $updates['business_name'] = 'Kopi Drip Sidikalang';
        }

        if (blank($settings->tagline)) {
            $updates['tagline'] = 'Kopi, cerita, dan cara seduh.';
        }

        if (blank($settings->homepage_hero_image)) {
            $updates['homepage_hero_image'] = 'editorial/hero-brewing.webp';
        }

        DB::table('website_settings')->where('id', $settings->id)->update($updates);
    }

    private function refreshHomepage(): void
    {
        $sections = [
            'hero' => [
                'eyebrow' => 'Kopi Sidikalang · Indonesia',
                'title' => 'Secangkir kopi,',
                'subtitle' => 'diracik dengan cerita.',
                'description' => 'Kenali kopi, jelajahi cara seduh, dan ikuti kabar terbaru dari Kopi Drip Sidikalang.',
                'image_path' => 'editorial/hero-brewing.webp',
                'image_alt' => 'Ilustrasi editorial proses menyeduh kopi dengan alat pour over.',
                'button_text' => 'Jelajahi kopi',
                'button_url' => '/coffee',
                'secondary_button_text' => 'Hubungi kami',
                'secondary_button_url' => '/contact',
            ],
            'introduction' => [
                'eyebrow' => 'Tentang Kopi Drip',
                'title' => 'Ruang untuk menikmati kopi dengan cara Anda.',
                'description' => 'Temukan inspirasi seduh, catatan seputar kopi, dan informasi terbaru dari Kopi Drip Sidikalang.',
                'button_text' => 'Baca jurnal',
                'button_url' => '/journal',
            ],
            'featured_coffee' => [
                'eyebrow' => 'Eksplorasi kopi',
                'title' => 'Mulai dari rasa ingin tahu.',
                'description' => 'Pelajari biji kopi, sangrai, dan metode seduh. Informasi setiap produk ditampilkan setelah detail resminya tersedia.',
                'button_text' => 'Kenali kopi',
                'button_url' => '/coffee',
            ],
            'featured_products' => [
                'eyebrow' => 'Pilihan Kopi Drip',
                'title' => 'Temukan kopi untuk menemani hari.',
                'description' => 'Tanyakan pilihan produk, harga, dan ketersediaan melalui halaman kontak.',
                'button_text' => 'Tanyakan produk',
                'button_url' => '/contact',
            ],
            'coffee_shop' => [
                'eyebrow' => 'Ritual sehari-hari',
                'title' => 'Nikmati kopi dengan ritme sendiri.',
                'description' => 'Dari seduhan harian hingga waktu jeda, temukan cara menikmati kopi yang paling Anda sukai.',
                'button_text' => 'Hubungi kami',
                'button_url' => '/contact',
            ],
            'our_story' => [
                'eyebrow' => 'Kopi Drip Sidikalang',
                'title' => 'Kopi, cerita, dan ruang untuk terus mengeksplorasi.',
                'description' => 'Kenali dunia kopi melalui panduan seduh, cerita, dan informasi terbaru dari Kopi Drip Sidikalang.',
                'button_text' => 'Baca jurnal',
                'button_url' => '/journal',
                'secondary_button_text' => 'Hubungi kami',
                'secondary_button_url' => '/contact',
            ],
            'gallery' => [
                'eyebrow' => 'Galeri editorial',
                'title' => 'Momen di sekitar kopi.',
                'description' => 'Lihat visual editorial tentang biji kopi dan cara menyeduh.',
                'button_text' => 'Lihat galeri',
                'button_url' => '/gallery',
            ],
            'journal' => [
                'eyebrow' => 'Jurnal',
                'title' => 'Catatan untuk penikmat kopi.',
                'description' => 'Panduan ringan untuk mengenal rasa dan mengeksplorasi metode seduh.',
                'button_text' => 'Semua tulisan',
                'button_url' => '/journal',
            ],
            'location' => [
                'eyebrow' => 'Kunjungi & hubungi',
                'title' => 'Cari informasi kunjungan.',
                'description' => 'Untuk informasi terbaru tentang kunjungan, silakan hubungi Kopi Drip.',
                'button_text' => 'Lihat kontak',
                'button_url' => '/contact',
            ],
            'cta' => [
                'eyebrow' => 'Kopi Drip Sidikalang',
                'title' => 'Ada yang ingin ditanyakan?',
                'description' => 'Hubungi kami untuk bertanya tentang kopi dan informasi terbaru.',
                'button_text' => 'Hubungi kami',
                'button_url' => '/contact',
            ],
        ];

        foreach ($sections as $key => $data) {
            DB::table('homepage_sections')->where('section_key', $key)->update([...$data, 'updated_at' => now()]);
        }
    }

    private function refreshPages(): void
    {
        $pages = [
            'about' => [
                'title' => 'Tentang Kopi Drip Sidikalang',
                'excerpt' => 'Kopi, cerita, dan inspirasi untuk menikmati seduhan.',
                'content' => '<p>Kopi Drip Sidikalang adalah ruang untuk mengenal kopi, mengeksplorasi cara seduh, dan mengikuti kabar terbaru seputar Kopi Drip.</p><p>Di sini Anda dapat menemukan catatan dan panduan umum tentang kopi. Informasi spesifik mengenai produk, menu, dan kunjungan akan ditampilkan setelah dikonfirmasi.</p>',
                'seo_title' => 'Tentang Kopi Drip Sidikalang',
                'seo_description' => 'Kenali Kopi Drip Sidikalang melalui cerita, jurnal, dan inspirasi seputar kopi.',
            ],
            'coffee' => [
                'title' => 'Kopi & cara seduh',
                'excerpt' => 'Jelajahi pengetahuan dasar tentang kopi dan cara menyeduhnya.',
                'content' => '<h2>Mulai mengenal kopi</h2><p>Rasa dalam cangkir dipengaruhi oleh banyak hal, termasuk biji, proses pengolahan, tingkat sangrai, penyimpanan, air, dan metode seduh. Mengubah satu hal dalam satu waktu dapat membantu Anda memahami perbedaannya.</p><h2>Informasi produk</h2><p>Produk, harga, dan ketersediaan akan ditampilkan di katalog setelah detailnya dikonfirmasi.</p>',
                'seo_title' => 'Kopi & cara seduh | Kopi Drip Sidikalang',
                'seo_description' => 'Pelajari dasar kopi dan temukan inspirasi untuk mencoba cara seduh yang berbeda.',
            ],
            'contact' => [
                'title' => 'Hubungi Kopi Drip',
                'excerpt' => 'Kirim pertanyaan melalui formulir kontak. Informasi kontak resmi tersedia di halaman ini setelah diatur.',
                'content' => '<p>Kirim pesan melalui formulir di halaman ini. Jika nomor WhatsApp resmi telah dicantumkan, Anda juga dapat menghubungi kami lewat WhatsApp.</p>',
                'seo_title' => 'Kontak | Kopi Drip Sidikalang',
                'seo_description' => 'Hubungi Kopi Drip Sidikalang melalui formulir kontak atau WhatsApp yang tercantum.',
            ],
            'location' => [
                'title' => 'Informasi kunjungan',
                'excerpt' => 'Alamat dan jam operasional akan ditampilkan setelah informasinya dikonfirmasi.',
                'content' => '<p>Untuk informasi kunjungan, alamat, dan jam operasional terbaru, silakan kirim pertanyaan melalui halaman kontak.</p>',
                'seo_title' => 'Lokasi & jam operasional | Kopi Drip Sidikalang',
                'seo_description' => 'Hubungi Kopi Drip Sidikalang untuk informasi kunjungan terbaru.',
            ],
        ];

        foreach ($pages as $slug => $data) {
            DB::table('cms_pages')->where('slug', $slug)->update([
                ...$data,
                'status' => 'published',
                'published_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function hideUnverifiedCatalog(): void
    {
        if (Schema::hasTable('products') && Schema::hasColumn('products', 'is_demo')) {
            $products = DB::table('products')->where('is_demo', true)->get(['id', 'name']);
            $ids = $products->pluck('id');

            foreach ($products as $product) {
                DB::table('products')->where('id', $product->id)->update([
                    'name' => preg_replace('/^DEMO[\s—-]+/iu', '', $product->name),
                    'sku' => null,
                    'short_description' => null,
                    'description' => null,
                    'price' => null,
                    'compare_price' => null,
                    'stock' => 0,
                    'featured' => false,
                    'status' => 'draft',
                    'published_at' => null,
                    'image_path' => null,
                    'og_image' => null,
                    'seo_title' => null,
                    'seo_description' => null,
                    'updated_at' => now(),
                ]);
            }

            if ($ids->isNotEmpty()) {
                DB::table('product_images')->whereIn('product_id', $ids)->delete();
                DB::table('product_variants')->whereIn('product_id', $ids)->update([
                    'sku' => null,
                    'price' => null,
                    'stock' => 0,
                    'is_active' => false,
                    'updated_at' => now(),
                ]);
            }
        }

        if (Schema::hasTable('product_categories')) {
            $categories = DB::table('product_categories')->where('slug', 'like', 'demo-%')->get(['id', 'name']);

            foreach ($categories as $category) {
                DB::table('product_categories')->where('id', $category->id)->update([
                    'name' => preg_replace('/^DEMO[\s—-]+/iu', '', $category->name),
                    'is_active' => false,
                    'description' => null,
                    'updated_at' => now(),
                ]);
            }
        }

        if (Schema::hasTable('menu_categories')) {
            $categories = DB::table('menu_categories')->where('slug', 'like', 'demo-%')->get(['id', 'name']);
            $categoryIds = $categories->pluck('id');

            foreach ($categories as $category) {
                DB::table('menu_categories')->where('id', $category->id)->update([
                    'name' => preg_replace('/^DEMO[\s—-]+/iu', '', $category->name),
                    'description' => null,
                    'is_active' => false,
                    'updated_at' => now(),
                ]);
            }

            foreach (DB::table('menu_items')->whereIn('menu_category_id', $categoryIds)->get(['id', 'name']) as $item) {
                DB::table('menu_items')->where('id', $item->id)->update([
                    'name' => preg_replace('/^DEMO[\s—-]+/iu', '', $item->name),
                    'description' => null,
                    'is_available' => false,
                    'is_featured' => false,
                    'price' => null,
                    'updated_at' => now(),
                ]);
            }
        }

        DB::table('navigation_items')->where('url', '/menu')->update(['is_active' => false, 'updated_at' => now()]);
        foreach (DB::table('navigation_items')->where('label', 'like', 'DEMO%')->get(['id', 'label']) as $item) {
            DB::table('navigation_items')->where('id', $item->id)->update([
                'label' => preg_replace('/^DEMO[\s—-]+/iu', '', $item->label),
                'is_active' => false,
                'updated_at' => now(),
            ]);
        }
        DB::table('gallery_items')->where('title', 'like', 'DEMO%')->delete();
    }

    private function publishEditorialGallery(): void
    {
        $items = [
            ['title' => 'Ritual seduh kopi', 'image_path' => 'editorial/hero-brewing.webp', 'alt_text' => 'Ilustrasi editorial secangkir kopi dan alat pour over di atas meja.', 'caption' => 'Visual editorial · ritual seduh kopi', 'description' => 'Ilustrasi editorial, bukan dokumentasi outlet atau produk aktual.', 'category' => 'editorial', 'sort_order' => 10],
            ['title' => 'Biji kopi dan penggiling', 'image_path' => 'editorial/coffee-beans-grinder.webp', 'alt_text' => 'Ilustrasi editorial biji kopi dengan penggiling manual.', 'caption' => 'Visual editorial · biji kopi dan penggiling', 'description' => 'Ilustrasi editorial tentang perlengkapan seduh kopi.', 'category' => 'editorial', 'sort_order' => 20],
            ['title' => 'Menyeduh dengan pour over', 'image_path' => 'editorial/pour-over.webp', 'alt_text' => 'Ilustrasi editorial tangan menuangkan air ke alat pour over.', 'caption' => 'Visual editorial · metode pour over', 'description' => 'Ilustrasi editorial tentang salah satu metode seduh kopi.', 'category' => 'editorial', 'sort_order' => 30],
        ];

        foreach ($items as $item) {
            $existing = DB::table('gallery_items')->where('title', $item['title'])->first();
            $values = [...$item, 'status' => 'published', 'updated_at' => now()];

            if ($existing) {
                DB::table('gallery_items')->where('id', $existing->id)->update($values);
            } else {
                DB::table('gallery_items')->insert([...$values, 'created_at' => now()]);
            }
        }
    }

    private function publishJournal(): void
    {
        $posts = [
            [
                'slug' => 'mengenal-karakter-kopi-sidikalang',
                'title' => 'Mengenal rasa kopi: aroma, keasaman, dan body',
                'excerpt' => 'Cara sederhana memperhatikan aroma dan rasa kopi tanpa terburu-buru memberi penilaian.',
                'content' => '<p>Menikmati kopi dapat dimulai dengan memperhatikan aroma sebelum dan sesudah diseduh. Setelah itu, cicipi sedikit demi sedikit dan amati kesan yang muncul: apakah terasa ringan atau pekat, lembut atau tajam, serta bagaimana rasanya setelah ditelan.</p><h2>Catat kesan Anda</h2><p>Gunakan kata-kata yang akrab bagi Anda. Catatan sederhana seperti “ringan”, “manis”, atau “beraroma kacang” sudah cukup sebagai awal. Tidak ada satu kosakata yang wajib digunakan semua orang.</p><h2>Bandingkan dengan cara yang sama</h2><p>Jika membandingkan dua kopi, usahakan memakai alat, air, dan cara seduh yang serupa. Hasilnya tetap dipengaruhi banyak faktor, tetapi cara ini membantu Anda mengenali perbedaan yang terasa.</p>',
                'image_path' => 'editorial/coffee-beans-grinder.webp',
                'seo_description' => 'Pelajari cara sederhana memperhatikan aroma, rasa, dan tekstur kopi dalam cangkir.',
            ],
            [
                'slug' => 'panduan-menyeduh-kopi-di-rumah',
                'title' => 'Panduan pour over sederhana di rumah',
                'excerpt' => 'Langkah dasar menyeduh kopi dengan dripper, kertas saring, air panas, dan teko.',
                'content' => '<p>Pour over adalah metode seduh dengan menuangkan air secara bertahap ke kopi yang berada di dalam dripper berlapis kertas saring. Air melewati bubuk kopi lalu menetes ke wadah di bawahnya.</p><h2>Siapkan alat</h2><p>Gunakan dripper, kertas saring, kopi yang baru digiling bila tersedia, teko, dan cangkir. Basahi kertas saring terlebih dahulu, lalu buang air bilasannya.</p><h2>Tuang dengan tenang</h2><p>Tuangkan air panas perlahan hingga seluruh bubuk kopi basah. Lanjutkan menuang bertahap dengan gerakan memutar dan jaga aliran tetap stabil. Catat takaran dan waktu yang Anda gunakan supaya seduhan berikutnya mudah dibandingkan.</p><h2>Sesuaikan dengan selera</h2><p>Jika hasilnya terlalu kuat atau terlalu ringan bagi Anda, ubah jumlah kopi, jumlah air, atau ukuran gilingan pada percobaan selanjutnya—satu hal dalam satu waktu.</p>',
                'image_path' => 'editorial/pour-over.webp',
                'seo_description' => 'Ikuti langkah dasar pour over dan sesuaikan seduhan kopi dengan selera Anda.',
            ],
            [
                'slug' => 'mengenal-perbedaan-arabica-dan-robusta',
                'title' => 'Arabika dan robusta: mengenal dua jenis kopi',
                'excerpt' => 'Kenali perbedaan umum arabika dan robusta serta faktor lain yang membentuk rasa.',
                'content' => '<p>Arabika dan robusta adalah sebutan yang umum digunakan untuk dua jenis kopi yang berbeda. Keduanya dapat hadir dengan karakter yang beragam, sehingga nama jenis saja tidak cukup untuk memastikan rasa di dalam cangkir.</p><h2>Banyak faktor memengaruhi rasa</h2><p>Lingkungan tumbuh, varietas, pengolahan setelah panen, tingkat sangrai, kondisi penyimpanan, dan cara seduh ikut memengaruhi hasil akhir. Karena itu, dua kopi dengan nama jenis sama tetap bisa terasa berbeda.</p><h2>Pilih berdasarkan pengalaman</h2><p>Cobalah kopi dengan informasi yang jelas dan perhatikan apa yang Anda sukai. Catatan rasa pribadi akan lebih berguna daripada mengandalkan gambaran umum sebagai aturan pasti.</p>',
                'image_path' => 'editorial/coffee-beans-grinder.webp',
                'seo_description' => 'Pelajari perbedaan umum arabika dan robusta serta faktor yang membentuk rasa kopi.',
            ],
            [
                'slug' => 'tips-menikmati-drip-coffee',
                'title' => 'Cara menikmati kopi drip',
                'excerpt' => 'Kenali cara kerja kopi drip dan beberapa hal yang perlu diperhatikan saat menyeduhnya.',
                'content' => '<p>Kopi drip menggunakan bubuk kopi di dalam kantong saring kecil yang dipasang pada bibir cangkir. Air panas dituangkan melalui bubuk kopi dan hasil seduhannya menetes langsung ke cangkir.</p><h2>Langkah dasar</h2><ol><li>Buka kemasan dan pasang kantong saring mengikuti bentuk dudukannya.</li><li>Tuang sedikit air panas untuk membasahi kopi.</li><li>Lanjutkan menuang perlahan dalam beberapa tahap, sesuai petunjuk pada kemasan.</li><li>Angkat kantong setelah tetesan selesai, lalu nikmati seduhan.</li></ol><p>Takaran dan petunjuk dapat berbeda untuk tiap produk. Ikuti keterangan pada kemasan kopi yang Anda gunakan.</p>',
                'image_path' => 'editorial/hero-brewing.webp',
                'seo_description' => 'Panduan umum memasang kantong kopi drip dan menyeduhnya langsung di cangkir.',
            ],
            [
                'slug' => 'coffee-brewing-basics',
                'title' => 'Mencari seduhan favorit, satu langkah setiap kali',
                'excerpt' => 'Buat catatan seduh sederhana dan ubah satu variabel untuk menemukan hasil yang Anda sukai.',
                'content' => '<p>Resep seduh dapat menjadi titik awal, tetapi selera tiap orang berbeda. Catatan singkat membantu Anda mengingat apa yang dilakukan dan bagaimana hasilnya terasa.</p><h2>Catat hal yang mudah diulang</h2><p>Tulis metode, jumlah kopi, jumlah air, waktu seduh, serta kesan rasa. Tidak perlu format rumit; beberapa baris sudah cukup untuk melihat pola.</p><h2>Ubah satu hal</h2><p>Pada percobaan berikutnya, ubah satu variabel saja, misalnya ukuran gilingan atau cara menuang. Dengan begitu Anda lebih mudah melihat pengaruh perubahan tersebut terhadap cangkir.</p><p>Gunakan panduan ini sebagai eksperimen pribadi dan ikuti instruksi khusus yang tercantum pada kemasan produk yang Anda gunakan.</p>',
                'image_path' => 'editorial/pour-over.webp',
                'seo_description' => 'Gunakan catatan seduh sederhana untuk mengeksplorasi kopi sesuai selera.',
            ],
        ];

        foreach ($posts as $index => $post) {
            $publishedAt = now()->subDays(count($posts) - $index);
            $values = [
                ...$post,
                'author_name' => 'Kopi Drip',
                'status' => 'published',
                'is_demo' => false,
                'published_at' => $publishedAt,
                'seo_title' => $post['title'].' | Kopi Drip Sidikalang',
                'updated_at' => now(),
            ];

            $existing = DB::table('journal_posts')->where('slug', $post['slug'])->first();

            if ($existing) {
                DB::table('journal_posts')->where('id', $existing->id)->update($values);
            } else {
                DB::table('journal_posts')->insert([...$values, 'created_at' => now()]);
            }
        }
    }

    private function publishFaqs(): void
    {
        $faqs = [
            ['question' => 'Apa itu kopi drip?', 'category' => 'Kopi', 'answer' => 'Kopi drip biasanya merujuk pada bubuk kopi di dalam kantong saring kecil yang dipasang pada cangkir. Air panas dituangkan melalui kopi dan menetes langsung ke cangkir. Petunjuk dapat berbeda menurut produk yang digunakan.'],
            ['question' => 'Bagaimana cara menghubungi Kopi Drip?', 'category' => 'Kontak', 'answer' => 'Kirim pertanyaan melalui formulir pada halaman Kontak. Jika nomor WhatsApp resmi telah dicantumkan di sana, Anda juga dapat menghubungi kami lewat WhatsApp.'],
            ['question' => 'Di mana saya bisa melihat produk yang tersedia?', 'category' => 'Produk', 'answer' => 'Produk yang sudah memiliki informasi dan harga terkonfirmasi akan ditampilkan pada halaman Produk. Anda juga dapat mengirim pertanyaan melalui halaman Kontak.'],
            ['question' => 'Bagaimana mulai menyeduh kopi di rumah?', 'category' => 'Seduh', 'answer' => 'Pilih metode seduh dan ikuti petunjuk pada alat atau kemasan yang digunakan. Catat jumlah kopi, air, dan waktu seduh, lalu sesuaikan satu hal pada percobaan berikutnya.'],
        ];

        foreach ($faqs as $index => $faq) {
            if (DB::table('faqs')->where('question', $faq['question'])->exists()) {
                continue;
            }

            DB::table('faqs')->insert([
                ...$faq,
                'status' => 'published',
                'sort_order' => ($index + 1) * 10,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        // Content changes are intentionally retained on rollback to protect edited site content.
    }
};
