<?php

namespace App\Filament\Pages;

use App\Filament\Resources\TransportBookings\TransportBookingResource;
use App\Filament\Traits\HasTranslatablePageContent;
use App\Models\Setting;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
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

class OurTransportPage extends Page
{
    use HasTranslatablePageContent;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedTruck;

    protected static ?string $navigationLabel = 'Our Transport';

    protected static ?string $title = 'Our Transport Page';

    protected static string|\UnitEnum|null $navigationGroup = 'Pages';

    public array $ourTransportForm = [];

    protected static ?int $navigationSort = 56;

    protected string $view = 'filament.pages.homepage';

    public function getHeaderActions(): array
    {
        return [
            Action::make('viewBookings')
                ->label('View submissions')
                ->url(TransportBookingResource::getUrl('index'))
                ->icon(Heroicon::OutlinedTruck),
        ];
    }

    public function mount(): void
    {
        $heroImage = Setting::get('page_our_transport_hero_image', '');
        $heroImage = is_array($heroImage) ? ($heroImage[0] ?? '') : $heroImage;

        $seoOgImage = Setting::get('page_our_transport_seo_og_image', '');
        $seoOgImage = is_array($seoOgImage) ? ($seoOgImage[0] ?? '') : $seoOgImage;

        $vehicles = $this->getTranslatedSetting('page_our_transport_vehicles', '');
        $vehicles = is_string($vehicles) ? (json_decode($vehicles, true) ?: []) : ($vehicles ?: []);

        $featureCards = $this->getTranslatedSetting('page_our_transport_feature_cards', '');
        $featureCards = is_string($featureCards) ? (json_decode($featureCards, true) ?: []) : ($featureCards ?: []);
        if (empty($featureCards)) {
            $featureCards = [
                ['icon' => 'fa-shield-halved', 'icon_image' => '', 'title' => 'Safe & Reliable', 'description' => 'Regularly maintained vehicles for worry-free travel'],
                ['icon' => 'fa-couch', 'icon_image' => '', 'title' => 'Modern & Comfortable', 'description' => 'Air conditioning, comfortable seating, cozy ambient'],
                ['icon' => 'fa-id-badge', 'icon_image' => '', 'title' => 'Experienced Drivers', 'description' => 'Professional expert drivers for Albania & The Balkans'],
                ['icon' => 'fa-users', 'icon_image' => '', 'title' => 'All group sizes', 'description' => 'All size vehicles available from 3 - 55 seater'],
            ];
        }

        $this->getSchema('ourTransportForm')->fill([
            'hero_title' => $this->getTranslatedSetting('page_our_transport_hero_title', 'Our Transport'),
            'hero_subtitle' => $this->getTranslatedSetting('page_our_transport_hero_subtitle', 'Travel comfortably across Albania with our modern fleet. From minivans to coaches, we ensure a smooth ride for every journey.'),
            'hero_image' => $heroImage,
            'vehicles' => $vehicles,
            'feature_section_title' => $this->getTranslatedSetting('page_our_transport_feature_section_title', 'Why our transport stands out'),
            'feature_cards' => $featureCards,
            'form_title' => $this->getTranslatedSetting('page_our_transport_form_title', 'Book Your Transport Today'),
            'form_subtitle' => $this->getTranslatedSetting('page_our_transport_form_subtitle', 'Let us handle your transport so you can enjoy Albania stress-free'),
            'form_success_message' => $this->getTranslatedSetting('page_our_transport_form_success_message', 'Thank you! Your transport request has been submitted. We\'ll get back to you soon.'),
            'seo_title' => $this->getTranslatedSetting('page_our_transport_seo_title', ''),
            'seo_description' => $this->getTranslatedSetting('page_our_transport_seo_description', ''),
            'seo_og_image' => $seoOgImage,
        ]);
    }

    public function ourTransportForm(Schema $schema): Schema
    {
        return $schema
            ->statePath('ourTransportForm')
            ->components([
                SchemaSection::make('Our Transport Page')
                    ->description('Manage the Our Transport page at /our-transport. Hero section and SEO. More sections will be added in the next step.')
                    ->schema([
                        SchemaSection::make('Hero')
                            ->description('The hero section at the top – same style as tours archive, Private Group Tour Requests, etc. with global icons.')
                            ->collapsible()
                            ->schema([
                                TextInput::make('hero_title')
                                    ->label('Title')
                                    ->maxLength(255)
                                    ->default('Our Transport')
                                    ->columnSpanFull(),
                                Textarea::make('hero_subtitle')
                                    ->label('Subtitle')
                                    ->rows(2)
                                    ->columnSpanFull(),
                                FileUpload::make('hero_image')
                                    ->label('Background image')
                                    ->image()
                                    ->disk('public')
                                    ->directory('pages/our-transport')
                                    ->visibility('public')
                                    ->imagePreviewHeight(120)
                                    ->columnSpanFull(),
                            ])
                            ->columns(1),
                        SchemaSection::make('Vehicles')
                            ->description('Vehicles displayed in a 4-per-row grid. Each vehicle has a gallery (Swiper with fade), title, and features list.')
                            ->collapsible()
                            ->schema([
                                Repeater::make('vehicles')
                                    ->schema([
                                        FileUpload::make('gallery_images')
                                            ->label('Gallery images')
                                            ->image()
                                            ->multiple()
                                            ->maxFiles(10)
                                            ->disk('public')
                                            ->directory('pages/our-transport/vehicles')
                                            ->visibility('public')
                                            ->imagePreviewHeight(120)
                                            ->reorderable()
                                            ->columnSpanFull(),
                                        TextInput::make('title')
                                            ->label('Vehicle title')
                                            ->required()
                                            ->maxLength(255)
                                            ->placeholder('e.g. Mercedes Benz Travego VIP Black'),
                                        Repeater::make('features')
                                            ->label('Features / specifications')
                                            ->schema([
                                                TextInput::make('label')->label('Label')->required()->placeholder('e.g. Seating Capacity'),
                                                TextInput::make('value')->label('Value')->required()->placeholder('e.g. 55 seats'),
                                            ])
                                            ->columns(2)
                                            ->defaultItems(0)
                                            ->addActionLabel('Add feature')
                                            ->reorderable()
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(1)
                                    ->defaultItems(0)
                                    ->addActionLabel('Add vehicle')
                                    ->reorderable()
                                    ->reorderableWithButtons()
                                    ->collapsible(),
                            ]),
                        SchemaSection::make('Why our transport stands out')
                            ->description('Feature cards displayed between vehicles and the booking form. Each card: icon (image or Font Awesome class), title, description.')
                            ->collapsible()
                            ->schema([
                                TextInput::make('feature_section_title')
                                    ->label('Section title')
                                    ->maxLength(255)
                                    ->default('Why our transport stands out')
                                    ->columnSpanFull(),
                                Repeater::make('feature_cards')
                                    ->schema([
                                        FileUpload::make('icon_image')
                                            ->label('Icon image (optional)')
                                            ->image()
                                            ->disk('public')
                                            ->directory('pages/our-transport/feature-cards')
                                            ->visibility('public')
                                            ->imagePreviewHeight(80)
                                            ->helperText('Upload a custom image. If empty, Font Awesome icon is used.'),
                                        TextInput::make('icon')
                                            ->label('Font Awesome icon (fallback)')
                                            ->placeholder('fa-shield-halved')
                                            ->helperText('e.g. fa-shield-halved, fa-couch, fa-id-badge, fa-users. Used when no image is set.'),
                                        TextInput::make('title')
                                            ->label('Card title')
                                            ->required()
                                            ->maxLength(255)
                                            ->placeholder('e.g. Safe & Reliable'),
                                        Textarea::make('description')
                                            ->label('Description')
                                            ->rows(2)
                                            ->required()
                                            ->placeholder('e.g. Regularly maintained vehicles for worry-free travel'),
                                    ])
                                    ->columns(2)
                                    ->defaultItems(4)
                                    ->addActionLabel('Add card')
                                    ->reorderable()
                                    ->reorderableWithButtons()
                                    ->collapsible(),
                            ]),
                        SchemaSection::make('Booking Form')
                            ->description('Form shown below vehicles. "Book This Vehicle" scrolls to this form and pre-fills the selected vehicle.')
                            ->collapsible()
                            ->schema([
                                TextInput::make('form_title')
                                    ->label('Form title')
                                    ->maxLength(255)
                                    ->default('Book Your Transport Today'),
                                Textarea::make('form_subtitle')
                                    ->label('Form subtitle')
                                    ->rows(2)
                                    ->default('Let us handle your transport so you can enjoy Albania stress-free'),
                                Textarea::make('form_success_message')
                                    ->label('Success message after submit')
                                    ->rows(2)
                                    ->columnSpanFull()
                                    ->helperText('Shown after the form is submitted.'),
                            ])
                            ->columns(1),
                        SchemaSection::make('SEO')
                            ->description('Meta title, description and OG image for this page.')
                            ->collapsible()
                            ->schema([
                                TextInput::make('seo_title')
                                    ->label('Meta Title')
                                    ->maxLength(70)
                                    ->placeholder('Our Transport - ' . config('app.name')),
                                Textarea::make('seo_description')
                                    ->label('Meta Description')
                                    ->rows(2)
                                    ->maxLength(160)
                                    ->placeholder('Travel comfortably across Albania with our modern fleet.'),
                                FileUpload::make('seo_og_image')
                                    ->label('OG Image')
                                    ->image()
                                    ->disk('public')
                                    ->directory('pages/our-transport')
                                    ->visibility('public')
                                    ->imagePreviewHeight(120)
                                    ->helperText('Recommended: 1200×630px. Leave empty to use default from Settings.'),
                            ])
                            ->columns(1),
                    ]),
            ]);
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Form::make([EmbeddedSchema::make('ourTransportForm')])
                    ->id('ourTransportForm')
                    ->livewireSubmitHandler('saveOurTransport')
                    ->footer([
                        Actions::make([
                            Action::make('saveOurTransport')
                                ->label('Save')
                                ->submit('saveOurTransport'),
                        ])->alignment(\Filament\Support\Enums\Alignment::Start),
                    ]),
            ]);
    }

    public function saveOurTransport(): void
    {
        $data = $this->getSchema('ourTransportForm')->getState();

        $heroImage = $data['hero_image'] ?? '';
        $heroImage = is_array($heroImage) ? ($heroImage[0] ?? '') : $heroImage;

        $seoOgImage = $data['seo_og_image'] ?? '';
        $seoOgImage = is_array($seoOgImage) ? ($seoOgImage[0] ?? '') : $seoOgImage;

        $this->setTranslatedSetting('page_our_transport_hero_title', $data['hero_title'] ?? '');
        $this->setTranslatedSetting('page_our_transport_hero_subtitle', $data['hero_subtitle'] ?? '');
        Setting::set('page_our_transport_hero_image', $heroImage);

        $vehicles = $data['vehicles'] ?? [];
        foreach ($vehicles as &$v) {
            $imgs = $v['gallery_images'] ?? [];
            $v['gallery_images'] = is_array($imgs) ? array_values($imgs) : [];
        }
        $this->setTranslatedSetting('page_our_transport_vehicles', json_encode($vehicles));

        $featureCards = $data['feature_cards'] ?? [];
        foreach ($featureCards as &$c) {
            $img = $c['icon_image'] ?? '';
            $c['icon_image'] = is_array($img) ? ($img[0] ?? '') : $img;
        }
        $this->setTranslatedSetting('page_our_transport_feature_section_title', $data['feature_section_title'] ?? '');
        $this->setTranslatedSetting('page_our_transport_feature_cards', json_encode($featureCards));

        $this->setTranslatedSetting('page_our_transport_form_title', $data['form_title'] ?? '');
        $this->setTranslatedSetting('page_our_transport_form_subtitle', $data['form_subtitle'] ?? '');
        $this->setTranslatedSetting('page_our_transport_form_success_message', $data['form_success_message'] ?? '');

        $this->setTranslatedSetting('page_our_transport_seo_title', $data['seo_title'] ?? '');
        $this->setTranslatedSetting('page_our_transport_seo_description', $data['seo_description'] ?? '');
        Setting::set('page_our_transport_seo_og_image', $seoOgImage);

        Notification::make()->title('Our Transport page saved.')->success()->send();
    }
}
