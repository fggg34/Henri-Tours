<?php

namespace App\Filament\Resources\BlogPosts\Pages;

use App\Filament\Resources\BlogPosts\BlogPostResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBlogPost extends EditRecord
{
    protected static string $resource = BlogPostResource::class;

    protected array $tagsToSync = [];

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['tags'] = $this->record->tags->pluck('id')->toArray();
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->tagsToSync = $data['tags'] ?? [];
        unset($data['tags']);
        return $data;
    }

    protected function afterSave(): void
    {
        $this->record->tags()->sync(
            collect($this->tagsToSync)->map(fn ($id) => (int) $id)->filter()->values()->all()
        );
    }
}
