<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;

// Models
use App\Models\User;
use App\Models\Donation;
use App\Models\Project;
use App\Models\Expense;
use App\Models\Gallery;
use App\Models\Inquiry;
use App\Models\Page;
use App\Models\Setting;
use App\Models\Subscription;
use App\Models\Testimonial;
use App\Models\Designation;
use App\Models\District;
use App\Models\Upazila;
use App\Models\Activity;

// Policies
use App\Policies\UserPolicy;
use App\Policies\DonationPolicy;
use App\Policies\ProjectPolicy;
use App\Policies\ExpensePolicy;
use App\Policies\GalleryPolicy;
use App\Policies\InquiryPolicy;
use App\Policies\PagePolicy;
use App\Policies\SettingPolicy;
use App\Policies\SubscriptionPolicy;
use App\Policies\TestimonialPolicy;
use App\Policies\DesignationPolicy;
use App\Policies\DistrictPolicy;
use App\Policies\UpazilaPolicy;
use App\Policies\ActivityPolicy;
use App\Policies\BloodDonorPolicy;

// Observers
use App\Observers\UserObserver;
use App\Observers\DonationObserver;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // ── Policy Registrations ──
        Gate::policy(User::class,         UserPolicy::class);
        Gate::policy(Donation::class,     DonationPolicy::class);
        Gate::policy(Project::class,      ProjectPolicy::class);
        Gate::policy(Expense::class,      ExpensePolicy::class);
        Gate::policy(Gallery::class,      GalleryPolicy::class);
        Gate::policy(Inquiry::class,      InquiryPolicy::class);
        Gate::policy(Page::class,         PagePolicy::class);
        Gate::policy(Setting::class,      SettingPolicy::class);
        Gate::policy(Subscription::class, SubscriptionPolicy::class);
        Gate::policy(Testimonial::class,  TestimonialPolicy::class);
        Gate::policy(Designation::class,  DesignationPolicy::class);
        Gate::policy(District::class,     DistrictPolicy::class);
        Gate::policy(Upazila::class,      UpazilaPolicy::class);
        Gate::policy(Activity::class,     ActivityPolicy::class);

        // Add this inside boot() with other Gate::policy calls
        // Note: BloodDonorResource uses User model but with its own policy
        // We register it via canViewAny/canEdit directly in the resource
        // so no Gate::policy needed here — it's self-contained

        // ── Global Settings ──
        if (Schema::hasTable('settings')) {
            $settings = Setting::firstOrCreate(['id' => 1], [
                'site_name'    => 'NGO Foundation',
                'email'        => 'info@ngofoundation.com',
                'phone'        => '+880 1234 567890',
                'address'      => '123 Charity Lane, Dhaka, Bangladesh',
                'about_footer' => 'Empowering communities and building a better future for everyone.',
            ]);
            View::share('settings', $settings);
        }

        // ── Observers ──
        User::observe(UserObserver::class);
        Donation::observe(DonationObserver::class);
    }
}