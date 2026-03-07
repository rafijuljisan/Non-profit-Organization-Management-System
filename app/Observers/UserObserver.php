<?php

namespace App\Observers;

use App\Models\User;
use App\Models\District; // Import the District model
use App\Models\Donation; // <-- 🚀 এই লাইনটি যুক্ত করা হয়েছে
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
        // 🚀 অটো-সিঙ্ক লজিক: 
        // নতুন অ্যাকাউন্ট খোলা ইউজারের ফোন নাম্বারের সাথে আগের কোনো গেস্ট ডোনেশনের ফোন নাম্বার মিলে গেলে,
        // সেই ডোনেশনগুলোতে এই ইউজারের আইডি (user_id) বসিয়ে দেওয়া হবে।
        
        Donation::whereNull('user_id')
            ->where('donor_phone', $user->phone)
            ->update(['user_id' => $user->id]);
            
        // (যদি আপনার ডোনেশন ফর্মে ইমেইল নেওয়ার অপশন থাকে, তাহলে নিচের লাইনটিও আনকমেন্ট করতে পারেন)
        /*
        if ($user->email) {
            Donation::whereNull('user_id')
                ->where('donor_email', $user->email)
                ->update(['user_id' => $user->id]);
        }
        */
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