<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

/**
 * @property-read Schema $form
 */
class Settings extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?string $navigationLabel = 'Settings';

    protected static ?string $title = 'Site Settings';

    protected static ?int $navigationSort = 100;

    /** @var array<string, mixed> Form state (bound to schema statePath 'form') */
    public array $form = [];

    protected string $view = 'filament.pages.settings';

    public function mount(): void
    {
        $this->getSchema('form')->fill([
            'site_name' => Setting::get('site_name', ''),
            'site_tagline' => Setting::get('site_tagline', ''),
            'site_logo' => Setting::get('site_logo', ''),
            'site_icon' => Setting::get('site_icon', ''),
            'footer_logo' => Setting::get('footer_logo', ''),
            'contact_email' => Setting::get('contact_email', ''),
            'contact_phone' => Setting::get('contact_phone', ''),
            'contact_address' => Setting::get('contact_address', ''),
            'currency' => Setting::get('currency', 'EUR'),
            'facebook_url' => Setting::get('facebook_url', ''),
            'instagram_url' => Setting::get('instagram_url', ''),
            'tiktok_url' => Setting::get('tiktok_url', ''),
            'youtube_url' => Setting::get('youtube_url', ''),
            'seo_og_image' => Setting::get('seo_og_image', ''),
            'seo_default_title' => Setting::get('seo_default_title', ''),
            'seo_default_description' => Setting::get('seo_default_description', ''),
            'nav_menu_items' => $this->getNavMenuItems(),
            'footer_menu_1' => $this->getFooterMenu('footer_menu_1'),
            'footer_menu_2' => $this->getFooterMenu('footer_menu_2'),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('form')
            ->components([
                \Filament\Schemas\Components\Section::make('General')
                    ->schema([
                        \Filament\Forms\Components\TextInput::make('site_name')->label('Site Name'),
                        \Filament\Forms\Components\TextInput::make('site_tagline')->label('Tagline'),
                        \Filament\Forms\Components\TextInput::make('currency')->label('Currency')->maxLength(10),
                    ])
                    ->columns(2),
                \Filament\Schemas\Components\Section::make('Branding')
                    ->schema([
                        FileUpload::make('site_logo')
                            ->label('Site Logo (Header)')
                            ->image()
                            ->disk('public')
                            ->directory('settings')
                            ->visibility('public')
                            ->imagePreviewHeight('80')
                            ->maxSize(2048)
                            ->helperText('Logo displayed in the site header. Recommended: PNG or SVG, max 2MB.'),
                        FileUpload::make('footer_logo')
                            ->label('Footer Logo')
                            ->image()
                            ->disk('public')
                            ->directory('settings')
                            ->visibility('public')
                            ->imagePreviewHeight('80')
                            ->maxSize(2048)
                            ->helperText('Logo displayed in the footer. Leave empty to use the header logo.'),
                        FileUpload::make('site_icon')
                            ->label('Site Icon (Favicon)')
                            ->image()
                            ->disk('public')
                            ->directory('settings')
                            ->visibility('public')
                            ->acceptedFileTypes(['image/png', 'image/x-icon', 'image/svg+xml', 'image/jpeg'])
                            ->imagePreviewHeight('48')
                            ->maxSize(512)
                            ->helperText('Favicon shown in browser tab. Use PNG, ICO, or SVG. Recommended size: 32x32 or 48x48.'),
                    ])
                    ->columns(2),
                \Filament\Schemas\Components\Section::make('Contact')
                    ->schema([
                        \Filament\Forms\Components\TextInput::make('contact_email')->label('Email')->email(),
                        \Filament\Forms\Components\TextInput::make('contact_phone')->label('Phone')->tel(),
                        \Filament\Forms\Components\Textarea::make('contact_address')->label('Address')->rows(2),
                    ])
                    ->columns(1),
                \Filament\Schemas\Components\Section::make('Navigation Menu')
                    ->description('Configure the main navigation links. Add simple links or dropdowns with sub-items.')
                    ->schema([
                        Repeater::make('nav_menu_items')
                            ->schema([
                                \Filament\Forms\Components\Select::make('type')
                                    ->options(['link' => 'Simple link', 'dropdown' => 'Dropdown with sub-items'])
                                    ->default('link')
                                    ->required()
                                    ->live(),
                                \Filament\Forms\Components\TextInput::make('label')
                                    ->label('Menu label')
                                    ->required()
                                    ->maxLength(100),
                                \Filament\Forms\Components\TextInput::make('url')
                                    ->label('URL')
                                    ->placeholder('/tours')
                                    ->helperText('Path like /tours, /cities, or full URL. Leave empty for dropdown.')
                                    ->visible(fn ($get) => $get('type') === 'link'),
                                Repeater::make('children')
                                    ->label('Sub-menu items')
                                    ->schema([
                                        \Filament\Forms\Components\TextInput::make('label')->required()->maxLength(100),
                                        \Filament\Forms\Components\TextInput::make('url')->label('URL')->required()->placeholder('/about'),
                                    ])
                                    ->columns(2)
                                    ->defaultItems(0)
                                    ->addActionLabel('Add sub-item')
                                    ->reorderable()
                                    ->visible(fn ($get) => $get('type') === 'dropdown'),
                            ])
                            ->columns(1)
                            ->defaultItems(0)
                            ->addActionLabel('Add menu item')
                            ->reorderable()
                            ->reorderableWithButtons()
                            ->collapsible(),
                    ]),
                \Filament\Schemas\Components\Section::make('Footer Menus')
                    ->description('Two footer link columns. Set the title and add links for each menu.')
                    ->schema([
                        \Filament\Schemas\Components\Section::make('Footer Menu 1 (Explore Albania)')
                            ->schema([
                                \Filament\Forms\Components\TextInput::make('footer_menu_1.title')
                                    ->label('Menu title')
                                    ->required()
                                    ->default('Explore Albania')
                                    ->maxLength(100),
                                Repeater::make('footer_menu_1.items')
                                    ->schema([
                                        \Filament\Forms\Components\TextInput::make('label')->required()->maxLength(100),
                                        \Filament\Forms\Components\TextInput::make('url')->label('URL')->required()->placeholder('/tours'),
                                    ])
                                    ->columns(2)
                                    ->defaultItems(0)
                                    ->addActionLabel('Add link')
                                    ->reorderable()
                                    ->reorderableWithButtons(),
                            ])
                            ->columns(1)
                            ->collapsible(),
                        \Filament\Schemas\Components\Section::make('Footer Menu 2 (Why Choose Us)')
                            ->schema([
                                \Filament\Forms\Components\TextInput::make('footer_menu_2.title')
                                    ->label('Menu title')
                                    ->required()
                                    ->default('Why Choose Us')
                                    ->maxLength(100),
                                Repeater::make('footer_menu_2.items')
                                    ->schema([
                                        \Filament\Forms\Components\TextInput::make('label')->required()->maxLength(100),
                                        \Filament\Forms\Components\TextInput::make('url')->label('URL')->required()->placeholder('/about'),
                                    ])
                                    ->columns(2)
                                    ->defaultItems(0)
                                    ->addActionLabel('Add link')
                                    ->reorderable()
                                    ->reorderableWithButtons(),
                            ])
                            ->columns(1)
                            ->collapsible(),
                    ]),
                \Filament\Schemas\Components\Section::make('Social')
                    ->schema([
                        \Filament\Forms\Components\TextInput::make('instagram_url')->label('Instagram URL')->url(),
                        \Filament\Forms\Components\TextInput::make('facebook_url')->label('Facebook URL')->url(),
                        \Filament\Forms\Components\TextInput::make('tiktok_url')->label('TikTok URL')->url(),
                        \Filament\Forms\Components\TextInput::make('youtube_url')->label('YouTube URL')->url(),
                    ])
                    ->columns(2),
                \Filament\Schemas\Components\Section::make('SEO (Default)')
                    ->description('Default meta tags and Open Graph image used when pages do not define their own.')
                    ->schema([
                        FileUpload::make('seo_og_image')
                            ->label('Default OG Image')
                            ->image()
                            ->disk('public')
                            ->directory('settings')
                            ->visibility('public')
                            ->imagePreviewHeight(120)
                            ->helperText('Recommended: 1200×630px. Used for social sharing when a page has no specific image.'),
                        \Filament\Forms\Components\TextInput::make('seo_default_title')
                            ->label('Default Meta Title')
                            ->maxLength(70)
                            ->helperText('Fallback when a page has no custom title. Leave empty to use Site Name.'),
                        \Filament\Forms\Components\Textarea::make('seo_default_description')
                            ->label('Default Meta Description')
                            ->rows(2)
                            ->maxLength(160)
                            ->helperText('Fallback when a page has no custom description. Leave empty to use Tagline.'),
                    ])
                    ->columns(1),
            ]);
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Form::make([EmbeddedSchema::make('form')])
                    ->id('form')
                    ->livewireSubmitHandler('save')
                    ->footer([
                        Actions::make([
                            Action::make('save')
                                ->label('Save settings')
                                ->submit('save'),
                        ])->alignment(\Filament\Support\Enums\Alignment::Start),
                    ]),
            ]);
    }

    public function save(): void
    {
        $data = $this->getSchema('form')->getState();
        foreach ($data as $key => $value) {
            if ($key === 'nav_menu_items') {
                Setting::set($key, json_encode($value ?? []));
                continue;
            }
            if ($key === 'footer_menu_1' || $key === 'footer_menu_2') {
                Setting::set($key, json_encode($value ?? ['title' => '', 'items' => []]));
                continue;
            }
            // FileUpload may return array (single path) - normalize to string
            if (is_array($value)) {
                $value = $value[0] ?? '';
            }
            Setting::set($key, $value ?? '');
        }
        Notification::make()->title('Settings saved.')->success()->send();
    }

    private function getFooterMenu(string $key): array
    {
        $menu = Setting::get($key, '');
        $menu = is_string($menu) ? (json_decode($menu, true) ?: []) : $menu;

        if (empty($menu) || ! isset($menu['title'])) {
            if ($key === 'footer_menu_1') {
                return [
                    'title' => 'Explore Albania',
                    'items' => [
                        ['label' => 'Day Tours', 'url' => '/tours/category/day-tours'],
                        ['label' => 'Multi-Day Tour', 'url' => '/tours/category/multi-day-tours'],
                        ['label' => 'Cross Country', 'url' => '/tours/category/cross-country-tours'],
                        ['label' => 'Confirmed Group Tours', 'url' => '/tours'],
                        ['label' => 'Private Group Tour Requests', 'url' => '/private-group-tour-requests'],
                    ],
                ];
            }
            return [
                'title' => 'Why Choose Us',
                'items' => [
                    ['label' => 'About Us', 'url' => '/about'],
                    ['label' => 'Our Transport', 'url' => '#'],
                    ['label' => 'Blog', 'url' => '/blog'],
                    ['label' => 'Contact us', 'url' => '/contact'],
                    ['label' => 'Terms & Cancellation Policy', 'url' => '/faq'],
                ],
            ];
        }

        return array_merge(['title' => '', 'items' => []], $menu);
    }

    private function getNavMenuItems(): array
    {
        $items = Setting::get('nav_menu_items', '');
        $items = is_string($items) ? (json_decode($items, true) ?: []) : $items;
        if (empty($items)) {
            return [
                [
                    'type' => 'dropdown',
                    'label' => 'Destinations',
                    'url' => '',
                    'children' => [
                        ['label' => 'All Destinations', 'url' => '/cities'],
                    ],
                ],
                [
                    'type' => 'dropdown',
                    'label' => 'Travel Collections',
                    'url' => '',
                    'children' => [
                        ['label' => 'All Tours', 'url' => '/tours'],
                        ['label' => 'Popular Tours', 'url' => '/tours?sort=popular'],
                        ['label' => 'Travel Stories', 'url' => '/blog'],
                    ],
                ],
                [
                    'type' => 'dropdown',
                    'label' => 'About',
                    'url' => '',
                    'children' => [
                        ['label' => 'About Us', 'url' => '/about'],
                        ['label' => 'Blog', 'url' => '/blog'],
                        ['label' => 'Contact', 'url' => '/contact'],
                    ],
                ],
                ['type' => 'link', 'label' => 'Create Your Trip', 'url' => '/tours', 'children' => []],
                ['type' => 'link', 'label' => 'My Trips', 'url' => '/dashboard', 'children' => []],
            ];
        }
        return $items;
    }
}
