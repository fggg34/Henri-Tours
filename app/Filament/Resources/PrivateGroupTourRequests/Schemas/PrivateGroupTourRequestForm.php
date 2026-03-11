<?php

namespace App\Filament\Resources\PrivateGroupTourRequests\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class PrivateGroupTourRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('first_name')
                    ->required(),
                TextInput::make('last_name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                TextInput::make('phone')
                    ->tel()
                    ->required(),
                DatePicker::make('expected_departure_date')
                    ->required(),
                DatePicker::make('expected_return_date')
                    ->required(),
                TextInput::make('number_of_participants')
                    ->required()
                    ->numeric(),
                TextInput::make('departing_from')
                    ->required(),
                Textarea::make('additional_info')
                    ->columnSpanFull(),
            ]);
    }
}
