<?php

namespace App\Filament\Resources\ActivityLogResource\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

// 🚀 এক্সপোর্টের জন্য প্যাকেজ
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class ActivityLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // ✅ ১. Action-এ ডাইনামিক কালার এবং সুন্দর ব্যাজ
                TextColumn::make('log_name')
                    ->label('Type')
                    ->badge()
                    ->color(fn(string $state): string => match (strtolower($state)) {
                        'created', 'create' => 'success',
                        'updated', 'update' => 'warning',
                        'deleted', 'delete' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state) => ucfirst($state))
                    ->sortable(),

                TextColumn::make('description')
                    ->searchable()
                    ->label('Action Info'),

                TextColumn::make('causer.name')
                    ->label('Done By')
                    ->badge()
                    ->color('info')
                    ->searchable()
                    ->placeholder('System / Guest'), // কেউ লগইন না থাকলে এটি দেখাবে

                TextColumn::make('subject_type')
                    ->label('Module')
                    ->searchable()
                    ->formatStateUsing(fn(string $state): string => class_basename($state)),

                // ✅ ২. Changes কলামে Tailwind এর ডিজাইন আরও ক্লিন করা হলো
                TextColumn::make('changes')
                    ->label('Data Changes')
                    ->getStateUsing(function ($record) {
                        return json_encode($record->properties->toArray());
                    })
                    ->formatStateUsing(function ($state) {
                        if (blank($state)) {
                            return new HtmlString('<span class="text-gray-400 text-xs italic">No changes</span>');
                        }

                        $data = json_decode($state, true) ?? [];
                        $old = $data['old'] ?? [];
                        $new = $data['attributes'] ?? [];

                        // সিকিউরিটির জন্য সেনসিটিভ কলামগুলো লগ থেকে বাদ দেওয়া হলো
                        $skipKeys = ['updated_at', 'created_at', 'password', 'remember_token'];

                        if (empty($old) && empty($new)) {
                            return new HtmlString('<span class="text-gray-400 text-xs italic">No changes</span>');
                        }

                        $rows = '';

                        // 🟢 Created — show new values
                        if (empty($old) && !empty($new)) {
                            foreach ($new as $key => $value) {
                                if (in_array($key, $skipKeys))
                                    continue;
                                $valText = is_array($value) ? json_encode($value) : e($value);

                                $rows .= "<tr>
                                    <td class='font-semibold text-gray-500 pr-3 py-1 capitalize'>" . str_replace('_', ' ', $key) . "</td>
                                    <td class='text-green-600 bg-green-50 px-2 rounded'>{$valText}</td>
                                </tr>";
                            }
                            return new HtmlString($rows ? "<table class='text-xs leading-5 w-full'>{$rows}</table>" : '<span class="text-gray-400 text-xs">—</span>');
                        }

                        // 🟠 Updated — old → new
                        foreach ($new as $key => $value) {
                            if (in_array($key, $skipKeys))
                                continue;

                            $oldValue = $old[$key] ?? '—';
                            $newValue = $value ?? '—';

                            if ($oldValue == $newValue)
                                continue;

                            $oldText = is_array($oldValue) ? json_encode($oldValue) : e($oldValue);
                            $newText = is_array($newValue) ? json_encode($newValue) : e($newValue);

                            $rows .= "<tr class='border-b border-gray-100 last:border-0'>
                                <td class='font-semibold text-gray-500 pr-3 py-1 capitalize'>" . str_replace('_', ' ', $key) . "</td>
                                <td class='text-red-500 line-through pr-2 bg-red-50 px-1 rounded'>{$oldText}</td>
                                <td class='text-gray-400 px-2'>→</td>
                                <td class='text-green-600 bg-green-50 px-1 rounded'>{$newText}</td>
                            </tr>";
                        }

                        return new HtmlString($rows ? "<table class='text-xs leading-5 w-full'>{$rows}</table>" : '<span class="text-gray-400 text-xs">—</span>');
                    })
                    ->wrap(),

                TextColumn::make('created_at')
                    ->dateTime('d M Y, h:i A')
                    ->sortable()
                    ->label('Time'),
            ])
            ->defaultSort('created_at', 'desc')

            // ✅ ৩. অ্যাডভান্সড ফিল্টারস যুক্ত করা হলো
            ->filters([
                SelectFilter::make('log_name')
                    ->label('Action Type')
                    ->options([
                        'created' => 'Created',
                        'updated' => 'Updated',
                        'deleted' => 'Deleted',
                    ]),

                SelectFilter::make('causer_id')
                    ->label('Done By')
                    ->options(
                        \App\Models\User::orderBy('name')
                            ->pluck('name', 'id')
                            ->toArray()
                    ),

                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from')->label('From Date'),
                        DatePicker::make('created_until')->label('To Date'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['created_from'], fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date))
                            ->when($data['created_until'], fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date));
                    }),
            ])

            // ✅ ৪. এক্সপোর্ট বাটন যুক্ত করা হলো
            ->headerActions([
                ExportAction::make()
                    ->label('Export Audit Log')
                    ->color('primary')
                    ->icon('heroicon-o-document-arrow-down')
                    ->exports([
                        ExcelExport::make('table')->fromTable(),
                    ]),
            ])
            ->actions([])
            ->bulkActions([]);
    }
}