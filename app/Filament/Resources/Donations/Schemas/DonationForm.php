<?php

namespace App\Filament\Resources\Donations\Schemas;

use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DonationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Donor Information')
                    ->schema([
                        TextInput::make('donor_name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('donor_phone')
                            ->tel()
                            ->maxLength(255),
                    ])->columns(2),

                Section::make('Donation Details')
                    ->schema([
                        TextInput::make('amount')
                            ->required()
                            ->numeric()
                            ->prefix('৳'),
                        Select::make('project_id')
                            ->relationship('project', 'name')
                            ->searchable()
                            ->preload()
                            ->label('Related Project (Optional)'),
                        Select::make('payment_method')
                            ->options([
                                'cash' => 'Cash',
                                'bkash' => 'bKash',
                                'nagad' => 'Nagad',
                                'bank' => 'Bank Transfer',
                            ])
                            ->label('Payment Method'),
                        TextInput::make('receipt_no')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->label('Receipt / TrxID'),
                        Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'completed' => 'Completed',
                                'failed' => 'Failed',
                            ])
                            ->default('completed')
                            ->required(),
                    ])->columns(2),
            ]);
    }
}