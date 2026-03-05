<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donation;
use App\Models\Project;

class DonationController extends Controller
{
    // ডোনেশন পেজ দেখানোর জন্য
    public function index()
    {
        // শুধু চলমান প্রজেক্টগুলো ড্রপডাউনে দেখানোর জন্য আনছি
        $projects = Project::where('status', 'ongoing')->get();
        return view('donate', compact('projects'));
    }

    // ডোনেশন ফর্ম সাবমিট করার জন্য
    public function store(Request $request)
    {
        // ফর্মের ডাটা ভ্যালিডেশন
        $request->validate([
            'donor_name' => 'required|string|max:255',
            'donor_phone' => 'required|string|max:20',
            'amount' => 'required|numeric|min:10',
            'project_id' => 'nullable|exists:projects,id',
            'payment_method' => 'required|string',
            'receipt_no' => 'required|string|unique:donations,receipt_no',
        ]);

        // ডাটাবেসে সেভ করা (স্ট্যাটাস pending থাকবে)
        Donation::create([
            'donor_name' => $request->donor_name,
            'donor_phone' => $request->donor_phone,
            'amount' => $request->amount,
            'project_id' => $request->project_id,
            'payment_method' => $request->payment_method,
            'receipt_no' => $request->receipt_no,
            'status' => 'pending', 
        ]);

        // সাকসেস মেসেজ সহ আগের পেজে ফেরত পাঠানো
        return redirect()->back()->with('success', 'Thank you for your donation! Your transaction is pending verification.');
    }
}