<?php

namespace App\Filament\Resources\BloodDonors;

use App\Filament\Resources\BloodDonors\Pages\ListBloodDonors;
use App\Filament\Resources\BloodDonors\Pages\EditBloodDonor;
use App\Filament\Resources\BloodDonors\Pages\CreateBloodDonor;
use App\Filament\Resources\BloodDonors\Tables\BloodDonorsTable;
use App\Filament\Resources\BloodDonors\Schemas\BloodDonorForm;
use App\Models\User;
use App\Models\BloodDonor;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;

class BloodDonorResource extends Resource
{
    protected static ?string $model = BloodDonor::class;

    protected static ?string $modelLabel = 'Blood Donor';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHeart;
    protected static string|UnitEnum|null $navigationGroup = 'Blood Management';
    protected static ?string $navigationLabel = 'Blood Donors';
    protected static ?string $slug = 'blood-donors';

    public static function canViewAny(): bool
    {
        /** @var \Illuminate\Contracts\Auth\Guard $auth */
        $auth = auth();
        /** @var \App\Models\User|null $user */
        $user = $auth->user();
        if (!$user) return false;
        return $user->hasRole('super_admin')
            || $user->hasRole('blood_secretary')
            || $user->hasPermissionTo('blood_donor_manage');
    }

    public static function canCreate(): bool
    {
        /** @var \Illuminate\Contracts\Auth\Guard $auth */
        $auth = auth();
        /** @var \App\Models\User|null $user */
        $user = $auth->user();
        
        if (!$user) return false;
        
        return $user->hasRole('super_admin')
            || $user->hasRole('blood_secretary')
            || $user->hasPermissionTo('blood_donor_manage');
    }

    public static function canEdit($record): bool
    {
        /** @var \Illuminate\Contracts\Auth\Guard $auth */
        $auth = auth();
        /** @var \App\Models\User|null $user */
        $user = $auth->user();
        if (!$user) return false;
        return $user->hasRole('super_admin')
            || $user->hasRole('blood_secretary')
            || $user->hasPermissionTo('blood_donor_manage');
    }

    public static function canDelete($record): bool { return false; }

    // Only show blood donors in this resource
    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()
            ->where('is_blood_donor', true)
            ->where('status', 'active');
    }

    public static function form(Schema $schema): Schema
    {
        // This will load all the Name, Phone, and Location fields from BloodDonorForm.php
        return BloodDonorForm::configure($schema); 
    }

    public static function table(Table $table): Table
    {
        return BloodDonorsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListBloodDonors::route('/'),
            'create' => CreateBloodDonor::route('/create'), // Add this
            'edit'   => EditBloodDonor::route('/{record}/edit'),
        ];
    }
}