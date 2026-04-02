<?php
namespace App\Policies;
use App\Models\Page;
class PagePolicy extends BasePolicy
{
    protected static string $permission = 'page';
}