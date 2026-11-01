<?php

namespace App\Models;

use App\Rules\SafeGoogleMapsEmbedUrl;
use App\Support\BrandColorContrast;
use Illuminate\Database\Eloquent\Model;

class WebsiteSetting extends Model
{
    protected $fillable = [
        'business_name', 'tagline', 'logo', 'logo_white', 'favicon', 'email',
        'phone', 'whatsapp', 'address', 'opening_hours', 'homepage_hero_image', 'map_embed_url', 'instagram', 'facebook',
        'tiktok', 'youtube', 'primary_color', 'secondary_color', 'accent_color', 'site_is_demo',
    ];

    protected function casts(): array
    {
        return ['opening_hours' => 'array', 'site_is_demo' => 'boolean'];
    }

    public function safeMapEmbedUrl(): ?string
    {
        return SafeGoogleMapsEmbedUrl::isValid($this->map_embed_url) ? $this->map_embed_url : null;
    }

    public function whatsappUrl(?string $message = null): ?string
    {
        $number = preg_replace('/\D+/', '', (string) $this->whatsapp);

        if ($number === '') {
            return null;
        }

        if (str_starts_with($number, '00')) {
            $number = substr($number, 2);
        } elseif (str_starts_with($number, '0')) {
            $number = '62'.substr($number, 1);
        }

        if (! preg_match('/^\d{7,15}$/', $number)) {
            return null;
        }

        $url = 'https://wa.me/'.$number;

        return filled($message) ? $url.'?text='.rawurlencode($message) : $url;
    }

    public function brandColors(): array
    {
        return [
            'primary' => BrandColorContrast::safeColor($this->primary_color, '#1d211c'),
            'secondary' => BrandColorContrast::safeColor($this->secondary_color, '#49382a'),
            'accent' => BrandColorContrast::safeColor($this->accent_color, '#56614a'),
        ];
    }
}
