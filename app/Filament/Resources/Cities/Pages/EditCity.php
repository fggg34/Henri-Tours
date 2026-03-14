<?php

namespace App\Filament\Resources\Cities\Pages;

use App\Filament\Resources\Cities\CityResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditCity extends EditRecord
{
    protected static string $resource = CityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('preview')
                ->label('Preview')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->url(fn (): string => route('cities.show', $this->record->slug))
                ->openUrlInNewTab()
                ->visible(fn (): bool => (bool) $this->record->slug),
        ];
    }
}
