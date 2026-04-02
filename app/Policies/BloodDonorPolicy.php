<?php

namespace App\Policies;

use App\Models\User;
use App\Models\BloodDonor;

class BloodDonorPolicy
{
    public function before(User $user, string $ability): bool|null
    {
        // Spatie requires an array or hasAnyRole for checking multiple roles
        if ($user->hasAnyRole(['super_admin', 'admin'])) return true;
        
        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasRole('blood_secretary')
            || $user->hasPermissionTo('blood_donor_manage');
    }

    public function view(User $user, BloodDonor $bloodDonor): bool
    {
        return $user->hasRole('blood_secretary')
            || $user->hasPermissionTo('blood_donor_manage');
    }

    public function update(User $user, BloodDonor $bloodDonor): bool
    {
        return $user->hasRole('blood_secretary')
            || $user->hasPermissionTo('blood_donor_manage');
    }

    public function create(User $user): bool 
    { 
        return $user->hasRole('blood_secretary')
            || $user->hasPermissionTo('blood_donor_manage');
    }

    public function delete(User $user, BloodDonor $bloodDonor): bool 
    { 
        return false; // Leave as false if you don't want them deleting donors
    }

    public function deleteAny(User $user): bool 
    { 
        return false; 
    }
}