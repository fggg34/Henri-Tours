<?php

namespace App\Filament\Pages;

use App\Filament\Traits\HasTranslatablePageContent;
use App\Models\Setting;
use Filament\Actions\Action;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section as SchemaSection;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class GlobalSections extends Page
{
    use HasTranslatablePageContent;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = 'Global Sections';

    protected static ?string $title = 'Global Sections';

    protected static string|\UnitEnum|null $navigationGroup = 'Pages';

    /** @var array<string, mixed> */
    public array $globalForm = [];

    protected static ?int $navigationSort = 48;

    protected string $view = 'filament.pages.homepage';

    public function mount(): void
    {
        $items = $this->getTranslatedSetting('global_section_info_bar_items', '');
        $items = is_string($items) ? (json_decode($items, true) ?: []) : $items;
        if (empty($items)) {
            $items = [
                [
                    'svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 511.999 511.999" class="w-4 h-4" fill="currentColor"><g><g><path d="M399.647,227.207c-53.203,0-93.887,22.533-93.887,84.499v88.88c0,61.966,40.684,84.499,93.887,84.499 c52.576,0,93.887-22.533,93.887-84.499v-88.88C493.535,249.74,452.223,227.207,399.647,227.207z M430.943,400.587L430.943,400.587 c0,20.654-11.892,30.043-31.295,30.043c-19.403,0-30.67-9.389-30.67-30.043v-88.88c0-20.656,11.267-30.044,30.67-30.044 c19.403,0,31.295,9.389,31.295,30.044V400.587z"></path></g></g><g><g><path d="M363.345,0c-11.267,0-21.282,5.007-26.289,15.648L117.359,466.933c-1.878,3.756-3.13,8.137-3.13,11.892 c0,15.648,13.77,33.174,35.052,33.174c11.892,0,23.785-6.259,28.166-15.648L397.77,45.066c1.877-3.756,2.502-8.137,2.502-11.893 C400.272,13.144,380.869,0,363.345,0z"></path></g></g><g><g><path d="M112.351,25.662c-53.203,0-93.887,22.533-93.887,84.499v88.88c0,61.966,40.685,84.499,93.887,84.499 c52.577,0,93.887-22.534,93.887-84.499v-88.88C206.239,48.195,164.929,25.662,112.351,25.662z M143.648,199.042 c0,20.656-11.892,30.043-31.295,30.043c-19.403,0-30.67-9.389-30.67-30.043v-88.88c0-20.656,11.267-30.044,30.67-30.044 c19.403,0,31.295,9.389,31.295,30.044V199.042z"></path></g></g></svg>',
                    'title' => 'Book with only a 10% deposit',
                    'content' => 'Secure your booking with a small payment and pay the remaining balance only when the tour starts.',
                ],
                [
                    'svg' => '<svg viewBox="0 0 24 25" class="w-4 h-4"><path fill="currentColor" fill-rule="evenodd" d="M21.5.7h-19A2.5 2.5 0 000 3.2v19a2.5 2.5 0 002.5 2.5h19a2.5 2.5 0 002.5-2.5v-19A2.5 2.5 0 0021.5.7zm-3 9.5H14a1 1 0 110-2h4.5a1 1 0 110 2zm0 9a1 1 0 100-2H14a1 1 0 100 2zM11.3 6.3l-3 4a1 1 0 01-.729.4H7.5a1 1 0 01-.707-.293l-1.5-1.5a1 1 0 111.414-1.415l.685.685L9.7 5.1a1 1 0 111.6 1.2zm-3 14l3-4a1 1 0 00-1.6-1.2l-2.308 3.078-.685-.685a1 1 0 00-1.414 1.414l1.5 1.5A1.018 1.018 0 008.3 20.3z" clip-rule="evenodd"/></svg>',
                    'title' => 'Easy Booking & Cancellation',
                    'content' => 'Multi-day tour deposits are fully refundable up to 1 month before travel and can be easily cancelled online.',
                ],
                [
                    'svg' => '<svg viewBox="0 0 512 512" class="w-4 h-4" xmlns="http://www.w3.org/2000/svg"><path fill="currentColor" d="m512 112v65h-512v-65c0-33.1 26.9-60 60-60h35v23c0 19.3 15.7 35 35 35s35-15.7 35-35v-23h182v23c0 19.3 15.7 35 35 35s35-15.7 35-35v-23h35c33.1 0 60 26.9 60 60zm-130-12c13.8 0 25-11.2 25-25v-50c0-13.8-11.2-25-25-25s-25 11.2-25 25v50c0 13.8 11.2 25 25 25zm-252 0c13.8 0 25-11.2 25-25v-50c0-13.8-11.2-25-25-25s-25 11.2-25 25v50c0 13.8 11.2 25 25 25zm-130 87h512v265c0 33.1-26.9 60-60 60h-392c-33.1 0-60-26.9-60-60zm386 80c0 5.52 4.48 10 10 10h50c5.52 0 10-4.48 10-10v-50c0-5.52-4.48-10-10-10h-50c-5.52 0-10 4.48-10 10zm0 102.5c0 5.52 4.48 10 10 10h50c5.52 0 10-4.48 10-10v-50c0-5.52-4.48-10-10-10h-50c-5.52 0-10 4.48-10 10zm0 102.5c0 5.52 4.48 10 10 10h50c5.52 0 10-4.48 10-10v-50c0-5.52-4.48-10-10-10h-50c-5.52 0-10 4.48-10 10z"/></svg>',
                    'title' => 'Flexible & Confirmed Departures',
                    'content' => 'Choose from several confirmed departures each month and pick the date that works best for you. Instantly confirmed and easy to reschedule.',
                ],
                [
                    'svg' => '<svg viewBox="0 0 428.16 428.16" class="w-4 h-4" xmlns="http://www.w3.org/2000/svg"><path fill="currentColor" d="M393.8,110.208c-0.512-11.264-0.512-22.016-0.512-32.768c0-8.704-6.656-15.36-15.36-15.36c-64,0-112.64-18.432-153.088-57.856c-6.144-5.632-15.36-5.632-21.504,0C162.888,43.648,114.248,62.08,50.248,62.08c-8.704,0-15.36,6.656-15.36,15.36c0,10.752,0,21.504-0.512,32.768c-2.048,107.52-5.12,254.976,174.592,316.928l5.12,1.024l5.12-1.024C398.408,365.184,395.848,218.24,393.8,110.208z M201.8,259.2c-3.072,2.56-6.656,4.096-10.752,4.096h-0.512c-4.096,0-8.192-2.048-10.752-5.12l-47.616-52.736l23.04-20.48l37.376,41.472l82.944-78.848l20.992,22.528L201.8,259.2z"/></svg>',
                    'title' => 'Trusted by Travelers',
                    'content' => "We've served over 20,000 travelers in the past 2 years and received more than 2,000 reviews online.",
                ],
            ];
        }

        $this->getSchema('globalForm')->fill([
            'info_bar_items' => $items,
        ]);
    }

    public function globalForm(Schema $schema): Schema
    {
        return $schema
            ->statePath('globalForm')
            ->components([
                SchemaSection::make('Global Sections')
                    ->description('Manage content that appears across multiple pages on your site.')
                    ->schema([
                        $this->getLocaleSelectSchema(),
                        SchemaSection::make('Info Bar')
                            ->description('The trust points bar shown below the hero on homepage, tours, and other pages. Each item has an icon, title, and tooltip content.')
                            ->collapsible()
                            ->schema([
                                Repeater::make('info_bar_items')
                                    ->schema([
                                        Textarea::make('svg')
                                            ->label('SVG Code')
                                            ->rows(4)
                                            ->required()
                                            ->helperText('Paste the full SVG markup. Use class="w-4 h-4" and fill="currentColor" for consistent sizing.'),
                                        TextInput::make('title')
                                            ->label('Title')
                                            ->required()
                                            ->maxLength(255)
                                            ->placeholder('e.g. Book with only a 10% deposit'),
                                        Textarea::make('content')
                                            ->label('Content (tooltip text)')
                                            ->rows(2)
                                            ->required()
                                            ->maxLength(500)
                                            ->helperText('Short text shown in the hover tooltip.'),
                                    ])
                                    ->columns(1)
                                    ->defaultItems(4)
                                    ->addActionLabel('Add item')
                                    ->reorderable()
                                    ->reorderableWithButtons()
                                    ->collapsible(),
                            ]),
                    ]),
            ]);
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Form::make([EmbeddedSchema::make('globalForm')])
                    ->id('globalForm')
                    ->livewireSubmitHandler('saveGlobalSections')
                    ->footer([
                        Actions::make([
                            Action::make('saveGlobalSections')
                                ->label('Save global sections')
                                ->submit('saveGlobalSections'),
                        ])->alignment(\Filament\Support\Enums\Alignment::Start),
                    ]),
            ]);
    }

    public function saveGlobalSections(): void
    {
        $data = $this->getSchema('globalForm')->getState();
        $items = $data['info_bar_items'] ?? [];
        $this->setTranslatedSetting('global_section_info_bar_items', json_encode($items));
        Notification::make()->title('Global sections saved.')->success()->send();
    }
}
