<?php

namespace App\Filament\Resources\Hotels\Schemas;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class HotelForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Hotel')
                    ->tabs([
                        Tab::make('Overview')
                            ->schema([
                                Section::make('Basic information')
                                    ->schema([
                                        Select::make('city_id')
                                            ->relationship('city', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->required()
                                            ->label('City'),
                                        TextInput::make('name')
                                            ->required()
                                            ->maxLength(255)
                                            ->live(onBlur: true),
                                        TextInput::make('slug')
                                            ->required()
                                            ->maxLength(255)
                                            ->unique(ignoreRecord: true),
                                        FileUpload::make('image')
                                            ->label('Main image')
                                            ->image()
                                            ->disk('public')
                                            ->directory('hotels')
                                            ->visibility('public')
                                            ->imagePreviewHeight('280')
                                            ->panelAspectRatio('16/10')
                                            ->panelLayout('integrated')
                                            ->columnSpanFull(),
                                        FileUpload::make('gallery')
                                            ->label('Gallery images')
                                            ->image()
                                            ->disk('public')
                                            ->directory('hotels/gallery')
                                            ->visibility('public')
                                            ->multiple()
                                            ->reorderable()
                                            ->maxFiles(20)
                                            ->imagePreviewHeight('160')
                                            ->panelLayout('grid')
                                            ->helperText('Additional images shown in the gallery (2x2 grid next to main image).')
                                            ->columnSpanFull(),
                                        Select::make('stars_rating')
                                            ->label('Star rating')
                                            ->options([
                                                1 => '1 Star',
                                                2 => '2 Stars',
                                                3 => '3 Stars',
                                                4 => '4 Stars',
                                                5 => '5 Stars',
                                            ])
                                            ->nullable(),
                                        TextInput::make('total_reviews')
                                            ->numeric()
                                            ->minValue(0)
                                            ->default(0)
                                            ->suffix('reviews'),
                                        RichEditor::make('description')
                                            ->label('Description')
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(2),
                                Section::make('Location & map')
                                    ->schema([
                                        TextInput::make('location')
                                            ->label('Address / Location')
                                            ->maxLength(255)
                                            ->columnSpanFull(),
                                        TextInput::make('map_lat')
                                            ->label('Map latitude')
                                            ->numeric()
                                            ->placeholder('e.g. 41.3275')
                                            ->helperText('For the pin on the map and Google Maps link.'),
                                        TextInput::make('map_lng')
                                            ->label('Map longitude')
                                            ->numeric()
                                            ->placeholder('e.g. 19.8187'),
                                    ])
                                    ->columns(2),
                                Section::make('Hotel amenities')
                                    ->schema([
                                        CheckboxList::make('amenities')
                                            ->relationship('amenities', 'name', fn ($query) => $query->orderBy('sort_order')->orderBy('name'))
                                            ->columns(2)
                                            ->searchable()
                                            ->bulkToggleable()
                                            ->helperText('Select the amenities/facilities this hotel offers. Manage the list in Locations → Amenities.'),
                                    ])
                                    ->collapsed(false),
                                Section::make('House rules')
                                    ->schema([
                                        Repeater::make('house_rules')
                                            ->schema([
                                                TextInput::make('label')
                                                    ->label('Rule label')
                                                    ->required()
                                                    ->maxLength(100)
                                                    ->placeholder('e.g. Check In'),
                                                TextInput::make('value')
                                                    ->label('Value')
                                                    ->required()
                                                    ->maxLength(255)
                                                    ->placeholder('e.g. 12:00 pm'),
                                            ])
                                            ->columns(2)
                                            ->defaultItems(0)
                                            ->reorderable()
                                            ->collapsible()
                                            ->itemLabel(fn (array $state): ?string => ($state['label'] ?? '') . ': ' . ($state['value'] ?? '')),
                                    ])
                                    ->collapsed(false),
                            ]),
                        Tab::make('Contact')
                            ->schema([
                                Section::make('Contact details')
                                    ->schema([
                                        TextInput::make('phone')
                                            ->tel()
                                            ->maxLength(50),
                                        TextInput::make('email')
                                            ->email()
                                            ->maxLength(255),
                                        TextInput::make('website')
                                            ->url()
                                            ->maxLength(255)
                                            ->prefix('https://')
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(2),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
