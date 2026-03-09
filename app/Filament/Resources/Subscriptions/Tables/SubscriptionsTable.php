<?php

namespace App\Filament\Resources\Subscriptions\Tables;

use App\Models\Subscription;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class SubscriptionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Member Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (Subscription $record): string => 'ID: ' . ($record->user->member_id ?? 'N/A')),

                TextColumn::make('month')
                    ->label('Billing Month')
                    ->formatStateUsing(fn ($state) => $state ? \Carbon\Carbon::parse($state)->format('F, Y') : '—')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('amount')
                    ->label('Amount (৳)')
                    ->money('BDT')
                    ->sortable(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match (strtolower($state)) {
                        'paid'    => 'success',
                        'unpaid'  => 'warning',
                        'partial' => 'info',
                        default   => 'gray',
                    })
                    ->formatStateUsing(fn (string $state) => strtoupper($state)),

                TextColumn::make('payment_date')
                    ->label('Paid On')
                    ->dateTime('d M, Y')
                    ->placeholder('Not Paid Yet')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->date('d M, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])

            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'paid'    => 'Paid (পরিশোধিত)',
                        'unpaid'  => 'Unpaid (বকেয়া)',
                        'partial' => 'Partial (আংশিক)',
                    ]),

                Filter::make('month')
                    ->form([
                        \Filament\Forms\Components\Select::make('month_select')
                            ->label('Billing Month')
                            ->options([
                                '01' => 'January',  '02' => 'February', '03' => 'March',
                                '04' => 'April',    '05' => 'May',      '06' => 'June',
                                '07' => 'July',     '08' => 'August',   '09' => 'September',
                                '10' => 'October',  '11' => 'November', '12' => 'December',
                            ]),
                        \Filament\Forms\Components\Select::make('year_select')
                            ->label('Year')
                            ->options(
                                collect(range(now()->year, now()->year - 4))
                                    ->mapWithKeys(fn ($y) => [$y => $y])
                                    ->toArray()
                            )
                            ->default(now()->year),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        $month = $data['month_select'] ?? null;
                        $year  = $data['year_select'] ?? null;
                        return $query->when($month && $year, fn (Builder $q) =>
                            $q->whereYear('month', $year)->whereMonth('month', $month)
                        );
                    }),

                SelectFilter::make('user_id')
                    ->relationship('user', 'name')
                    ->label('Filter by Member')
                    ->searchable()
                    ->preload(),

                Filter::make('payment_date')
                    ->form([
                        DatePicker::make('paid_from')->label('Paid From'),
                        DatePicker::make('paid_until')->label('Paid Until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['paid_from'] ?? null,
                                fn (Builder $q, $date) => $q->whereDate('payment_date', '>=', $date)
                            )
                            ->when(
                                $data['paid_until'] ?? null,
                                fn (Builder $q, $date) => $q->whereDate('payment_date', '<=', $date)
                            );
                    }),
            ])

            ->recordActions([
                EditAction::make(),

                // ✅ Mark as Paid
                Action::make('mark_as_paid')
                    ->label('Mark Paid')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Confirm Payment')
                    ->modalDescription('Mark this subscription as paid?')
                    ->action(function (Subscription $record) {
                        $record->update([
                            'status'       => 'paid',
                            'payment_date' => now(),
                        ]);
                        Notification::make()
                            ->title('Marked as Paid!')
                            ->success()
                            ->send();
                    })
                    ->visible(function (Subscription $record) {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $record->status !== 'paid'
                            && $user !== null
                            && $user->hasRole('super_admin');
                    }),

                // ⚠️ Mark as Unpaid (reset)
                Action::make('mark_as_overdue')
                    ->label('Mark Unpaid')
                    ->icon('heroicon-o-exclamation-circle')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Reset to Unpaid?')
                    ->action(function (Subscription $record) {
                        $record->update(['status' => 'unpaid', 'payment_date' => null]);
                        Notification::make()
                            ->title('Marked as Unpaid!')
                            ->warning()
                            ->send();
                    })
                    ->visible(function (Subscription $record) {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $record->status === 'unpaid'
                            && $user !== null
                            && $user->hasRole('super_admin');
                    }),

                DeleteAction::make(),
            ])

            ->toolbarActions([
                // 🚀 Generate Monthly Dues
                Action::make('generate_dues')
                    ->label('Generate Monthly Dues')
                    ->icon('heroicon-o-bolt')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Generate Dues for ' . now()->format('F, Y'))
                    ->modalDescription('This will create pending bills for ALL active members who don\'t have a bill this month.')
                    ->action(function () {
                        $monthDate  = now()->startOfMonth()->toDateString();
                        $labelMonth = now()->format('F, Y');

                        $activeUsers = User::where('status', 'active')->get();
                        $count = 0;

                        foreach ($activeUsers as $user) {
                            $exists = Subscription::where('user_id', $user->id)
                                ->where('month', $monthDate)
                                ->exists();

                            if (!$exists) {
                                Subscription::create([
                                    'user_id' => $user->id,
                                    'month'   => $monthDate,
                                    'amount'  => $user->monthly_fee ?? 100, // ✅ use user's own fee
                                    'status'  => 'unpaid',
                                ]);
                                $count++;
                            }
                        }

                        Notification::make()
                            ->title("Generated {$count} new bills for {$labelMonth}!")
                            ->success()
                            ->send();
                    })
                    ->visible(function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user !== null && $user->hasRole('super_admin');
                    }),

                // 📊 Export Excel
                ExportAction::make()
                    ->label('Export Excel')
                    ->color('success')
                    ->icon('heroicon-o-document-arrow-down')
                    ->exports([
                        ExcelExport::make('table')->fromTable(),
                    ]),

                BulkActionGroup::make([
                    DeleteBulkAction::make(),

                    // ✅ Bulk Mark Paid
                    BulkAction::make('mark_all_paid')
                        ->label('Mark Selected as Paid')
                        ->color('success')
                        ->icon('heroicon-o-check-circle')
                        ->requiresConfirmation()
                        ->action(function (Collection $records) {
                            $count = 0;
                            $records->each(function ($record) use (&$count) {
                                if ($record->status !== 'paid') {
                                    $record->update([
                                        'status'       => 'paid',
                                        'payment_date' => now(),
                                    ]);
                                    $count++;
                                }
                            });
                            Notification::make()
                                ->title("{$count} bills marked as Paid!")
                                ->success()
                                ->send();
                        }),

                    // ⚠️ Bulk Mark Unpaid
                    BulkAction::make('mark_all_overdue')
                        ->label('Mark Selected as Unpaid')
                        ->color('warning')
                        ->icon('heroicon-o-exclamation-circle')
                        ->requiresConfirmation()
                        ->action(function (Collection $records) {
                            $records->each(fn ($r) => $r->status !== 'paid'
                                ? $r->update(['status' => 'unpaid', 'payment_date' => null])
                                : null
                            );
                            Notification::make()
                                ->title('Selected bills marked as Unpaid!')
                                ->warning()
                                ->send();
                        }),
                ]),
            ])

            ->defaultSort('created_at', 'desc');
    }
}