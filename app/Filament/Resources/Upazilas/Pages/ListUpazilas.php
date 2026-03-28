<?php

namespace App\Filament\Resources\Upazilas\Pages;

use App\Filament\Resources\Upazilas\UpazilaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListUpazilas extends ListRecords
{
    protected static string $resource = UpazilaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
