<?php

namespace App\Filament\Resources\Upazilas\Pages;

use App\Filament\Resources\Upazilas\UpazilaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditUpazila extends EditRecord
{
    protected static string $resource = UpazilaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
