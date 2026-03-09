<?php

namespace App\Filament\Resources\Expenses\Schemas;

use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

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
                                'pending' => 'Pending Approval (অপেক্ষমান)',
                                'completed' => 'Approved & Paid (অনুমোদিত)',
                                'rejected' => 'Rejected (বাতিল)',
                            ])
                            ->default('pending')
                            // 🚀 FIXED: closure ব্যবহার করে User মডেল টাইপ-হিন্ট করে দেওয়া হলো
                            ->disabled(function () {
                                /** @var \App\Models\User|null $user */
                                $user = Auth::user();
                                return $user === null || !$user->hasRole('super_admin');
                            }) 
                            ->dehydrated() 
                            ->required(),
                    ])->columns(2),

                Section::make('Approval & Documents')
                    ->schema([
                        Select::make('created_by')
                            ->relationship('creator', 'name')
                            ->default(fn () => Auth::id())
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