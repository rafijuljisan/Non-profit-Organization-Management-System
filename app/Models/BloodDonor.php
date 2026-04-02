<?php

namespace App\Models;

class BloodDonor extends User
{
    // Tell Laravel this model still uses the users table
    protected $table = 'users';
    // Add this inside App\Models\BloodDonor
    public function donationHistories()
    {
        return $this->hasMany(\App\Models\BloodDonationHistory::class, 'user_id');
    }
}