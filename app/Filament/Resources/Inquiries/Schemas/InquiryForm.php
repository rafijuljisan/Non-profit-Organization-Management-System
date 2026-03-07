<?php

namespace App\Filament\Resources\Inquiries\Schemas; // <-- সঠিক নেমস্পেস

use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class InquiryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Inquiry Details')
                    ->schema([
                        TextInput::make('name')->disabled(),
                        TextInput::make('phone')->disabled(),
                        TextInput::make('email')->disabled(),
                        TextInput::make('type')
                            ->formatStateUsing(fn (string $state): string => ucfirst($state))
                            ->disabled(),
                        
                        Textarea::make('message')
                            ->disabled()
                            ->rows(5)
                            ->columnSpanFull(),
                            
                        Toggle::make('is_read')
                            ->label('Mark as Read')
                            ->inline(false),
                    ])->columns(2),
            ]);
    }
}