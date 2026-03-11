<?php

namespace App\Filament\Resources\PrivateGroupTourRequests;

use App\Filament\Resources\PrivateGroupTourRequests\Pages\ListPrivateGroupTourRequests;
use App\Filament\Resources\PrivateGroupTourRequests\Pages\ViewPrivateGroupTourRequest;
use App\Filament\Resources\PrivateGroupTourRequests\Schemas\PrivateGroupTourRequestForm;
use App\Filament\Resources\PrivateGroupTourRequests\Schemas\PrivateGroupTourRequestInfolist;
use App\Filament\Resources\PrivateGroupTourRequests\Tables\PrivateGroupTourRequestsTable;
use App\Models\PrivateGroupTourRequest;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PrivateGroupTourRequestResource extends Resource
{
    protected static ?string $model = PrivateGroupTourRequest::class;

    protected static ?string $navigationLabel = 'Private Group Requests';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static string|\UnitEnum|null $navigationGroup = 'Pages';

    protected static ?int $navigationSort = 56;

    public static function form(Schema $schema): Schema
    {
        return PrivateGroupTourRequestForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PrivateGroupTourRequestInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PrivateGroupTourRequestsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPrivateGroupTourRequests::route('/'),
            'view' => ViewPrivateGroupTourRequest::route('/{record}'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
