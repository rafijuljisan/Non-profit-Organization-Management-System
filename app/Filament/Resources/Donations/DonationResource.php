<?php

namespace App\Filament\Resources\Donations;

use App\Filament\Resources\Donations\Pages\CreateDonation;
use App\Filament\Resources\Donations\Pages\EditDonation;
use App\Filament\Resources\Donations\Pages\ListDonations;
use App\Filament\Resources\Donations\Schemas\DonationForm;
use App\Filament\Resources\Donations\Tables\DonationsTable;
use App\Models\Donation;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use BackedEnum;
use UnitEnum;
use Filament\Support\Icons\Heroicon;

class DonationResource extends Resource
{
    protected static ?string $model = Donation::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCreditCard;
    protected static string|UnitEnum|null $navigationGroup = 'Financial Management';
    protected static ?int $navigationSort = 1;

    protected static array $allowedRoles = ['super_admin', 'admin', 'district_admin'];

    public static function canViewAny(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user !== null && $user->hasAnyRole(static::$allowedRoles);
    }

    public static function canCreate(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user !== null && $user->hasAnyRole(['super_admin', 'admin']);
    }

    public static function canEdit(Model $record): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user !== null && $user->hasAnyRole(['super_admin', 'admin']);
    }

    public static function canDelete(Model $record): bool
    {
        return false; // Financial integrity — no deletion
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return DonationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DonationsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListDonations::route('/'),
            'create' => CreateDonation::route('/create'),
            'edit'   => EditDonation::route('/{record}/edit'),
        ];
    }
}