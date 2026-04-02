<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class BasePolicy
{
    /** @var string Override in each child policy e.g. 'donation' */
    protected static string $permission = '';

    public function before(User $user, string $ability): bool|null
    {
        if ($user->hasRole('super_admin')) {
            return true;
        }
        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(static::$permission . '_view');
    }

    public function view(User $user, Model $model): bool
    {
        return $user->hasPermissionTo(static::$permission . '_view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo(static::$permission . '_create');
    }

    public function update(User $user, Model $model): bool
    {
        return $user->hasPermissionTo(static::$permission . '_edit');
    }

    public function delete(User $user, Model $model): bool
    {
        return $user->hasPermissionTo(static::$permission . '_delete');
    }

    public function deleteAny(User $user): bool
    {
        return $user->hasPermissionTo(static::$permission . '_delete');
    }

    public function restore(User $user, Model $model): bool
    {
        return $user->hasPermissionTo(static::$permission . '_delete');
    }

    public function forceDelete(User $user, Model $model): bool
    {
        return $user->hasRole('super_admin');
    }
}