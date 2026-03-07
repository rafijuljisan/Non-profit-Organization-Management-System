<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donation;
use App\Models\Subscription;
use App\Models\Expense;

class FrontendController extends Controller
{
    public function transparency()
    {
        // ১. আয়-ব্যয়ের হিসাব
        $totalDonations = Donation::where('status', 'completed')->sum('amount');
        $totalSubscriptions = Subscription::where('status', 'paid')->sum('amount');
        
        $totalIncome = $totalDonations + $totalSubscriptions;
        $totalExpense = Expense::sum('amount'); 
        $currentBalance = $totalIncome - $totalExpense;

        // ২. লাইভ লেজার (সর্বশেষ ৫টি ট্রানজেকশন)
        $recentDonations = Donation::where('status', 'completed')->latest()->take(5)->get();
        $recentExpenses = Expense::latest()->take(5)->get();

        return view('pages.transparency', compact('totalIncome', 'totalExpense', 'currentBalance', 'recentDonations', 'recentExpenses'));
    }
}