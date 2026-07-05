<?php

namespace App\Filament\Widgets;

use App\Models\Sale;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;

class SalesLastSevenDaysChart extends ChartWidget
{
    protected ?string $heading = 'Penjualan 7 Hari Terakhir';

    protected static bool $isLazy = false;

    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = ['md' => 1];

    protected function getData(): array
    {
        $dates = collect(range(6, 0))
            ->map(fn (int $daysAgo) => today()->subDays($daysAgo));

        $salesByDate = Sale::query()
            ->whereBetween('occurred_at', [today()->subDays(6)->startOfDay(), today()->endOfDay()])
            ->get(['total_amount', 'occurred_at'])
            ->groupBy(fn (Sale $sale): string => $sale->occurred_at->toDateString())
            ->map(fn ($sales): int => (int) $sales->sum('total_amount'));

        return [
            'datasets' => [
                [
                    'label' => 'Total penjualan',
                    'data' => $dates
                        ->map(fn ($date): int => $salesByDate->get($date->toDateString(), 0))
                        ->all(),
                    'backgroundColor' => 'rgba(251, 191, 36, 0.12)',
                    'borderColor' => '#fbbf24',
                    'borderWidth' => 3,
                    'fill' => true,
                    'tension' => 0.42,
                    'pointBackgroundColor' => '#fbbf24',
                    'pointBorderColor' => '#0d2035',
                    'pointBorderWidth' => 2,
                    'pointRadius' => 4,
                    'pointHoverRadius' => 6,
                ],
            ],
            'labels' => $dates
                ->map(fn ($date): string => $date->locale('id')->translatedFormat('D, d M'))
                ->all(),
        ];
    }

    protected function getOptions(): RawJs
    {
        return RawJs::make(<<<'JS'
            {
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: (context) => 'Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y),
                        },
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                            callback: (value) => 'Rp ' + new Intl.NumberFormat('id-ID', { notation: 'compact' }).format(value),
                        },
                    },
                    x: {
                        grid: { display: false },
                    },
                },
            }
            JS);
    }

    protected function getType(): string
    {
        return 'line';
    }
}
