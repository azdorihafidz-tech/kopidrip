<?php

namespace Tests\Feature;

use App\Models\GalleryItem;
use App\Models\JournalPost;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\NavigationItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\WebsiteSetting;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DemoContentSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_demo_seed_is_labeled_complete_and_idempotent(): void
    {
        Storage::fake('public');

        $this->seed(DatabaseSeeder::class);
        $product = Product::query()->where('slug', 'arabica-sidikalang')->firstOrFail();
        $product->update(['name' => 'Owner-edited demo title']);
        NavigationItem::query()->create(['label' => 'Journal', 'url' => '/journal', 'sort_order' => 60, 'is_active' => true]);
        NavigationItem::query()->create(['label' => 'About', 'url' => '/about', 'sort_order' => 20, 'is_active' => true]);
        $this->seed(DatabaseSeeder::class);

        $this->assertDatabaseCount('products', 8);
        $this->assertDatabaseCount('product_variants', 15);
        $this->assertDatabaseCount('menu_categories', 4);
        $this->assertDatabaseCount('menu_items', 19);
        $this->assertDatabaseCount('gallery_items', 6);
        $this->assertDatabaseCount('journal_posts', 5);
        $this->assertTrue(WebsiteSetting::query()->firstOrFail()->site_is_demo);
        $this->assertSame('Owner-edited demo title', $product->fresh()->name);
        $this->assertTrue($product->fresh()->is_demo);
        $this->assertSame('DEMO-COF-001', $product->sku);
        $this->assertTrue(Product::query()->where('is_demo', false)->doesntExist());
        $this->assertTrue(ProductVariant::query()->whereNull('sku')->doesntExist());
        Storage::disk('public')->assertExists($product->images()->firstOrFail()->path);
        Storage::disk('public')->assertExists(GalleryItem::query()->firstOrFail()->image_path);
        $this->assertTrue(MenuCategory::query()->where('is_active', true)->exists());
        $this->assertTrue(MenuItem::query()->whereNull('price')->exists());
        $this->assertTrue(JournalPost::query()->where('title', 'like', 'DEMO%')->exists());
        $this->assertSame(5, JournalPost::query()->where('is_demo', true)->count());
        $navigationItems = NavigationItem::publicItems();
        $this->assertCount(9, $navigationItems);
        $this->assertCount(9, $navigationItems->pluck('label')->unique());
        $this->assertFalse(NavigationItem::query()->whereIn('label', ['Journal', 'About'])->where('is_active', true)->exists());
        $this->get('/')->assertOk()->assertSee('DEMO CONTENT — REPLACE BEFORE PRODUCTION');
        $this->get('/products')->assertOk()->assertSee('DEMO — Arabica Sidikalang')->assertSee('Harga demo');
        $this->get('/products/arabica-sidikalang')->assertOk()->assertSee('noindex,nofollow', false)->assertDontSee('"@type":"Product"', false)->assertSee('bukan penawaran untuk dibeli');
        $this->get('/menu')->assertOk()->assertSee('DEMO — Coffee')->assertSee('Harga belum tersedia');
        $this->get('/journal')->assertOk()->assertSee('DEMO — Coffee Brewing Basics');
        $this->get('/journal/coffee-brewing-basics')->assertOk()->assertSee('noindex,nofollow', false)->assertDontSee('"@type":"Article"', false);
        $this->get('/gallery')->assertOk()->assertSee('DEMO — Coffee Still Life');
    }
}
