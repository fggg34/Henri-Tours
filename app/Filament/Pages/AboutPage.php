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

class AboutPage extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedInformationCircle;

    protected static ?string $navigationLabel = 'About Us';

    protected static ?string $title = 'About Us Page';

    protected static string|\UnitEnum|null $navigationGroup = 'Pages';

    public array $aboutForm = [];

    protected static ?int $navigationSort = 53;

    protected string $view = 'filament.pages.homepage';

    public function mount(): void
    {
        $values = Setting::get('page_about_values', '');
        $values = is_string($values) ? (json_decode($values, true) ?: []) : $values;
        if (empty($values)) {
            $values = [
                ['icon' => 'fa-heart', 'title' => 'Honesty over hype', 'description' => "We'll tell you honestly which tours are worth it and which spots are overhyped."],
                ['icon' => 'fa-people-group', 'title' => 'Small groups, real connections', 'description' => "We keep groups small on purpose. You're not a ticket number -- you're someone we want to show a good time."],
                ['icon' => 'fa-seedling', 'title' => 'Respect the places we visit', 'description' => 'We work with local families and support the communities that make Albania special.'],
            ];
        }

        $expectItems = Setting::get('page_about_expect_items', '');
        $expectItems = is_string($expectItems) ? (json_decode($expectItems, true) ?: []) : $expectItems;
        if (empty($expectItems)) {
            $expectItems = [
                ['title' => 'Guides who actually love this', 'description' => "Our guides aren't reading from a script. They're locals who are genuinely passionate."],
                ['title' => 'No surprise costs', 'description' => 'The price you see is the price you pay. We include what we say we include.'],
                ['title' => 'Flexibility when life happens', 'description' => 'Plans change, we get it. We make rescheduling and cancellations as painless as possible.'],
                ['title' => 'A real person to talk to', 'description' => "Have a question? You'll reach a real person, not a chatbot."],
            ];
        }

        $this->getSchema('aboutForm')->fill([
            'hero_title' => Setting::get('page_about_hero_title', 'Our Story'),
            'hero_image' => $this->normalizeFile(Setting::get('page_about_hero_image', '')),
            'intro_label' => Setting::get('page_about_intro_label', 'Nice to meet you'),
            'intro_title' => Setting::get('page_about_intro_title', 'We started with a simple idea: share the Albania we love'),
            'intro_content' => Setting::get('page_about_intro_content', "It all began with a group of friends who grew up exploring the mountains, coastlines, and old towns of Albania. We knew every hidden beach, every family-run restaurant with the best byrek, every mountain trail with a view that makes you stop and just breathe.\n\nPeople kept asking us: \"Where should I go? What should I see?\" So we started showing them ourselves. What began as informal trips for friends of friends turned into something bigger -- a real company, built on the same love and excitement we've always had.\n\nToday, we're still those same people. We just have a website now."),
            'intro_image' => $this->normalizeFile(Setting::get('page_about_intro_image', '')),
            'intro_badge_title' => Setting::get('page_about_intro_badge_title', 'Since day one'),
            'intro_badge_subtitle' => Setting::get('page_about_intro_badge_subtitle', 'Passionate about Albania'),
            'values_label' => Setting::get('page_about_values_label', 'What matters to us'),
            'values_title' => Setting::get('page_about_values_title', "We're not a big corporation. We're a small team that genuinely cares."),
            'values' => $values,
            'quote_text' => Setting::get('page_about_quote_text', "We don't just organize tours. We share the places that shaped us -- the cliffs we jumped off as kids, the villages where our grandparents grew up, the sunsets we never get tired of watching."),
            'expect_label' => Setting::get('page_about_expect_label', 'What to expect'),
            'expect_title' => Setting::get('page_about_expect_title', "When you book with us, here's what you get"),
            'expect_items' => $expectItems,
            'expect_image_1' => $this->normalizeFile(Setting::get('page_about_expect_image_1', '')),
            'expect_image_2' => $this->normalizeFile(Setting::get('page_about_expect_image_2', '')),
            'seo_title' => Setting::get('page_about_seo_title', ''),
            'seo_description' => Setting::get('page_about_seo_description', ''),
            'seo_og_image' => $this->normalizeFile(Setting::get('page_about_seo_og_image', '')),
        ]);
    }

    private function normalizeFile(mixed $value): string
    {
        if (is_array($value)) {
            return $value[0] ?? '';
        }
        return (string) $value;
    }

    public function aboutForm(Schema $schema): Schema
    {
        return $schema
            ->statePath('aboutForm')
            ->components([
                SchemaSection::make('About Us Page')
                    ->description('Manage the About Us page content.')
                    ->schema([
                        SchemaSection::make('Hero')
                            ->schema([
                                TextInput::make('hero_title')->label('Title')->default('Our Story'),
                                FileUpload::make('hero_image')
                                    ->label('Background image')
                                    ->image()
                                    ->disk('public')
                                    ->directory('pages/about')
                                    ->visibility('public')
                                    ->imagePreviewHeight(120),
                            ])
                            ->columns(2)
                            ->collapsible(),
                        SchemaSection::make('Intro Section')
                            ->schema([
                                TextInput::make('intro_label')->label('Label (small text)'),
                                TextInput::make('intro_title')->label('Title'),
                                Textarea::make('intro_content')->label('Content (paragraphs)')->rows(6)->helperText('Use line breaks for paragraphs.'),
                                FileUpload::make('intro_image')->label('Image')->image()->disk('public')->directory('pages/about')->visibility('public')->imagePreviewHeight(120),
                                TextInput::make('intro_badge_title')->label('Badge title'),
                                TextInput::make('intro_badge_subtitle')->label('Badge subtitle'),
                            ])
                            ->columns(2)
                            ->collapsible(),
                        SchemaSection::make('Values Section (3 cards)')
                            ->schema([
                                TextInput::make('values_label')->label('Section label'),
                                TextInput::make('values_title')->label('Section title'),
                                Repeater::make('values')
                                    ->schema([
                                        TextInput::make('icon')->label('Icon (Font Awesome class)')->placeholder('fa-heart'),
                                        TextInput::make('title')->label('Card title')->required(),
                                        Textarea::make('description')->label('Description')->rows(2)->required(),
                                    ])
                                    ->columns(1)
                                    ->defaultItems(3)
                                    ->addActionLabel('Add value')
                                    ->reorderable()
                                    ->collapsible(),
                            ])
                            ->collapsible(),
                        SchemaSection::make('Quote Section')
                            ->schema([
                                Textarea::make('quote_text')->label('Quote text')->rows(4)->helperText('Leave empty to hide this section.'),
                            ])
                            ->collapsible(),
                        SchemaSection::make('What to Expect Section')
                            ->schema([
                                TextInput::make('expect_label')->label('Section label'),
                                TextInput::make('expect_title')->label('Section title'),
                                Repeater::make('expect_items')
                                    ->schema([
                                        TextInput::make('title')->label('Item title')->required(),
                                        Textarea::make('description')->label('Description')->rows(2)->required(),
                                    ])
                                    ->columns(1)
                                    ->defaultItems(1)
                                    ->addActionLabel('Add item')
                                    ->reorderable()
                                    ->collapsible(),
                                FileUpload::make('expect_image_1')->label('Image 1')->image()->disk('public')->directory('pages/about')->visibility('public')->imagePreviewHeight(80),
                                FileUpload::make('expect_image_2')->label('Image 2')->image()->disk('public')->directory('pages/about')->visibility('public')->imagePreviewHeight(80),
                            ])
                            ->columns(2)
                            ->collapsible(),
                        SchemaSection::make('SEO')
                            ->description('Meta title, description and OG image for this page.')
                            ->collapsible()
                            ->schema([
                                TextInput::make('seo_title')->label('Meta Title')->maxLength(70)->placeholder('About Us - ' . config('app.name')),
                                Textarea::make('seo_description')->label('Meta Description')->rows(2)->maxLength(160)->placeholder('The story behind our tours and our team.'),
                                FileUpload::make('seo_og_image')
                                    ->label('OG Image')
                                    ->image()
                                    ->disk('public')
                                    ->directory('pages/about')
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
                Form::make([EmbeddedSchema::make('aboutForm')])
                    ->id('aboutForm')
                    ->livewireSubmitHandler('saveAbout')
                    ->footer([
                        Actions::make([
                            Action::make('saveAbout')
                                ->label('Save About page')
                                ->submit('saveAbout'),
                        ])->alignment(\Filament\Support\Enums\Alignment::Start),
                    ]),
            ]);
    }

    public function saveAbout(): void
    {
        $data = $this->getSchema('aboutForm')->getState();

        $keys = [
            'hero_title', 'hero_image', 'intro_label', 'intro_title', 'intro_content', 'intro_image',
            'intro_badge_title', 'intro_badge_subtitle', 'values_label', 'values_title', 'values',
            'quote_text', 'expect_label', 'expect_title', 'expect_items', 'expect_image_1', 'expect_image_2',
            'seo_title', 'seo_description', 'seo_og_image',
        ];

        foreach ($keys as $key) {
            $value = $data[$key] ?? null;
            if (in_array($key, ['hero_image', 'intro_image', 'expect_image_1', 'expect_image_2', 'seo_og_image'])) {
                $value = is_array($value) ? ($value[0] ?? '') : $value;
            }
            if (in_array($key, ['values', 'expect_items'])) {
                $value = json_encode($value ?? []);
            }
            Setting::set('page_about_' . $key, $value ?? '');
        }

        Notification::make()->title('About page saved.')->success()->send();
    }
}
