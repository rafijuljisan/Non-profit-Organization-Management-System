<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Donation;
use App\Models\Project;
use Barryvdh\DomPDF\Facade\Pdf;

class DonationController extends Controller
{
    // ডোনেশন পেজ দেখানোর জন্য
    public function index()
    {
        $projects = Project::where('status', 'ongoing')->get();
        return view('donate', compact('projects'));
    }

    // ডোনেশন ফর্ম সাবমিট করার জন্য
    public function store(Request $request)
    {
        $request->validate([
            'donor_name' => 'required|string|max:255',
            'donor_phone' => 'required|string|max:20',
            'amount' => 'required|numeric|min:10',
            'project_id' => 'nullable|exists:projects,id',
            'payment_method' => 'required|string',
            'receipt_no' => 'required|string|unique:donations,receipt_no',
        ]);

        // 🚀 ফিক্স: Donation ডাটাটি একটি ভেরিয়েবলে ($donation) রাখা হলো, যাতে এর ID পাওয়া যায়
        $donation = Donation::create([
            'user_id' => Auth::id(),
            'donor_name' => $request->donor_name,
            'donor_phone' => $request->donor_phone,
            'amount' => $request->amount,
            'project_id' => $request->project_id,
            'payment_method' => $request->payment_method,
            'receipt_no' => $request->receipt_no,
            'status' => 'pending', 
        ]);

        // 🚀 ফিক্স: সাকসেস মেসেজের সাথে donation_id পাঠানো হলো, তাহলে বাটনটি শো করবে!
        return redirect()->back()->with([
            'success' => 'Thank you for your donation! Your transaction is pending verification.',
            'donation_id' => $donation->id 
        ]);
    }

    // পিডিএফ রিসিট ডাউনলোড করার জন্য
    public function downloadReceipt($id)
    {
        $donation = Donation::with('project')->findOrFail($id);
        $pdf = Pdf::loadView('pdf.receipt', compact('donation'));
        return $pdf->download('Donation_Receipt_' . $donation->receipt_no . '.pdf');
    }

    // 🟢 NEW: ফোন নাম্বার দিয়ে রিসিট সার্চ করার মেথড
    public function searchReceipt(Request $request)
    {
        $donations = null;

        // যদি ইউজার ফোন নাম্বার দিয়ে সার্চ করে
        if ($request->has('phone')) {
            $request->validate(['phone' => 'required|string|max:20']);
            // ওই ফোন নাম্বারের সব ডোনেশন লেটেস্ট অনুযায়ী খুঁজে আনবে
            $donations = Donation::with('project')
                ->where('donor_phone', $request->phone)
                ->latest()
                ->get();
        }

        return view('receipt_search', compact('donations'));
    }
}