<?php

namespace App\Filament\JobseekerPanel\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        return [
            Stat::make('Number of jobseekers', '12.1k')
                ->chart([17, 16, 14, 15, 14, 13, 12, 19, 24, 28])
                ->color('success')
            ,
            Stat::make('Bounce rate', '12%')
                ->chart([20, 32, 25, 24, 18, 13, 12, 19, 24, 10])
            ,
            Stat::make('Average time on page', '47:12')
                ->chart([10, 15, 12, 14, 19, 22, 34, 26, 40])
                ->color('success')
            ,
        ];
    }
}
