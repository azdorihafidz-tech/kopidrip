<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\GalleryItem;
use App\Models\HomepageSection;
use App\Models\JournalPost;
use App\Models\NavigationItem;
use App\Models\WebsiteSetting;
use Illuminate\Database\Seeder;

class BrandContentSeeder extends Seeder
{
    public function run(): void
    {
        $settings = WebsiteSetting::query()->firstOrCreate([], [
            'business_name' => 'Kopi Drip Sidikalang',
            'tagline' => 'Kopi, cerita, dan cara seduh.',
            'site_is_demo' => false,
        ]);

        $settings->forceFill([
            'business_name' => $settings->business_name ?: 'Kopi Drip Sidikalang',
            'tagline' => $settings->tagline ?: 'Kopi, cerita, dan cara seduh.',
            'site_is_demo' => false,
        ])->save();

        HomepageSection::query()->where('section_key', 'hero')->whereNull('image_path')->update([
            'image_path' => 'editorial/hero-brewing.webp',
            'image_alt' => 'Ilustrasi editorial proses menyeduh kopi dengan alat pour over.',
        ]);

        NavigationItem::query()->where('url', '/menu')->update(['is_active' => false]);

        foreach ([
            ['Ritual seduh kopi', 'editorial/hero-brewing.webp', 'Ilustrasi editorial secangkir kopi dan alat pour over di atas meja.', 'Visual editorial · ritual seduh kopi', 'Ilustrasi editorial, bukan dokumentasi outlet atau produk aktual.', 10],
            ['Biji kopi dan penggiling', 'editorial/coffee-beans-grinder.webp', 'Ilustrasi editorial biji kopi dengan penggiling manual.', 'Visual editorial · biji kopi dan penggiling', 'Ilustrasi editorial tentang perlengkapan seduh kopi.', 20],
            ['Menyeduh dengan pour over', 'editorial/pour-over.webp', 'Ilustrasi editorial tangan menuangkan air ke alat pour over.', 'Visual editorial · metode pour over', 'Ilustrasi editorial tentang salah satu metode seduh kopi.', 30],
        ] as [$title, $image, $alt, $caption, $description, $sort]) {
            GalleryItem::query()->firstOrCreate(
                ['title' => $title],
                [
                    'image_path' => $image,
                    'alt_text' => $alt,
                    'caption' => $caption,
                    'description' => $description,
                    'category' => 'editorial',
                    'status' => 'published',
                    'sort_order' => $sort,
                ],
            );
        }

        $articles = [
            [
                'slug' => 'mengenal-karakter-kopi-sidikalang',
                'title' => 'Mengenal rasa kopi: aroma, keasaman, dan body',
                'excerpt' => 'Cara sederhana memperhatikan aroma dan rasa kopi tanpa terburu-buru memberi penilaian.',
                'content' => '<p>Menikmati kopi dapat dimulai dengan memperhatikan aroma sebelum dan sesudah diseduh. Setelah itu, cicipi sedikit demi sedikit dan amati kesan yang muncul: apakah terasa ringan atau pekat, lembut atau tajam, serta bagaimana rasanya setelah ditelan.</p><h2>Catat kesan Anda</h2><p>Gunakan kata-kata yang akrab bagi Anda. Catatan sederhana seperti “ringan”, “manis”, atau “beraroma kacang” sudah cukup sebagai awal. Tidak ada satu kosakata yang wajib digunakan semua orang.</p><h2>Bandingkan dengan cara yang sama</h2><p>Jika membandingkan dua kopi, usahakan memakai alat, air, dan cara seduh yang serupa. Hasilnya tetap dipengaruhi banyak faktor, tetapi cara ini membantu Anda mengenali perbedaan yang terasa.</p>',
                'image_path' => 'editorial/coffee-beans-grinder.webp',
            ],
            [
                'slug' => 'panduan-menyeduh-kopi-di-rumah',
                'title' => 'Panduan pour over sederhana di rumah',
                'excerpt' => 'Langkah dasar menyeduh kopi dengan dripper, kertas saring, air panas, dan teko.',
                'content' => '<p>Pour over adalah metode seduh dengan menuangkan air secara bertahap ke kopi yang berada di dalam dripper berlapis kertas saring. Air melewati bubuk kopi lalu menetes ke wadah di bawahnya.</p><h2>Siapkan alat</h2><p>Gunakan dripper, kertas saring, kopi yang baru digiling bila tersedia, teko, dan cangkir. Basahi kertas saring terlebih dahulu, lalu buang air bilasannya.</p><h2>Tuang dengan tenang</h2><p>Tuangkan air panas perlahan hingga seluruh bubuk kopi basah. Lanjutkan menuang bertahap dengan gerakan memutar dan jaga aliran tetap stabil. Catat takaran dan waktu yang Anda gunakan supaya seduhan berikutnya mudah dibandingkan.</p><h2>Sesuaikan dengan selera</h2><p>Jika hasilnya terlalu kuat atau terlalu ringan bagi Anda, ubah jumlah kopi, jumlah air, atau ukuran gilingan pada percobaan selanjutnya—satu hal dalam satu waktu.</p>',
                'image_path' => 'editorial/pour-over.webp',
            ],
            [
                'slug' => 'mengenal-perbedaan-arabica-dan-robusta',
                'title' => 'Arabika dan robusta: mengenal dua jenis kopi',
                'excerpt' => 'Kenali perbedaan umum arabika dan robusta serta faktor lain yang membentuk rasa.',
                'content' => '<p>Arabika dan robusta adalah sebutan yang umum digunakan untuk dua jenis kopi yang berbeda. Keduanya dapat hadir dengan karakter yang beragam, sehingga nama jenis saja tidak cukup untuk memastikan rasa di dalam cangkir.</p><h2>Banyak faktor memengaruhi rasa</h2><p>Lingkungan tumbuh, varietas, pengolahan setelah panen, tingkat sangrai, kondisi penyimpanan, dan cara seduh ikut memengaruhi hasil akhir. Karena itu, dua kopi dengan nama jenis sama tetap bisa terasa berbeda.</p><h2>Pilih berdasarkan pengalaman</h2><p>Cobalah kopi dengan informasi yang jelas dan perhatikan apa yang Anda sukai. Catatan rasa pribadi akan lebih berguna daripada mengandalkan gambaran umum sebagai aturan pasti.</p>',
                'image_path' => 'editorial/coffee-beans-grinder.webp',
            ],
            [
                'slug' => 'tips-menikmati-drip-coffee',
                'title' => 'Cara menikmati kopi drip',
                'excerpt' => 'Kenali cara kerja kopi drip dan beberapa hal yang perlu diperhatikan saat menyeduhnya.',
                'content' => '<p>Kopi drip menggunakan bubuk kopi di dalam kantong saring kecil yang dipasang pada bibir cangkir. Air panas dituangkan melalui bubuk kopi dan hasil seduhannya menetes langsung ke cangkir.</p><h2>Langkah dasar</h2><ol><li>Buka kemasan dan pasang kantong saring mengikuti bentuk dudukannya.</li><li>Tuang sedikit air panas untuk membasahi kopi.</li><li>Lanjutkan menuang perlahan dalam beberapa tahap, sesuai petunjuk pada kemasan.</li><li>Angkat kantong setelah tetesan selesai, lalu nikmati seduhan.</li></ol><p>Takaran dan petunjuk dapat berbeda untuk tiap produk. Ikuti keterangan pada kemasan kopi yang Anda gunakan.</p>',
                'image_path' => 'editorial/hero-brewing.webp',
            ],
            [
                'slug' => 'coffee-brewing-basics',
                'title' => 'Mencari seduhan favorit, satu langkah setiap kali',
                'excerpt' => 'Buat catatan seduh sederhana dan ubah satu variabel untuk menemukan hasil yang Anda sukai.',
                'content' => '<p>Resep seduh dapat menjadi titik awal, tetapi selera tiap orang berbeda. Catatan singkat membantu Anda mengingat apa yang dilakukan dan bagaimana hasilnya terasa.</p><h2>Catat hal yang mudah diulang</h2><p>Tulis metode, jumlah kopi, jumlah air, waktu seduh, serta kesan rasa. Tidak perlu format rumit; beberapa baris sudah cukup untuk melihat pola.</p><h2>Ubah satu hal</h2><p>Pada percobaan berikutnya, ubah satu variabel saja, misalnya ukuran gilingan atau cara menuang. Dengan begitu Anda lebih mudah melihat pengaruh perubahan tersebut terhadap cangkir.</p><p>Gunakan panduan ini sebagai eksperimen pribadi dan ikuti instruksi khusus yang tercantum pada kemasan produk yang Anda gunakan.</p>',
                'image_path' => 'editorial/pour-over.webp',
            ],
        ];

        foreach ($articles as $index => $article) {
            JournalPost::query()->firstOrCreate(
                ['slug' => $article['slug']],
                $article + [
                    'author_name' => 'Kopi Drip',
                    'status' => 'published',
                    'is_demo' => false,
                    'published_at' => now()->subDays(count($articles) - $index),
                    'seo_title' => $article['title'].' | Kopi Drip Sidikalang',
                    'seo_description' => $article['excerpt'],
                ],
            );
        }

        foreach ([
            ['Apa itu kopi drip?', 'Kopi', 'Kopi drip biasanya merujuk pada bubuk kopi di dalam kantong saring kecil yang dipasang pada cangkir. Air panas dituangkan melalui kopi dan menetes langsung ke cangkir. Petunjuk dapat berbeda menurut produk yang digunakan.'],
            ['Bagaimana cara menghubungi Kopi Drip?', 'Kontak', 'Kirim pertanyaan melalui formulir pada halaman Kontak. Jika nomor WhatsApp resmi telah dicantumkan di sana, Anda juga dapat menghubungi kami lewat WhatsApp.'],
            ['Di mana saya bisa melihat produk yang tersedia?', 'Produk', 'Produk yang sudah memiliki informasi dan harga terkonfirmasi akan ditampilkan pada halaman Produk. Anda juga dapat mengirim pertanyaan melalui halaman Kontak.'],
            ['Bagaimana mulai menyeduh kopi di rumah?', 'Seduh', 'Pilih metode seduh dan ikuti petunjuk pada alat atau kemasan yang digunakan. Catat jumlah kopi, air, dan waktu seduh, lalu sesuaikan satu hal pada percobaan berikutnya.'],
        ] as $index => [$question, $category, $answer]) {
            Faq::query()->firstOrCreate(
                ['question' => $question],
                ['category' => $category, 'answer' => $answer, 'status' => 'published', 'sort_order' => ($index + 1) * 10],
            );
        }
    }
}
