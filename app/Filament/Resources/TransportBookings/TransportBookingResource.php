<?php

namespace App\Filament\Resources\TransportBookings;

use App\Filament\Resources\TransportBookings\Pages\ListTransportBookings;
use App\Filament\Resources\TransportBookings\Pages\ViewTransportBooking;
use App\Filament\Resources\TransportBookings\Schemas\TransportBookingForm;
use App\Filament\Resources\TransportBookings\Schemas\TransportBookingInfolist;
use App\Filament\Resources\TransportBookings\Tables\TransportBookingsTable;
use App\Models\TransportBooking;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TransportBookingResource extends Resource
{
    protected static ?string $model = TransportBooking::class;

    protected static ?string $navigationLabel = 'Transport Bookings';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTruck;

    protected static string|\UnitEnum|null $navigationGroup = 'Pages';

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Schema $schema): Schema
    {
        return TransportBookingForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TransportBookingInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TransportBookingsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTransportBookings::route('/'),
            'view' => ViewTransportBooking::route('/{record}'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
