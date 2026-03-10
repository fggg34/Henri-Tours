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

class ContactPage extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedEnvelope;

    protected static ?string $navigationLabel = 'Contact';

    protected static ?string $title = 'Contact Page';

    protected static string|\UnitEnum|null $navigationGroup = 'Pages';

    public array $contactForm = [];

    protected static ?int $navigationSort = 54;

    protected string $view = 'filament.pages.homepage';

    public function mount(): void
    {
        $heroImage = Setting::get('page_contact_hero_image', '');
        if (is_array($heroImage)) {
            $heroImage = $heroImage[0] ?? '';
        }

        $this->getSchema('contactForm')->fill([
            'hero_title' => Setting::get('page_contact_hero_title', 'Get in touch'),
            'hero_subtitle' => Setting::get('page_contact_hero_subtitle', "We'd love to hear from you"),
            'hero_image' => $heroImage,
            'form_title' => Setting::get('page_contact_form_title', 'Send us a message'),
            'form_description' => Setting::get('page_contact_form_description', "Fill out the form below and we'll get back to you as soon as possible."),
            'sidebar_title' => Setting::get('page_contact_sidebar_title', 'Need quick help?'),
            'sidebar_description' => Setting::get('page_contact_sidebar_description', 'Check our frequently asked questions for instant answers.'),
            'sidebar_button_text' => Setting::get('page_contact_sidebar_button_text', 'Browse tours'),
            'sidebar_button_url' => Setting::get('page_contact_sidebar_button_url', '') ?: route('tours.index'),
            'seo_title' => Setting::get('page_contact_seo_title', ''),
            'seo_description' => Setting::get('page_contact_seo_description', ''),
            'seo_og_image' => is_array($heroImage = Setting::get('page_contact_seo_og_image', '')) ? ($heroImage[0] ?? '') : $heroImage,
        ]);
    }

    public function contactForm(Schema $schema): Schema
    {
        return $schema
            ->statePath('contactForm')
            ->components([
                SchemaSection::make('Contact Page')
                    ->description('Manage the Contact page content.')
                    ->schema([
                        SchemaSection::make('Hero')
                            ->schema([
                                TextInput::make('hero_title')->label('Title')->default('Get in touch'),
                                TextInput::make('hero_subtitle')->label('Subtitle')->default("We'd love to hear from you"),
                                FileUpload::make('hero_image')
                                    ->label('Background image')
                                    ->image()
                                    ->disk('public')
                                    ->directory('pages/contact')
                                    ->visibility('public')
                                    ->imagePreviewHeight(120),
                            ])
                            ->columns(2)
                            ->collapsible(),
                        SchemaSection::make('Form Section')
                            ->schema([
                                TextInput::make('form_title')->label('Form title'),
                                Textarea::make('form_description')->label('Form description')->rows(2),
                            ])
                            ->columns(1)
                            ->collapsible(),
                        SchemaSection::make('Sidebar (Quick help block)')
                            ->schema([
                                TextInput::make('sidebar_title')->label('Title'),
                                Textarea::make('sidebar_description')->label('Description')->rows(2),
                                TextInput::make('sidebar_button_text')->label('Button text'),
                                TextInput::make('sidebar_button_url')->label('Button URL')->url(),
                            ])
                            ->columns(2)
                            ->collapsible(),
                        SchemaSection::make('SEO')
                            ->description('Meta title, description and OG image for this page.')
                            ->collapsible()
                            ->schema([
                                TextInput::make('seo_title')->label('Meta Title')->maxLength(70)->placeholder('Contact - ' . config('app.name')),
                                Textarea::make('seo_description')->label('Meta Description')->rows(2)->maxLength(160)->placeholder('Get in touch with us.'),
                                FileUpload::make('seo_og_image')
                                    ->label('OG Image')
                                    ->image()
                                    ->disk('public')
                                    ->directory('pages/contact')
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
                Form::make([EmbeddedSchema::make('contactForm')])
                    ->id('contactForm')
                    ->livewireSubmitHandler('saveContact')
                    ->footer([
                        Actions::make([
                            Action::make('saveContact')
                                ->label('Save Contact page')
                                ->submit('saveContact'),
                        ])->alignment(\Filament\Support\Enums\Alignment::Start),
                    ]),
            ]);
    }

    public function saveContact(): void
    {
        $data = $this->getSchema('contactForm')->getState();

        $heroImage = $data['hero_image'] ?? '';
        if (is_array($heroImage)) {
            $heroImage = $heroImage[0] ?? '';
        }

        Setting::set('page_contact_hero_title', $data['hero_title'] ?? '');
        Setting::set('page_contact_hero_subtitle', $data['hero_subtitle'] ?? '');
        Setting::set('page_contact_hero_image', $heroImage);
        Setting::set('page_contact_form_title', $data['form_title'] ?? '');
        Setting::set('page_contact_form_description', $data['form_description'] ?? '');
        Setting::set('page_contact_sidebar_title', $data['sidebar_title'] ?? '');
        Setting::set('page_contact_sidebar_description', $data['sidebar_description'] ?? '');
        Setting::set('page_contact_sidebar_button_text', $data['sidebar_button_text'] ?? '');
        Setting::set('page_contact_sidebar_button_url', $data['sidebar_button_url'] ?? '');

        $seoOgImage = $data['seo_og_image'] ?? '';
        if (is_array($seoOgImage)) {
            $seoOgImage = $seoOgImage[0] ?? '';
        }
        Setting::set('page_contact_seo_title', $data['seo_title'] ?? '');
        Setting::set('page_contact_seo_description', $data['seo_description'] ?? '');
        Setting::set('page_contact_seo_og_image', $seoOgImage);

        Notification::make()->title('Contact page saved.')->success()->send();
    }
}
