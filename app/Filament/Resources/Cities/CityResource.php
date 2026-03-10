<?php

namespace App\Filament\Resources\Cities;

use App\Filament\Resources\Cities\Pages\ManageCities;
use App\Models\City;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CityResource extends Resource
{
    protected static ?string $model = City::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    protected static string|\UnitEnum|null $navigationGroup = 'Cities';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true),
                TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                TextInput::make('country')
                    ->required()
                    ->maxLength(255),
                Toggle::make('is_active')
                    ->default(true)
                    ->helperText('Inactive cities are hidden from the homepage hero search and tour filters.'),
                RichEditor::make('description')
                    ->label('Description')
                    ->columnSpanFull(),
                FileUpload::make('city_image')
                    ->label('City image (hero)')
                    ->image()
                    ->disk('public')
                    ->directory('cities')
                    ->visibility('public')
                    ->imagePreviewHeight('260')
                    ->panelAspectRatio('16/10')
                    ->panelLayout('integrated')
                    ->helperText('Main image shown at the top of the city page.')
                    ->columnSpanFull(),
                FileUpload::make('gallery')
                    ->label('Gallery')
                    ->image()
                    ->disk('public')
                    ->directory('cities/gallery')
                    ->visibility('public')
                    ->multiple()
                    ->reorderable()
                    ->maxFiles(12)
                    ->imagePreviewHeight('120')
                    ->panelLayout('grid')
                    ->helperText('Add multiple images to the city gallery (up to 12).')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('city_image')
                    ->disk('public')
                    ->circular()
                    ->toggleable(),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active'),
                TextColumn::make('country')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('hotels_count')
                    ->counts('hotels')
                    ->label('Hotels')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                Action::make('preview')
                    ->label('Preview')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn (City $record): string => route('cities.show', $record->slug))
                    ->openUrlInNewTab()
                    ->visible(fn (City $record): bool => (bool) $record->slug),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageCities::route('/'),
        ];
    }
}
