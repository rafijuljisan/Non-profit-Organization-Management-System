<?php

namespace App\Filament\Resources\Roles\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RolesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Role Name')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info'),
                TextColumn::make('guard_name')
                    ->label('Guard')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('permissions_count')
                    ->label('Permissions')
                    ->counts('permissions')
                    ->badge()
                    ->color('success'),
                TextColumn::make('users_count')
                    ->label('Users')
                    ->counts('users')
                    ->badge()
                    ->color('warning'),
                TextColumn::make('created_at')
                    ->label('Created')
                    ->since()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'asc')
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}