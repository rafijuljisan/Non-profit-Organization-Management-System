<?php

namespace App\Filament\Resources\Testimonials\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TestimonialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Person Info')
                ->components([
                    TextInput::make('name')
                        ->required()
                        ->maxLength(255)
                        ->label('Name'),

                    TextInput::make('designation')
                        ->label('Designation / Area')
                        ->placeholder('e.g. Dhaka, Volunteer')
                        ->maxLength(255),

                    FileUpload::make('avatar')
                        ->image()
                        ->disk('public')
                        ->directory('testimonials')
                        ->label('Photo (Optional)')
                        ->avatar()
                        ->columnSpanFull(),
                ])->columns(2),

            Section::make('Review')
                ->components([
                    Textarea::make('message')
                        ->required()
                        ->rows(4)
                        ->label('Message')
                        ->columnSpanFull(),

                    Select::make('rating')
                        ->options([
                            1 => '⭐ 1 Star',
                            2 => '⭐⭐ 2 Stars',
                            3 => '⭐⭐⭐ 3 Stars',
                            4 => '⭐⭐⭐⭐ 4 Stars',
                            5 => '⭐⭐⭐⭐⭐ 5 Stars',
                        ])
                        ->default(5)
                        ->required()
                        ->label('Rating'),

                    TextInput::make('sort_order')
                        ->numeric()
                        ->default(0)
                        ->label('Sort Order'),

                    Toggle::make('is_active')
                        ->default(true)
                        ->label('Show on Website'),
                ])->columns(2),
        ]);
    }
}