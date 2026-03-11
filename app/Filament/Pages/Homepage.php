<?php

namespace App\Filament\Pages;

use App\Models\HomepageHero;
use App\Models\Setting;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section as SchemaSection;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;

class Homepage extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedHome;

    protected static ?string $navigationLabel = 'Homepage';

    protected static ?string $title = 'Homepage';

    protected static string|\UnitEnum|null $navigationGroup = 'Pages';

    /** @var array<string, mixed> Form state for all homepage sections */
    public array $homepageForm = [];

    protected static ?int $navigationSort = 50;

    protected string $view = 'filament.pages.homepage';

    public function mount(): void
    {
        $hero = HomepageHero::getActive() ?? HomepageHero::first() ?? new HomepageHero([
            'title' => 'Adventure Simplified',
            'subtitle' => 'Guides, local transport, accommodation, and like-minded travelers are always included. Book securely & flexibly.',
            'banner_type' => 'image',
            'is_active' => true,
        ]);

        if (! $hero->exists) {
            $hero->save();
        }

        $formData = $hero->only([
            'title', 'banner_type', 'banner_image', 'banner_video', 'is_active',
        ]);
        $formData['seo_title'] = Setting::get('homepage_seo_title', '');
        $formData['seo_description'] = Setting::get('homepage_seo_description', '');
        $seoOgImage = Setting::get('homepage_seo_og_image', '');
        $formData['seo_og_image'] = is_array($seoOgImage) ? ($seoOgImage[0] ?? '') : $seoOgImage;

        $formData['albania_visible'] = filter_var(Setting::get('homepage_albania_visible', true), FILTER_VALIDATE_BOOLEAN);
        $formData['albania_title'] = Setting::get('homepage_albania_title', 'Albania Inbound');
        $formData['albania_subtitle'] = Setting::get('homepage_albania_subtitle', 'Trusted experts for vacation packages, day trips, group tours and activities in Albania!');
        $albaniaImages = Setting::get('homepage_albania_images', '');
        $formData['albania_images'] = is_string($albaniaImages) ? (json_decode($albaniaImages, true) ?: []) : ($albaniaImages ?: []);
        $checkItems = Setting::get('homepage_albania_check_items', '');
        $formData['albania_check_items'] = is_string($checkItems) ? (json_decode($checkItems, true) ?: []) : ($checkItems ?: []);
        if (empty($formData['albania_check_items'])) {
            $formData['albania_check_items'] = [
                ['text' => 'Best Selection of Tours Expertly Crafted'],
                ['text' => 'Easy Booking & Free Cancelations'],
                ['text' => 'Expert Travel Agents and Local Guidance'],
                ['text' => 'English Customer Service'],
            ];
        }
        $platforms = Setting::get('homepage_albania_platforms', '');
        $formData['albania_platforms'] = is_string($platforms) ? (json_decode($platforms, true) ?: []) : ($platforms ?: []);
        if (empty($formData['albania_platforms'])) {
            $formData['albania_platforms'] = [
                ['label' => "Google Top Rated\nService", 'rating' => '4.9', 'reviews' => '84', 'icon_type' => 'google'],
                ['label' => "TripAdvisor\nTravelers' Favorite", 'rating' => '4.8', 'reviews' => '221', 'icon_type' => 'tripadvisor'],
                ['label' => "GetYourGuide Top\nRated Experience", 'rating' => '4.9', 'reviews' => '1,045', 'icon_type' => 'getyourguide'],
                ['label' => "Facebook\nCustomer Favorite", 'rating' => '4.8', 'reviews' => '43', 'icon_type' => 'facebook'],
            ];
        }

        $this->getSchema('homepageForm')->fill($formData);
    }

    public function homepageForm(Schema $schema): Schema
    {
        return $schema
            ->statePath('homepageForm')
            ->components([
                SchemaSection::make('Homepage')
                    ->description('Manage your homepage sections. Each section can be controlled independently.')
                    ->schema([
                        SchemaSection::make('Homepage Hero')
                            ->description('Customize the main hero banner displayed at the top of the homepage.')
                            ->collapsible()
                            ->schema([
                                TextInput::make('title')
                                    ->required()
                                    ->maxLength(255)
                                    ->columnSpanFull(),
                                Select::make('banner_type')
                                    ->options([
                                        'image' => 'Image',
                                        'video' => 'Video',
                                    ])
                                    ->default('image')
                                    ->required()
                                    ->live(),
                                FileUpload::make('banner_image')
                                    ->label('Banner image')
                                    ->image()
                                    ->disk('public')
                                    ->directory('heroes')
                                    ->visibility('public')
                                    ->imagePreviewHeight(200)
                                    ->panelAspectRatio('16/9')
                                    ->panelLayout('integrated')
                                    ->visible(fn ($get) => $get('banner_type') === 'image')
                                    ->columnSpanFull(),
                                FileUpload::make('banner_video')
                                    ->label('Banner video (MP4)')
                                    ->acceptedFileTypes(['video/mp4'])
                                    ->disk('public')
                                    ->directory('heroes')
                                    ->visibility('public')
                                    ->visible(fn ($get) => $get('banner_type') === 'video')
                                    ->columnSpanFull(),
                                Toggle::make('is_active')
                                    ->label('Show this hero on the homepage')
                                    ->default(true)
                                    ->helperText('When active, this hero is displayed on the homepage.'),
                            ]),
                        SchemaSection::make('Albania Inbound Section')
                            ->description('The "Albania Inbound" block with team gallery, checkmarks, and platform ratings.')
                            ->collapsible()
                            ->schema([
                                Toggle::make('albania_visible')
                                    ->label('Show this section')
                                    ->default(true)
                                    ->helperText('When enabled, this section is displayed on the homepage.'),
                                TextInput::make('albania_title')
                                    ->label('Section title')
                                    ->default('Albania Inbound')
                                    ->maxLength(255),
                                Textarea::make('albania_subtitle')
                                    ->label('Subtitle')
                                    ->rows(2)
                                    ->default('Trusted experts for vacation packages, day trips, group tours and activities in Albania!'),
                                FileUpload::make('albania_images')
                                    ->label('Gallery images')
                                    ->image()
                                    ->multiple()
                                    ->disk('public')
                                    ->directory('homepage/albania')
                                    ->visibility('public')
                                    ->imagePreviewHeight(120)
                                    ->reorderable()
                                    ->helperText('Add one or more images for the slider. Shown in order.'),
                                Repeater::make('albania_check_items')
                                    ->label('Checkmark items')
                                    ->schema([
                                        TextInput::make('text')
                                            ->label('Item text')
                                            ->required()
                                            ->maxLength(255),
                                    ])
                                    ->defaultItems(4)
                                    ->addActionLabel('Add item')
                                    ->reorderable()
                                    ->reorderableWithButtons()
                                    ->collapsible(),
                                Repeater::make('albania_platforms')
                                    ->label('Platform rating cards')
                                    ->schema([
                                        Textarea::make('label')
                                            ->label('Platform label')
                                            ->rows(2)
                                            ->required()
                                            ->helperText('Use line break for 2-line labels.'),
                                        TextInput::make('rating')
                                            ->label('Rating (e.g. 4.9)')
                                            ->required()
                                            ->maxLength(10),
                                        TextInput::make('reviews')
                                            ->label('Reviews count (e.g. 84)')
                                            ->maxLength(20),
                                        Select::make('icon_type')
                                            ->label('Platform icon')
                                            ->options([
                                                'google' => 'Google',
                                                'tripadvisor' => 'TripAdvisor',
                                                'getyourguide' => 'GetYourGuide',
                                                'facebook' => 'Facebook',
                                            ])
                                            ->required(),
                                    ])
                                    ->defaultItems(4)
                                    ->addActionLabel('Add platform')
                                    ->reorderable()
                                    ->reorderableWithButtons()
                                    ->collapsible(),
                            ]),
                        SchemaSection::make('SEO')
                            ->description('Meta title, description and OG image for the homepage.')
                            ->collapsible()
                            ->schema([
                                TextInput::make('seo_title')->label('Meta Title')->maxLength(70)->placeholder(\App\Models\Setting::get('site_name', config('app.name')) . ' - ' . \App\Models\Setting::get('site_tagline', '')),
                                Textarea::make('seo_description')->label('Meta Description')->rows(2)->maxLength(160)->placeholder(\App\Models\Setting::get('hero_subtitle', '')),
                                FileUpload::make('seo_og_image')
                                    ->label('OG Image')
                                    ->image()
                                    ->disk('public')
                                    ->directory('pages/homepage')
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
                Form::make([EmbeddedSchema::make('homepageForm')])
                    ->id('homepageForm')
                    ->livewireSubmitHandler('saveHomepage')
                    ->footer([
                        Actions::make([
                            Action::make('saveHomepage')
                                ->label('Save homepage')
                                ->submit('saveHomepage'),
                        ])->alignment(\Filament\Support\Enums\Alignment::Start),
                    ]),
            ]);
    }

    public function saveHomepage(): void
    {
        $data = $this->getSchema('homepageForm')->getState();

        $hero = HomepageHero::getActive() ?? HomepageHero::first();

        if (! $hero) {
            $hero = new HomepageHero();
        }

        $hero->fill(collect($data)->only($hero->getFillable())->toArray());
        $hero->save();

        $seoKeys = ['seo_title' => 'homepage_seo_title', 'seo_description' => 'homepage_seo_description', 'seo_og_image' => 'homepage_seo_og_image'];
        foreach ($seoKeys as $formKey => $settingKey) {
            $value = $data[$formKey] ?? null;
            if (is_array($value)) {
                $value = $value[0] ?? '';
            }
            Setting::set($settingKey, $value ?? '');
        }

        Setting::set('homepage_albania_visible', isset($data['albania_visible']) ? ($data['albania_visible'] ? '1' : '0') : '1');
        Setting::set('homepage_albania_title', $data['albania_title'] ?? '');
        Setting::set('homepage_albania_subtitle', $data['albania_subtitle'] ?? '');
        Setting::set('homepage_albania_images', json_encode($data['albania_images'] ?? []));
        Setting::set('homepage_albania_check_items', json_encode($data['albania_check_items'] ?? []));
        Setting::set('homepage_albania_platforms', json_encode($data['albania_platforms'] ?? []));

        Notification::make()->title('Homepage saved.')->success()->send();
    }

    private function normalizeFile(mixed $value): string
    {
        if (is_array($value)) {
            return $value[0] ?? '';
        }
        return (string) $value;
    }
}
