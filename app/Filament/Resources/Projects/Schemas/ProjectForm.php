<?php

namespace App\Filament\Resources\Projects\Schemas;

use Filament\Schemas\Components\Section; // Using the v4 namespace you provided earlier
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Schema;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Project Details')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Select::make('district_id')
                            ->relationship('district', 'name')
                            ->searchable()
                            ->preload()
                            ->label('District (Optional)'),
                        Textarea::make('description')
                            ->columnSpanFull()
                            ->rows(3),
                    ])->columns(2),

                Section::make('Budget & Timeline')
                    ->schema([
                        DatePicker::make('start_date'),
                        DatePicker::make('end_date'),
                        TextInput::make('target_budget')
                            ->numeric()
                            ->default(0)
                            ->prefix('৳'),
                        Select::make('status')
                            ->options([
                                'planning' => 'Planning',
                                'ongoing' => 'Ongoing',
                                'completed' => 'Completed',
                            ])
                            ->default('planning')
                            ->required(),
                    ])->columns(2),
            ]);
    }
}