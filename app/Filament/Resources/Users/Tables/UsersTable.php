<?php

namespace App\Filament\Resources\Users\Tables;

use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\EditAction;        // ✅ v4 namespace
use Filament\Actions\Action;            // ✅ v4 namespace
use Filament\Actions\BulkActionGroup;   // ✅ v4 namespace
use Filament\Actions\DeleteBulkAction;  // ✅ v4 namespace
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('member_id')->searchable()->sortable(),
                TextColumn::make('name')->searchable(),
                TextColumn::make('phone')->searchable(),
                TextColumn::make('district.name')->sortable()->searchable(),
                TextColumn::make('monthly_fee')->money('bdt')->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'pending' => 'warning',
                        'suspended' => 'danger',
                        'inactive' => 'gray',
                        default => 'gray',
                    }),
            ])
            ->filters([
                SelectFilter::make('district_id')
                    ->relationship('district', 'name')
                    ->label('Filter by District'),
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'active' => 'Active',
                        'suspended' => 'Suspended',
                        'inactive' => 'Inactive',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),

                Action::make('pdf')
                    ->label('ID Card')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('info')
                    ->action(function (User $record) {
                        return response()->streamDownload(function () use ($record) {
                            echo Pdf::loadView('pdf.id-card', ['user' => $record])->output();
                        }, $record->member_id . '-id-card.pdf');
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}