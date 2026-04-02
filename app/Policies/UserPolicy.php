<?php
namespace App\Policies;
use App\Models\User;
class UserPolicy extends BasePolicy
{
    protected static string $permission = 'user';
}