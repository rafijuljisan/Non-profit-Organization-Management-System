<?php

namespace App\Filament\Resources\Upazilas\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

class UpazilaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Upazila / Thana Details')
                    ->description('Add a new Upazila under a specific District.')
                    ->schema([
                        Select::make('district_id')
                            ->relationship('district', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->label('Select District')
                            ->columnSpanFull(),
                            
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->label('Upazila / Thana Name')
                            ->placeholder('e.g. Madhukhali')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}