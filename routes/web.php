<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\Project;
use App\Models\Donation;
use App\Models\Subscription;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\DonorAuthController;
use App\Http\Controllers\VolunteerController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\BloodBankController;


// ✅ This calls FrontendController@index which passes $testimonials
Route::get('/', [FrontendController::class, 'index'])->name('home');
// 🟢 Guest Routes (লগইন ছাড়া দেখা যাবে)
Route::middleware('guest')->group(function () {
    Route::get('/login', [DonorAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [DonorAuthController::class, 'login'])->name('login.post');
    Route::get('/register', [DonorAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [DonorAuthController::class, 'register'])->name('register.post');
});

// 🟢 Auth Routes (লগইন করার পর দেখা যাবে)
Route::middleware('auth')->group(function () {
    Route::get('/donor/dashboard', [DonorAuthController::class, 'dashboard'])->name('donor.dashboard');
    
    // 🚀 NEW: ব্লাড প্রোফাইল আপডেটের রাউট
    Route::post('/donor/blood-profile', [DonorAuthController::class, 'updateBloodProfile'])->name('donor.update_blood_profile');
    
    Route::post('/logout', [DonorAuthController::class, 'logout'])->name('logout');
});
Route::middleware(['auth'])->group(function () {
    // মেম্বার আইডি কার্ড দেখার রাউট
    Route::get('/member/{id}/id-card', [FrontendController::class, 'idCard'])->name('member.id-card');
});
// --- Existing Routes ---
/*Route::get('/', function () {
    $totalMembers = User::where('status', 'active')->count();
    $totalProjects = Project::count();
    $totalDonations = Donation::where('status', 'completed')->sum('amount');
    $totalSubscriptions = Subscription::where('status', 'paid')->sum('amount');
    $totalFund = $totalDonations + $totalSubscriptions;
    $ongoingProjects = Project::where('status', 'ongoing')->latest()->take(3)->get();

    return view('welcome', compact('totalMembers', 'totalProjects', 'totalFund', 'ongoingProjects'));
})->name('home');*/

Route::get('/projects', function () {
    $ongoingProjects = Project::with('district')->where('status', 'ongoing')->latest()->get();
    $completedProjects = Project::with('district')->where('status', 'completed')->latest()->get();
    return view('projects', compact('ongoingProjects', 'completedProjects'));
})->name('projects.index');
Route::get('/projects/{id}/details', [FrontendController::class, 'projectDetails'])->name('projects.details');

Route::get('/donate', [DonationController::class, 'index'])->name('donate.index');
Route::post('/donate', [DonationController::class, 'store'])->name('donate.store')->middleware('throttle:5,1');
Route::get('/donate/receipt/{id}', [DonationController::class, 'downloadReceipt'])->name('donate.receipt');
Route::get('/find-receipts', [DonationController::class, 'searchReceipt'])->name('receipt.search');

// --- 🟢 NEW STATIC ROUTES ---
Route::view('/about', 'pages.about')->name('about');
Route::view('/mission-vision', 'pages.mission')->name('mission');
Route::view('/financial-transparency', 'pages.transparency')->name('transparency');
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery');

// Volunteer & Contact (We will create controllers for these later so users can submit forms)
Route::get('/volunteer', [VolunteerController::class, 'index'])->name('volunteer'); // এই পেজে ভলান্টিয়ারদের তালিকা দেখানো হবে
Route::view('/contact', 'pages.contact')->name('contact');

// ফর্ম সাবমিট করার রাউট (Spam কমানোর জন্য রেট লিমিট দেওয়া হলো)
Route::post('/inquiry/submit', [InquiryController::class, 'store'])
    ->name('inquiry.store')
    ->middleware('throttle:3,1'); // প্রতি মিনিটে সর্বোচ্চ ৩টি মেসেজ

Route::get('/financial-transparency', [FrontendController::class, 'transparency'])->name('transparency');

// Blood Bank Route
Route::get('/blood-bank', [BloodBankController::class, 'index'])->name('blood_bank.index');
Route::get('/get-thanas/{district_id}', [App\Http\Controllers\BloodBankController::class, 'getThanas']);