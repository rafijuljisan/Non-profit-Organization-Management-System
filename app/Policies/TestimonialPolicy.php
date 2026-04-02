<?php
namespace App\Policies;
use App\Models\Testimonial;
class TestimonialPolicy extends BasePolicy
{
    protected static string $permission = 'testimonial';
}