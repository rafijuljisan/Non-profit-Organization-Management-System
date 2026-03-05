<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\Project;
use App\Models\Donation;
use App\Models\Subscription;
use App\Http\Controllers\DonationController;

// --- Existing Routes ---
Route::get('/', function () {
    $totalMembers = User::where('status', 'active')->count();
    $totalProjects = Project::count();
    $totalDonations = Donation::where('status', 'completed')->sum('amount');
    $totalSubscriptions = Subscription::where('status', 'paid')->sum('amount');
    $totalFund = $totalDonations + $totalSubscriptions;
    $ongoingProjects = Project::where('status', 'ongoing')->latest()->take(3)->get();

    return view('welcome', compact('totalMembers', 'totalProjects', 'totalFund', 'ongoingProjects'));
})->name('home');

Route::get('/projects', function () {
    $ongoingProjects = Project::with('district')->where('status', 'ongoing')->latest()->get();
    $completedProjects = Project::with('district')->where('status', 'completed')->latest()->get();
    return view('projects', compact('ongoingProjects', 'completedProjects'));
})->name('projects.index');

Route::get('/donate', [DonationController::class, 'index'])->name('donate.index');
Route::post('/donate', [DonationController::class, 'store'])->name('donate.store')->middleware('throttle:5,1');

// --- 🟢 NEW STATIC ROUTES ---
Route::view('/about', 'pages.about')->name('about');
Route::view('/mission-vision', 'pages.mission')->name('mission');
Route::view('/financial-transparency', 'pages.transparency')->name('transparency');

// Volunteer & Contact (We will create controllers for these later so users can submit forms)
Route::view('/volunteer', 'pages.volunteer')->name('volunteer');
Route::view('/contact', 'pages.contact')->name('contact');