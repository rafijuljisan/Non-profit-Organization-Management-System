<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase; // <-- এটি পরিবর্তন করা হয়েছে
use Tests\TestCase;
use App\Models\Donation;

class DonationTest extends TestCase
{
    // RefreshDatabase ব্যবহার করলে লারাভেল টেস্ট রান হওয়ার আগে অটোমেটিক ডাটাবেস মাইগ্রেট করে নিবে 
    // এবং টেস্ট শেষ হলে আবার সব ডিলিট করে ক্লিন করে দিবে।
    use RefreshDatabase; 

    /**
     * Test if the donation page loads successfully.
     */
    public function test_donation_page_loads_successfully(): void
    {
        // ডোনেশন পেজে (GET রিকোয়েস্ট) যাচ্ছে কিনা চেক করা
        $response = $this->get('/donate');

        // পেজটি ঠিকমতো লোড হলে স্ট্যাটাস 200 হবে এবং পেজে 'Make a Donation' লেখাটি থাকবে
        $response->assertStatus(200);
        $response->assertSee('Make a Donation');
    }

    /**
     * Test if a user can submit a donation successfully.
     */
    public function test_user_can_submit_donation(): void
    {
        // ফর্ম সাবমিট করার জন্য কিছু ডামি ডাটা
        $donationData = [
            'donor_name' => 'Test Donor',
            'donor_phone' => '01700000000',
            'amount' => 1000,
            'payment_method' => 'bkash',
            'receipt_no' => 'TEST_TRX_' . time(), // ইউনিক ট্রানজেকশন আইডি
        ];

        // POST রিকোয়েস্টের মাধ্যমে ডাটা সাবমিট করা
        $response = $this->post('/donate', $donationData);

        // সাবমিট হওয়ার পর সাকসেস মেসেজ নিয়ে পেজটি রিডাইরেক্ট (302) হয়েছে কিনা চেক করা
        $response->assertSessionHas('success');
        $response->assertStatus(302);

        // ডাটাবেসে ডাটাটি সেভ হয়েছে কিনা তা চেক করা
        $this->assertDatabaseHas('donations', [
            'donor_name' => 'Test Donor',
            'amount' => 1000,
            'status' => 'pending', 
        ]);
    }
}