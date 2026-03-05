<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\Project;
use App\Models\Donation;
use App\Models\Subscription;
use App\Http\Controllers\DonationController;

Route::get('/', function () {
    // 1. Get total active members
    $totalMembers = User::where('status', 'active')->count();
    
    // 2. Get total number of projects
    $totalProjects = Project::count();
    
    // 3. Calculate total collected funds (Donations + Subscriptions)
    $totalDonations = Donation::where('status', 'completed')->sum('amount');
    $totalSubscriptions = Subscription::where('status', 'paid')->sum('amount');
    $totalFund = $totalDonations + $totalSubscriptions;

    // 4. Get latest 3 ongoing projects for display
    $ongoingProjects = Project::where('status', 'ongoing')->latest()->take(3)->get();

    return view('welcome', compact('totalMembers', 'totalProjects', 'totalFund', 'ongoingProjects'));
});
Route::get('/donate', [DonationController::class, 'index'])->name('donate.index');
Route::post('/donate', [DonationController::class, 'store'])
    ->name('donate.store')
    ->middleware('throttle:5,1'); // প্রতি মিনিটে ৫টি রিকোয়েস্ট

// পাবলিক প্রজেক্ট পেজ রাউট
Route::get('/projects', function () {
    // N+1 Query Problem সলভ করার জন্য with('district') (Eager Loading) ব্যবহার করা হয়েছে
    $ongoingProjects = Project::with('district')->where('status', 'ongoing')->latest()->get();
    $completedProjects = Project::with('district')->where('status', 'completed')->latest()->get();

    return view('projects', compact('ongoingProjects', 'completedProjects'));
})->name('projects.index');