<?php

namespace App\Filament\Resources\BloodDonors\Pages;

use App\Filament\Resources\BloodDonors\BloodDonorResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateBloodDonor extends CreateRecord
{
    protected static string $resource = BloodDonorResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Force these flags so they appear in the blood donor list automatically
        $data['is_blood_donor'] = true;
        $data['status'] = 'active'; 

        // If your User table REQUIRES an email, generate a dummy one if left blank
        if (empty($data['email'])) {
            $data['email'] = 'donor_' . time() . '@placeholder.com';
        }

        // If your User table REQUIRES a password, generate a random one
        if (empty($data['password'])) {
            $data['password'] = bcrypt(Str::random(12));
        }

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}