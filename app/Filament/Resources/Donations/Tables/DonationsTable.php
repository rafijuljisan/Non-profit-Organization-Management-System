<?php

namespace App\Filament\Resources\Donations\Tables;

use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class DonationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('donor_name')
                    ->label('Donor Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('donor_phone')
                    ->label('Phone')
                    ->searchable()
                    ->placeholder('—'),

                TextColumn::make('amount')
                    ->money('BDT')
                    ->sortable(),

                TextColumn::make('project.name')
                    ->label('Project')
                    ->searchable()
                    ->placeholder('General'),

                TextColumn::make('payment_method')
                    ->label('Method')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'cash'  => 'success',
                        'bkash' => 'warning',
                        'nagad' => 'danger',
                        'bank'  => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'cash'  => 'Cash',
                        'bkash' => 'bKash',
                        'nagad' => 'Nagad',
                        'bank'  => 'Bank',
                        default => ucfirst($state),
                    }),

                TextColumn::make('receipt_no')
                    ->label('Receipt No')
                    ->searchable()
                    ->copyable(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'completed' => 'success',
                        'pending'   => 'warning',
                        'failed'    => 'danger',
                        default     => 'gray',
                    })
                    ->formatStateUsing(fn (string $state) => ucfirst($state)),

                TextColumn::make('created_at')
                    ->label('Date')
                    ->date('d M, Y')
                    ->sortable(),
            ])

            ->filters([
                SelectFilter::make('project_id')
                    ->relationship('project', 'name')
                    ->label('Filter by Project')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('status')
                    ->options([
                        'pending'   => 'Pending',
                        'completed' => 'Completed',
                        'failed'    => 'Failed',
                    ]),

                SelectFilter::make('payment_method')
                    ->label('Payment Method')
                    ->options([
                        'cash'  => 'Cash',
                        'bkash' => 'bKash',
                        'nagad' => 'Nagad',
                        'bank'  => 'Bank Transfer',
                    ]),
            ])

            ->recordActions([
                EditAction::make(),
            ])

            ->toolbarActions([
                ExportAction::make()
                    ->label('Export Excel')
                    ->color('success')
                    ->icon('heroicon-o-document-arrow-down')
                    ->exports([
                        ExcelExport::make('table')->fromTable(),
                    ]),

                BulkActionGroup::make([
                    // Delete intentionally disabled for financial integrity
                ]),
            ])

            ->defaultSort('created_at', 'desc');
    }
}