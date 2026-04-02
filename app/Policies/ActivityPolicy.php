<?php

namespace App\Policies;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class ActivityPolicy extends BasePolicy
{
    protected static string $permission = 'activity';

    // Logs are read-only — nobody creates or edits
    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Model $model): bool
    {
        return false;
    }
}