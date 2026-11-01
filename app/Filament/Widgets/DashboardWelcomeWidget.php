<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class DashboardWelcomeWidget extends Widget
{
    protected static ?int $sort = 1;

    protected static string $view = 'filament.widgets.dashboard-welcome';

    protected int | string | array $columnSpan = 'full';

    protected function getViewData(): array
    {
        return [
            'adminName' => auth()->user()?->name,
            'today' => now()->format('d/m/Y'),
        ];
    }
}
