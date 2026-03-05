<?php

namespace App\Filament\Resources\Districts\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class DistrictForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('code')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(10)
                    ->placeholder('যেমন: DHA, SYL'),
                Select::make('coordinator_id')
                    ->relationship('coordinator', 'name')
                    ->searchable()
                    ->preload()
                    ->label('District Coordinator'),
                Toggle::make('status')
                    ->default(true)
                    ->label('Active Status'),
            ]);
    }
}