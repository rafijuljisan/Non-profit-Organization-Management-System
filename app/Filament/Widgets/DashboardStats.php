<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Subscription;
use App\Models\Donation;
use App\Models\Expense;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStats extends BaseWidget
{
    /**
     * Set the sorting order of the widget on the dashboard
     */
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        // 1. Calculate Total Active Members
        $totalMembers = User::where('status', 'active')->count();
        
        // 2. Calculate Total Collected Funds (Subscriptions + Donations)
        $totalSubscriptions = Subscription::where('status', 'paid')->sum('amount');
        $totalDonations = Donation::where('status', 'completed')->sum('amount');
        $totalFunds = $totalSubscriptions + $totalDonations;
        
        // 3. Count Pending Expenses that need approval
        $pendingExpenses = Expense::where('status', 'pending')->count();

        return [
            Stat::make('Active Members', $totalMembers)
                ->description('Total active members in the system')
                ->descriptionIcon('heroicon-m-users')
                ->color('success')
                ->chart([7, 2, 10, 3, 15, 4, 17]), // A dummy chart line for visual appeal
                
            Stat::make('Total Collected Funds', '৳ ' . number_format($totalFunds, 2))
                ->description('From Subscriptions & Donations')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('primary')
                ->chart([10, 15, 12, 20, 18, 25, 30]),
                
            Stat::make('Pending Expenses', $pendingExpenses)
                ->description('Expenses waiting for approval')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
        ];
    }
}