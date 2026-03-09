<?php

namespace App\Filament\Resources\Donations\Schemas;

use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
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
                            ->maxLength(255)
                            ->label('Donor Name'),

                        TextInput::make('donor_phone')
                            ->tel()
                            ->maxLength(20)
                            ->label('Phone Number'),
                    ])->columns(2),

                Section::make('Donation Details')
                    ->schema([
                        TextInput::make('amount')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->prefix('৳')
                            ->label('Amount'),

                        Select::make('project_id')
                            ->relationship('project', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->label('Related Project (Optional)'),

                        Select::make('payment_method')
                            ->options([
                                'cash'  => 'Cash',
                                'bkash' => 'bKash',
                                'nagad' => 'Nagad',
                                'bank'  => 'Bank Transfer',
                            ])
                            ->required()
                            ->label('Payment Method'),

                        TextInput::make('receipt_no')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->label('Receipt / TrxID'),

                        Select::make('status')
                            ->options([
                                'pending'   => 'Pending',
                                'completed' => 'Completed',
                                'failed'    => 'Failed',
                            ])
                            ->default('completed')
                            ->required(),

                        Textarea::make('notes')
                            ->label('Notes (Optional)')
                            ->rows(2)
                            ->nullable()
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }
}