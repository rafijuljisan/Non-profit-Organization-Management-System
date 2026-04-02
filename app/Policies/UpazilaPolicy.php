<?php
namespace App\Policies;
use App\Models\Upazila;
class UpazilaPolicy extends BasePolicy
{
    protected static string $permission = 'upazila';
}