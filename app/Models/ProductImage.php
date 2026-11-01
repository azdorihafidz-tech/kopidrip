<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ProductImage extends Model
{
    protected $fillable = ['product_id', 'path', 'alt_text', 'sort_order', 'is_primary'];

    protected function casts(): array
    {
        return ['sort_order' => 'integer', 'is_primary' => 'boolean'];
    }

    protected static function booted(): void
    {
        static::creating(function (ProductImage $image): void {
            if (! static::query()->where('product_id', $image->product_id)->exists()) {
                $image->is_primary = true;
            }
        });

        static::saving(function (ProductImage $image): void {
            if ($image->is_primary) {
                static::query()->where('product_id', $image->product_id)
                    ->when($image->exists, fn ($query) => $query->whereKeyNot($image->getKey()))
                    ->update(['is_primary' => false]);
            }
        });

        static::deleted(function (ProductImage $image): void {
            Storage::disk('public')->delete($image->path);

            if ($image->is_primary) {
                $image->product?->images()->orderBy('sort_order')->first()?->update(['is_primary' => true]);
            }
        });
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
