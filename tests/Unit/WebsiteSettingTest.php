<?php

namespace Tests\Unit;

use App\Models\WebsiteSetting;
use App\Rules\SafeGoogleMapsEmbedUrl;
use App\Support\BrandColorContrast;
use PHPUnit\Framework\TestCase;

class WebsiteSettingTest extends TestCase
{
    public function test_only_google_maps_embed_urls_are_accepted(): void
    {
        $settings = new WebsiteSetting(['map_embed_url' => 'https://www.google.com/maps/embed?pb=sample']);

        $this->assertSame('https://www.google.com/maps/embed?pb=sample', $settings->safeMapEmbedUrl());

        $settings->map_embed_url = 'https://example.test/maps/embed?pb=sample';

        $this->assertNull($settings->safeMapEmbedUrl());

        $settings->map_embed_url = 'javascript:alert(1)';

        $this->assertNull($settings->safeMapEmbedUrl());
    }

    public function test_map_embed_validation_rejects_deceptive_google_urls(): void
    {
        $this->assertTrue(SafeGoogleMapsEmbedUrl::isValid('https://www.google.com/maps/embed?pb=sample'));
        $this->assertFalse(SafeGoogleMapsEmbedUrl::isValid('https://www.google.com.attacker.test/maps/embed'));
        $this->assertFalse(SafeGoogleMapsEmbedUrl::isValid('https://www.google.com/maps/embed-malicious'));
        $this->assertFalse(SafeGoogleMapsEmbedUrl::isValid('https://user@www.google.com/maps/embed'));
    }

    public function test_brand_color_contrast_rule_accepts_readable_color_and_rejects_low_contrast(): void
    {
        $this->assertTrue(BrandColorContrast::isAccessible('#1d211c'));
        $this->assertFalse(BrandColorContrast::isAccessible('#f5f2e9'));
        $this->assertFalse(BrandColorContrast::isAccessible('red'));
    }
}
