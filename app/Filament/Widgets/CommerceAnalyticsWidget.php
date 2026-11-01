<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Support\CmsPermissions;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class CommerceAnalyticsWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    public static function canView(): bool
    {
        return CmsPermissions::allows('analytics.view');
    }

    protected function getStats(): array
    {
        $weekStart = now()->startOfDay()->subDays(6);
        $monthStart = now()->subDays(30);
        $pageViews = DB::table('analytics_events')->where('event_name', 'page_view')->where('created_at', '>=', $weekStart)->count();
        $visitors = DB::table('analytics_events')->where('event_name', 'page_view')->where('created_at', '>=', $weekStart)->distinct('visitor_key')->count('visitor_key');
        $orders = Order::query()->where('created_at', '>=', $monthStart)->count();
        $awaiting = Order::query()->whereIn('status', ['pending', 'confirmed', 'processing'])->count();

        return [
            Stat::make('Page views (7 hari)', number_format($pageViews, 0, ',', '.'))
                ->description('Tayangan seluruh halaman publik')
                ->descriptionIcon('heroicon-m-eye'),
            Stat::make('Visitors unik (7 hari)', number_format($visitors, 0, ',', '.'))
                ->description('Perkiraan berdasarkan sesi browser')
                ->descriptionIcon('heroicon-m-user-group'),
            Stat::make('Pesanan baru (30 hari)', number_format($orders, 0, ',', '.')),
            Stat::make('Pesanan perlu ditindaklanjuti', number_format($awaiting, 0, ',', '.'))
                ->description('Menunggu proses berikutnya')
                ->descriptionIcon('heroicon-m-clock')
                ->color($awaiting > 0 ? 'warning' : 'success'),
        ];
    }
}
