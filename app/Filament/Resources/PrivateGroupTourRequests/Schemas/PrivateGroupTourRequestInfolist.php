<?php

namespace App\Filament\Resources\PrivateGroupTourRequests\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PrivateGroupTourRequestInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('first_name'),
                TextEntry::make('last_name'),
                TextEntry::make('email')
                    ->label('Email address'),
                TextEntry::make('phone'),
                TextEntry::make('expected_departure_date')
                    ->date(),
                TextEntry::make('expected_return_date')
                    ->date(),
                TextEntry::make('number_of_participants')
                    ->numeric(),
                TextEntry::make('departing_from'),
                TextEntry::make('additional_info')
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
