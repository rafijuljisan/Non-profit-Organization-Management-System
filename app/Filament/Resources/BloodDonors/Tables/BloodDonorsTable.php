<?php

namespace App\Filament\Resources\BloodDonors\Tables;

use Carbon\Carbon;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Upazila;

class BloodDonorsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('name')
                    ->label('Donor Name')
                    ->searchable(query: function ($query, string $search) {
                        $query->orWhere('name', 'like', '%' . $search . '%');
                    })
                    ->sortable(),

                TextColumn::make('phone')
                    ->label('Phone')
                    ->searchable(query: function ($query, string $search) {
                        // Strip everything except digits
                        $normalized = preg_replace('/[^0-9]/', '', $search);

                        if (empty($normalized)) {
                            return $query;
                        }

                        // Match last N digits of stored phone against last N digits of search
                        $query->orWhereRaw(
                            "REPLACE(REPLACE(REPLACE(REPLACE(phone, '+', ''), '-', ''), ' ', ''), '(', '') LIKE ?",
                            ['%' . $normalized]
                        );
                    })
                    ->copyable(),

                TextColumn::make('blood_group')
                    ->label('Blood Group')
                    ->badge()
                    ->color('danger')
                    ->sortable(),

                TextColumn::make('district.name')
                    ->label('District')
                    ->sortable()
                    ->placeholder('—'),

                TextColumn::make('thana')
                    ->label('Thana')
                    ->placeholder('—'),

                TextColumn::make('last_donation_date')
                    ->label('Last Donated')
                    ->date('d M, Y')
                    ->placeholder('Never donated')
                    ->sortable(),

                TextColumn::make('donation_count')
                    ->label('Total Donations')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                TextColumn::make('eligibility')
                    ->label('Eligibility')
                    ->getStateUsing(function ($record) {
                        if (!$record->last_donation_date) {
                            return 'ready';
                        }
                        $daysPassed = Carbon::parse($record->last_donation_date)
                            ->startOfDay()
                            ->diffInDays(Carbon::now()->startOfDay());

                        if ($daysPassed >= 90) {
                            return 'ready';
                        }
                        return (90 - $daysPassed) . ' days left';
                    })
                    ->badge()
                    ->color(fn($state) => $state === 'ready' ? 'success' : 'warning')
                    ->formatStateUsing(fn($state) => $state === 'ready' ? '✓ Ready' : $state),

            ])

            ->filters([
                SelectFilter::make('blood_group')
                    ->label('Blood Group')
                    ->options([
                        'A+' => 'A+',
                        'A-' => 'A-',
                        'B+' => 'B+',
                        'B-' => 'B-',
                        'AB+' => 'AB+',
                        'AB-' => 'AB-',
                        'O+' => 'O+',
                        'O-' => 'O-',
                    ]),

                Filter::make('location')
                    ->form([
                        Select::make('district_id')
                            ->label('District')
                            ->relationship('district', 'name')
                            ->searchable()
                            ->preload()
                            ->live() // This tells Filament to update the form when the district changes
                            ->afterStateUpdated(fn($set) => $set('thana', null)), // Removed 'Set' typehint

                        Select::make('thana')
                            ->label('Thana / Upazila')
                            ->searchable()
                            ->options(function ($get) { // Removed 'Get' typehint
                                // Get the currently selected district ID
                                $districtId = $get('district_id');

                                // If a district IS selected, fetch only its associated thanas
                                if ($districtId) {
                                    return Upazila::where('district_id', $districtId)
                                        ->orderBy('name')
                                        ->pluck('name', 'name')
                                        ->toArray();
                                }

                                // If NO district is selected, fetch all thanas
                                return Upazila::orderBy('name')
                                    ->pluck('name', 'name')
                                    ->toArray();
                            }),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        // This applies the actual database query based on what the user selected
                        return $query
                            ->when(
                                $data['district_id'] ?? null,
                                fn(Builder $query, $districtId) => $query->where('district_id', $districtId),
                            )
                            ->when(
                                $data['thana'] ?? null,
                                fn(Builder $query, $thana) => $query->where('thana', $thana),
                            );
                    }),

                SelectFilter::make('eligibility')
                    ->label('Eligibility Status')
                    ->options([
                        'ready' => 'Ready to Donate',
                        'not_ready' => 'Not Ready Yet',
                    ])
                    ->query(function ($query, $state) {
                        if ($state['value'] === 'ready') {
                            $query->where(function ($q) {
                                $q->whereNull('last_donation_date')
                                    ->orWhereRaw('DATEDIFF(NOW(), last_donation_date) >= 90');
                            });
                        } elseif ($state['value'] === 'not_ready') {
                            $query->whereNotNull('last_donation_date')
                                ->whereRaw('DATEDIFF(NOW(), last_donation_date) < 90');
                        }
                    }),
            ])

            ->recordActions([
                Action::make('record_new_donation')
                    ->label('Log Donation')
                    ->icon(Heroicon::OutlinedHeart)
                    ->color('danger') // Changed to red/danger to match blood theme
                    ->form([
                        DatePicker::make('donation_date')
                            ->label('Date of Donation')
                            ->default(now()) // Default to today to save time
                            ->maxDate(now())
                            ->displayFormat('d/m/Y')
                            ->required(),
                    ])
                    ->action(function ($record, array $data) {
                        // 1. Create the history record
                        \App\Models\BloodDonationHistory::create([
                            'user_id' => $record->id,
                            'donation_date' => $data['donation_date'],
                        ]);

                        // 2. Update the donor's main record & increment count
                        $record->update([
                            'last_donation_date' => $data['donation_date'],
                            'donation_count' => $record->donation_count + 1,
                        ]);
                    })
                    ->successNotificationTitle('New donation logged successfully!'),

                EditAction::make()
                    ->label('Edit'),
            ])

            ->defaultSort('name', 'asc');
    }
}