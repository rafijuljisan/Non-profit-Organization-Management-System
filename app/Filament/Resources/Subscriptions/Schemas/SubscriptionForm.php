<?php

namespace App\Filament\Resources\Subscriptions\Schemas;

use Filament\Schemas\Components\Section; // v4 namespace for Section
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Schema;

class SubscriptionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Subscription Details')
                    ->schema([
                        Select::make('user_id')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->label('Member'),
                        DatePicker::make('month')
                            ->required()
                            ->label('Subscription Month')
                            ->displayFormat('F Y'), // E.g., March 2026
                        TextInput::make('amount')
                            ->required()
                            ->numeric()
                            ->prefix('৳'),
                        Select::make('status')
                            ->options([
                                'paid' => 'Paid',
                                'unpaid' => 'Unpaid',
                                'partial' => 'Partial',
                            ])
                            ->default('unpaid')
                            ->required(),
                    ])->columns(2),
                    
                Section::make('Payment Information')
                    ->schema([
                        Select::make('payment_method')
                            ->options([
                                'cash' => 'Cash',
                                'bkash' => 'bKash',
                                'nagad' => 'Nagad',
                                'bank' => 'Bank Transfer',
                            ])
                            ->label('Payment Method'),
                        TextInput::make('transaction_id')
                            ->label('Transaction ID / Receipt No')
                            ->maxLength(255),
                        Select::make('approved_by')
                            ->relationship('approver', 'name')
                            ->searchable()
                            ->preload()
                            ->label('Approved By (Admin)'),
                    ])->columns(2),
            ]);
    }
}