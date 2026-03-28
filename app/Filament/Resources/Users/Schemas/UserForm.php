<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\Designation;
use App\Models\Upazila;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

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
                            ->label('Specific Area / Union')
                            ->maxLength(255),

                        TextInput::make('password')
                            ->password()
                            ->dehydrated(fn(?string $state) => filled($state))
                            ->required(fn(string $operation): bool => $operation === 'create')
                            ->columnSpanFull(),

                        Select::make('roles')
                            ->relationship('roles', 'name', modifyQueryUsing: function (Builder $query) {
                                /** @var \App\Models\User|null $user */
                                $user = Auth::user();
                                if ($user !== null && !$user->hasRole('super_admin')) {
                                    $query->where('name', '!=', 'super_admin');
                                }
                                return $query;
                            })
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->label('Assign Roles'),
                    ])->columns(2),

                // 🩸 Blood Donation Section
                Section::make('Blood Donation Details')
                    ->schema([
                        Toggle::make('is_blood_donor')
                            ->label('Is a Blood Donor?')
                            ->inline(false)
                            ->default(false),
                        Select::make('blood_group')
                            ->options([
                                'A+' => 'A+', 'A-' => 'A-', 'B+' => 'B+', 'B-' => 'B-',
                                'AB+' => 'AB+', 'AB-' => 'AB-', 'O+' => 'O+', 'O-' => 'O-',
                            ])
                            ->placeholder('Select blood group'),
                        DatePicker::make('last_donation_date')
                            ->label('Last Donation Date')
                            ->maxDate(now())
                            ->displayFormat('d/m/Y'),
                    ])->columns(3),

                Section::make('Membership Details')
                    ->schema([
                        TextInput::make('member_id')
                            ->disabled()
                            ->dehydrated(false)
                            ->placeholder('Auto Generated on Save'),
                        Select::make('designation')
                            ->label('Designation')
                            ->options(Designation::orderBy('priority')->pluck('name', 'name'))
                            ->searchable()
                            ->placeholder('Select designation'),
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
                        Toggle::make('show_in_volunteer')
                            ->label('Show in Volunteer Page')
                            ->default(true)
                            ->inline(false),
                    ])->columns(2),

            ]);
    }
}