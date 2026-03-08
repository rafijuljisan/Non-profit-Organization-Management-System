<?php

namespace App\Filament\Resources\Roles;

use App\Filament\Resources\Roles\Pages\CreateRole;
use App\Filament\Resources\Roles\Pages\EditRole;
use App\Filament\Resources\Roles\Pages\ListRoles;
use App\Filament\Resources\Roles\Schemas\RoleForm;
use App\Filament\Resources\Roles\Tables\RolesTable;
use Spatie\Permission\Models\Role;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShieldCheck;
    protected static string|UnitEnum|null $navigationGroup = 'System Management';
    protected static ?string $navigationLabel = 'Roles & Permissions';
    protected static ?string $recordTitleAttribute = 'name';

    public static function canViewAny(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user !== null && $user->hasRole('super_admin');
    }

    public static function form(Schema $schema): Schema
    {
        return RoleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RolesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListRoles::route('/'),
            'create' => CreateRole::route('/create'),
            'edit'   => EditRole::route('/{record}/edit'),
        ];
    }
}