<?php
namespace App\Policies;
use App\Models\Subscription;
class SubscriptionPolicy extends BasePolicy
{
    protected static string $permission = 'subscription';
}