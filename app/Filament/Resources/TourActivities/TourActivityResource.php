<?php

namespace App\Filament\Resources\TourActivities;

use App\Filament\Resources\TourActivities\Pages\EditTourActivity;
use App\Filament\Resources\TourActivities\Pages\ManageTourActivities;
use App\Filament\Resources\TourActivities\RelationManagers\TranslationsRelationManager;
use App\Models\TourActivity;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Illuminate\Support\Str;
use Filament\Tables\Table;

class TourActivityResource extends Resource
{
    protected static ?string $model = TourActivity::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSparkles;

    protected static string|\UnitEnum|null $navigationGroup = 'Content';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Tour Activities';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                FileUpload::make('icon')
                    ->label('Icon (SVG)')
                    ->acceptedFileTypes(['image/svg+xml', 'image/svg'])
                    ->disk('public')
                    ->directory('tour_activities')
                    ->visibility('public')
                    ->helperText('Upload an SVG icon. Recommended size: 48×48 or 64×64px.'),
                TextInput::make('sort_order')
                    ->numeric()
                    ->default(0)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                ViewColumn::make('icon')
                    ->label('Icon')
                    ->view('filament.components.tour-activity-icon'),
                TextColumn::make('sort_order')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('tours_count')
                    ->counts('tours')
                    ->label('Tours')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('sort_order')
            ->filters([
                //
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

    public static function getRelations(): array
    {
        return [
            RelationManagers\TranslationsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageTourActivities::route('/'),
            'edit' => EditTourActivity::route('/{record}/edit'),
        ];
    }
}
