<?php

namespace App\Filament\Resources\BloodRequests\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea; // এটি ইমপোর্ট করতে ভুলবেন না

class BloodRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Request Details')
                    ->schema([
                        Select::make('donor_id')
                            ->relationship('donor', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->label('Requested Donor'),

                        Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'accepted' => 'Accepted',
                                'rejected' => 'Rejected',
                            ])
                            ->required()
                            ->default('pending'),

                        TextInput::make('requester_name')->required()->maxLength(255),
                        TextInput::make('requester_phone')->tel()->required()->maxLength(20),
                        TextInput::make('patient_name')->required()->maxLength(255),
                        TextInput::make('hospital_name')->required()->maxLength(255),
                        TextInput::make('bags_needed')->numeric()->required()->minValue(1)->default(1),
                        
                        // ===== নতুন Note ফিল্ড =====
                        Textarea::make('note')
                            ->label('Admin Note / Remarks')
                            ->placeholder('এখানে কোনো গুরুত্বপূর্ণ তথ্য বা আপডেট লিখে রাখতে পারেন...')
                            ->columnSpanFull() // পুরো জায়গা জুড়ে থাকবে
                            ->rows(3),
                    ])->columns(2),
            ]);
    }
}