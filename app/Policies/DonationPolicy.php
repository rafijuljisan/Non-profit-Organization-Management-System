<?php
namespace App\Policies;
use App\Models\Donation;
class DonationPolicy extends BasePolicy
{
    protected static string $permission = 'donation';
}