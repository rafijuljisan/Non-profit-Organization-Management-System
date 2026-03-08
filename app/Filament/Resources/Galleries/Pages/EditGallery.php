<?php
// ── File: app/Filament/Resources/Galleries/Pages/EditGallery.php

namespace App\Filament\Resources\Galleries\Pages;

use App\Filament\Resources\Galleries\GalleryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditGallery extends EditRecord
{
    protected static string $resource = GalleryResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }

    protected function afterSave(): void
    {
        $record = $this->getRecord();
        $media = $record->getFirstMedia('photo');
        if ($media) {
            $record->photo_path = $media->id . '/' . $media->file_name;
            $record->saveQuietly();
        }
    }
}