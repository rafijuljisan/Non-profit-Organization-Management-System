<?php

namespace App\Filament\Resources\Projects\Tables;

use App\Models\Donation;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Notifications\Notification;

class ProjectsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('district.name')
                    ->label('District')
                    ->sortable()
                    ->searchable()
                    ->placeholder('All Districts'),

                TextColumn::make('start_date')
                    ->date('d M, Y')
                    ->sortable(),

                TextColumn::make('end_date')
                    ->date('d M, Y')
                    ->sortable()
                    ->placeholder('Ongoing'),

                TextColumn::make('target_budget')
                    ->money('BDT')
                    ->sortable()
                    ->label('Target Budget'),

                // ✅ Live calculated from donations table
                TextColumn::make('collected_amount')
                    ->label('Collected')
                    ->money('BDT')
                    ->sortable()
                    ->getStateUsing(function ($record): float {
                        return Donation::where('project_id', $record->id)
                            ->where('status', 'completed')
                            ->sum('amount');
                    })
                    ->color(function ($record): string {
                        $collected = Donation::where('project_id', $record->id)
                            ->where('status', 'completed')
                            ->sum('amount');
                        $target = $record->target_budget ?? 0;
                        if ($target <= 0) return 'gray';
                        return $collected >= $target ? 'success' : 'warning';
                    }),

                // ✅ Progress percentage
                TextColumn::make('progress')
                    ->label('Progress')
                    ->getStateUsing(function ($record): string {
                        $collected = Donation::where('project_id', $record->id)
                            ->where('status', 'completed')
                            ->sum('amount');
                        $target = $record->target_budget ?? 0;
                        if ($target <= 0) return '—';
                        $pct = min(100, round(($collected / $target) * 100));
                        return $pct . '%';
                    })
                    ->badge()
                    ->color(function ($record): string {
                        $collected = Donation::where('project_id', $record->id)
                            ->where('status', 'completed')
                            ->sum('amount');
                        $target = $record->target_budget ?? 0;
                        if ($target <= 0) return 'gray';
                        $pct = ($collected / $target) * 100;
                        if ($pct >= 100) return 'success';
                        if ($pct >= 50)  return 'warning';
                        return 'danger';
                    }),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'planning'  => 'warning',
                        'ongoing'   => 'success',
                        'completed' => 'info',
                        default     => 'gray',
                    })
                    ->formatStateUsing(fn (string $state) => ucfirst($state)),
            ])

            ->filters([
                SelectFilter::make('district_id')
                    ->relationship('district', 'name')
                    ->label('Filter by District'),

                SelectFilter::make('status')
                    ->options([
                        'planning'  => 'Planning',
                        'ongoing'   => 'Ongoing',
                        'completed' => 'Completed',
                    ]),
            ])

            ->recordActions([
                EditAction::make(),

                // ✅ Sync collected_amount to DB column
                Action::make('sync_collected')
                    ->label('Sync')
                    ->icon('heroicon-o-arrow-path')
                    ->color('info')
                    ->action(function ($record) {
                        $collected = Donation::where('project_id', $record->id)
                            ->where('status', 'completed')
                            ->sum('amount');
                        $record->update(['collected_amount' => $collected]);
                        Notification::make()
                            ->title('Collected amount synced!')
                            ->success()
                            ->send();
                    }),
            ])

            ->toolbarActions([
                // ✅ Sync ALL projects at once
                Action::make('sync_all')
                    ->label('Sync All Collected')
                    ->icon('heroicon-o-arrow-path')
                    ->color('info')
                    ->requiresConfirmation()
                    ->modalDescription('This will recalculate collected_amount for all projects from actual donations.')
                    ->action(function () {
                        \App\Models\Project::all()->each(function ($project) {
                            $collected = Donation::where('project_id', $project->id)
                                ->where('status', 'completed')
                                ->sum('amount');
                            $project->update(['collected_amount' => $collected]);
                        });
                        Notification::make()
                            ->title('All projects synced!')
                            ->success()
                            ->send();
                    }),

                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])

            ->defaultSort('created_at', 'desc');
    }
}