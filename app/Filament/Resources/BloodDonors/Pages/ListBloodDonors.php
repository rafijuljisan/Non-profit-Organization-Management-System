<?php

namespace App\Filament\Resources\BloodDonors\Pages;

use App\Filament\Resources\BloodDonors\BloodDonorResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab; // <-- Correct v5 namespace
use Illuminate\Database\Eloquent\Builder;

class ListBloodDonors extends ListRecords
{
    protected static string $resource = BloodDonorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Donors'),
            
            'ready' => Tab::make('Ready to Donate')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereNull('last_donation_date')
                    ->orWhereRaw('DATEDIFF(NOW(), last_donation_date) >= 90')),
                    
            'not_ready' => Tab::make('Not Ready')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereNotNull('last_donation_date')
                    ->whereRaw('DATEDIFF(NOW(), last_donation_date) < 90')),
        ];
    }
}