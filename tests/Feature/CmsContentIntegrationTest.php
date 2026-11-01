<?php

namespace Tests\Feature;

use App\Filament\Pages\WebsiteSettings;
use App\Filament\Resources\CmsPageResource;
use App\Filament\Resources\ProductCategoryResource\Pages\CreateProductCategory;
use App\Filament\Resources\ProductResource;
use App\Filament\Resources\ProductResource\Pages\CreateProduct;
use App\Models\CmsPage;
use App\Models\GalleryItem;
use App\Models\HomepageSection;
use App\Models\JournalPost;
use App\Models\NavigationItem;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\User;
use App\Models\WebsiteSetting;
use App\Rules\SafeNavigationUrl;
use Database\Seeders\CmsContentSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Livewire\Livewire;
use Tests\TestCase;

class CmsContentIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([RoleSeeder::class, CmsContentSeeder::class]);
    }

    public function test_published_cms_page_is_rendered_and_draft_page_is_hidden(): void
    {
        CmsPage::query()->create([
            'title' => 'Halaman Publik Uji',
            'slug' => 'halaman-publik-uji',
            'content' => '<p>Konten yang dikelola admin.</p><script>alert(1)</script>',
            'status' => 'published',
        ]);
        CmsPage::query()->create([
            'title' => 'Halaman Draft Uji',
            'slug' => 'halaman-draft-uji',
            'content' => 'Konten draft.',
            'status' => 'draft',
        ]);

        $this->get('/halaman-publik-uji')->assertOk()
            ->assertSee('Konten yang dikelola admin.')
            ->assertDontSee('alert(1)');
        $this->get('/halaman-draft-uji')->assertNotFound();
    }

    public function test_homepage_and_navigation_use_admin_managed_records(): void
    {
        HomepageSection::query()->where('section_key', 'hero')->update([
            'title' => 'Judul beranda hasil CMS',
            'subtitle' => 'Subtitle CMS',
        ]);
        NavigationItem::query()->delete();
        NavigationItem::query()->create([
            'label' => 'Tautan khusus', 'url' => '/about', 'sort_order' => 1, 'is_active' => true,
        ]);
        NavigationItem::query()->create([
            'label' => 'Tautan nonaktif', 'url' => '/coffee', 'sort_order' => 2, 'is_active' => false,
        ]);

        $this->get('/')->assertOk()
            ->assertSee('Judul beranda hasil CMS')
            ->assertSee('Tautan khusus')
            ->assertDontSee('Tautan nonaktif');
    }

    public function test_published_product_category_product_journal_and_gallery_are_public(): void
    {
        $category = ProductCategory::query()->create(['name' => 'Kopi uji', 'slug' => 'kopi-uji', 'is_active' => true]);
        $product = Product::query()->create([
            'product_category_id' => $category->id,
            'name' => 'Produk CMS Uji', 'slug' => 'produk-cms-uji', 'status' => 'published', 'price' => 15000,
        ]);
        ProductVariant::query()->create([
            'product_id' => $product->id, 'attribute_name' => 'Ukuran', 'name' => '200g', 'stock' => 4, 'is_active' => true,
        ]);
        ProductCategory::query()->create(['name' => 'Kategori tersembunyi', 'slug' => 'kategori-tersembunyi', 'is_active' => false]);
        JournalPost::query()->create([
            'title' => 'Jurnal CMS Uji', 'slug' => 'jurnal-cms-uji', 'status' => 'published', 'content' => '<p>Isi jurnal.</p>',
        ]);
        GalleryItem::query()->create([
            'title' => 'Galeri CMS Uji', 'image_path' => 'gallery/uji.webp', 'alt_text' => 'Foto uji',
            'category' => 'coffee', 'status' => 'published',
        ]);

        $this->get('/products')->assertOk()->assertSee('Produk CMS Uji')->assertSee('Kopi uji')->assertDontSee('Kategori tersembunyi');
        $this->get('/products/category/kopi-uji')->assertOk()->assertSee('Produk CMS Uji');
        $this->get('/products/produk-cms-uji')->assertOk()->assertSee('Ukuran: 200g');
        $this->get('/journal')->assertOk()->assertSee('Jurnal CMS Uji');
        $this->get('/gallery')->assertOk()->assertSee('Galeri CMS Uji');
    }

    public function test_duplicate_product_slug_is_rejected_by_database_and_model_normalizes_slug(): void
    {
        Product::query()->create(['name' => 'Produk Satu', 'slug' => 'Produk Satu', 'status' => 'draft']);

        $this->assertDatabaseHas('products', ['slug' => 'produk-satu']);
        $this->expectException(QueryException::class);
        Product::query()->create(['name' => 'Produk Duplikat', 'slug' => 'produk-satu', 'status' => 'draft']);
    }

    public function test_upload_validation_accepts_images_and_rejects_executable_files(): void
    {
        $rules = ['image' => 'required|image|mimes:jpg,jpeg,png,webp,gif|max:5120'];
        $valid = Validator::make(['image' => UploadedFile::fake()->image('coffee.jpg')], $rules);
        $invalid = Validator::make(['image' => UploadedFile::fake()->create('payload.php', 10, 'application/x-php')], $rules);

        $this->assertTrue($valid->passes());
        $this->assertTrue($invalid->fails());
    }

    public function test_product_images_keep_a_primary_image_and_remove_deleted_uploads(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('products/one.webp', 'first image');
        Storage::disk('public')->put('products/two.webp', 'second image');

        $product = Product::query()->create(['name' => 'Produk Foto', 'slug' => 'produk-foto', 'status' => 'draft']);
        $first = ProductImage::query()->create(['product_id' => $product->id, 'path' => 'products/one.webp']);
        $second = ProductImage::query()->create(['product_id' => $product->id, 'path' => 'products/two.webp', 'is_primary' => true]);

        $this->assertFalse($first->fresh()->is_primary);
        $this->assertTrue($second->fresh()->is_primary);
        $second->delete();

        $this->assertTrue($first->fresh()->is_primary);
        Storage::disk('public')->assertMissing('products/two.webp');

        $product->delete();
        Storage::disk('public')->assertMissing('products/one.webp');
    }

    public function test_navigation_url_rule_rejects_open_redirects_and_admin_paths(): void
    {
        $this->assertTrue(SafeNavigationUrl::isValid('/products'));
        $this->assertTrue(SafeNavigationUrl::isValid('https://example.test/menu'));
        $this->assertFalse(SafeNavigationUrl::isValid('//evil.test/path'));
        $this->assertFalse(SafeNavigationUrl::isValid('/admin/users'));
        $this->assertFalse(SafeNavigationUrl::isValid('javascript:alert(1)'));
    }

    public function test_roles_enforce_settings_and_catalog_permissions(): void
    {
        $editor = User::factory()->create();
        $editor->assignRole('editor');
        $this->actingAs($editor);

        $this->assertFalse(WebsiteSettings::canAccess());
        $this->assertFalse(ProductResource::canViewAny());
        $this->assertTrue(CmsPageResource::canViewAny());

        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $this->actingAs($admin);

        $this->assertTrue(WebsiteSettings::canAccess());
        $this->assertTrue(ProductResource::canViewAny());
    }

    public function test_admin_panel_requires_authentication_and_allows_authorized_admin(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');

        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $this->actingAs($admin)->get('/admin')->assertOk();
        $this->get('/admin/website-settings')->assertOk();
    }

    public function test_admin_cms_resource_lists_render_for_admin(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $this->actingAs($admin);

        foreach ([
            '/admin/homepage-sections', '/admin/cms-pages', '/admin/navigation-items',
            '/admin/product-categories', '/admin/products', '/admin/menu-categories',
            '/admin/menu-items', '/admin/gallery-items', '/admin/journal-posts',
        ] as $path) {
            $this->get($path)->assertOk();
        }
    }

    public function test_editor_is_forbidden_from_website_settings_route(): void
    {
        $editor = User::factory()->create();
        $editor->assignRole('editor');

        $this->actingAs($editor)->get('/admin/website-settings')->assertForbidden();
    }

    public function test_website_settings_admin_form_persists_changes(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $this->actingAs($admin);

        Livewire::test(WebsiteSettings::class)
            ->set('data.business_name', 'Nama resmi dari admin')
            ->set('data.primary_color', '#1d211c')
            ->set('data.secondary_color', '#49382a')
            ->set('data.accent_color', '#56614a')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('website_settings', ['business_name' => 'Nama resmi dari admin']);
    }

    public function test_product_admin_form_rejects_negative_price_and_stock(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $this->actingAs($admin);

        Livewire::test(CreateProduct::class)
            ->fillForm([
                'name' => 'Produk tidak valid',
                'slug' => 'produk-tidak-valid',
                'price' => -1,
                'stock' => -1,
                'status' => 'draft',
            ])
            ->call('create')
            ->assertHasFormErrors(['price' => 'min', 'stock' => 'min']);
    }

    public function test_product_category_admin_form_rejects_duplicate_slug(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $this->actingAs($admin);
        ProductCategory::query()->create(['name' => 'Kategori lama', 'slug' => 'kategori-lama']);

        Livewire::test(CreateProductCategory::class)
            ->fillForm([
                'name' => 'Kategori baru',
                'slug' => 'kategori-lama',
                'sort_order' => 0,
                'is_active' => true,
            ])
            ->call('create')
            ->assertHasFormErrors(['slug' => 'unique']);
    }

    public function test_public_site_uses_settings_and_omits_empty_social_links(): void
    {
        WebsiteSetting::query()->create([
            'business_name' => 'Brand dari Settings',
            'instagram' => 'https://instagram.com/kopidrip',
            'facebook' => null,
            'tiktok' => '',
            'youtube' => null,
        ]);

        $this->get('/')->assertOk()->assertSee('Brand dari Settings')->assertSee('https://instagram.com/kopidrip')
            ->assertDontSee('https://facebook.com')->assertDontSee('youtube.com');
    }
}
