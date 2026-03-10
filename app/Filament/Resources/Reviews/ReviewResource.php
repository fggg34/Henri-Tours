<?php

namespace App\Filament\Resources\Reviews;

use App\Filament\Resources\Reviews\Pages\ManageReviews;
use App\Models\Review;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('tour_id')
                    ->relationship('tour', 'title')
                    ->required(),
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->helperText('Leave empty for external/manual reviews.'),
                TextInput::make('name')
                    ->label('Display name (when no user)')
                    ->placeholder('e.g. John D.')
                    ->maxLength(255)
                    ->helperText('Used on frontend when no user is assigned.'),
                DatePicker::make('review_date')
                    ->label('Review date')
                    ->helperText('Optional. Overrides created date on frontend when set.'),
                TextInput::make('rating')
                    ->required()
                    ->numeric(),
                TextInput::make('title'),
                Textarea::make('comment')
                    ->columnSpanFull(),
                Select::make('platform')
                    ->label('Platform')
                    ->options([
                        'Getyourguide' => 'Getyourguide',
                        'Tripadvisor' => 'Tripadvisor',
                        'Tourradar' => 'Tourradar',
                        'TourHq' => 'TourHq',
                    ])
                    ->placeholder('Select a platform'),
                TextInput::make('platform_tour_url')
                    ->label('Tour URL from platform')
                    ->url()
                    ->placeholder('https://...')
                    ->maxLength(500)
                    ->helperText('When user clicks the platform logo, they will be taken to this URL.'),
                Toggle::make('is_approved')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tour.title')
                    ->searchable(),
                TextColumn::make('user.name')
                    ->placeholder('—')
                    ->searchable(),
                TextColumn::make('name')
                    ->label('Display name')
                    ->placeholder('—')
                    ->searchable(),
                TextColumn::make('review_date')
                    ->date()
                    ->sortable()
                    ->placeholder('—'),
                TextColumn::make('rating')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('platform')
                    ->badge(),
                IconColumn::make('is_approved')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('approve')
                    ->action(fn (Review $record) => $record->update(['is_approved' => true]))
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->visible(fn (Review $record) => ! $record->is_approved),
                Action::make('reject')
                    ->action(fn (Review $record) => $record->update(['is_approved' => false]))
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->visible(fn (Review $record) => $record->is_approved),
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
            'index' => ManageReviews::route('/'),
        ];
    }
}
