<?php

namespace App\Filament\Resources\Users\Tables;

use App\Models\User;
use Filament\Actions\EditAction;        // ✅ v4 namespace
use Filament\Actions\Action;            // ✅ v4 namespace
use Filament\Actions\BulkActionGroup;   // ✅ v4 namespace
use Filament\Actions\DeleteBulkAction;  // ✅ v4 namespace
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Mpdf\Tag\I;
use Filament\Tables\Columns\ImageColumn;

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
                TextColumn::make('monthly_fee')->money('bdt')->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
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

                            // Initialize mPDF with Bengali font configuration
                            $mpdf = new \Mpdf\Mpdf([
                                'mode' => 'utf-8',
                                'format' => 'A4',
                                'margin_top' => 15,
                                'margin_left' => 15,
                                'margin_right' => 15,
                                'margin_bottom' => 15,
                                'fontDir' => [public_path('fonts')],
                                'fontdata' => [
                                    'solaimanlipi' => [
                                        'R' => 'SolaimanLipi.ttf',
                                        'B' => 'SolaimanLipi.ttf',
                                        'I' => 'SolaimanLipi.ttf',
                                        'BI' => 'SolaimanLipi.ttf',
                                        'useOTL' => 0xFF,
                                        'useKashida' => 75,
                                    ],
                                ],
                                'default_font' => 'solaimanlipi',
                                'autoScriptToLang' => false,
                                'autoLangToFont' => false,
                            ]);

                            // Render the blade view to HTML
                            $html = view('pdf.id_card', ['user' => $record])->render();

                            // Write HTML and output as a string
                            $mpdf->WriteHTML($html);
                            echo $mpdf->Output('', 'S');

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