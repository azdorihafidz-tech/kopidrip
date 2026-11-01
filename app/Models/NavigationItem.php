<?php

namespace App\Models;

use App\Rules\SafeNavigationUrl;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class NavigationItem extends Model
{
    protected $fillable = ['label', 'url', 'sort_order', 'is_active', 'open_new_tab', 'is_cta'];

    protected function casts(): array
    {
        return ['sort_order' => 'integer', 'is_active' => 'boolean', 'open_new_tab' => 'boolean', 'is_cta' => 'boolean'];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)->orderBy('sort_order')->orderBy('id');
    }

    /** @return Collection<int, NavigationItem> */
    public static function publicItems(): Collection
    {
        $items = static::active()->get()->filter(fn (NavigationItem $item) => $item->publicUrl() !== null)->values();

        if ($items->isNotEmpty()) {
            return $items;
        }

        return collect([
            ['label' => 'Beranda', 'url' => '/'],
            ['label' => 'Tentang', 'url' => '/about'],
            ['label' => 'Kopi', 'url' => '/coffee'],
            ['label' => 'Belanja', 'url' => '/products'],
            ['label' => 'Menu', 'url' => '/menu'],
            ['label' => 'Jurnal', 'url' => '/journal'],
            ['label' => 'Galeri', 'url' => '/gallery'],
            ['label' => 'Kontak', 'url' => '/contact'],
            ['label' => 'Belanja kopi', 'url' => '/products', 'is_cta' => true],
        ])->map(fn (array $attributes) => new static($attributes));
    }

    public function publicUrl(): ?string
    {
        if (! SafeNavigationUrl::isValid($this->url)) {
            return null;
        }

        if (str_starts_with($this->url, '#')) {
            return url()->current().$this->url;
        }

        return str_starts_with($this->url, '/') ? url(ltrim($this->url, '/')) : $this->url;
    }
}
