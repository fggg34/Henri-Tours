<?php

namespace App\Filament\Resources\PrivateGroupTourRequests\Pages;

use App\Filament\Resources\PrivateGroupTourRequests\PrivateGroupTourRequestResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPrivateGroupTourRequest extends EditRecord
{
    protected static string $resource = PrivateGroupTourRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
