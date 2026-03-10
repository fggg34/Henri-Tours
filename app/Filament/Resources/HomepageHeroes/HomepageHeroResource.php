<?php

namespace App\Filament\Resources\HomepageHeroes;

use App\Filament\Resources\HomepageHeroes\Pages\CreateHomepageHero;
use App\Filament\Resources\HomepageHeroes\Pages\EditHomepageHero;
use App\Filament\Resources\HomepageHeroes\Pages\ListHomepageHeroes;
use App\Models\HomepageHero;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class HomepageHeroResource extends Resource
{
    protected static ?string $model = HomepageHero::class;

    protected static BackedEnum|string|null $navigationIcon = Heroicon::OutlinedPhoto;

    protected static string|\UnitEnum|null $navigationGroup = 'Content';

    protected static ?string $navigationLabel = 'Homepage Hero';

    protected static ?string $modelLabel = 'Homepage Hero';

    protected static ?string $pluralModelLabel = 'Homepage Heroes';

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                Textarea::make('subtitle')
                    ->rows(3)
                    ->columnSpanFull(),
                Select::make('banner_type')
                    ->options([
                        'image' => 'Image',
                        'video' => 'Video',
                    ])
                    ->default('image')
                    ->required()
                    ->live(),
                FileUpload::make('banner_image')
                    ->label('Banner image')
                    ->image()
                    ->disk('public')
                    ->directory('heroes')
                    ->visibility('public')
                    ->imagePreviewHeight('200')
                    ->panelAspectRatio('16/9')
                    ->panelLayout('integrated')
                    ->visible(fn ($get) => $get('banner_type') === 'image')
                    ->columnSpanFull(),
                FileUpload::make('banner_video')
                    ->label('Banner video (MP4)')
                    ->acceptedFileTypes(['video/mp4'])
                    ->disk('public')
                    ->directory('heroes')
                    ->visibility('public')
                    ->visible(fn ($get) => $get('banner_type') === 'video')
                    ->columnSpanFull(),
                TextInput::make('cta_text')
                    ->maxLength(255)
                    ->columnSpanFull(),
                Toggle::make('is_active')
                    ->default(true)
                    ->helperText('Only one hero should be active; it will be shown on the homepage.'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('banner_type')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => ucfirst($state)),
                IconColumn::make('is_active')
                    ->boolean(),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListHomepageHeroes::route('/'),
            'create' => CreateHomepageHero::route('/create'),
            'edit' => EditHomepageHero::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return true;
    }
}
