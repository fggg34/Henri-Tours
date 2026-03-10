<?php

namespace App\Filament\Pages;

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

class FaqPage extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedQuestionMarkCircle;

    protected static ?string $navigationLabel = 'FAQ';

    protected static ?string $title = 'FAQ Page';

    protected static string|\UnitEnum|null $navigationGroup = 'Pages';

    public array $faqForm = [];

    protected static ?int $navigationSort = 52;

    protected string $view = 'filament.pages.homepage';

    public function mount(): void
    {
        $sections = Setting::get('page_faq_sections', '');
        $sections = is_string($sections) ? (json_decode($sections, true) ?: []) : $sections;
        if (empty($sections)) {
            $sections = [
                [
                    'category_label' => 'Booking & payments',
                    'category_title' => 'How booking works',
                    'items' => [
                        ['q' => 'How do I book a tour?', 'a' => 'Simply browse our tours, select the one you like, pick your preferred date and number of travelers, and complete the booking form.'],
                        ['q' => 'What payment methods do you accept?', 'a' => 'We accept all major credit and debit cards, as well as bank transfers.'],
                        ['q' => 'Can I book for a group?', 'a' => 'Yes! Many of our tours accommodate groups. Contact us for customized options.'],
                        ['q' => 'Will I receive a confirmation?', 'a' => 'Yes, once your booking is confirmed, you\'ll receive an email with all the details.'],
                    ],
                ],
                [
                    'category_label' => 'Cancellations & changes',
                    'category_title' => 'Flexibility when you need it',
                    'items' => [
                        ['q' => 'What is your cancellation policy?', 'a' => 'Most tours offer free cancellation up to 7 days before the tour date.'],
                        ['q' => 'Can I change my booking date?', 'a' => 'Yes, you can reschedule subject to availability.'],
                        ['q' => 'What happens if a tour is cancelled?', 'a' => 'You\'ll receive a full refund or the option to reschedule at no extra cost.'],
                        ['q' => 'How do I request a refund?', 'a' => 'Contact us through the contact page or reply to your booking confirmation email.'],
                    ],
                ],
                [
                    'category_label' => 'Tours & experiences',
                    'category_title' => 'About our tours',
                    'items' => [
                        ['q' => 'Are your tours guided?', 'a' => 'Most tours include professional local guides.'],
                        ['q' => 'What should I bring on a tour?', 'a' => 'Comfortable walking shoes, weather-appropriate clothing, sunscreen, and a camera.'],
                        ['q' => 'Are meals included?', 'a' => 'This varies by tour. Check the "What\'s Included" section on each tour page.'],
                        ['q' => 'How large are the groups?', 'a' => 'We keep groups small (typically 8-15 people) for a personal experience.'],
                        ['q' => 'Are tours available in multiple languages?', 'a' => 'Most tours are offered in English, with many in additional languages.'],
                    ],
                ],
            ];
        }

        $heroImage = Setting::get('page_faq_hero_image', '');
        if (is_array($heroImage)) {
            $heroImage = $heroImage[0] ?? '';
        }

        $seoOgImage = Setting::get('page_faq_seo_og_image', '');
        if (is_array($seoOgImage)) {
            $seoOgImage = $seoOgImage[0] ?? '';
        }

        $this->getSchema('faqForm')->fill([
            'hero_title' => Setting::get('page_faq_hero_title', 'Frequently Asked Questions'),
            'hero_subtitle' => Setting::get('page_faq_hero_subtitle', 'Everything you need to know'),
            'hero_image' => $heroImage,
            'sections' => $sections,
            'cta_title' => Setting::get('page_faq_cta_title', 'Still have questions?'),
            'cta_description' => Setting::get('page_faq_cta_description', "Can't find what you're looking for? Our team is happy to help."),
            'cta_button_text' => Setting::get('page_faq_cta_button_text', 'Contact us'),
            'cta_button_url' => Setting::get('page_faq_cta_button_url', '') ?: route('contact'),
            'seo_title' => Setting::get('page_faq_seo_title', ''),
            'seo_description' => Setting::get('page_faq_seo_description', ''),
            'seo_og_image' => $seoOgImage,
        ]);
    }

    public function faqForm(Schema $schema): Schema
    {
        return $schema
            ->statePath('faqForm')
            ->components([
                SchemaSection::make('FAQ Page')
                    ->description('Manage the FAQ page content.')
                    ->schema([
                        SchemaSection::make('Hero')
                            ->schema([
                                TextInput::make('hero_title')->label('Title')->default('Frequently Asked Questions'),
                                TextInput::make('hero_subtitle')->label('Subtitle')->default('Everything you need to know'),
                                FileUpload::make('hero_image')
                                    ->label('Background image')
                                    ->image()
                                    ->disk('public')
                                    ->directory('pages/faq')
                                    ->visibility('public')
                                    ->imagePreviewHeight(120),
                            ])
                            ->columns(2)
                            ->collapsible(),
                        SchemaSection::make('FAQ Sections')
                            ->schema([
                                Repeater::make('sections')
                                    ->schema([
                                        TextInput::make('category_label')->label('Section label (small text)')->required(),
                                        TextInput::make('category_title')->label('Section title')->required(),
                                        Repeater::make('items')
                                            ->schema([
                                                TextInput::make('q')->label('Question')->required(),
                                                Textarea::make('a')->label('Answer')->rows(3)->required(),
                                            ])
                                            ->columns(1)
                                            ->defaultItems(1)
                                            ->addActionLabel('Add question')
                                            ->reorderable(),
                                    ])
                                    ->columns(1)
                                    ->defaultItems(1)
                                    ->addActionLabel('Add section')
                                    ->reorderable()
                                    ->collapsible(),
                            ])
                            ->collapsible(),
                        SchemaSection::make('CTA (Contact block)')
                            ->schema([
                                TextInput::make('cta_title')->label('Title')->default('Still have questions?'),
                                Textarea::make('cta_description')->label('Description')->rows(2),
                                TextInput::make('cta_button_text')->label('Button text')->default('Contact us'),
                                TextInput::make('cta_button_url')->label('Button URL')->url()->placeholder(route('contact')),
                            ])
                            ->columns(2)
                            ->collapsible(),
                        SchemaSection::make('SEO')
                            ->description('Meta title, description and OG image for this page.')
                            ->collapsible()
                            ->schema([
                                TextInput::make('seo_title')->label('Meta Title')->maxLength(70)->placeholder('FAQ - ' . config('app.name')),
                                Textarea::make('seo_description')->label('Meta Description')->rows(2)->maxLength(160)->placeholder('Frequently asked questions about our tours and services.'),
                                FileUpload::make('seo_og_image')
                                    ->label('OG Image')
                                    ->image()
                                    ->disk('public')
                                    ->directory('pages/faq')
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
                Form::make([EmbeddedSchema::make('faqForm')])
                    ->id('faqForm')
                    ->livewireSubmitHandler('saveFaq')
                    ->footer([
                        Actions::make([
                            Action::make('saveFaq')
                                ->label('Save FAQ page')
                                ->submit('saveFaq'),
                        ])->alignment(\Filament\Support\Enums\Alignment::Start),
                    ]),
            ]);
    }

    public function saveFaq(): void
    {
        $data = $this->getSchema('faqForm')->getState();

        $heroImage = $data['hero_image'] ?? '';
        if (is_array($heroImage)) {
            $heroImage = $heroImage[0] ?? '';
        }

        Setting::set('page_faq_hero_title', $data['hero_title'] ?? '');
        Setting::set('page_faq_hero_subtitle', $data['hero_subtitle'] ?? '');
        Setting::set('page_faq_hero_image', $heroImage);
        Setting::set('page_faq_sections', json_encode($data['sections'] ?? []));
        Setting::set('page_faq_cta_title', $data['cta_title'] ?? '');
        Setting::set('page_faq_cta_description', $data['cta_description'] ?? '');
        Setting::set('page_faq_cta_button_text', $data['cta_button_text'] ?? '');
        Setting::set('page_faq_cta_button_url', $data['cta_button_url'] ?? '');

        $seoOgImage = $data['seo_og_image'] ?? '';
        if (is_array($seoOgImage)) {
            $seoOgImage = $seoOgImage[0] ?? '';
        }
        Setting::set('page_faq_seo_title', $data['seo_title'] ?? '');
        Setting::set('page_faq_seo_description', $data['seo_description'] ?? '');
        Setting::set('page_faq_seo_og_image', $seoOgImage);

        Notification::make()->title('FAQ page saved.')->success()->send();
    }
}
