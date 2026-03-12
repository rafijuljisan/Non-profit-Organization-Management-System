<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donation;
use App\Models\Subscription;
use App\Models\Expense;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Setting;



class FrontendController extends Controller
{
    public function transparency()
    {
        // ১. আয়-ব্যয়ের হিসাব (Completed/Paid স্ট্যাটাস চেক করা বাধ্যতামূলক)
        $totalDonations = Donation::where('status', 'completed')->sum('amount');
        $totalSubscriptions = Subscription::where('status', 'paid')->sum('amount');

        $totalIncome = $totalDonations + $totalSubscriptions;

        // 🚀 FIXED: শুধুমাত্র অনুমোদিত খরচগুলোই যোগ হবে
        $totalExpense = Expense::whereIn('status', ['completed', 'approved'])->sum('amount');

        $currentBalance = $totalIncome - $totalExpense;

        // ২. অন্যান্য স্ট্যাটস
        $activeMembers = User::where('status', 'active')->count();

        // ৩. লাইভ লেজার (সর্বশেষ ৫টি ট্রানজেকশন)
        $recentDonations = Donation::where('status', 'completed')->latest()->take(5)->get();
        $recentExpenses = Expense::whereIn('status', ['completed', 'approved'])->latest()->take(5)->get();

        return view('pages.transparency', compact(
            'totalIncome',
            'totalExpense',
            'currentBalance',
            'activeMembers',
            'recentDonations',
            'recentExpenses'
        ));
    }
    public function contact()
    {
        $settings = Setting::first();
        return view('contact', compact('settings'));
    }
    // 🚀 NEW: মেম্বার আইডি কার্ড ডাউনলোড মেথড
    public function idCard($id)
    {
        $user = \App\Models\User::with('district')->findOrFail($id);

        // যদি Settings মডেল থাকে
        // $settings = \App\Models\Setting::first(); 

        // mPDF কনফিগারেশন
        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_top' => 15,
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_bottom' => 15,
            'fontDir' => [public_path('fonts')],
            'fontdata' => [
                'solaimanlipi' => [
                    'R' => 'SolaimanLipi.ttf',
                    'B' => 'SolaimanLipi.ttf',
                    'I' => 'SolaimanLipi.ttf',
                    'BI' => 'SolaimanLipi.ttf',
                    'useOTL' => 0xFF,
                    'useKashida' => 75,
                ],
            ],
            'default_font' => 'solaimanlipi',
            'autoScriptToLang' => false,
            'autoLangToFont' => false,
        ]);

        // pdf.id_card ভিউ লোড করা
        $html = view('pdf.id_card', compact('user'))->render();
        $mpdf->WriteHTML($html);

        $filename = 'ID_Card_' . ($user->member_id ?? $user->id) . '.pdf';

        return response()->streamDownload(
            fn() => print ($mpdf->Output('', 'S')),
            $filename,
            ['Content-Type' => 'application/pdf']
        );
    }
}