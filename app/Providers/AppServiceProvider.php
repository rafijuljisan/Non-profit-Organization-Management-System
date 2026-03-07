<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\User;               // <-- ইমপোর্ট করুন
use App\Observers\UserObserver;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use App\Models\Setting;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (Schema::hasTable('settings')) {
            // প্রথম রো (id 1) না থাকলে ডিফল্ট ডাটা দিয়ে তৈরি করে নেবে
            $settings = Setting::firstOrCreate(['id' => 1], [
                'site_name' => 'NGO Foundation',
                'email' => 'info@ngofoundation.com',
                'phone' => '+880 1234 567890',
                'address' => '123 Charity Lane, Dhaka, Bangladesh',
                'about_footer' => 'Empowering communities and building a better future for everyone.',
            ]);
            
            // $settings ভেরিয়েবলটি ওয়েবসাইটের সব Blade ফাইলে গ্লোবাল করে দেওয়া হলো!
            View::share('settings', $settings);
        }
        User::observe(UserObserver::class);
    }
}
