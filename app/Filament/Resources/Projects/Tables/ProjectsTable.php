<?php

namespace App\Filament\Resources\Projects\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ProjectsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('district.name')->label('District')->sortable()->searchable(),
                TextColumn::make('start_date')->date()->sortable(),
                TextColumn::make('target_budget')->money('bdt')->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'planning' => 'warning',
                        'ongoing' => 'success',
                        'completed' => 'info',
                        default => 'gray',
                    }),
            ])
            ->filters([
                SelectFilter::make('district_id')
                    ->relationship('district', 'name')
                    ->label('Filter by District'),
                SelectFilter::make('status')
                    ->options([
                        'planning' => 'Planning',
                        'ongoing' => 'Ongoing',
                        'completed' => 'Completed',
                    ]),
            ])
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