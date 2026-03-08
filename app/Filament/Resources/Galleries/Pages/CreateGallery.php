<?php
// ── File: app/Filament/Resources/Galleries/Pages/CreateGallery.php

namespace App\Filament\Resources\Galleries\Pages;

use App\Filament\Resources\Galleries\GalleryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateGallery extends CreateRecord
{
    protected static string $resource = GalleryResource::class;

    protected function afterCreate(): void
    {
        $record = $this->getRecord();
        $media = $record->getFirstMedia('photo');
        if ($media) {
            $record->photo_path = $media->id . '/' . $media->file_name;
            $record->saveQuietly();
        }
    }
}