<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Donation;
use App\Models\District;
use App\Models\Setting; // 🚀 Settings মডেল ইমপোর্ট করা হলো
use Illuminate\Support\Facades\Storage;

class DonorAuthController extends Controller
{
    // 🟢 লগইন পেজ
    public function showLogin()
    {
        $settings = Setting::first(); // Blade এ টাইটেলের জন্য settings পাঠানো হলো
        return view('auth.login', compact('settings'));
    }

    private function normalizePhone(string $phone): string
    {
        // Strip everything except digits
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Always return last 10 digits (core number without any prefix)
        return substr($phone, -10); // 1957850240
    }
    // 🟢 লগইন লজিক
    public function login(Request $request)
    {
        $request->validate(['phone' => 'required|string', 'password' => 'required|string']);

        $normalizedPhone = $this->normalizePhone($request->phone);

        // Match any stored format that ends with these 10 digits
        $user = User::whereRaw("REPLACE(REPLACE(REPLACE(phone, '+', ''), '-', ''), ' ', '') LIKE ?", ["%{$normalizedPhone}"])
            ->first();

        if ($user && Auth::attempt(['phone' => $user->phone, 'password' => $request->password])) {
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

        $normalizedPhone = $this->normalizePhone($request->phone);

        // Check uniqueness after normalization
        if (User::where('phone', $normalizedPhone)->exists()) {
            return back()->withErrors(['phone' => 'This phone number is already registered.']);
        }

        $user = User::create([
            'name' => $request->name,
            'phone' => $normalizedPhone, // Always stored as 01957850240
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

    // 🟢 Profile Edit Page
    public function editProfile()
    {
        $user = Auth::user();
        $districts = District::orderBy('name', 'asc')->get();
        $settings = Setting::first();
        return view('donor.edit_profile', compact('user', 'districts', 'settings'));
    }

    // 🟢 Profile Update Logic
    public function updateProfile(Request $request)
    {
        $user = User::find(Auth::id());

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'district_id' => 'nullable|exists:districts,id',
            'thana' => 'nullable|string|max:255',
            'area' => 'nullable|string|max:255',
            'nid_number' => 'nullable|string|unique:users,nid_number,' . $user->id,
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'password' => 'nullable|min:6|confirmed',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'district_id' => $request->district_id,
            'thana' => $request->thana,
            'area' => $request->area,
            'nid_number' => $request->nid_number,
        ];

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }
            $data['photo'] = $request->file('photo')->store('members/photos', 'public');
        }

        // Handle password change
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('donor.profile.edit')->with('success', 'আপনার প্রোফাইল সফলভাবে আপডেট হয়েছে!');
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