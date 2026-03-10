<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Filament\Widgets\ChartWidget;

class BookingsChartWidget extends ChartWidget
{
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 1;

    protected ?string $heading = 'Reservations by month';

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
            $data[] = Booking::query()
                ->where('status', '!=', 'cancelled')
                ->whereBetween('booking_date', [$start, $end])
                ->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Reservations',
                    'data' => $data,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.5)',
                    'borderColor' => 'rgb(59, 130, 246)',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
