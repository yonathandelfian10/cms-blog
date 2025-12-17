<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BlogStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            // Card 1: Menghitung Total User
            Stat::make('Total Users', User::count())
                ->description('Registered users')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success'), // Warna Hijau

            // Card 2: Menghitung Total Post
            Stat::make('Total Posts', Post::count())
                ->description('Published articles')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('primary'), // Warna Kuning/Emas
        ];
    }
}