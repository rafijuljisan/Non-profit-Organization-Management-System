<?php

namespace App\Filament\Resources\Expenses;

use App\Filament\Resources\Expenses\Pages\CreateExpense;
use App\Filament\Resources\Expenses\Pages\EditExpense;
use App\Filament\Resources\Expenses\Pages\ListExpenses;
use App\Filament\Resources\Expenses\Schemas\ExpenseForm;
use App\Filament\Resources\Expenses\Tables\ExpensesTable;
use App\Models\Expense;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ExpenseResource extends Resource
{
    protected static ?string $model = Expense::class;
    
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCreditCard;
    protected static string|UnitEnum|null $navigationGroup = 'Financial Management';

    public static function form(Schema $schema): Schema
    {
        return ExpenseForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ExpensesTable::configure($table);
    }

    // 🛡️ Financial Security: Disable deleting a single expense
    public static function canDelete(Model $record): bool 
    {
        return false;
    }

    // 🛡️ Financial Security: Disable bulk deleting expenses
    public static function canDeleteAny(): bool 
    {
        return false;
    }
    
    public static function getPages(): array
    {
        return [
            'index' => ListExpenses::route('/'),
            'create' => CreateExpense::route('/create'),
            'edit' => EditExpense::route('/{record}/edit'),
        ];
    }
}