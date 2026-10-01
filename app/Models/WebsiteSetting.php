<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebsiteSetting extends Model
{
    protected $fillable = [
        'business_name', 'tagline', 'logo', 'logo_white', 'favicon', 'email',
        'phone', 'whatsapp', 'address', 'opening_hours', 'instagram', 'facebook',
        'tiktok', 'youtube',
    ];

    protected function casts(): array
    {
        return ['opening_hours' => 'array'];
    }
}