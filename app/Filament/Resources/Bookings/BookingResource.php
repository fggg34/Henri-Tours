<?php

namespace App\Filament\Resources\Bookings;

use App\Filament\Resources\Bookings\Pages\EditBooking;
use App\Filament\Resources\Bookings\Pages\ManageBookings;
use App\Models\Booking;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Booking & Tour')
                    ->schema([
                        Select::make('user_id')
                            ->relationship('user', 'name')
                            ->searchable(),
                        Select::make('tour_id')
                            ->relationship('tour', 'title')
                            ->required()
                            ->searchable(),
                        Hidden::make('tour_date_id'),
                        TextInput::make('tour_date_formatted')
                            ->label('Tour date')
                            ->disabled()
                            ->dehydrated(false),
                        Select::make('status')
                            ->options(['pending' => 'Pending', 'confirmed' => 'Confirmed', 'cancelled' => 'Cancelled'])
                            ->required()
                            ->default('pending'),
                        TextInput::make('total_amount')
                            ->required()
                            ->numeric()
                            ->default(0),
                        TextInput::make('currency')
                            ->required()
                            ->default('EUR'),
                        TextInput::make('guest_count')
                            ->required()
                            ->numeric()
                            ->default(1),
                        TextInput::make('confirmation_token')
                            ->disabled()
                            ->dehydrated(false)
                            ->visible(fn (string $operation): bool => $operation === 'edit'),
                    ])
                    ->columns(2),
                Section::make('Guest info (submitted by user)')
                    ->schema([
                        TextInput::make('guest_name')
                            ->required()
                            ->columnSpanFull(),
                        TextInput::make('first_name')
                            ->label('First name'),
                        TextInput::make('last_name')
                            ->label('Last name'),
                        TextInput::make('guest_email')
                            ->email()
                            ->required(),
                        TextInput::make('guest_phone')
                            ->tel(),
                        TextInput::make('pickup_location')
                            ->label('Pickup location')
                            ->columnSpanFull(),
                        Textarea::make('special_requests')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                Section::make('Billing address (submitted by user)')
                    ->schema([
                        TextInput::make('billing_country')
                            ->label('Country'),
                        TextInput::make('billing_region')
                            ->label('Region'),
                        TextInput::make('billing_city')
                            ->label('City'),
                        TextInput::make('billing_address')
                            ->label('Address')
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->collapsed(),
                Section::make('Payment')
                    ->schema([
                        TextInput::make('payment_status')
                            ->default('pending'),
                        TextInput::make('payment_method'),
                        TextInput::make('stripe_session_id')
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->collapsed(),
            ]);
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()
            ->with('tourDate')
            ->latest('created_at');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->searchable(),
                TextColumn::make('tour.title')
                    ->searchable(),
                TextColumn::make('booking_date')
                    ->label('Date')
                    ->formatStateUsing(fn ($state, $record) => $record->tourDate?->date?->format('Y-m-d') ?? $record->booking_date?->format('Y-m-d') ?? '—')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'confirmed' => 'success',
                        'cancelled' => 'danger',
                        default => 'warning',
                    }),
                TextColumn::make('total_amount')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('currency')
                    ->searchable(),
                TextColumn::make('guest_count')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('guest_name')
                    ->searchable(),
                TextColumn::make('guest_email')
                    ->searchable(),
                TextColumn::make('guest_phone')
                    ->searchable(),
                TextColumn::make('payment_status')
                    ->searchable(),
                TextColumn::make('payment_method')
                    ->searchable(),
                TextColumn::make('stripe_session_id')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make()
                    ->label('View details')
                    ->modalHeading(fn ($record) => 'Booking details – ' . ($record->reference ?? '#' . $record->id))
                    ->mutateRecordDataUsing(function (array $data, Model $record): array {
                        $record->loadMissing('tourDate');
                        $date = $record->tourDate?->date ?? $record->booking_date;
                        $data['tour_date_formatted'] = $date ? $date->format('Y-m-d') : '—';
                        return $data;
                    }),
                EditAction::make()
                    ->label('Edit')
                    ->url(fn ($record) => static::getUrl('edit', ['record' => $record])),
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
            'index' => ManageBookings::route('/'),
            'edit' => EditBooking::route('/{record}/edit'),
        ];
    }
}
