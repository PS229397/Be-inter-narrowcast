<?php

namespace App\Filament\App\Widgets;

use App\Models\Location;
use App\Models\Slide;
use App\Models\Slideshow;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class CustomerStatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $customerId = Auth::guard('web')->user()?->customer_id;

        if (! $customerId) {
            return [];
        }

        return [
            Stat::make('Active Screens', (string) Location::query()
                ->where('customer_id', $customerId)
                ->whereHas('slideshows', fn ($q) => $q->where('is_active', true))
                ->count())
                ->icon('heroicon-o-computer-desktop')
                ->color('success'),
            Stat::make('Total Slides', (string) Slide::query()
                ->where('customer_id', $customerId)
                ->count())
                ->icon('heroicon-o-photo')
                ->color('info'),
            Stat::make('Active Slideshows', (string) Slideshow::query()
                ->where('customer_id', $customerId)
                ->where('is_active', true)
                ->count())
                ->icon('heroicon-o-play-circle')
                ->color('warning'),
        ];
    }
}
