<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\Designation;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Profile Photo')
                    ->schema([
                        FileUpload::make('photo')
                            ->image()
                            ->disk('public')
                            ->directory('members/photos')
                            ->imageEditor()
                            ->circleCropper()
                            ->maxSize(2048)
                            ->label('Member Photo')
                            ->columnSpanFull(),
                    ]),

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
                            ->label('NID Number')
                            ->unique(ignoreRecord: true),
                        TextInput::make('password')
                            ->password()
                            ->dehydrated(fn (?string $state) => filled($state))
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->columnSpanFull(),
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
                        Select::make('designation')
                            ->label('Designation')
                            ->options(
                                Designation::orderBy('priority')->pluck('name', 'name')
                            )
                            ->searchable()
                            ->placeholder('Select designation'),
                        TextInput::make('monthly_fee')
                            ->numeric()
                            ->default(0)
                            ->prefix('৳'),
                        Select::make('status')
                            ->options([
                                'pending'   => 'Pending',
                                'active'    => 'Active',
                                'suspended' => 'Suspended',
                                'inactive'  => 'Inactive',
                            ])
                            ->default('pending')
                            ->required(),
                        Toggle::make('show_in_volunteer')
                            ->label('Show in Volunteer Page')
                            ->default(true)
                            ->inline(false),
                    ])->columns(2),

            ]);
    }
}