<?php

namespace App\Filament\Resources\Designations\Tables;

use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class DesignationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('priority')
                    ->label('Priority')
                    ->sortable()
                    ->badge()
                    ->color('info'),
                TextColumn::make('name')
                    ->label('Designation')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('users_count')
                    ->label('Members')
                    ->counts('users')
                    ->badge()
                    ->color('success'),
                TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->since()
                    ->sortable(),
            ])
            ->defaultSort('priority', 'asc')
            ->reorderable('priority')
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}