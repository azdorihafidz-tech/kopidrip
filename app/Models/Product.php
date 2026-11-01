<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_category_id', 'name', 'slug', 'short_description', 'description', 'image_path', 'seo_title', 'seo_description',
        'og_image', 'price', 'compare_price', 'stock', 'status', 'featured', 'is_demo', 'sku', 'weight_grams', 'published_at',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'compare_price' => 'decimal:2',
            'featured' => 'boolean',
            'is_demo' => 'boolean',
            'stock' => 'integer',
            'weight_grams' => 'integer',
            'published_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Product $product): void {
            $product->slug = Str::slug($product->slug ?: $product->name);

            if ($product->status === 'published' && $product->published_at === null) {
                $product->published_at = now();
            }
        });

        static::deleting(function (Product $product): void {
            $paths = $product->images()->pluck('path')->push($product->image_path, $product->og_image);
            Storage::disk('public')->delete($paths->filter()->unique()->all());
        });
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')
            ->where(fn (Builder $query) => $query->whereNull('published_at')->orWhere('published_at', '<=', now()));
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class)->orderBy('sort_order');
    }

    public function primaryImage(): HasMany
    {
        return $this->hasMany(ProductImage::class)->where('is_primary', true)->limit(1);
    }

    public function getPrimaryImagePathAttribute(): ?string
    {
        return $this->primaryImage->first()?->path ?: $this->image_path;
    }
}
