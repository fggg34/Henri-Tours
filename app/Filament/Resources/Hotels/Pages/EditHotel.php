<?php

namespace App\Filament\Resources\Hotels\Pages;

use App\Filament\Resources\Hotels\HotelResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditHotel extends EditRecord
{
    protected static string $resource = HotelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('preview')
                ->label('Preview')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->url(fn (): string => route('hotels.show', $this->record->slug))
                ->openUrlInNewTab()
                ->visible(fn (): bool => (bool) $this->record->slug),
        ];
    }
}
