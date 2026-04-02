<?php
namespace App\Policies;
use App\Models\Setting;
class SettingPolicy extends BasePolicy
{
    protected static string $permission = 'setting';
}