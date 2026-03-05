<?php

namespace App\Observers;

use App\Models\User;
use App\Models\District; // Import the District model
use Carbon\Carbon;       // Import Carbon for date/time operations

class UserObserver
{
    /**
     * Handle the User "creating" event.
     * This function is triggered right before a new user is saved to the database.
     */
    public function creating(User $user): void
    {
        // If the user has a district selected and no member_id is currently set
        if ($user->district_id && empty($user->member_id)) {
            
            $district = District::find($user->district_id);
            $year = Carbon::now()->format('Y');

            // Count existing members in this district for the current year to determine the serial number
            $count = User::where('district_id', $user->district_id)
                         ->whereYear('created_at', $year)
                         ->count();

            // Format the serial number to be 4 digits (e.g., 1 becomes '0001')
            $serialNo = str_pad($count + 1, 4, '0', STR_PAD_LEFT); 

            // Generate and assign the final member ID to the user model
            $user->member_id = "BD-" . strtoupper($district->code) . "-" . $year . "-" . $serialNo;
        }
    }

    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        // Code to run after the user is fully created
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}