<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Actions\Action;
use Filament\Forms\Components\RichEditor;
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

class TermsAndCancellationPolicyPage extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static ?string $navigationLabel = 'Terms & Cancellation Policy';

    protected static ?string $title = 'Terms & Cancellation Policy Page';

    protected static string|\UnitEnum|null $navigationGroup = 'Pages';

    public array $termsForm = [];

    protected static ?int $navigationSort = 57;

    protected string $view = 'filament.pages.homepage';

    public function mount(): void
    {
        $this->getSchema('termsForm')->fill([
            'content' => Setting::get('page_terms_content', ''),
            'seo_title' => Setting::get('page_terms_seo_title', ''),
            'seo_description' => Setting::get('page_terms_seo_description', ''),
        ]);
    }

    public function termsForm(Schema $schema): Schema
    {
        return $schema
            ->statePath('termsForm')
            ->components([
                SchemaSection::make('Terms & Cancellation Policy Page')
                    ->description('Manage the Terms & Cancellation Policy page at /terms-and-cancellation-policy.')
                    ->schema([
                        SchemaSection::make('Content')
                            ->schema([
                                RichEditor::make('content')
                                    ->label('Page content')
                                    ->toolbarButtons([
                                        'bold', 'italic', 'underline', 'strike',
                                        'h2', 'h3', 'bulletList', 'orderedList',
                                        'blockquote', 'link', 'undo', 'redo',
                                    ])
                                    ->columnSpanFull()
                                    ->helperText('Use the editor to add your terms and cancellation policy. Supports headings, lists, links, and formatting.'),
                            ]),
                        SchemaSection::make('SEO')
                            ->collapsible()
                            ->schema([
                                TextInput::make('seo_title')
                                    ->label('Meta Title')
                                    ->maxLength(70)
                                    ->placeholder('Terms & Cancellation Policy - ' . config('app.name')),
                                Textarea::make('seo_description')
                                    ->label('Meta Description')
                                    ->rows(2)
                                    ->maxLength(160)
                                    ->placeholder('Our booking terms and cancellation policy.'),
                            ])
                            ->columns(1),
                    ]),
            ]);
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Form::make([EmbeddedSchema::make('termsForm')])
                    ->id('termsForm')
                    ->livewireSubmitHandler('saveTerms')
                    ->footer([
                        Actions::make([
                            Action::make('saveTerms')
                                ->label('Save')
                                ->submit('saveTerms'),
                        ])->alignment(\Filament\Support\Enums\Alignment::Start),
                    ]),
            ]);
    }

    public function saveTerms(): void
    {
        $data = $this->getSchema('termsForm')->getState();

        $content = $data['content'] ?? '';
        if (is_array($content)) {
            $content = \Filament\Forms\Components\RichEditor\RichContentRenderer::make($content)->toUnsafeHtml();
        }

        Setting::set('page_terms_content', (string) $content);
        Setting::set('page_terms_seo_title', $data['seo_title'] ?? '');
        Setting::set('page_terms_seo_description', $data['seo_description'] ?? '');

        Notification::make()->title('Terms & Cancellation Policy page saved.')->success()->send();
    }
}
