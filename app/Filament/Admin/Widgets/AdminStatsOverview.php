<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Customer;
use App\Models\Layout;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminStatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Customers', (string) Customer::query()->count())
                ->icon('heroicon-o-building-office-2')
                ->color('primary'),
            Stat::make('Total Users', (string) User::query()->count())
                ->icon('heroicon-o-users')
                ->color('info'),
            Stat::make('Total Layouts', (string) Layout::query()->count())
                ->icon('heroicon-o-squares-2x2')
                ->color('warning'),
        ];
    }
}

