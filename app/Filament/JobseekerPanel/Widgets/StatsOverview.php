<?php

namespace App\Filament\JobseekerPanel\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Number of jobseekers', '192.1k'),
            Stat::make('Bounce rate', '21%'),
            Stat::make('Average time on page', '47:12'),
        ];
    }
}
