<?php

namespace App\Filament\Resources\TourActivities\Pages;

use App\Filament\Resources\TourActivities\TourActivityResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageTourActivities extends ManageRecords
{
    protected static string $resource = TourActivityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
