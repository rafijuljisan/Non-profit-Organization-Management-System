<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Subscription;
use App\Models\Donation;
use App\Models\Expense;
use App\Models\Project;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class DashboardStats extends BaseWidget
{
    public static function canView(): bool
    {
        /** @var \Illuminate\Contracts\Auth\Guard $auth */
        $auth = auth();
        /** @var \App\Models\User|null $user */
        $user = $auth->user();
        
        // Only super_admin or admin can see this widget. 
        // The blood_secretary will be blocked.
        return $user && $user->hasAnyRole(['super_admin', 'admin']);
    }
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        $isDistrictAdmin = $user !== null && $user->hasRole('District Admin');
        $districtId = $user->district_id ?? null;

        // 1. Base Queries
        $membersQuery = User::where('status', 'active');
        $subscriptionsQuery = Subscription::where('status', 'paid');
        $donationsQuery = Donation::where('status', 'completed');
        $expensesQuery = Expense::where('status', 'approved');        // ← fixed
        $pendingExpensesQuery = Expense::where('status', 'pending');
        $projectsQuery = Project::query();

        // 2. Apply District Filter
        if ($isDistrictAdmin && $districtId) {
            $membersQuery->where('district_id', $districtId);

            $subscriptionsQuery->whereHas('user', function ($query) use ($districtId) {
                $query->where('district_id', $districtId);
            });

            $donationsQuery->where('district_id', $districtId);
            $expensesQuery->where('district_id', $districtId);
            $pendingExpensesQuery->where('district_id', $districtId);
            $projectsQuery->where('district_id', $districtId);
        }

        // 3. Calculate Totals
        $totalMembers = $membersQuery->count();
        $totalSubscriptions = $subscriptionsQuery->sum('amount');
        $totalDonations = $donationsQuery->sum('amount');
        $totalFunds = $totalSubscriptions + $totalDonations;

        $totalCompletedExpenses = $expensesQuery->sum('amount');
        $pendingExpensesCount = $pendingExpensesQuery->count();
        $netBalance = $totalFunds - $totalCompletedExpenses;
        $totalProjects = $projectsQuery->count();

        return [
            Stat::make('Net Balance (বর্তমান ফান্ড)', '৳ ' . number_format($netBalance, 2))
                ->description($isDistrictAdmin ? 'Your District Balance' : 'Total System Balance')
                ->descriptionIcon('heroicon-m-wallet')
                ->color($netBalance >= 0 ? 'success' : 'danger')
                ->chart([10, 20, 15, 30, 25, 45, 50]),

            Stat::make('Total Collected Funds', '৳ ' . number_format($totalFunds, 2))
                ->description('Subscriptions & Donations')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('primary')
                ->chart([10, 15, 12, 20, 18, 25, 30]),

            Stat::make('Total Expenses', '৳ ' . number_format($totalCompletedExpenses, 2))
                ->description('Approved Expenses')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('danger')
                ->chart([5, 10, 8, 15, 12, 20, 18]),

            Stat::make('Active Members', $totalMembers)
                ->description($isDistrictAdmin ? 'Members in your district' : 'Total members')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),

            Stat::make('Total Projects', $totalProjects)
                ->description('Ongoing and completed')
                ->descriptionIcon('heroicon-m-briefcase')
                ->color('info'),

            Stat::make('Pending Expenses', $pendingExpensesCount)
                ->description('Waiting for approval')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
        ];
    }
}