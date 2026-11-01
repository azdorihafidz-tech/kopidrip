<?php

namespace App\Filament\Widgets;

use App\Support\CmsPermissions;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\DB;

class WebsiteInsightsWidget extends Widget
{
    protected static ?int $sort = 4;

    protected static string $view = 'filament.widgets.website-insights';

    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        return CmsPermissions::allows('analytics.view');
    }

    protected function getViewData(): array
    {
        $since = now()->startOfDay()->subDays(6);
        $baseQuery = fn () => DB::table('analytics_events')
            ->where('event_name', 'page_view')
            ->where('created_at', '>=', $since);

        $countryNames = [
            'ID' => 'Indonesia', 'MY' => 'Malaysia', 'SG' => 'Singapura', 'TH' => 'Thailandia',
            'PH' => 'Filipina', 'VN' => 'Vietnam', 'JP' => 'Jepang', 'KR' => 'Korea Selatan',
            'CN' => 'Tiongkok', 'IN' => 'India', 'AU' => 'Australia', 'NZ' => 'Selandia Baru',
            'US' => 'Amerika Serikat', 'CA' => 'Kanada', 'GB' => 'Britania Raya', 'NL' => 'Belanda',
            'DE' => 'Jerman', 'FR' => 'Prancis', 'IT' => 'Italia', 'ES' => 'Spanyol',
            'SA' => 'Arab Saudi', 'AE' => 'Uni Emirat Arab', 'BR' => 'Brasil',
        ];

        $countries = $baseQuery()
            ->whereNotNull('country_code')
            ->select('country_code')
            ->selectRaw('COUNT(DISTINCT visitor_key) as visitors')
            ->groupBy('country_code')
            ->orderByDesc('visitors')
            ->limit(6)
            ->get()
            ->map(function ($row) use ($countryNames): array {
                $localizedName = class_exists(\Locale::class)
                    ? \Locale::getDisplayRegion('und-'.$row->country_code, 'id')
                    : null;

                return [
                    'name' => filled($localizedName) && $localizedName !== $row->country_code
                        ? $localizedName
                        : ($countryNames[$row->country_code] ?? $row->country_code),
                    'code' => $row->country_code,
                    'visitors' => (int) $row->visitors,
                ];
            });

        $topPages = $baseQuery()
            ->select('path')
            ->selectRaw('COUNT(*) as views')
            ->groupBy('path')
            ->orderByDesc('views')
            ->limit(6)
            ->get();

        $topSources = $baseQuery()
            ->select('referrer_host')
            ->selectRaw('COUNT(*) as visits')
            ->groupBy('referrer_host')
            ->orderByDesc('visits')
            ->limit(6)
            ->get()
            ->map(fn ($row) => [
                'name' => $row->referrer_host ?: 'Langsung / tanpa referer',
                'visits' => (int) $row->visits,
            ]);

        return [
            'countries' => $countries,
            'topPages' => $topPages,
            'topSources' => $topSources,
        ];
    }
}
