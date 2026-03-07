<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Donation;

class DonorAuthController extends Controller
{
    // লগইন পেজ
    public function showLogin() { return view('auth.login'); }

    // লগইন লজিক
    public function login(Request $request)
    {
        $request->validate(['phone' => 'required|string', 'password' => 'required|string']);

        if (Auth::attempt(['phone' => $request->phone, 'password' => $request->password])) {
            $request->session()->regenerate();
            return redirect()->route('donor.dashboard');
        }
        return back()->withErrors(['phone' => 'The provided credentials do not match our records.']);
    }

    // রেজিস্ট্রেশন পেজ
    public function showRegister() { return view('auth.register'); }

    // রেজিস্ট্রেশন লজিক
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

    // লগআউট
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }

    // ডোনার ড্যাশবোর্ড
    public function dashboard()
    {
        $user = Auth::user();
        // ইউজারের সব ডোনেশন আনা হচ্ছে
        $donations = Donation::with('project')->where('user_id', $user->id)->latest()->get();
        $totalDonated = $donations->where('status', 'completed')->sum('amount');

        return view('donor.dashboard', compact('user', 'donations', 'totalDonated'));
    }
}