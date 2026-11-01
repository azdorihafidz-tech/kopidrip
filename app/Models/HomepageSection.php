<?php

namespace App\Models;

use App\Rules\SafeNavigationUrl;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class HomepageSection extends Model
{
    public const KEYS = [
        'hero', 'introduction', 'featured_coffee', 'featured_products', 'coffee_shop',
        'our_story', 'gallery', 'journal', 'location', 'cta',
    ];

    protected $fillable = [
        'section_key', 'label', 'eyebrow', 'title', 'subtitle', 'description', 'image_path', 'image_alt',
        'button_text', 'button_url', 'secondary_button_text', 'secondary_button_url', 'is_visible', 'sort_order',
    ];

    protected function casts(): array
    {
        return ['is_visible' => 'boolean', 'sort_order' => 'integer'];
    }

    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('is_visible', true)->orderBy('sort_order');
    }

    public function safeButtonUrl(string $field = 'button_url'): ?string
    {
        $value = $this->getAttribute($field);

        if (! SafeNavigationUrl::isValid($value)) {
            return null;
        }

        if (str_starts_with($value, '#')) {
            return url()->current().$value;
        }

        return str_starts_with($value, '/') ? url(ltrim($value, '/')) : $value;
    }
}
