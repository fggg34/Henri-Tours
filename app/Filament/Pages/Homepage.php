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
