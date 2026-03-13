<?php

namespace App\Filament\Resources\BlogCategories\RelationManagers;

use App\Models\BlogCategoryTranslation;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TranslationsRelationManager extends RelationManager
{
    protected static string $relationship = 'translations';

    protected static ?string $title = 'Translations';

    protected static ?string $recordTitleAttribute = 'locale';

    protected static array $supportedLocales = ['en', 'zh_CN', 'fr', 'de', 'he', 'it', 'mt', 'es'];

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('locale')
                    ->label('Language')
                    ->options(function () {
                        $all = array_combine(static::$supportedLocales, array_map(
                            fn ($l) => __("locales.{$l}"),
                            static::$supportedLocales
                        ));
                        $used = $this->getOwnerRecord()->translations()->pluck('locale')->toArray();
                        $available = array_diff_key($all, array_flip($used));
                        return ! empty($available) ? $available : $all;
                    })
                    ->required()
                    ->disabled(fn ($get) => (bool) $get('id')),
                TextInput::make('name')->required()->maxLength(255),
                TextInput::make('slug')->maxLength(255),
                RichEditor::make('description')->label('Description')->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('locale')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => __('locales.' . $state)),
                TextColumn::make('name')->limit(40),
                TextColumn::make('slug')->limit(30),
            ])
            ->headerActions([
                CreateAction::make()
                    ->visible(fn () => $this->getOwnerRecord()->translations()->count() < count(static::$supportedLocales)),
            ])
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
}
