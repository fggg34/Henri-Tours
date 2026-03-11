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

class PrivateGroupTourRequestsPage extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?string $navigationLabel = 'Private Group Tour Requests';

    protected static ?string $title = 'Private Group Tour Requests Page';

    protected static string|\UnitEnum|null $navigationGroup = 'Pages';

    public array $privateGroupForm = [];

    protected static ?int $navigationSort = 55;

    protected string $view = 'filament.pages.homepage';

    public function mount(): void
    {
        $heroImage = Setting::get('page_private_group_tour_requests_hero_image', '');
        $heroImage = is_array($heroImage) ? ($heroImage[0] ?? '') : $heroImage;

        $seoOgImage = Setting::get('page_private_group_tour_requests_seo_og_image', '');
        $seoOgImage = is_array($seoOgImage) ? ($seoOgImage[0] ?? '') : $seoOgImage;

        $this->getSchema('privateGroupForm')->fill([
            'hero_title' => Setting::get('page_private_group_tour_requests_hero_title', 'Private Group Tour Requests'),
            'hero_subtitle' => Setting::get('page_private_group_tour_requests_hero_subtitle', 'Request a custom tour for your group. Tell us your dates, group size, and preferences – we\'ll create a tailored itinerary just for you.'),
            'hero_image' => $heroImage,
            'seo_title' => Setting::get('page_private_group_tour_requests_seo_title', ''),
            'seo_description' => Setting::get('page_private_group_tour_requests_seo_description', ''),
            'seo_og_image' => $seoOgImage,
        ]);
    }

    public function privateGroupForm(Schema $schema): Schema
    {
        return $schema
            ->statePath('privateGroupForm')
            ->components([
                SchemaSection::make('Private Group Tour Requests Page')
                    ->description('Manage the Private Group Tour Requests page at /private-group-tour-requests. More sections can be added here as needed.')
                    ->schema([
                        SchemaSection::make('Hero')
                            ->description('The hero section at the top of the page – same style as the tours archive and category pages.')
                            ->collapsible()
                            ->schema([
                                TextInput::make('hero_title')
                                    ->label('Title')
                                    ->maxLength(255)
                                    ->default('Private Group Tour Requests')
                                    ->columnSpanFull(),
                                Textarea::make('hero_subtitle')
                                    ->label('Subtitle')
                                    ->rows(2)
                                    ->columnSpanFull(),
                                FileUpload::make('hero_image')
                                    ->label('Background image')
                                    ->image()
                                    ->disk('public')
                                    ->directory('pages/private-group-tour-requests')
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
                                    ->placeholder('Private Group Tour Requests - ' . config('app.name')),
                                Textarea::make('seo_description')
                                    ->label('Meta Description')
                                    ->rows(2)
                                    ->maxLength(160)
                                    ->placeholder('Request a custom tour for your group. Tell us your dates and preferences.'),
                                FileUpload::make('seo_og_image')
                                    ->label('OG Image')
                                    ->image()
                                    ->disk('public')
                                    ->directory('pages/private-group-tour-requests')
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
                Form::make([EmbeddedSchema::make('privateGroupForm')])
                    ->id('privateGroupForm')
                    ->livewireSubmitHandler('savePrivateGroup')
                    ->footer([
                        Actions::make([
                            Action::make('savePrivateGroup')
                                ->label('Save')
                                ->submit('savePrivateGroup'),
                        ])->alignment(\Filament\Support\Enums\Alignment::Start),
                    ]),
            ]);
    }

    public function savePrivateGroup(): void
    {
        $data = $this->getSchema('privateGroupForm')->getState();

        $heroImage = $data['hero_image'] ?? '';
        $heroImage = is_array($heroImage) ? ($heroImage[0] ?? '') : $heroImage;

        $seoOgImage = $data['seo_og_image'] ?? '';
        $seoOgImage = is_array($seoOgImage) ? ($seoOgImage[0] ?? '') : $seoOgImage;

        Setting::set('page_private_group_tour_requests_hero_title', $data['hero_title'] ?? '');
        Setting::set('page_private_group_tour_requests_hero_subtitle', $data['hero_subtitle'] ?? '');
        Setting::set('page_private_group_tour_requests_hero_image', $heroImage);
        Setting::set('page_private_group_tour_requests_seo_title', $data['seo_title'] ?? '');
        Setting::set('page_private_group_tour_requests_seo_description', $data['seo_description'] ?? '');
        Setting::set('page_private_group_tour_requests_seo_og_image', $seoOgImage);

        Notification::make()->title('Private Group Tour Requests page saved.')->success()->send();
    }
}
