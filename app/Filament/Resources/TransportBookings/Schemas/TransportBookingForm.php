<?php

namespace App\Filament\Resources\TransportBookings\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class TransportBookingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                TextInput::make('telephone')
                    ->tel()
                    ->required(),
                DatePicker::make('travel_date')
                    ->required(),
                DatePicker::make('travel_end_date')
                    ->required(),
                TextInput::make('pickup_location')
                    ->required(),
                TextInput::make('dropoff_location')
                    ->required(),
                TextInput::make('preferred_vehicle'),
                TextInput::make('group_size')
                    ->numeric(),
                Textarea::make('message')
                    ->columnSpanFull(),
            ]);
    }
}
