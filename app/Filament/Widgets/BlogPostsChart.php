<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use Filament\Widgets\ChartWidget;

class BlogPostsChart extends ChartWidget
{
    protected static ?string $heading = 'Analisa Konten per Kategori';
    protected static ?int $sort = 2; // Urutan ke-2 setelah kartu stats

    protected function getData(): array
    {
        // Ambil data kategori dan hitung jumlah post-nya
        $categories = Category::withCount('posts')->get();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Artikel',
                    'data' => $categories->pluck('posts_count')->toArray(),
                    'backgroundColor' => '#3b82f6', // Warna Biru
                ],
            ],
            'labels' => $categories->pluck('name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar'; // Grafik Batang
    }
}