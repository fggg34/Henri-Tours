<?php

namespace App\Filament\Resources\TransportBookings\Pages;

use App\Filament\Resources\TransportBookings\TransportBookingResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditTransportBooking extends EditRecord
{
    protected static string $resource = TransportBookingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
