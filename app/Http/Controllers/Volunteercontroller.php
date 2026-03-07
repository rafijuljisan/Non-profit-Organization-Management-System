<?php

// Add this method to your existing web controller, or create a new VolunteerController
// Route should be: Route::get('/volunteer', [VolunteerController::class, 'index'])->name('volunteer');
// (Change from Route::view to Route::get to pass data)

namespace App\Http\Controllers;

use App\Models\Designation;
use App\Models\User;
use Illuminate\Http\Request;

class VolunteerController extends Controller
{
    public function index()
    {
        // Get all designations ordered by admin-set priority
        $designations = Designation::orderBy('priority', 'asc')->get();

        // Get active members who should be shown, grouped by designation priority
        $volunteers = User::with('district')
            ->where('status', 'active')
            ->where('show_in_volunteer', true)
            ->whereNotNull('designation')
            ->get()
            ->sortBy(function ($user) use ($designations) {
                $designation = $designations->firstWhere('name', $user->designation);
                return $designation ? $designation->priority : 999;
            })
            ->groupBy('designation');

        // Members without designation (shown last)
        $generalMembers = User::with('district')
            ->where('status', 'active')
            ->where('show_in_volunteer', true)
            ->whereNull('designation')
            ->get();

        return view('pages.volunteer', compact('volunteers', 'designations', 'generalMembers'));
    }

    // Handle volunteer inquiry form submission
    public function store(Request $request)
    {
        // Reuse inquiry store — just redirect to inquiry.store
        return redirect()->route('inquiry.store');
    }
}