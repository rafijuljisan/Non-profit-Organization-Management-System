<?php
namespace App\Policies;
use App\Models\Inquiry;
class InquiryPolicy extends BasePolicy
{
    protected static string $permission = 'inquiry';
}