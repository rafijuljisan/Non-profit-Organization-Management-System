<?php

namespace App\Filament\Resources\Expenses\Tables;

use App\Models\Expense;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class ExpensesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('category')
                    ->label('Category')
                    ->searchable(),

                TextColumn::make('project.name')
                    ->label('Project')
                    ->searchable(),

                TextColumn::make('amount')
                    ->money('BDT')
                    ->sortable(),

                ImageColumn::make('voucher_upload')
                    ->label('Voucher')
                    ->disk('public'),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending'  => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default    => 'gray',
                    }),

                TextColumn::make('created_at')
                    ->label('Date')
                    ->date('d M Y')
                    ->sortable(),
            ])

            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending'  => 'Pending (অপেক্ষমান)',
                        'approved' => 'Approved (অনুমোদিত)',
                        'rejected' => 'Rejected (বাতিল)',
                    ]),

                SelectFilter::make('project_id')
                    ->relationship('project', 'name')
                    ->label('Filter by Project')
                    ->searchable()
                    ->preload(),

                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from')->label('From Date'),
                        DatePicker::make('created_until')->label('To Date'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'] ?? null,
                                fn (Builder $q, $date) => $q->whereDate('created_at', '>=', $date)
                            )
                            ->when(
                                $data['created_until'] ?? null,
                                fn (Builder $q, $date) => $q->whereDate('created_at', '<=', $date)
                            );
                    }),
            ])

            ->recordActions([
                ViewAction::make(),
                EditAction::make(),

                // ✅ Approve
                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Approve Expense')
                    ->modalDescription('Are you sure? This will deduct the amount from the net balance.')
                    ->action(fn (Expense $record) => $record->update(['status' => 'approved']))
                    ->visible(function (Expense $record) {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $record->status === 'pending'
                            && $user !== null
                            && $user->hasRole('super_admin');
                    }),

                // ❌ Reject
                Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Reject Expense')
                    ->form([
                        \Filament\Forms\Components\Textarea::make('rejection_reason')
                            ->label('Reason for Rejection (বাতিলের কারণ)')
                            ->required(),
                    ])
                    ->action(fn (Expense $record) => $record->update(['status' => 'rejected']))
                    ->visible(function (Expense $record) {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $record->status === 'pending'
                            && $user !== null
                            && $user->hasRole('super_admin');
                    }),
            ])

            ->toolbarActions([
                // PDF Export
                Action::make('export_pdf')
                    ->label('Export PDF')
                    ->color('danger')
                    ->icon('heroicon-o-document-arrow-down')
                    ->action(function ($livewire) {
                        $records = $livewire->getFilteredTableQuery()->get();
                        $pdf = Pdf::loadView('pdf.expense_report', ['records' => $records]);
                        return response()->streamDownload(
                            fn () => print($pdf->output()),
                            'Expense_Report_' . now()->format('d_M_Y') . '.pdf'
                        );
                    }),

                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])

            ->defaultSort('created_at', 'desc');
    }
}