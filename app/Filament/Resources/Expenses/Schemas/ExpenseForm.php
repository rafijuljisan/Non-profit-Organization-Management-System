<?php

namespace App\Filament\Resources\Expenses\Schemas;

use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth; // <-- Auth Facade ইমপোর্ট করা হলো

class ExpenseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Expense Details')
                    ->schema([
                        TextInput::make('category')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g., Office Rent, Event Cost'),
                        Select::make('project_id')
                            ->relationship('project', 'name')
                            ->searchable()
                            ->preload()
                            ->label('Related Project (Optional)'),
                        TextInput::make('amount')
                            ->required()
                            ->numeric()
                            ->prefix('৳'),
                        Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'approved' => 'Approved',
                                'rejected' => 'Rejected',
                            ])
                            ->default('pending')
                            ->required(),
                    ])->columns(2),

                Section::make('Approval & Documents')
                    ->schema([
                        Select::make('created_by')
                            ->relationship('creator', 'name')
                            ->default(fn () => Auth::id()) // <-- এখানে Auth::id() ব্যবহার করা হলো
                            ->required()
                            ->label('Created By'),
                        Select::make('approved_by')
                            ->relationship('approver', 'name')
                            ->searchable()
                            ->preload()
                            ->label('Approved By'),
                        FileUpload::make('voucher_upload')
                            ->directory('vouchers')
                            ->image()
                            ->maxSize(2048)
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }
}