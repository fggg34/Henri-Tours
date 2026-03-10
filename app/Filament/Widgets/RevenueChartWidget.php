<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Filament\Widgets\ChartWidget;

class RevenueChartWidget extends ChartWidget
{
    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = 1;

    protected ?string $heading = 'Revenue by month';

    protected ?string $description = 'Last 12 months (excluding cancelled).';

    protected ?string $maxHeight = '300px';

    protected static bool $isLazy = false;

    protected function getData(): array
    {
        $labels = [];
        $data = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $start = $date->copy()->startOfMonth();
            $end = $date->copy()->endOfMonth();

            $labels[] = $date->format('M Y');
            $data[] = (float) Booking::query()
                ->where('status', '!=', 'cancelled')
                ->whereBetween('booking_date', [$start, $end])
                ->sum('total_amount');
        }

        return [
            'datasets' => [
                [
                    'label' => 'Revenue (€)',
                    'data' => $data,
                    'fill' => true,
                    'borderColor' => 'rgb(34, 197, 94)',
                    'backgroundColor' => 'rgba(34, 197, 94, 0.1)',
                    'tension' => 0.3,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
