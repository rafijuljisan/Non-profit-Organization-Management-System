<?php

namespace App\Filament\Resources\BloodDonors\Schemas;

use App\Models\Upazila;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Utilities\Get;

class BloodDonorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Donor Information')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                            
                        TextInput::make('phone')
                            ->tel()
                            ->required()
                            ->unique(ignoreRecord: true),
                            
                        Select::make('blood_group')
                            ->required()
                            ->options([
                                'A+' => 'A+', 'A-' => 'A-', 'B+' => 'B+', 'B-' => 'B-',
                                'AB+' => 'AB+', 'AB-' => 'AB-', 'O+' => 'O+', 'O-' => 'O-',
                            ]),
                            
                        DatePicker::make('last_donation_date')
                            ->label('Last Donation Date')
                            ->maxDate(now())
                            ->displayFormat('d/m/Y'),
                    ])->columns(2),

                Section::make('Location')
                    ->schema([
                        Select::make('district_id')
                            ->relationship('district', 'name')
                            ->searchable()
                            ->preload()
                            ->live()
                            ->label('District'),

                        Select::make('thana')
                            ->label('Upazila / Thana')
                            ->options(function (Get $get) {
                                $districtId = $get('district_id');
                                if (!$districtId) {
                                    return [];
                                }
                                return Upazila::where('district_id', $districtId)->pluck('name', 'name');
                            })
                            ->searchable()
                            ->preload(),
                            
                        TextInput::make('area')
                            ->label('Specific Area / Address')
                            ->maxLength(255),
                    ])->columns(2),
            ]);
    }
}