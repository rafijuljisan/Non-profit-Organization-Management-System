<?php

namespace App\Filament\Resources\BloodRequests\Pages;

use App\Filament\Resources\BloodRequests\BloodRequestResource;
use Filament\Resources\Pages\ListRecords;

class ListBloodRequests extends ListRecords
{
    protected static string $resource = BloodRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Intentionally removed CreateAction::make() to enforce frontend-only request creation.
        ];
    }
}