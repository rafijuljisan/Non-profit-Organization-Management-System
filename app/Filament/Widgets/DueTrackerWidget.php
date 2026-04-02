<?php

namespace App\Filament\Widgets;

use App\Models\Subscription;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DueTrackerWidget extends BaseWidget
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
    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        // ডিসপ্লের জন্য মাসের নাম (March)
        $currentMonthName = now()->format('F'); 
        
        // ডাটাবেসে খোঁজার জন্য মাস এবং বছরের সংখ্যা (যেমন: 03 এবং 2026)
        $currentMonthNum = now()->format('m'); 
        $currentYear = now()->format('Y');      

        // 🚀 FIXED: ডাটাবেসের 'month' কলামটি যেহেতু Date (2026-03-01), তাই whereMonth ও whereYear ব্যবহার করা হলো
        $pendingMembersCount = Subscription::whereMonth('month', $currentMonthNum)
            ->whereYear('month', $currentYear)
            ->whereIn('status', ['unpaid', 'UNPAID', 'pending'])
            ->count();

        $totalDueAmount = Subscription::whereMonth('month', $currentMonthNum)
            ->whereYear('month', $currentYear)
            ->whereIn('status', ['unpaid', 'UNPAID', 'pending'])
            ->sum('amount');

        $totalCollectedAmount = Subscription::whereMonth('month', $currentMonthNum)
            ->whereYear('month', $currentYear)
            ->whereIn('status', ['paid', 'PAID', 'Paid'])
            ->sum('amount');

        return [
            Stat::make("Unpaid Members ({$currentMonthName})", $pendingMembersCount . ' জন')
                ->description('যাদের চাঁদা এখনো বকেয়া আছে')
                ->descriptionIcon('heroicon-m-users')
                ->color('danger'),

            Stat::make("Total Due Amount ({$currentMonthName})", '৳ ' . number_format($totalDueAmount))
                ->description('এই মাসের মোট পাওনা টাকা')
                ->descriptionIcon('heroicon-m-exclamation-circle')
                ->color('warning'),

            Stat::make("Collected So Far ({$currentMonthName})", '৳ ' . number_format($totalCollectedAmount))
                ->description('এই মাসে মোট আদায় হয়েছে')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success'),
        ];
    }
}