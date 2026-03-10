<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BookingStatsOverview extends BaseWidget
{
    protected static ?int $sort = 0;

    protected int | string | array $columnSpan = 'full';

    protected ?string $heading = 'Booking overview';

    protected ?string $description = 'Revenue and reservations (excluding cancelled).';

    protected static bool $isLazy = false;

    protected function getStats(): array
    {
        $confirmed = fn ($q) => $q->where('status', '!=', 'cancelled');

        $totalRevenue = (float) Booking::query()->when(true, $confirmed)->sum('total_amount');
        $totalBookings = Booking::query()->when(true, $confirmed)->count();

        $thisMonthStart = now()->startOfMonth();
        $thisMonthEnd = now()->endOfMonth();
        $lastMonthStart = now()->subMonth()->startOfMonth();
        $lastMonthEnd = now()->subMonth()->endOfMonth();

        $revenueThisMonth = (float) Booking::query()
            ->when(true, $confirmed)
            ->whereBetween('booking_date', [$thisMonthStart, $thisMonthEnd])
            ->sum('total_amount');
        $bookingsThisMonth = Booking::query()
            ->when(true, $confirmed)
            ->whereBetween('booking_date', [$thisMonthStart, $thisMonthEnd])
            ->count();

        $revenueLastMonth = (float) Booking::query()
            ->when(true, $confirmed)
            ->whereBetween('booking_date', [$lastMonthStart, $lastMonthEnd])
            ->sum('total_amount');
        $bookingsLastMonth = Booking::query()
            ->when(true, $confirmed)
            ->whereBetween('booking_date', [$lastMonthStart, $lastMonthEnd])
            ->count();

        $revenueDiff = $revenueLastMonth > 0
            ? round((($revenueThisMonth - $revenueLastMonth) / $revenueLastMonth) * 100, 1)
            : ($revenueThisMonth > 0 ? 100 : 0);
        $bookingsDiff = $bookingsLastMonth > 0
            ? $bookingsThisMonth - $bookingsLastMonth
            : ($bookingsThisMonth > 0 ? $bookingsThisMonth : 0);

        return [
            Stat::make('Total revenue', '€' . number_format($totalRevenue, 0))
                ->description('All time')
                ->descriptionIcon('heroicon-m-currency-euro')
                ->color('success'),
            Stat::make('Total reservations', (string) $totalBookings)
                ->description('All time')
                ->descriptionIcon('heroicon-m-ticket')
                ->color('primary'),
            Stat::make('Revenue this month', '€' . number_format($revenueThisMonth, 0))
                ->description($revenueDiff >= 0 ? "+{$revenueDiff}% vs last month" : "{$revenueDiff}% vs last month")
                ->descriptionIcon($revenueDiff >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->descriptionColor($revenueDiff >= 0 ? 'success' : 'danger')
                ->color('success'),
            Stat::make('Reservations this month', (string) $bookingsThisMonth)
                ->description($bookingsDiff >= 0 ? "+{$bookingsDiff} vs last month" : "{$bookingsDiff} vs last month")
                ->descriptionIcon($bookingsDiff >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->descriptionColor($bookingsDiff >= 0 ? 'success' : 'danger')
                ->color('primary'),
        ];
    }
}
