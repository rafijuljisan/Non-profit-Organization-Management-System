<?php

namespace App\Filament\Resources\Donations;

use App\Filament\Resources\Donations\Pages\CreateDonation;
use App\Filament\Resources\Donations\Pages\EditDonation;
use App\Filament\Resources\Donations\Pages\ListDonations;
use App\Filament\Resources\Donations\Schemas\DonationForm;
use App\Filament\Resources\Donations\Tables\DonationsTable;
use App\Models\Donation;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class DonationResource extends Resource
{
    protected static ?string $model = Donation::class;
    
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHeart;
    protected static string|UnitEnum|null $navigationGroup = 'Financial Management';

    public static function form(Schema $schema): Schema
    {
        return DonationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DonationsTable::configure($table);
    }

    // 🛡️ Financial Security: Disable deleting a single donation
    public static function canDelete(Model $record): bool 
    {
        return false;
    }

    // 🛡️ Financial Security: Disable bulk deleting donations
    public static function canDeleteAny(): bool 
    {
        return false;
    }
    public static function getPages(): array
    {
        return [
            'index' => ListDonations::route('/'),
            'create' => CreateDonation::route('/create'),
            'edit' => EditDonation::route('/{record}/edit'),
        ];
    }
}