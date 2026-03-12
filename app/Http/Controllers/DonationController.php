<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Donation;
use App\Models\Project;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use App\Models\Setting;

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

        return redirect()->back()->with([
            'success' => 'Thank you for your donation! Your transaction is pending verification.',
            'donation_id' => $donation->id
        ]);
    }

    // 🚀 FIXED: mPDF ব্যবহার করে পিডিএফ রিসিট জেনারেট ও ডাউনলোড
    public function downloadReceipt($id)
    {
        $donation = Donation::with('project')->findOrFail($id);

        // 🚀 mPDF এর কনফিগারেশন সরাসরি এখানে সেট করা হলো
        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_top' => 10,
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_bottom' => 10,
            'fontDir' => [public_path('fonts')],
            'fontdata' => [
                'solaimanlipi' => [
                    'R' => 'SolaimanLipi.ttf',
                    'B' => 'SolaimanLipi.ttf', // no bold variant — use same
                    'I' => 'SolaimanLipi.ttf',
                    'BI' => 'SolaimanLipi.ttf',
                    'useOTL' => 0xFF,               // ✅ proper OpenType Layout for Bangla
                    'useKashida' => 75,
                ],
            ],
            'default_font' => 'solaimanlipi',
            'autoScriptToLang' => false,               // ✅ disable — conflicts with manual font
            'autoLangToFont' => false,
        ]);

        // ব্লেড ফাইল থেকে HTML রেন্ডার করা
        $html = view('pdf.receipt', compact('donation'))->render();

        // mPDF এ HTML লেখা
        $mpdf->WriteHTML($html);

        $filename = 'Donation_Receipt_' . $donation->receipt_no . '.pdf';

        // সরাসরি ব্রাউজারে পিডিএফ ডাউনলোড করা
        return response()->streamDownload(
            fn() => print ($mpdf->Output('', 'S')),
            $filename,
            ['Content-Type' => 'application/pdf']
        );
    }

    public function contact()
    {
        $settings = Setting::first();
        return view('contact', compact('settings'));
    }
    // ফোন নাম্বার দিয়ে রিসিট সার্চ করার মেথড
    public function searchReceipt(Request $request)
    {
        $donations = null;

        // যদি ইউজার ফোন নাম্বার দিয়ে সার্চ করে
        if ($request->has('phone')) {
            $request->validate(['phone' => 'required|string|max:20']);

            // ওই ফোন নাম্বারের সব ডোনেশন লেটেস্ট অনুযায়ী খুঁজে আনবে
            $donations = Donation::with('project')
                ->where('donor_phone', $request->phone)
                ->latest()
                ->get();
        }

        return view('receipt_search', compact('donations'));
    }
}