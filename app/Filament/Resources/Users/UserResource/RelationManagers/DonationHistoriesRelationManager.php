<?php

namespace App\Filament\Resources\Users\UserResource\RelationManagers;

use Filament\Forms\Components\DatePicker;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions; // <-- THIS is correct for v5

class DonationHistoriesRelationManager extends RelationManager
{
    protected static string $relationship = 'donationHistories';

    protected static ?string $title = 'Blood Donation History';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('donation_date')
                    ->label('Blood Donation Date')
                    ->required()
                    ->maxDate(now())
                    ->displayFormat('d/m/Y'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('donation_date')
            ->columns([
                TextColumn::make('donation_date')
                    ->label('Blood Donation Date')
                    ->date('d M, Y')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Logged On')
                    ->dateTime('d M, Y h:i A')
                    ->color('gray')
                    ->sortable(),
            ])
            ->defaultSort('donation_date', 'desc')
            ->headerActions([
                Actions\CreateAction::make()
                    ->label('Add History Record'),
            ])
            ->recordActions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ]);
    }
}