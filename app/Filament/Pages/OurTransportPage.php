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

class OurTransportPage extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedTruck;

    protected static ?string $navigationLabel = 'Our Transport';

    protected static ?string $title = 'Our Transport Page';

    protected static string|\UnitEnum|null $navigationGroup = 'Pages';

    public array $ourTransportForm = [];

    protected static ?int $navigationSort = 56;

    protected string $view = 'filament.pages.homepage';

    public function mount(): void
    {
        $heroImage = Setting::get('page_our_transport_hero_image', '');
        $heroImage = is_array($heroImage) ? ($heroImage[0] ?? '') : $heroImage;

        $seoOgImage = Setting::get('page_our_transport_seo_og_image', '');
        $seoOgImage = is_array($seoOgImage) ? ($seoOgImage[0] ?? '') : $seoOgImage;

        $this->getSchema('ourTransportForm')->fill([
            'hero_title' => Setting::get('page_our_transport_hero_title', 'Our Transport'),
            'hero_subtitle' => Setting::get('page_our_transport_hero_subtitle', 'Travel comfortably across Albania with our modern fleet. From minivans to coaches, we ensure a smooth ride for every journey.'),
            'hero_image' => $heroImage,
            'seo_title' => Setting::get('page_our_transport_seo_title', ''),
            'seo_description' => Setting::get('page_our_transport_seo_description', ''),
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

        Setting::set('page_our_transport_hero_title', $data['hero_title'] ?? '');
        Setting::set('page_our_transport_hero_subtitle', $data['hero_subtitle'] ?? '');
        Setting::set('page_our_transport_hero_image', $heroImage);
        Setting::set('page_our_transport_seo_title', $data['seo_title'] ?? '');
        Setting::set('page_our_transport_seo_description', $data['seo_description'] ?? '');
        Setting::set('page_our_transport_seo_og_image', $seoOgImage);

        Notification::make()->title('Our Transport page saved.')->success()->send();
    }
}
