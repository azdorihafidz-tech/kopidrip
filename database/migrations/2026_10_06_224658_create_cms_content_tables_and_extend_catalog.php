<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('website_settings', function (Blueprint $table): void {
            $table->string('primary_color', 7)->nullable();
            $table->string('secondary_color', 7)->nullable();
            $table->string('accent_color', 7)->nullable();
        });

        Schema::create('homepage_sections', function (Blueprint $table): void {
            $table->id();
            $table->string('section_key', 60)->unique();
            $table->string('label', 100);
            $table->string('eyebrow')->nullable();
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->text('description')->nullable();
            $table->string('image_path')->nullable();
            $table->string('image_alt')->nullable();
            $table->string('button_text')->nullable();
            $table->string('button_url', 2048)->nullable();
            $table->string('secondary_button_text')->nullable();
            $table->string('secondary_button_url', 2048)->nullable();
            $table->boolean('is_visible')->default(true)->index();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->index(['is_visible', 'sort_order']);
        });

        Schema::create('cms_pages', function (Blueprint $table): void {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content')->nullable();
            $table->string('featured_image')->nullable();
            $table->string('status', 20)->default('draft')->index();
            $table->timestamp('published_at')->nullable()->index();
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->string('canonical_url', 2048)->nullable();
            $table->string('og_image')->nullable();
            $table->timestamps();
            $table->index(['status', 'published_at']);
        });

        Schema::create('navigation_items', function (Blueprint $table): void {
            $table->id();
            $table->string('label', 100);
            $table->string('url', 2048);
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true)->index();
            $table->boolean('open_new_tab')->default(false);
            $table->timestamps();
            $table->index(['is_active', 'sort_order']);
        });

        Schema::create('product_categories', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('image_path')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->unsignedInteger('sort_order')->default(0);
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->timestamps();
        });

        Schema::table('products', function (Blueprint $table): void {
            $table->foreignId('product_category_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('compare_price', 12, 2)->nullable();
            $table->unsignedInteger('stock')->default(0);
            $table->string('og_image')->nullable();
            $table->index(['product_category_id', 'status']);
        });

        Schema::create('product_images', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('path');
            $table->string('alt_text')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
            $table->index(['product_id', 'sort_order']);
        });

        Schema::create('product_variants', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('attribute_name', 80);
            $table->string('name', 120);
            $table->string('sku')->nullable()->unique();
            $table->decimal('price', 12, 2)->nullable();
            $table->unsignedInteger('stock')->default(0);
            $table->unsignedInteger('weight_grams')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->index(['product_id', 'is_active', 'sort_order']);
        });

        Schema::table('menu_categories', function (Blueprint $table): void {
            $table->text('description')->nullable();
            $table->string('image_path')->nullable();
        });

        Schema::table('menu_items', function (Blueprint $table): void {
            $table->string('image_path')->nullable();
            $table->boolean('is_featured')->default(false)->index();
        });

        Schema::table('gallery_items', function (Blueprint $table): void {
            $table->string('title')->nullable();
            $table->text('description')->nullable();
        });

        Schema::table('journal_posts', function (Blueprint $table): void {
            $table->string('author_name')->nullable();
            $table->string('og_image')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('journal_posts', function (Blueprint $table): void {
            $table->dropColumn(['author_name', 'og_image']);
        });

        Schema::table('gallery_items', function (Blueprint $table): void {
            $table->dropColumn(['title', 'description']);
        });

        Schema::table('menu_items', function (Blueprint $table): void {
            $table->dropColumn(['image_path', 'is_featured']);
        });

        Schema::table('menu_categories', function (Blueprint $table): void {
            $table->dropColumn(['description', 'image_path']);
        });

        Schema::dropIfExists('product_variants');
        Schema::dropIfExists('product_images');

        Schema::table('products', function (Blueprint $table): void {
            $table->dropForeign(['product_category_id']);
            $table->dropIndex(['product_category_id', 'status']);
            $table->dropColumn(['product_category_id', 'compare_price', 'stock', 'og_image']);
        });

        Schema::dropIfExists('product_categories');
        Schema::dropIfExists('navigation_items');
        Schema::dropIfExists('cms_pages');
        Schema::dropIfExists('homepage_sections');

        Schema::table('website_settings', function (Blueprint $table): void {
            $table->dropColumn(['primary_color', 'secondary_color', 'accent_color']);
        });
    }
};
