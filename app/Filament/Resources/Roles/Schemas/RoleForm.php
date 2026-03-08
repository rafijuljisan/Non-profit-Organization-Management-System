<?php

namespace App\Filament\Resources\Roles\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Spatie\Permission\Models\Permission;

class RoleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Role Details')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->label('Role Name')
                            ->placeholder('e.g. admin, district_admin'),
                        TextInput::make('guard_name')
                            ->required()
                            ->default('web')
                            ->label('Guard Name'),
                    ])->columns(2),

                Section::make('Permissions')
                    ->schema([
                        Select::make('permissions')
                            ->label('Assign Permissions')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->relationship('permissions', 'name'),
                    ]),
            ]);
    }
}