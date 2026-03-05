<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Schemas\Components\Section;  // ✅ v4 namespace
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Personal Information')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true),
                        TextInput::make('phone')
                            ->tel()
                            ->unique(ignoreRecord: true),
                        TextInput::make('nid_number')
                            ->unique(ignoreRecord: true),
                        TextInput::make('password')
                            ->password()
                            ->dehydrated(fn (?string $state) => filled($state))
                            ->required(fn (string $operation): bool => $operation === 'create'),
                    ])->columns(2),

                Section::make('Membership Details')
                    ->schema([
                        TextInput::make('member_id')
                            ->disabled()
                            ->dehydrated(false)
                            ->placeholder('Auto Generated on Save'),
                        Select::make('district_id')
                            ->relationship('district', 'name')
                            ->searchable()
                            ->preload(),
                        TextInput::make('monthly_fee')
                            ->numeric()
                            ->default(0)
                            ->prefix('৳'),
                        Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'active' => 'Active',
                                'suspended' => 'Suspended',
                                'inactive' => 'Inactive',
                            ])
                            ->default('pending')
                            ->required(),
                    ])->columns(2),
            ]);
    }
}