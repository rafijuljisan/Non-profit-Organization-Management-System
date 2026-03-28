<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Donation;
use App\Models\District;
use App\Models\Setting; // 🚀 Settings মডেল ইমপোর্ট করা হলো

class DonorAuthController extends Controller
{
    // 🟢 লগইন পেজ
    public function showLogin() 
    { 
        $settings = Setting::first(); // Blade এ টাইটেলের জন্য settings পাঠানো হলো
        return view('auth.login', compact('settings')); 
    }

    // 🟢 লগইন লজিক
    public function login(Request $request)
    {
        $request->validate(['phone' => 'required|string', 'password' => 'required|string']);

        if (Auth::attempt(['phone' => $request->phone, 'password' => $request->password])) {
            $request->session()->regenerate();
            return redirect()->route('donor.dashboard');
        }
        return back()->withErrors(['phone' => 'The provided credentials do not match our records.']);
    }

    // 🟢 রেজিস্ট্রেশন পেজ
    public function showRegister() 
    { 
        $settings = Setting::first();
        return view('auth.register', compact('settings')); 
    }

    // 🟢 রেজিস্ট্রেশন লজিক
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);
        return redirect()->route('donor.dashboard');
    }

    // 🟢 লগআউট
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }

    // 🩸 ডোনার ড্যাশবোর্ড
    public function dashboard()
    {
        $user = Auth::user();
        
        // ইউজারের সব ডোনেশন আনা হচ্ছে
        $donations = Donation::with('project')->where('user_id', $user->id)->latest()->get();
        $totalDonated = $donations->where('status', 'completed')->sum('amount');
        
        // ফর্মে দেখানোর জন্য জেলার লিস্ট
        $districts = District::orderBy('name', 'asc')->get();

        return view('donor.dashboard', compact('user', 'donations', 'totalDonated', 'districts'));
    }

    // 🩸 ব্লাড ডোনেশন প্রোফাইল আপডেট মেথড
    public function updateBloodProfile(Request $request)
    {
        $request->validate([
            'blood_group' => 'nullable|string',
            'district_id' => 'nullable|exists:districts,id',
            'thana' => 'nullable|string',
            'last_donation_date' => 'nullable|date|before_or_equal:today',
        ]);

        // Auth::id() দিয়ে সরাসরি User মডেল ধরা হয়েছে
        $user = User::find(Auth::id());

        $user->update([
            'is_blood_donor' => $request->has('is_blood_donor'), // চেকবক্স টিক দিলে true, না দিলে false
            'blood_group' => $request->blood_group,
            'district_id' => $request->district_id,
            'thana' => $request->thana,
            'last_donation_date' => $request->last_donation_date,
        ]);

        return back()->with('success', 'আপনার ব্লাড ডোনেশন প্রোফাইল সফলভাবে আপডেট হয়েছে!');
    }
}