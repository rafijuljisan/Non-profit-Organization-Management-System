<?php

namespace App\Filament\Resources\Designations\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DesignationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Designation Details')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->label('Designation Name')
                            ->placeholder('e.g. সভাপতি, সাধারণ সম্পাদক'),
                        TextInput::make('priority')
                            ->numeric()
                            ->required()
                            ->default(99)
                            ->label('Display Priority')
                            ->hint('Lower number = shown first. e.g. 1 = top'),
                    ])->columns(2),
            ]);
    }
}