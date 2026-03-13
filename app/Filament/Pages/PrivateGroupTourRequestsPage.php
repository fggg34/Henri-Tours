<?php

namespace App\Filament\Pages;

use App\Filament\Resources\PrivateGroupTourRequests\PrivateGroupTourRequestResource;
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

class PrivateGroupTourRequestsPage extends Page
{
    use HasTranslatablePageContent;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?string $navigationLabel = 'Private Group Tour Requests';

    protected static ?string $title = 'Private Group Tour Requests Page';

    protected static string|\UnitEnum|null $navigationGroup = 'Pages';

    public array $privateGroupForm = [];

    protected static ?int $navigationSort = 55;

    protected string $view = 'filament.pages.homepage';

    public function getHeaderActions(): array
    {
        return [
            Action::make('viewSubmissions')
                ->label('View submissions')
                ->url(PrivateGroupTourRequestResource::getUrl('index'))
                ->icon(Heroicon::OutlinedUserGroup),
        ];
    }

    public function mount(): void
    {
        $heroImage = Setting::get('page_private_group_tour_requests_hero_image', '');
        $heroImage = is_array($heroImage) ? ($heroImage[0] ?? '') : $heroImage;

        $seoOgImage = Setting::get('page_private_group_tour_requests_seo_og_image', '');
        $seoOgImage = is_array($seoOgImage) ? ($seoOgImage[0] ?? '') : $seoOgImage;

        $featureCards = $this->getTranslatedSetting('page_private_group_tour_requests_feature_cards', '');
        $featureCards = is_string($featureCards) ? (json_decode($featureCards, true) ?: []) : $featureCards;
        if (empty($featureCards)) {
            $featureCards = [
                ['icon' => 'fa-award', 'title' => 'Over a Decade of Excellence', 'description' => 'With years of experience, Albania Inbound delivers unforgettable journeys, making every trip extraordinary.'],
                ['icon' => 'fa-map-location-dot', 'title' => 'Inspiring Journeys', 'description' => "We go beyond the usual, offering immersive experiences that uncover Albania's hidden gems."],
                ['icon' => 'fa-handshake', 'title' => 'Travel with Purpose', 'description' => 'Committed to sustainability, we ensure every trip supports local communities and preserves culture.'],
            ];
        }

        $this->getSchema('privateGroupForm')->fill([
            'hero_title' => $this->getTranslatedSetting('page_private_group_tour_requests_hero_title', 'Private Group Tour Requests'),
            'hero_subtitle' => $this->getTranslatedSetting('page_private_group_tour_requests_hero_subtitle', 'Request a custom tour for your group. Tell us your dates, group size, and preferences – we\'ll create a tailored itinerary just for you.'),
            'hero_image' => $heroImage,
            'intro_title' => $this->getTranslatedSetting('page_private_group_tour_requests_intro_title', 'Why choose Albania Inbound?'),
            'intro_content' => $this->getTranslatedSetting('page_private_group_tour_requests_intro_content', 'We offer fast, priority support for private group enquiries. Our dedicated travel agents will create a customized travel plan tailored to your group – no complex forms, no hassle. Just tell us your preferences and we\'ll take care of the rest.'),
            'intro_show_more_text' => $this->getTranslatedSetting('page_private_group_tour_requests_intro_show_more_text', 'Show more'),
            'intro_show_more_url' => Setting::get('page_private_group_tour_requests_intro_show_more_url', ''),
            'intro_show_more_content' => $this->getTranslatedSetting('page_private_group_tour_requests_intro_show_more_content', ''),
            'form_success_message' => $this->getTranslatedSetting('page_private_group_tour_requests_form_success_message', 'Thank you! Your request has been submitted. We\'ll get back to you soon.'),
            'feature_cards' => $featureCards,
            'seo_title' => $this->getTranslatedSetting('page_private_group_tour_requests_seo_title', ''),
            'seo_description' => $this->getTranslatedSetting('page_private_group_tour_requests_seo_description', ''),
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
                        SchemaSection::make('Intro Section')
                            ->description('The "Why choose Albania Inbound?" block above the form.')
                            ->collapsible()
                            ->schema([
                                TextInput::make('intro_title')
                                    ->label('Title')
                                    ->maxLength(255)
                                    ->default('Why choose Albania Inbound?')
                                    ->columnSpanFull(),
                                Textarea::make('intro_content')
                                    ->label('Content (paragraph)')
                                    ->rows(4)
                                    ->columnSpanFull()
                                    ->helperText('Main visible text. Use line breaks for paragraphs.'),
                                TextInput::make('intro_show_more_text')
                                    ->label('"Show more" link text')
                                    ->maxLength(100)
                                    ->placeholder('Show more'),
                                TextInput::make('intro_show_more_url')
                                    ->label('"Show more" link URL')
                                    ->url()
                                    ->placeholder('Leave empty to use expandable content below'),
                                Textarea::make('intro_show_more_content')
                                    ->label('Expandable content (if no URL)')
                                    ->rows(4)
                                    ->columnSpanFull()
                                    ->helperText('If "Show more" URL is empty, this content expands inline when clicked.'),
                            ])
                            ->columns(2),
                        SchemaSection::make('Form Section')
                            ->description('The enquiry form. Submissions are stored and can be viewed in the admin.')
                            ->collapsible()
                            ->schema([
                                Textarea::make('form_success_message')
                                    ->label('Success message after submit')
                                    ->rows(2)
                                    ->columnSpanFull()
                                    ->helperText('Shown to the user after they submit the form.'),
                            ]),
                        SchemaSection::make('Feature Cards')
                            ->description('Three cards below the form (e.g. Excellence, Inspiring Journeys, Travel with Purpose).')
                            ->collapsible()
                            ->schema([
                                Repeater::make('feature_cards')
                                    ->schema([
                                        TextInput::make('icon')
                                            ->label('Icon (Font Awesome class)')
                                            ->placeholder('fa-award')
                                            ->required(),
                                        TextInput::make('title')
                                            ->label('Card title')
                                            ->required()
                                            ->maxLength(255),
                                        Textarea::make('description')
                                            ->label('Description')
                                            ->rows(2)
                                            ->required(),
                                    ])
                                    ->columns(1)
                                    ->defaultItems(3)
                                    ->addActionLabel('Add card')
                                    ->reorderable()
                                    ->reorderableWithButtons()
                                    ->collapsible(),
                            ]),
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

        $this->setTranslatedSetting('page_private_group_tour_requests_hero_title', $data['hero_title'] ?? '');
        $this->setTranslatedSetting('page_private_group_tour_requests_hero_subtitle', $data['hero_subtitle'] ?? '');
        Setting::set('page_private_group_tour_requests_hero_image', $heroImage);
        $this->setTranslatedSetting('page_private_group_tour_requests_intro_title', $data['intro_title'] ?? '');
        $this->setTranslatedSetting('page_private_group_tour_requests_intro_content', $data['intro_content'] ?? '');
        $this->setTranslatedSetting('page_private_group_tour_requests_intro_show_more_text', $data['intro_show_more_text'] ?? '');
        Setting::set('page_private_group_tour_requests_intro_show_more_url', $data['intro_show_more_url'] ?? '');
        $this->setTranslatedSetting('page_private_group_tour_requests_intro_show_more_content', $data['intro_show_more_content'] ?? '');
        $this->setTranslatedSetting('page_private_group_tour_requests_form_success_message', $data['form_success_message'] ?? '');
        $this->setTranslatedSetting('page_private_group_tour_requests_feature_cards', json_encode($data['feature_cards'] ?? []));
        $this->setTranslatedSetting('page_private_group_tour_requests_seo_title', $data['seo_title'] ?? '');
        $this->setTranslatedSetting('page_private_group_tour_requests_seo_description', $data['seo_description'] ?? '');
        Setting::set('page_private_group_tour_requests_seo_og_image', $seoOgImage);

        Notification::make()->title('Private Group Tour Requests page saved.')->success()->send();
    }
}
