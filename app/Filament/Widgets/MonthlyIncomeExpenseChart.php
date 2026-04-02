<?php

namespace App\Filament\Widgets;

use App\Models\Donation;
use App\Models\Subscription;
use App\Models\Expense;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class MonthlyIncomeExpenseChart extends ChartWidget
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
    protected ?string $heading = 'Monthly Income vs Expense (Current Year)';
    protected static ?int $sort = 2;

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        $isDistrictAdmin = $user !== null && $user->hasRole('District Admin');
        $districtId = $user->district_id ?? null;

        $incomes = [];
        $expenses = [];
        $labels = [];
        $currentYear = date('Y');

        for ($month = 1; $month <= 12; $month++) {
            $labels[] = Carbon::create($currentYear, $month, 1)->format('M');

            $donationsQuery = Donation::where('status', 'completed')
                ->whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $month);

            $subscriptionsQuery = Subscription::where('status', 'paid')
                ->whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $month);

            $expensesQuery = Expense::where('status', 'approved')  // ← fixed
                ->whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $month);

            if ($isDistrictAdmin && $districtId) {
                $donationsQuery->where('district_id', $districtId);

                $subscriptionsQuery->whereHas('user', function ($query) use ($districtId) {
                    $query->where('district_id', $districtId);
                });

                $expensesQuery->where('district_id', $districtId);
            }

            $incomes[] = $donationsQuery->sum('amount') + $subscriptionsQuery->sum('amount');
            $expenses[] = $expensesQuery->sum('amount');
        }

        return [
            'datasets' => [
                [
                    'label' => 'Income (আয়)',
                    'data' => $incomes,
                    'backgroundColor' => '#22c55e',
                    'borderRadius' => 4,
                ],
                [
                    'label' => 'Expense (ব্যয়)',
                    'data' => $expenses,
                    'backgroundColor' => '#ef4444',
                    'borderRadius' => 4,
                ],
            ],
            'labels' => $labels,
        ];
    }
}