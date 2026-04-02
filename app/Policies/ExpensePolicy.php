<?php
namespace App\Policies;
use App\Models\Expense;
class ExpensePolicy extends BasePolicy
{
    protected static string $permission = 'expense';
}