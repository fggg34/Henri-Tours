<?php

namespace App\Filament\Resources\Tours\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class TourForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Tour')
                    ->tabs([
                        Tab::make('Overview')
                            ->schema([
                                Section::make('Main information')
                                    ->schema([
                                        Select::make('category_id')
                                            ->relationship('category', 'name')
                                            ->required(),
                                        Select::make('cities')
                                            ->relationship('cities', 'name')
                                            ->multiple()
                                            ->searchable()
                                            ->preload()
                                            ->label('Cities'),
                                        TextInput::make('title')->required()->live(onBlur: true),
                                        TextInput::make('slug')->required()->maxLength(255),
                                        Textarea::make('short_description')->rows(2)->columnSpanFull()->helperText('Brief plain-text summary.'),
                                        RichEditor::make('description')->label('Description')->columnSpanFull(),
                                    ])
                                    ->columns(2),
                                Section::make('Pricing')
                                    ->schema([
                                        TextInput::make('base_price')->numeric()->default(0)->prefix('€')->required()->label('Base price (per person, fallback when no tier matches)'),
                                        Repeater::make('pricingTiers')
                                            ->relationship()
                                            ->schema([
                                                TextInput::make('min_people')->numeric()->required()->minValue(1)->suffix('persons'),
                                                TextInput::make('max_people')->numeric()->nullable()->minValue(1)->placeholder('Leave empty for 9+'),
                                                TextInput::make('price_per_person')->numeric()->required()->prefix('€'),
                                            ])
                                            ->columns(3)
                                            ->defaultItems(0)
                                            ->reorderable()
                                            ->reorderableWithButtons()
                                            ->label('Group pricing tiers')
                                            ->helperText('e.g. 1-1: 80€, 2-4: 60€, 5-8: 50€, 9+: 45€'),
                                        Repeater::make('discounts')
                                            ->relationship()
                                            ->schema([
                                                \Filament\Forms\Components\DatePicker::make('start_date')->required()->label('Start date'),
                                                \Filament\Forms\Components\DatePicker::make('end_date')->required()->label('End date'),
                                                Select::make('discount_type')
                                                    ->options(['fixed' => 'Fixed amount (€)', 'percent' => 'Percentage (%)'])
                                                    ->required()
                                                    ->default('fixed'),
                                                TextInput::make('discount_value')->numeric()->required()->minValue(0),
                                                TextInput::make('label')->placeholder('e.g. Early bird')->label('Label (optional)'),
                                            ])
                                            ->columns(2)
                                            ->defaultItems(0)
                                            ->reorderable()
                                            ->reorderableWithButtons()
                                            ->label('Date-based discounts')
                                            ->helperText('Discounts for specific date ranges. First matching discount is applied when a date is selected.'),
                                    ])
                                    ->columns(1),
                                Section::make('Visibility & order')
                                    ->schema([
                                        Toggle::make('is_featured')->default(false),
                                        Toggle::make('is_active')->default(true),
                                        TextInput::make('sort_order')->numeric()->default(0),
                                    ])
                                    ->columns(3),
                            ]),
                        Tab::make('Schedule & locations')
                            ->schema([
                                Section::make('Duration & time')
                                    ->schema([
                                        TextInput::make('duration_hours')->numeric()->suffix('hours'),
                                        TextInput::make('duration_days')->numeric()->suffix('days'),
                                        TextInput::make('start_time')->maxLength(50)->placeholder('e.g. 09:00'),
                                    ])
                                    ->columns(3),
                                Section::make('Start & end locations')
                                    ->schema([
                                        TextInput::make('start_location')->columnSpanFull(),
                                        TextInput::make('end_location')->columnSpanFull(),
                                    ]),
                                Section::make('Group & languages')
                                    ->schema([
                                        TextInput::make('max_group_size')->numeric()->suffix('people'),
                                        TagsInput::make('languages')->placeholder('Add language'),
                                    ])
                                    ->columns(2),
                            ]),
                        Tab::make('Availability')
                            ->schema([
                                Section::make('Availability window')
                                    ->description('Date range when this tour can be booked. Leave empty for no limit.')
                                    ->schema([
                                        \Filament\Forms\Components\DatePicker::make('availability_start_date'),
                                        \Filament\Forms\Components\DatePicker::make('availability_end_date'),
                                    ])
                                    ->columns(2),
                                Section::make('Daily capacity & weekdays')
                                    ->schema([
                                        TextInput::make('default_daily_capacity')->numeric()->minValue(1)->placeholder('Max guests per day'),
                                        Select::make('available_weekdays')
                                            ->multiple()
                                            ->options([
                                                0 => 'Sunday',
                                                1 => 'Monday',
                                                2 => 'Tuesday',
                                                3 => 'Wednesday',
                                                4 => 'Thursday',
                                                5 => 'Friday',
                                                6 => 'Saturday',
                                            ])
                                            ->placeholder('All days if empty'),
                                    ])
                                    ->columns(2),
                                Section::make('Blocked dates')
                                    ->schema([
                                        \Filament\Forms\Components\TagsInput::make('closed_dates')
                                            ->placeholder('Add date (YYYY-MM-DD)')
                                            ->helperText('Dates when tour is not available (e.g. 2025-12-25)'),
                                    ]),
                            ]),
                        Tab::make('Included / Excluded')
                            ->schema([
                                Section::make('What\'s included')
                                    ->schema([
                                        TagsInput::make('included')
                                            ->placeholder('Add item (e.g. Guide, Transport)')
                                            ->splitKeys(['Tab', 'Enter']),
                                    ]),
                                Section::make('What\'s not included')
                                    ->schema([
                                        TagsInput::make('not_included')
                                            ->placeholder('Add item')
                                            ->splitKeys(['Tab', 'Enter']),
                                    ]),
                            ]),
                        Tab::make('Extras')
                            ->schema([
                                Section::make('What to bring')
                                    ->description('Items guests should bring on the tour. Add multiple options.')
                                    ->schema([
                                        TagsInput::make('what_to_bring')
                                            ->placeholder('Add item (e.g. Comfortable shoes, Sunscreen)')
                                            ->splitKeys(['Tab', 'Enter']),
                                    ]),
                                Section::make('Important notes')
                                    ->description('Key information guests should know before booking.')
                                    ->schema([
                                        RichEditor::make('important_notes')
                                            ->label('Important notes')
                                            ->columnSpanFull()
                                            ->toolbarButtons(['bold', 'italic', 'bulletList', 'orderedList', 'link']),
                                    ]),
                                Section::make('Season & difficulty')
                                    ->description('When is this tour typically available? Difficulty level for participants.')
                                    ->schema([
                                        Select::make('season')
                                            ->label('Availability (Season)')
                                            ->options([
                                                'all_season' => 'All season',
                                                'summer' => 'Summer',
                                                'winter' => 'Winter',
                                            ])
                                            ->placeholder('Select season')
                                            ->nullable(),
                                        Select::make('difficulty')
                                            ->options([
                                                'easy' => 'Easy',
                                                'moderate' => 'Moderate',
                                                'challenging' => 'Challenging',
                                                'strenuous' => 'Strenuous',
                                            ])
                                            ->placeholder('Select difficulty')
                                            ->nullable(),
                                    ])
                                    ->columns(2),
                                Section::make('Tour highlights')
                                    ->description('Key experiences the tour offers. Drag to reorder.')
                                    ->schema([
                                        Repeater::make('tour_highlights')
                                            ->simple(
                                                TextInput::make('text')
                                                    ->label('Highlight')
                                                    ->required()
                                                    ->placeholder('e.g. Exclusive access to historical sites')
                                            )
                                            ->defaultItems(0)
                                            ->reorderable()
                                            ->reorderableWithButtons()
                                            ->columnSpanFull()
                                            ->helperText('Each item is a key experience. Drag to reorder.'),
                                    ]),
                            ]),
                        Tab::make('Itinerary')
                            ->schema([
                                Section::make('Day-by-day itinerary')
                                    ->schema([
                                        Repeater::make('itineraries')
                                            ->relationship()
                                            ->schema([
                                                TextInput::make('day')->numeric()->nullable()->suffix('Day'),
                                                TextInput::make('title')->required(),
                                                RichEditor::make('description')->label('Description'),
                                                Select::make('hotel_id')
                                                    ->relationship('hotel', 'name')
                                                    ->searchable()
                                                    ->preload()
                                                    ->nullable()
                                                    ->label('Hotel (optional)'),
                                                Select::make('highlights')
                                                    ->relationship('highlights', 'title')
                                                    ->multiple()
                                                    ->searchable()
                                                    ->preload()
                                                    ->nullable()
                                                    ->label('Highlights (optional)')
                                                    ->helperText('Places or attractions visited on this day'),
                                                TextInput::make('sort_order')->numeric()->default(0),
                                            ])
                                            ->defaultItems(0)
                                            ->columnSpanFull()
                                            ->reorderable()
                                            ->reorderableWithButtons(),
                                    ]),
                            ]),
                        Tab::make('SEO')
                            ->schema([
                                Section::make('Search engine optimization')
                                    ->description('Optional. Leave blank to use the tour title and short description.')
                                    ->schema([
                                        TextInput::make('meta_title')->maxLength(60)->columnSpanFull(),
                                        Textarea::make('meta_description')->rows(3)->maxLength(500)->columnSpanFull(),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
