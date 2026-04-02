<?php
namespace App\Policies;
use App\Models\Designation;
class DesignationPolicy extends BasePolicy
{
    protected static string $permission = 'designation';
}