<?php
namespace App\Policies;
use App\Models\Project;
class ProjectPolicy extends BasePolicy
{
    protected static string $permission = 'project';
}