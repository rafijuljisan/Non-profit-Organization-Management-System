<?php

namespace App\Filament\Resources\Galleries\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class GalleriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('photo')
                    ->label('Preview')
                    ->collection('photo')
                    ->conversion('thumb')
                    ->width(90)
                    ->height(60)
                    ->extraImgAttributes(['class' => 'rounded-lg object-cover']),

                TextColumn::make('title')
                    ->label('Title')
                    ->searchable()
                    ->sortable()
                    ->limit(40)
                    ->description(fn ($record) => $record->category),

                TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'photo'    => 'success',
                        'youtube'  => 'danger',
                        'facebook' => 'info',
                        default    => 'gray',
                    }),

                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),

                TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Added')
                    ->since()
                    ->sortable(),
            ])
            ->defaultSort('sort_order', 'asc')
            ->reorderable('sort_order')
            ->filters([
                SelectFilter::make('type')
                    ->options([
                        'photo'    => 'Photo',
                        'youtube'  => 'YouTube',
                        'facebook' => 'Facebook',
                    ]),
                SelectFilter::make('is_active')
                    ->label('Visibility')
                    ->options([
                        '1' => 'Active',
                        '0' => 'Hidden',
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