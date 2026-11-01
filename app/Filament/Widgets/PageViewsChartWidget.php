<?php

namespace App\Filament\Widgets;

use App\Support\CmsPermissions;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class PageViewsChartWidget extends ChartWidget
{
    protected static ?int $sort = 3;

    protected static ?string $heading = 'Page views tujuh hari terakhir';

    protected static ?string $description = 'Tren kunjungan ke halaman publik setiap hari.';

    protected static string $color = 'warning';

    public static function canView(): bool
    {
        return CmsPermissions::allows('analytics.view');
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $start = now()->startOfDay()->subDays(6);
        $dailyViews = DB::table('analytics_events')
            ->where('event_name', 'page_view')
            ->where('created_at', '>=', $start)
            ->selectRaw('DATE(created_at) as view_date, COUNT(*) as total')
            ->groupBy('view_date')
            ->pluck('total', 'view_date');

        $days = collect(range(6, 0))->map(fn (int $daysAgo) => now()->startOfDay()->subDays($daysAgo));

        return [
            'datasets' => [[
                'label' => 'Page views',
                'data' => $days->map(fn ($day) => (int) ($dailyViews[$day->toDateString()] ?? 0))->all(),
                'borderColor' => '#8b6c3f',
                'backgroundColor' => 'rgba(139, 108, 63, 0.13)',
                'fill' => 'start',
                'tension' => 0.35,
                'pointRadius' => 3,
                'pointHoverRadius' => 5,
            ]],
            'labels' => $days->map(fn ($day) => $day->format('d/m'))->all(),
        ];
    }
}
