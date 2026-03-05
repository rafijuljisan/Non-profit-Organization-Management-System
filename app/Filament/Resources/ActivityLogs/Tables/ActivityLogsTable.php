<?php

namespace App\Filament\Resources\ActivityLogResource\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class ActivityLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('log_name')
                    ->badge()
                    ->sortable(),
                TextColumn::make('description')
                    ->searchable()
                    ->label('Action'),
                TextColumn::make('causer.name')
                    ->label('Done By')
                    ->searchable(),
                TextColumn::make('subject_type')
                    ->label('Module')
                    ->searchable()
                    ->formatStateUsing(fn(string $state): string => class_basename($state)),

                // ✅ Reads directly from $record bypassing Filament's casting
                TextColumn::make('changes')
                    ->label('Changes')
                    ->getStateUsing(function ($record) {
                        // ✅ Return as JSON string — avoids Filament's Collection rendering
                        return json_encode($record->properties->toArray());
                    })
                    ->formatStateUsing(function ($state) {
                        if (blank($state)) {
                            return new HtmlString('<span class="text-gray-400 text-xs">—</span>');
                        }

                        $data = json_decode($state, true) ?? [];
                        $old = $data['old'] ?? [];
                        $new = $data['attributes'] ?? [];
                        $skipKeys = ['updated_at', 'created_at'];

                        if (empty($old) && empty($new)) {
                            return new HtmlString('<span class="text-gray-400 text-xs">—</span>');
                        }

                        $rows = '';

                        // Created — show new values
                        if (empty($old) && !empty($new)) {
                            foreach ($new as $key => $value) {
                                if (in_array($key, $skipKeys))
                                    continue;
                                $rows .= "<tr>
                    <td class='font-medium text-gray-500 pr-3 py-0.5'>{$key}</td>
                    <td class='text-green-600'>" . e($value) . "</td>
                </tr>";
                            }
                            return new HtmlString(
                                $rows ? "<table class='text-xs leading-5'>{$rows}</table>"
                                : '<span class="text-gray-400 text-xs">—</span>'
                            );
                        }

                        // Updated — old → new
                        foreach ($new as $key => $value) {
                            if (in_array($key, $skipKeys))
                                continue;
                            $oldValue = $old[$key] ?? '—';
                            $newValue = $value ?? '—';
                            if ($oldValue == $newValue)
                                continue;
                            $rows .= "<tr>
                <td class='font-medium text-gray-500 pr-3 py-0.5'>{$key}</td>
                <td class='text-red-400 line-through pr-2'>" . e($oldValue) . "</td>
                <td class='text-gray-400 px-1'>→</td>
                <td class='text-green-600'>" . e($newValue) . "</td>
            </tr>";
                        }

                        return new HtmlString(
                            $rows ? "<table class='text-xs leading-5'>{$rows}</table>"
                            : '<span class="text-gray-400 text-xs">—</span>'
                        );
                    })
                    ->wrap(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Time'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([])
            ->actions([])
            ->bulkActions([]);
    }
}