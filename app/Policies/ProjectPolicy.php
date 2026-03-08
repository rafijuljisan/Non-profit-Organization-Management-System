<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    // সুপার এডমিন সব করতে পারবে, তাই তাকে বারবার চেক করার দরকার নেই
    public function before(User $user, string $ability): bool|null
    {
        if ($user->hasRole('super_admin')) {
            return true;
        }
        return null; 
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view_projects');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_projects');
    }

    public function update(User $user, Project $project): bool
    {
        return $user->hasPermissionTo('edit_projects');
    }

    public function delete(User $user, Project $project): bool
    {
        return $user->hasPermissionTo('delete_projects');
    }
}