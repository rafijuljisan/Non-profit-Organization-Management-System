<?php

namespace App\Filament\Resources\Inquiries\Tables;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class InquiriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('phone')->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'contact' => 'info',
                        'volunteer' => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\IconColumn::make('is_read')
                    ->boolean()
                    ->label('Read?'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Submitted At'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'contact' => 'Contact Form',
                        'volunteer' => 'Volunteer Form',
                    ]),
                Tables\Filters\SelectFilter::make('is_read')
                    ->options([
                        0 => 'Unread',
                        1 => 'Read',
                    ])
                    ->label('Status'),
            ])
            ->actions([
                EditAction::make()->label('View / Update'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}