<?php

namespace App\Filament\Resources\BloodDonors\Pages;

use App\Filament\Resources\BloodDonors\BloodDonorResource;
use Filament\Resources\Pages\EditRecord;

class EditBloodDonor extends EditRecord
{
    protected static string $resource = BloodDonorResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}