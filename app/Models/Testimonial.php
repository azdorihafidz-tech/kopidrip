<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $fillable = ['customer_name', 'customer_label', 'content', 'status', 'featured', 'sort_order'];

    protected function casts(): array
    {
        return ['featured' => 'boolean', 'sort_order' => 'integer'];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')
            ->orderByDesc('featured')
            ->orderBy('sort_order')
            ->orderBy('id');
    }
}
