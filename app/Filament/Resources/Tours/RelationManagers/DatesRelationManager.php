<?php

namespace App\Filament\Resources\Tours\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DatesRelationManager extends RelationManager
{
    protected static string $relationship = 'dates';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('date')->required(),
                TextInput::make('price')->numeric()->prefix('€'),
                TextInput::make('available_slots')->numeric()->default(10),
                Toggle::make('is_active')->default(true),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('date')
            ->columns([
                TextColumn::make('date')->date()->sortable(),
                TextColumn::make('price')->money('EUR'),
                TextColumn::make('available_slots'),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
