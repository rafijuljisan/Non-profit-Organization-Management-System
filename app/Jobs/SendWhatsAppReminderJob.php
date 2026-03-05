<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Subscription;

class SendWhatsAppReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Find users who are active but have 'unpaid' subscriptions
        $usersWithDues = User::where('status', 'active')
            ->whereHas('subscriptions', function ($query) {
                $query->where('status', 'unpaid');
            })
            ->with(['subscriptions' => function ($query) {
                $query->where('status', 'unpaid');
            }])
            ->get();

        foreach ($usersWithDues as $user) {
            // Count total unpaid months and calculate total amount
            $unpaidMonths = $user->subscriptions->count();
            $totalDue = $user->subscriptions->sum('amount');

            // Format the message in Bengali
            $message = "প্রিয় {$user->name},\n\nএটি এনজিও ফাউন্ডেশনের পক্ষ থেকে একটি বিনীত রিমাইন্ডার। আপনার {$unpaidMonths} মাসের চাঁদা বকেয়া রয়েছে, যার সর্বমোট পরিমাণ ৳{$totalDue}।\n\nঅনুগ্রহ করে আপনার সদস্যপদ সচল রাখতে বকেয়া চাঁদা পরিশোধ করুন।\n\nআমাদের সাথে থাকার জন্য ধন্যবাদ!";

            // ---------------------------------------------------------
            // 🚀 WHATSAPP API INTEGRATION (Example using a generic API)
            // ---------------------------------------------------------
            // Replace this block with your actual WhatsApp Gateway Provider 
            // (e.g., Twilio, Meta API, UltraMsg, Waboxapp)
            
            try {
                /*
                // Example API Request (Uncomment and configure when you have API keys)
                $response = Http::post('https://api.your-whatsapp-provider.com/send', [
                    'token' => env('WHATSAPP_API_TOKEN'),
                    'to' => $user->phone, // Make sure phone numbers have country code e.g., +880
                    'body' => $message
                ]);
                */

                // For testing purposes, we will just log the message to our laravel.log file
                Log::info("WhatsApp Reminder sent to {$user->phone}:\n{$message}");

            } catch (\Exception $e) {
                Log::error("Failed to send WhatsApp to {$user->phone}. Error: " . $e->getMessage());
            }
        }
    }
}