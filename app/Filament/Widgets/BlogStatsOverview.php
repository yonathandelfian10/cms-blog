<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BlogStatsOverview extends BaseWidget
{
    // Atur agar widget ini refresh otomatis setiap 30 detik (Live Data)
    protected static ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        return [
            // 1. Kartu Total Views (Poin Utama Analisa Performa)
            Stat::make('Total Pembaca (Views)', Post::sum('view_count'))
                ->description('Total artikel dilihat oleh guest')
                ->descriptionIcon('heroicon-m-eye')
                ->chart([7, 2, 10, 3, 15, 4, 17]) // Grafik hiasan kecil
                ->color('success'),

            // 2. Kartu Total Artikel
            Stat::make('Total Artikel', Post::where('status', 'published')->count())
                ->description('Artikel yang sudah terbit')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('primary'),

            // 3. Kartu Total Penulis
            Stat::make('Total Penulis', User::whereHas('roles', fn($q) => $q->where('name', '!=', 'user'))->count())
                ->description('Admin & Editor aktif')
                ->descriptionIcon('heroicon-m-users')
                ->color('warning'),
        ];
    }
}