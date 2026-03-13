<?php

namespace App\Filament\Resources\Tours\RelationManagers;

use App\Models\TourActivity;
use App\Models\TourCategory;
use App\Models\TourItineraryTranslation;
use App\Models\TourTranslation;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TranslationsRelationManager extends RelationManager
{
    protected static string $relationship = 'translations';

    protected static ?string $title = 'Translations';

    protected static ?string $recordTitleAttribute = 'locale';

    protected static array $supportedLocales = ['en', 'zh_CN', 'fr', 'de', 'he', 'it', 'mt', 'es'];

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('locale')
                    ->label('Language')
                    ->options(fn () => array_combine(static::$supportedLocales, array_map(
                        fn ($l) => __("locales.{$l}"),
                        static::$supportedLocales
                    )))
                    ->required(fn ($get) => ! $get('id'))
                    ->disabled(fn ($get) => (bool) $get('id'))
                    ->dehydrated(true)
                    ->default(fn ($record) => $record?->locale),
                Tabs::make('Translation')
                    ->tabs([
                        Tab::make('Overview')
                            ->schema([
                                Section::make('Category & activities')
                                    ->description('Only categories and activities with a translation in this language are shown. Add translations in Tour Categories and Tour Activities first.')
                                    ->schema([
                                        Select::make('category_id')
                                            ->label('Category')
                                            ->options(function ($get) {
                                                $locale = $get('locale') ?: ($this->record?->locale ?? 'en');
                                                return TourCategory::whereHas('translations', fn ($q) => $q->where('locale', $locale))
                                                    ->orderBy('sort_order')
                                                    ->get()
                                                    ->mapWithKeys(fn ($c) => [$c->id => $c->translate('name', $locale)]);
                                            })
                                            ->searchable()
                                            ->live()
                                            ->helperText('Assign a category for this language. Only categories with a translation in this locale appear.'),
                                        Select::make('activities')
                                            ->label('Tour activities')
                                            ->multiple()
                                            ->options(function ($get) {
                                                $locale = $get('locale') ?: ($this->record?->locale ?? 'en');
                                                return TourActivity::whereHas('translations', fn ($q) => $q->where('locale', $locale))
                                                    ->orderBy('sort_order')
                                                    ->get()
                                                    ->mapWithKeys(fn ($a) => [$a->id => $a->translate('title', $locale)]);
                                            })
                                            ->searchable()
                                            ->live()
                                            ->helperText('Assign activities for this language. Only activities with a translation in this locale appear.'),
                                    ])
                                    ->columns(2)
                                    ->collapsible(false),
                                Section::make('Main content')
                                    ->schema([
                                        TextInput::make('title')->required()->maxLength(255),
                                        TextInput::make('slug')->maxLength(255)->required(),
                                        Textarea::make('short_description')->rows(2)->columnSpanFull(),
                                        RichEditor::make('description')
                                            ->columnSpanFull()
                                            ->toolbarButtons(['bold', 'italic', 'bulletList', 'orderedList', 'link']),
                                    ])
                                    ->columns(2)
                                    ->collapsible(false),
                            ]),
                        Tab::make('Schedule & locations')
                            ->schema([
                                Section::make('Times & places')
                                    ->schema([
                                        TextInput::make('start_location')->label('Start location')->maxLength(255),
                                        TextInput::make('end_location')->label('End location')->maxLength(255),
                                        TextInput::make('start_time')->label('Start time')->maxLength(50)->placeholder('e.g. 09:00'),
                                        TagsInput::make('languages')->label('Languages')->placeholder('Add language')->splitKeys(['Tab', 'Enter']),
                                    ])
                                    ->columns(2)
                                    ->collapsible(false),
                            ]),
                        Tab::make('Inclusions')
                            ->schema([
                                Section::make('What\'s included')
                                    ->schema([
                                        TagsInput::make('included')->placeholder('Add item')->splitKeys(['Tab', 'Enter']),
                                        TagsInput::make('not_included')->label('Not included')->placeholder('Add item')->splitKeys(['Tab', 'Enter']),
                                        TagsInput::make('what_to_bring')->placeholder('Add item')->splitKeys(['Tab', 'Enter']),
                                    ])
                                    ->columns(1)
                                    ->collapsible()
                                    ->collapsed(false),
                                Section::make('Notes & highlights')
                                    ->schema([
                                        RichEditor::make('important_notes')
                                            ->toolbarButtons(['bold', 'italic', 'bulletList', 'orderedList', 'link']),
                                        Repeater::make('tour_highlights')
                                            ->simple(TextInput::make('text')->label('Highlight')->required())
                                            ->defaultItems(0)
                                            ->reorderable()
                                            ->reorderableWithButtons()
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(1)
                                    ->collapsible()
                                    ->collapsed(true),
                            ]),
                        Tab::make('Itinerary')
                            ->schema([
                                Section::make('Day-by-day')
                                    ->description('Translate the title and description for each itinerary day.')
                                    ->schema([
                                        Repeater::make('itinerary_items')
                                            ->schema([
                                                TextInput::make('tour_itinerary_id')->hidden()->dehydrated(),
                                                TextInput::make('day')->label('Day')->disabled()->dehydrated(false),
                                                TextInput::make('title')->label('Day title')->required(),
                                                RichEditor::make('description')
                                                    ->label('Day description')
                                                    ->toolbarButtons(['bold', 'italic', 'bulletList', 'orderedList', 'link']),
                                            ])
                                            ->default(function () {
                                                $tour = $this->getOwnerRecord();
                                                $record = $this->record ?? null;
                                                $locale = $record?->locale ?? request()->input('locale', 'en');
                                                return $tour->itineraries->map(function ($it) use ($locale) {
                                                    $tr = $it->translations()->where('locale', $locale)->first();
                                                    return [
                                                        'tour_itinerary_id' => $it->id,
                                                        'day' => $it->day,
                                                        'title' => $tr?->title ?? $it->title,
                                                        'description' => $tr?->description ?? $it->description,
                                                    ];
                                                })->values()->toArray();
                                            })
                                            ->dehydrated()
                                            ->itemLabel(fn (array $state) => ($state['day'] ?? '') ? 'Day ' . $state['day'] : ('Item #' . ($state['tour_itinerary_id'] ?? '')))
                                            ->columnSpanFull(),
                                    ])
                                    ->collapsible(false),
                            ]),
                        Tab::make('SEO')
                            ->schema([
                                Section::make('Meta')
                                    ->schema([
                                        TextInput::make('meta_title')->maxLength(60),
                                        Textarea::make('meta_description')->rows(2)->maxLength(500),
                                    ])
                                    ->columns(1)
                                    ->collapsible(false),
                            ]),
                    ])
                    ->columnSpanFull()
                    ->persistTabInQueryString('translation-tab'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('locale')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => __('locales.' . $state)),
                TextColumn::make('title')->limit(40),
                TextColumn::make('slug')->limit(30),
            ])
            ->headerActions([
                CreateAction::make()
                    ->slideOver()
                    ->modalWidth('5xl')
                    ->using(function (array $data) {
                        $itineraryItems = $data['itinerary_items'] ?? [];
                        $activities = $data['activities'] ?? [];
                        unset($data['itinerary_items'], $data['activities']);
                        $record = $this->getOwnerRecord()->translations()->create($data);
                        $record->activities()->sync($activities);
                        $this->syncItineraryTranslations($record, $itineraryItems);
                        return $record;
                    }),
            ])
            ->recordActions([
                EditAction::make()
                    ->slideOver()
                    ->modalWidth('5xl')
                    ->fillForm(function (mixed $livewire, TourTranslation $record): array {
                        $data = [...$record->attributesToArray(), 'activities' => $record->activities->pluck('id')->toArray()];
                        return $data;
                    })
                    ->using(function (TourTranslation $record, array $data) {
                        $itineraryItems = $data['itinerary_items'] ?? [];
                        $activityIds = $data['activities'] ?? [];
                        unset($data['itinerary_items'], $data['activities']);
                        // Disabled fields aren't submitted – preserve locale from record
                        if (! isset($data['locale'])) {
                            $data['locale'] = $record->locale;
                        }
                        $record->update($data);
                        $record->activities()->sync($activityIds);
                        $this->syncItineraryTranslations($record, $itineraryItems);
                        return $record;
                    }),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    protected function syncItineraryTranslations(TourTranslation $translation, array $itineraryItems): void
    {
        $locale = $translation->locale;
        foreach ($itineraryItems as $item) {
            $itineraryId = $item['tour_itinerary_id'] ?? null;
            if (! $itineraryId) {
                continue;
            }
            TourItineraryTranslation::updateOrCreate(
                [
                    'tour_itinerary_id' => $itineraryId,
                    'locale' => $locale,
                ],
                [
                    'title' => $item['title'] ?? '',
                    'description' => $item['description'] ?? null,
                ]
            );
        }
    }

    public function mount(): void
    {
        parent::mount();
    }
}
