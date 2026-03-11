<?php

namespace App\Filament\Resources\TransportBookings\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TransportBookingInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('email')
                    ->label('Email address'),
                TextEntry::make('telephone'),
                TextEntry::make('travel_date')
                    ->date(),
                TextEntry::make('travel_end_date')
                    ->date(),
                TextEntry::make('pickup_location'),
                TextEntry::make('dropoff_location'),
                TextEntry::make('preferred_vehicle')
                    ->placeholder('-'),
                TextEntry::make('group_size')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('message')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
