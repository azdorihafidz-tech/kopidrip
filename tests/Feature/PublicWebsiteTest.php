<?php

namespace Tests\Feature;

use App\Models\GalleryItem;
use App\Models\JournalPost;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Product;
use App\Models\WebsiteSetting;
use Database\Seeders\CmsContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicWebsiteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(CmsContentSeeder::class);
    }

    public function test_public_pages_load_without_admin_authentication(): void
    {
        foreach (['/', '/about', '/coffee', '/products', '/menu', '/gallery', '/journal', '/location', '/contact'] as $path) {
            $this->get($path)->assertOk();
        }

        $this->get('/')->assertSee('Kopi Drip');
    }

    public function test_public_website_settings_are_rendered_without_inventing_empty_values(): void
    {
        WebsiteSetting::query()->create([
            'business_name' => 'Nama Resmi Test',
            'tagline' => 'Tagline terverifikasi',
            'email' => 'hello@example.test',
        ]);

        $this->get('/')->assertOk()->assertSee('Nama Resmi Test')->assertSee('Tagline terverifikasi');
        $this->get('/contact')->assertOk()->assertSee('hello@example.test')->assertDontSee('tel:');
    }

    public function test_public_layout_renders_settings_driven_branding_and_seo_metadata(): void
    {
        WebsiteSetting::query()->create([
            'business_name' => 'Nama Brand Resmi',
            'homepage_hero_image' => 'brand/hero.webp',
        ]);

        $this->get('/')->assertOk()
            ->assertSee('<title>Kopi Drip · Nama Brand Resmi</title>', false)
            ->assertSee('<link rel="canonical" href="http://localhost">', false)
            ->assertSee('<meta name="robots" content="index,follow">', false)
            ->assertSee('<meta property="og:site_name" content="Nama Brand Resmi">', false)
            ->assertSee('http://localhost/storage/brand/hero.webp');
    }

    public function test_sitemap_lists_public_routes_and_published_content_only(): void
    {
        WebsiteSetting::query()->create(['business_name' => 'Situs Resmi Uji', 'site_is_demo' => false]);
        Product::query()->create(['name' => 'Produk Sitemap', 'slug' => 'produk-sitemap', 'status' => 'published']);
        Product::query()->create(['name' => 'Draft Sitemap', 'slug' => 'draft-sitemap', 'status' => 'draft']);
        JournalPost::query()->create(['title' => 'Jurnal Sitemap', 'slug' => 'jurnal-sitemap', 'status' => 'published']);

        $sitemap = $this->get('/sitemap.xml')->assertOk()
            ->assertHeader('Content-Type', 'application/xml; charset=UTF-8')
            ->assertSee('http://localhost/about')
            ->assertSee('http://localhost/products/produk-sitemap')
            ->assertSee('http://localhost/journal/jurnal-sitemap')
            ->assertDontSee('draft-sitemap');

        $document = new \DOMDocument;

        $this->assertTrue($document->loadXML($sitemap->getContent()));
    }

    public function test_only_published_products_are_publicly_accessible(): void
    {
        $published = Product::query()->create([
            'name' => 'Produk Uji',
            'slug' => 'produk-uji',
            'price' => '12500.00',
            'status' => 'published',
        ]);
        Product::query()->create([
            'name' => 'Produk Draft',
            'slug' => 'produk-draft',
            'status' => 'draft',
        ]);

        $this->get('/products')->assertOk()->assertSee('Produk Uji')->assertDontSee('Produk Draft');
        $this->get(route('products.show', $published->slug))->assertOk()->assertSee('Rp 12.500');
        $this->get('/products/produk-draft')->assertNotFound();
        $this->get('/products/tidak-ada')->assertNotFound();
    }

    public function test_only_published_journal_posts_are_publicly_accessible(): void
    {
        $published = JournalPost::query()->create([
            'title' => 'Catatan Uji',
            'slug' => 'catatan-uji',
            'excerpt' => 'Ringkasan yang aman.',
            'content' => '<p>Isi <strong>terkendali</strong>.</p>',
            'status' => 'published',
            'published_at' => now()->subMinute(),
        ]);
        JournalPost::query()->create([
            'title' => 'Catatan Draft',
            'slug' => 'catatan-draft',
            'status' => 'draft',
        ]);

        $this->get('/journal')->assertOk()->assertSee('Catatan Uji')->assertDontSee('Catatan Draft');
        $this->get(route('journal.show', $published->slug))->assertOk()->assertSee('Catatan Uji')->assertSee('Isi ')->assertDontSee('<strong>');
        $this->get('/journal/tidak-ada')->assertNotFound();
    }

    public function test_menu_and_gallery_only_render_configured_content(): void
    {
        $category = MenuCategory::query()->create([
            'name' => 'Kategori uji',
            'slug' => 'kategori-uji',
            'is_active' => true,
        ]);
        MenuItem::query()->create([
            'menu_category_id' => $category->id,
            'name' => 'Item menu uji',
            'price' => null,
            'is_available' => true,
        ]);
        GalleryItem::query()->create([
            'image_path' => 'gallery/test.webp',
            'alt_text' => 'Foto galeri uji',
            'caption' => 'Keterangan uji',
            'category' => 'coffee',
            'status' => 'published',
        ]);

        $this->get('/menu')->assertOk()->assertSee('Kategori uji')->assertSee('Item menu uji')->assertSee('Harga belum tersedia');
        $this->get('/gallery')->assertOk()->assertSee('Foto galeri uji')->assertSee('Keterangan uji');
    }

    public function test_location_map_only_embeds_an_allowed_google_maps_url(): void
    {
        $settings = WebsiteSetting::query()->create([
            'map_embed_url' => 'https://example.test/maps/embed?pb=not-google',
        ]);

        $this->get('/location')->assertOk()->assertDontSee('<iframe');

        $settings->update(['map_embed_url' => 'https://www.google.com/maps/embed?pb=sample']);

        $this->get('/location')->assertOk()->assertSee('<iframe', false);
    }

    public function test_contact_form_validates_fields_and_does_not_claim_delivery(): void
    {
        $this->post('/contact', [
            'name' => '', 'email' => 'invalid', 'subject' => '', 'message' => '',
        ])->assertSessionHasErrors(['name', 'email', 'subject', 'message']);

        $this->post('/contact', [
            'name' => 'Pengunjung',
            'email' => 'visitor@example.test',
            'subject' => 'Pertanyaan',
            'message' => 'Pesan untuk pengujian.',
        ])->assertRedirect('/contact')->assertSessionHas('contact-status');
    }

    public function test_admin_remains_protected_and_login_page_is_available(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
        $this->get('/admin/login')->assertOk();
    }
}
