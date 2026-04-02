<?php
namespace App\Policies;
use App\Models\District;
class DistrictPolicy extends BasePolicy
{
    protected static string $permission = 'district';
}