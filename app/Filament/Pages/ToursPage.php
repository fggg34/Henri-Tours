<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section as SchemaSection;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ToursPage extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedMapPin;

    protected static ?string $navigationLabel = 'Tours & Discounted Dates';

    protected static ?string $title = 'Tours & Discounted Dates Pages';

    protected static string|\UnitEnum|null $navigationGroup = 'Pages';

    public array $toursForm = [];

    protected static ?int $navigationSort = 52;

    protected string $view = 'filament.pages.homepage';

    public function mount(): void
    {
        $toursImage = Setting::get('page_tours_hero_image', '');
        $confirmedImage = Setting::get('page_confirmed_departures_hero_image', '');
        $toursImage = is_array($toursImage) ? ($toursImage[0] ?? '') : $toursImage;
        $confirmedImage = is_array($confirmedImage) ? ($confirmedImage[0] ?? '') : $confirmedImage;

        $this->getSchema('toursForm')->fill([
            'tours_hero_title' => Setting::get('page_tours_hero_title', 'Best Tours & Vacation Packages in Albania - Best Selection & Lowest Prices Guaranteed'),
            'tours_hero_subtitle' => Setting::get('page_tours_hero_subtitle', 'Choose from a wide range of tours, activities, and vacation packages across Albania and the Balkan region.'),
            'tours_hero_image' => $toursImage,
            'confirmed_hero_title' => Setting::get('page_confirmed_departures_hero_title', 'Organized Group Tours & Confirmed Departures – Discounted Rates in All Dates'),
            'confirmed_hero_subtitle' => Setting::get('page_confirmed_departures_hero_subtitle', 'Here we have listed all our confirmed dates with guaranteed departures – Take advantage of special discounts available only on these dates!'),
            'confirmed_hero_image' => $confirmedImage,
        ]);
    }

    public function toursForm(Schema $schema): Schema
    {
        return $schema
            ->statePath('toursForm')
            ->components([
                SchemaSection::make('Tours & Discounted Dates Pages')
                    ->description('Configure hero sections for the Tours index page and the Confirmed Departures (discounted dates) page. Category-specific heroes can be set when editing each Tour Category.')
                    ->schema([
                        SchemaSection::make('Tours page hero (/tours)')
                            ->description('Shown on the main tours listing page. Used as fallback when a category has no custom hero.')
                            ->collapsible()
                            ->schema([
                                TextInput::make('tours_hero_title')
                                    ->label('Title')
                                    ->maxLength(255)
                                    ->columnSpanFull(),
                                Textarea::make('tours_hero_subtitle')
                                    ->label('Subtitle')
                                    ->rows(2)
                                    ->columnSpanFull(),
                                FileUpload::make('tours_hero_image')
                                    ->label('Background image')
                                    ->image()
                                    ->disk('public')
                                    ->directory('pages/tours')
                                    ->visibility('public')
                                    ->imagePreviewHeight(120)
                                    ->columnSpanFull(),
                            ])
                            ->columns(1),
                        SchemaSection::make('Confirmed Departures page hero (/confirmed-departures)')
                            ->description('Shown on the discounted dates page.')
                            ->collapsible()
                            ->schema([
                                TextInput::make('confirmed_hero_title')
                                    ->label('Title')
                                    ->maxLength(255)
                                    ->columnSpanFull(),
                                Textarea::make('confirmed_hero_subtitle')
                                    ->label('Subtitle')
                                    ->rows(2)
                                    ->columnSpanFull(),
                                FileUpload::make('confirmed_hero_image')
                                    ->label('Background image')
                                    ->image()
                                    ->disk('public')
                                    ->directory('pages/confirmed-departures')
                                    ->visibility('public')
                                    ->imagePreviewHeight(120)
                                    ->columnSpanFull(),
                            ])
                            ->columns(1),
                    ]),
            ]);
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Form::make([EmbeddedSchema::make('toursForm')])
                    ->id('toursForm')
                    ->livewireSubmitHandler('saveTours')
                    ->footer([
                        Actions::make([
                            Action::make('saveTours')
                                ->label('Save')
                                ->submit('saveTours'),
                        ])->alignment(\Filament\Support\Enums\Alignment::Start),
                    ]),
            ]);
    }

    public function saveTours(): void
    {
        $data = $this->getSchema('toursForm')->getState();

        $toursImage = $data['tours_hero_image'] ?? '';
        $confirmedImage = $data['confirmed_hero_image'] ?? '';
        $toursImage = is_array($toursImage) ? ($toursImage[0] ?? '') : $toursImage;
        $confirmedImage = is_array($confirmedImage) ? ($confirmedImage[0] ?? '') : $confirmedImage;

        Setting::set('page_tours_hero_title', $data['tours_hero_title'] ?? '');
        Setting::set('page_tours_hero_subtitle', $data['tours_hero_subtitle'] ?? '');
        Setting::set('page_tours_hero_image', $toursImage);
        Setting::set('page_confirmed_departures_hero_title', $data['confirmed_hero_title'] ?? '');
        Setting::set('page_confirmed_departures_hero_subtitle', $data['confirmed_hero_subtitle'] ?? '');
        Setting::set('page_confirmed_departures_hero_image', $confirmedImage);

        Notification::make()->title('Tours & Discounted Dates pages saved.')->success()->send();
    }
}
