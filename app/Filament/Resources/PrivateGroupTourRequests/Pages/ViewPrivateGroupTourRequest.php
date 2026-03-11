<?php

namespace App\Filament\Resources\PrivateGroupTourRequests\Pages;

use App\Filament\Resources\PrivateGroupTourRequests\PrivateGroupTourRequestResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPrivateGroupTourRequest extends ViewRecord
{
    protected static string $resource = PrivateGroupTourRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
