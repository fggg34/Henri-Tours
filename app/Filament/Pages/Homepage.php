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
        $bookingItems = Setting::get('homepage_booking_items', '');
        $bookingItems = is_string($bookingItems) ? (json_decode($bookingItems, true) ?: []) : $bookingItems;
        if (empty($bookingItems)) {
            $bookingItems = [
                ['icon' => 'fa-solid fa-shield-halved', 'title' => 'Feel confident in booking', 'description' => 'Cancel and get a refund up to 7 days before'],
                ['icon' => 'fa-regular fa-calendar', 'title' => 'Change of plans?', 'description' => 'Easily reschedule your booking'],
                ['icon' => 'fa-regular fa-credit-card', 'title' => 'Pay your way', 'description' => 'Fast, secure checkout in your currency'],
            ];
        }
        $formData['booking_visible'] = filter_var(Setting::get('homepage_booking_visible', true), FILTER_VALIDATE_BOOLEAN);
        $formData['booking_items'] = $bookingItems;
        $formData['difference_visible'] = filter_var(Setting::get('homepage_difference_visible', true), FILTER_VALIDATE_BOOLEAN);
        $formData['difference_title'] = Setting::get('homepage_difference_title', '');
        $formData['difference_paragraph_1'] = Setting::get('homepage_difference_paragraph_1', '');
        $formData['difference_paragraph_2'] = Setting::get('homepage_difference_paragraph_2', '');
        $formData['difference_cta_text'] = Setting::get('homepage_difference_cta_text', 'Read more about us');
        $formData['difference_cta_url'] = Setting::get('homepage_difference_cta_url', '');
        $formData['difference_image_1'] = Setting::get('homepage_difference_image_1', '');
        $formData['difference_image_2'] = Setting::get('homepage_difference_image_2', '');
        $formData['seo_title'] = Setting::get('homepage_seo_title', '');
        $formData['seo_description'] = Setting::get('homepage_seo_description', '');
        $seoOgImage = Setting::get('homepage_seo_og_image', '');
        $formData['seo_og_image'] = is_array($seoOgImage) ? ($seoOgImage[0] ?? '') : $seoOgImage;
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
                        SchemaSection::make('Booking Confidence')
                            ->description('The three trust points displayed below the featured tours (e.g. refund policy, reschedule, payment).')
                            ->collapsible()
                            ->schema([
                                Toggle::make('booking_visible')
                                    ->label('Show this section')
                                    ->default(true)
                                    ->helperText('When enabled, this section is displayed on the homepage.'),
                                Repeater::make('booking_items')
                                    ->schema([
                                        TextInput::make('icon')
                                            ->label('Icon (Font Awesome class)')
                                            ->placeholder('fa-solid fa-shield-halved')
                                            ->helperText('Use full class e.g. fa-solid fa-shield-halved or fa-regular fa-calendar'),
                                        TextInput::make('title')->label('Title')->required(),
                                        Textarea::make('description')->label('Description')->rows(2)->required(),
                                    ])
                                    ->columns(1)
                                    ->defaultItems(3)
                                    ->addActionLabel('Add item')
                                    ->reorderable()
                                    ->reorderableWithButtons()
                                    ->collapsible(),
                            ]),
                        SchemaSection::make('The Difference')
                            ->description('The "Difference" section with text and images below the booking confidence block.')
                            ->collapsible()
                            ->schema([
                                Toggle::make('difference_visible')
                                    ->label('Show this section')
                                    ->default(true)
                                    ->helperText('When enabled, this section is displayed on the homepage.'),
                                TextInput::make('difference_title')
                                    ->label('Section title')
                                    ->maxLength(255)
                                    ->placeholder('The ' . \App\Models\Setting::get('site_name', config('app.name')) . ' Difference')
                                    ->helperText('Leave empty to use: "The [Site Name] Difference"'),
                                Textarea::make('difference_paragraph_1')
                                    ->label('First paragraph')
                                    ->rows(4)
                                    ->columnSpanFull(),
                                Textarea::make('difference_paragraph_2')
                                    ->label('Second paragraph')
                                    ->rows(4)
                                    ->columnSpanFull(),
                                TextInput::make('difference_cta_text')
                                    ->label('Button text')
                                    ->default('Read more about us')
                                    ->maxLength(255),
                                TextInput::make('difference_cta_url')
                                    ->label('Button URL')
                                    ->url()
                                    ->placeholder(route('about'))
                                    ->helperText('Leave empty to link to the About page.'),
                                FileUpload::make('difference_image_1')
                                    ->label('Back image (landscape)')
                                    ->image()
                                    ->disk('public')
                                    ->directory('homepage/difference')
                                    ->visibility('public')
                                    ->imagePreviewHeight(120)
                                    ->helperText('Displayed as the rear tilted image.'),
                                FileUpload::make('difference_image_2')
                                    ->label('Front image (portrait)')
                                    ->image()
                                    ->disk('public')
                                    ->directory('homepage/difference')
                                    ->visibility('public')
                                    ->imagePreviewHeight(120)
                                    ->helperText('Displayed as the front tilted image.'),
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

        $bookingItems = $data['booking_items'] ?? [];
        Setting::set('homepage_booking_visible', isset($data['booking_visible']) ? ($data['booking_visible'] ? '1' : '0') : '1');
        Setting::set('homepage_booking_items', json_encode($bookingItems));

        $differenceKeys = [
            'difference_visible' => 'homepage_difference_visible',
            'difference_title' => 'homepage_difference_title',
            'difference_paragraph_1' => 'homepage_difference_paragraph_1',
            'difference_paragraph_2' => 'homepage_difference_paragraph_2',
            'difference_cta_text' => 'homepage_difference_cta_text',
            'difference_cta_url' => 'homepage_difference_cta_url',
            'difference_image_1' => 'homepage_difference_image_1',
            'difference_image_2' => 'homepage_difference_image_2',
        ];
        $seoKeys = ['seo_title' => 'homepage_seo_title', 'seo_description' => 'homepage_seo_description', 'seo_og_image' => 'homepage_seo_og_image'];
        foreach (array_merge($differenceKeys, $seoKeys) as $formKey => $settingKey) {
            $value = $data[$formKey] ?? null;
            if (is_array($value)) {
                $value = $value[0] ?? '';
            }
            Setting::set($settingKey, $value ?? '');
        }

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
