<?php

namespace App\Filament\Resources\HomepageHeroes\Pages;

use App\Filament\Resources\HomepageHeroes\HomepageHeroResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditHomepageHero extends EditRecord
{
    protected static string $resource = HomepageHeroResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
