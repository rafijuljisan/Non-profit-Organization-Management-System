<?php

namespace App\Filament\Resources\Users\Tables;

use App\Models\User;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Filament\Tables\Columns\ImageColumn;
use Filament\Actions\BulkAction;
use Illuminate\Database\Eloquent\Collection;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('member_id')->searchable()->sortable(),
                ImageColumn::make('photo')
                    ->label('Photo')
                    ->disk('public')
                    ->circular()
                    ->size(40),
                TextColumn::make('name')->searchable(),
                TextColumn::make('phone')->searchable(),
                TextColumn::make('district.name')->sortable()->searchable(),

                // 🩸 Blood Donation Columns
                TextColumn::make('blood_group')
                    ->label('Blood Group')
                    ->searchable()
                    ->badge()
                    ->color('danger'),
                IconColumn::make('is_blood_donor')
                    ->label('Donor')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('monthly_fee')->money('bdt')->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'active'    => 'success',
                        'pending'   => 'warning',
                        'suspended' => 'danger',
                        'inactive'  => 'gray',
                        default     => 'gray',
                    }),
            ])
            ->filters([
                SelectFilter::make('district_id')
                    ->relationship('district', 'name')
                    ->label('Filter by District'),

                // 🩸 Blood Group Filter
                SelectFilter::make('blood_group')
                    ->label('Blood Group')
                    ->options([
                        'A+' => 'A+', 'A-' => 'A-', 'B+' => 'B+', 'B-' => 'B-',
                        'AB+' => 'AB+', 'AB-' => 'AB-', 'O+' => 'O+', 'O-' => 'O-',
                    ]),
                TernaryFilter::make('is_blood_donor')
                    ->label('Is Blood Donor?'),

                SelectFilter::make('status')
                    ->options([
                        'pending'   => 'Pending',
                        'active'    => 'Active',
                        'suspended' => 'Suspended',
                        'inactive'  => 'Inactive',
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
                            $mpdf = new \Mpdf\Mpdf([
                                'mode'          => 'utf-8',
                                'format'        => 'A4',
                                'margin_top'    => 15,
                                'margin_left'   => 15,
                                'margin_right'  => 15,
                                'margin_bottom' => 15,
                                'fontDir'       => [public_path('fonts')],
                                'fontdata'      => [
                                    'solaimanlipi' => [
                                        'R'           => 'SolaimanLipi.ttf',
                                        'B'           => 'SolaimanLipi.ttf',
                                        'I'           => 'SolaimanLipi.ttf',
                                        'BI'          => 'SolaimanLipi.ttf',
                                        'useOTL'      => 0xFF,
                                        'useKashida'  => 75,
                                    ],
                                ],
                                'default_font'       => 'solaimanlipi',
                                'autoScriptToLang'   => false,
                                'autoLangToFont'     => false,
                            ]);

                            $html = view('pdf.id_card', ['user' => $record])->render();
                            $mpdf->WriteHTML($html);
                            echo $mpdf->Output('', 'S');

                        }, $record->member_id . '-id-card.pdf');
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([

                    // ✅ Bulk Status Update
                    BulkAction::make('update_status')
                        ->label('Update Status')
                        ->icon('heroicon-o-arrow-path')
                        ->color('warning')
                        ->form([
                            Select::make('status')
                                ->label('New Status')
                                ->options([
                                    'active'    => 'Active',
                                    'pending'   => 'Pending',
                                    'suspended' => 'Suspended',
                                    'inactive'  => 'Inactive',
                                ])
                                ->required(),
                        ])
                        ->action(function (Collection $records, array $data) {
                            $records->each(fn (User $user) => $user->update(['status' => $data['status']]));

                            Notification::make()
                                ->title($records->count() . ' member(s) status updated to "' . ucfirst($data['status']) . '"')
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),

                    // ✅ Bulk Activate
                    BulkAction::make('activate')
                        ->label('Activate')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Activate Selected Members')
                        ->modalDescription('Are you sure you want to activate all selected members?')
                        ->action(function (Collection $records) {
                            $records->each(fn (User $user) => $user->update(['status' => 'active']));

                            Notification::make()
                                ->title($records->count() . ' member(s) activated successfully')
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),

                    // ✅ Bulk Suspend
                    BulkAction::make('suspend')
                        ->label('Suspend')
                        ->icon('heroicon-o-no-symbol')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('Suspend Selected Members')
                        ->modalDescription('Are you sure you want to suspend all selected members?')
                        ->action(function (Collection $records) {
                            $records->each(fn (User $user) => $user->update(['status' => 'suspended']));

                            Notification::make()
                                ->title($records->count() . ' member(s) suspended')
                                ->warning()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),

                    // ✅ Bulk Toggle Blood Donor
                    BulkAction::make('toggle_blood_donor')
                        ->label('Set Blood Donor Status')
                        ->icon('heroicon-o-heart')
                        ->color('danger')
                        ->form([
                            Toggle::make('is_blood_donor')
                                ->label('Mark as Blood Donor')
                                ->default(true),
                        ])
                        ->action(function (Collection $records, array $data) {
                            $records->each(fn (User $user) => $user->update(['is_blood_donor' => $data['is_blood_donor']]));

                            $label = $data['is_blood_donor'] ? 'marked as blood donors' : 'unmarked as blood donors';

                            Notification::make()
                                ->title($records->count() . ' member(s) ' . $label)
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),

                    // ✅ Bulk Assign Role
                    BulkAction::make('assign_role')
                        ->label('Assign Role')
                        ->icon('heroicon-o-user-group')
                        ->color('info')
                        ->form([
                            Select::make('role')
                                ->label('Role')
                                ->options(fn () => \Spatie\Permission\Models\Role::pluck('name', 'name'))
                                ->searchable()
                                ->required(),
                        ])
                        ->action(function (Collection $records, array $data) {
                            $records->each(fn (User $user) => $user->assignRole($data['role']));

                            Notification::make()
                                ->title('Role "' . $data['role'] . '" assigned to ' . $records->count() . ' member(s)')
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),

                    // ✅ Bulk Update Monthly Fee
                    BulkAction::make('update_monthly_fee')
                        ->label('Update Monthly Fee')
                        ->icon('heroicon-o-banknotes')
                        ->color('gray')
                        ->form([
                            \Filament\Forms\Components\TextInput::make('monthly_fee')
                                ->label('Monthly Fee (৳)')
                                ->numeric()
                                ->required()
                                ->minValue(0),
                        ])
                        ->action(function (Collection $records, array $data) {
                            $records->each(fn (User $user) => $user->update(['monthly_fee' => $data['monthly_fee']]));

                            Notification::make()
                                ->title('Monthly fee updated to ৳' . $data['monthly_fee'] . ' for ' . $records->count() . ' member(s)')
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),

                    // ✅ Bulk Export (CSV)
                    BulkAction::make('export_csv')
                        ->label('Export CSV')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->color('gray')
                        ->action(function (Collection $records) {
                            $filename = 'members-' . now()->format('Y-m-d-His') . '.csv';

                            $headers = [
                                'Content-Type'        => 'text/csv',
                                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
                            ];

                            $callback = function () use ($records) {
                                $handle = fopen('php://output', 'w');

                                // CSV Header row
                                fputcsv($handle, [
                                    'Member ID', 'Name', 'Email', 'Phone', 'NID',
                                    'District', 'Thana', 'Blood Group', 'Is Donor',
                                    'Designation', 'Monthly Fee', 'Status',
                                ]);

                                foreach ($records as $user) {
                                    fputcsv($handle, [
                                        $user->member_id,
                                        $user->name,
                                        $user->email,
                                        $user->phone,
                                        $user->nid_number,
                                        $user->district?->name,
                                        $user->thana,
                                        $user->blood_group,
                                        $user->is_blood_donor ? 'Yes' : 'No',
                                        $user->designation,
                                        $user->monthly_fee,
                                        $user->status,
                                    ]);
                                }

                                fclose($handle);
                            };

                            return response()->stream($callback, 200, $headers);
                        }),

                    // ✅ Bulk Delete
                    DeleteBulkAction::make(),

                ]),
            ]);
    }
}