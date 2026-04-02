<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Support\Icons\Heroicon;

class BloodSecretaryOverview extends BaseWidget
{
    // 1. Restrict who can see this widget on the dashboard
    public static function canView(): bool
    {
        /** @var \Illuminate\Contracts\Auth\Guard $auth */
        $auth = auth();
        /** @var \App\Models\User|null $user */
        $user = $auth->user();
        
        return $user && ($user->hasRole('super_admin') || $user->hasRole('blood_secretary'));
    }

    // 2. Define the data cards
    protected function getStats(): array
    {
        // Get total active donors
        $totalDonors = User::where('is_blood_donor', true)
            ->where('status', 'active')
            ->count();

        // Calculate who is eligible (null date or > 90 days ago)
        $readyDonors = User::where('is_blood_donor', true)
            ->where('status', 'active')
            ->where(function ($query) {
                $query->whereNull('last_donation_date')
                      ->orWhereRaw('DATEDIFF(NOW(), last_donation_date) >= 90');
            })
            ->count();

        // Optional: Get recently donated (within last 30 days)
        $recentDonations = User::where('is_blood_donor', true)
            ->whereNotNull('last_donation_date')
            ->whereRaw('DATEDIFF(NOW(), last_donation_date) <= 30')
            ->count();

        return [
            Stat::make('Total Blood Donors', $totalDonors)
                ->description('All registered active donors')
                ->descriptionIcon(Heroicon::OutlinedUsers)
                ->color('primary'),

            Stat::make('Ready to Donate', $readyDonors)
                ->description('Eligible right now')
                ->descriptionIcon(Heroicon::OutlinedCheckCircle)
                ->color('success'),

            Stat::make('Recent Donations', $recentDonations)
                ->description('Donated in the last 30 days')
                ->descriptionIcon(Heroicon::OutlinedHeart)
                ->color('danger'),
        ];
    }
}