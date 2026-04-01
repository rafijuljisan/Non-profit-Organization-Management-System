<?php

namespace App\Filament\Resources\Galleries\Pages;

use App\Filament\Resources\Galleries\GalleryResource;
use App\Models\Gallery;
use Filament\Resources\Pages\CreateRecord;

class CreateGallery extends CreateRecord
{
    protected static string $resource = GalleryResource::class;

    // You can keep this, but since we added dehydrated(false) in the form, 
    // it's technically optional now. Better safe than sorry!
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        unset($data['bulk_category']);
        unset($data['bulk_photo_files']);
        return $data;
    }

    protected function afterCreate(): void
    {
        $record = $this->getRecord();
        $data = $this->form->getRawState();
        $bulkCategory = $data['bulk_category'] ?? null;
        $bulkFiles = $data['bulk_photo_files'] ?? [];

        // ── Original single photo path save ──
        $media = $record->getFirstMedia('photo');
        if ($media) {
            $record->photo_path = $media->id . '/' . $media->file_name;
            $record->saveQuietly();
        }

        // ── Bulk upload ──
        if ($bulkCategory && !empty($bulkFiles)) {
            $index = 0;
            
            // 🚨 Check if the main record is missing a photo
            $isMainRecordEmpty = empty($media); 

            foreach ($bulkFiles as $file) {
                if (!$file || !method_exists($file, 'getRealPath')) continue;

                $realPath = $file->getRealPath();
                if (!$realPath || !file_exists($realPath)) continue;

                // 🚨 If the main record has no photo, assign the FIRST bulk photo to IT.
                if ($isMainRecordEmpty) {
                    // Update the blank record with the bulk category
                    $record->update([
                        'category' => $bulkCategory,
                    ]);

                    $record->addMedia($realPath)
                        ->usingFileName(basename($realPath))
                        ->preservingOriginal()
                        ->toMediaCollection('photo', 'public');

                    $moved = $record->fresh()->getFirstMedia('photo');
                    if ($moved) {
                        $record->photo_path = $moved->id . '/' . $moved->file_name;
                        $record->saveQuietly();
                    }
                    
                    // Set to false so the next loop iterations create new rows
                    $isMainRecordEmpty = false; 
                } 
                else {
                    // ── Create brand new gallery rows for the remaining files ──
                    $newGallery = \App\Models\Gallery::create([
                        'type' => 'photo',
                        'category' => $bulkCategory,
                        'title' => $data['title'] ?? null,
                        'sort_order' => $index,
                        'is_active' => true,
                    ]);

                    $newGallery->addMedia($realPath)
                        ->usingFileName(basename($realPath))
                        ->preservingOriginal()
                        ->toMediaCollection('photo', 'public');

                    $moved = $newGallery->fresh()->getFirstMedia('photo');
                    if ($moved) {
                        $newGallery->photo_path = $moved->id . '/' . $moved->file_name;
                        $newGallery->saveQuietly();
                    }
                }

                $index++;
            }
        }
    }
}