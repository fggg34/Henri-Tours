<?php

namespace App\Filament\Resources\TourPackages;

use App\Filament\Resources\TourPackages\Pages\CreateTourPackage;
use App\Filament\Resources\TourPackages\Pages\EditTourPackage;
use App\Filament\Resources\TourPackages\Pages\ListTourPackages;
use App\Models\TourPackage;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TourPackageResource extends Resource
{
    protected static ?string $model = TourPackage::class;

    protected static ?string $navigationLabel = 'Outbound Packages';

    protected static ?string $modelLabel = 'Outbound Package';

    protected static ?string $pluralModelLabel = 'Outbound Packages';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                FileUpload::make('image')
                    ->label('Package image')
                    ->image()
                    ->disk('public')
                    ->directory('tour_packages')
                    ->visibility('public')
                    ->imagePreviewHeight('200')
                    ->panelAspectRatio('16/10')
                    ->panelLayout('integrated')
                    ->required(),
                TextInput::make('instagram_post_url')
                    ->label('Instagram post link')
                    ->url()
                    ->placeholder('https://instagram.com/p/...')
                    ->maxLength(500)
                    ->helperText('When user clicks the image, they will be taken to this link.'),
                Toggle::make('show_on_home')
                    ->label('Show on homepage')
                    ->default(false)
                    ->helperText('Display this package in the homepage "Our Packages" section.'),
                TextInput::make('sort_order')
                    ->numeric()
                    ->default(0)
                    ->helperText('Order when displaying packages (lower = first).'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->disk('public'),
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                IconColumn::make('show_on_home')
                    ->label('Home')
                    ->boolean(),
                TextColumn::make('sort_order')
                    ->numeric()
                    ->sortable(),
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
            'index' => ListTourPackages::route('/'),
            'create' => CreateTourPackage::route('/create'),
            'edit' => EditTourPackage::route('/{record}/edit'),
        ];
    }
}
