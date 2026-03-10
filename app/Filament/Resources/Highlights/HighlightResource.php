<?php

namespace App\Filament\Resources\Highlights;

use App\Filament\Resources\Highlights\Pages\CreateHighlight;
use App\Filament\Resources\Highlights\Pages\EditHighlight;
use App\Filament\Resources\Highlights\Pages\ListHighlights;
use App\Models\Highlight;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class HighlightResource extends Resource
{
    protected static ?string $model = Highlight::class;

    protected static ?string $navigationLabel = 'Highlights';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSparkles;

    protected static string|\UnitEnum|null $navigationGroup = 'Cities';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true),
                TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                Select::make('cities')
                    ->relationship('cities', 'name', fn ($query) => $query->orderBy('name'))
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->helperText('Cities where this highlight appears (Places to visit carousel on the city page).')
                    ->columnSpanFull(),
                RichEditor::make('description')
                    ->label('Description')
                    ->columnSpanFull(),
                FileUpload::make('image')
                    ->label('Highlight image')
                    ->image()
                    ->disk('public')
                    ->directory('highlights')
                    ->visibility('public')
                    ->imagePreviewHeight('260')
                    ->panelAspectRatio('1')
                    ->panelLayout('integrated')
                    ->helperText('Used in city "Places to visit" carousel (circular crop).')
                    ->columnSpanFull(),
                TextInput::make('sort_order')
                    ->numeric()
                    ->default(0)
                    ->helperText('Order when listing highlights.'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->disk('public')
                    ->circular()
                    ->toggleable(),
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('sort_order')
                    ->numeric()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListHighlights::route('/'),
            'create' => CreateHighlight::route('/create'),
            'edit' => EditHighlight::route('/{record}/edit'),
        ];
    }
}
