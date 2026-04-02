<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use App\Models\BloodRequest;
use App\Models\Setting;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewBloodRequestMail;
use Illuminate\Support\Facades\Log;

class BloodBankController extends Controller
{
    public function index(Request $request)
    {
        // শুধু যারা ব্লাড ডোনার তাদের কোয়েরি করা হচ্ছে
        $query = User::with('district')->where('status', 'active')->where('is_blood_donor', true);

        // রক্তের গ্রুপ দিয়ে সার্চ
        if ($request->filled('blood_group')) {
            $query->where('blood_group', $request->blood_group);
        }

        // জেলা দিয়ে সার্চ
        if ($request->filled('district_id')) {
            $query->where('district_id', $request->district_id);
        }

        // উপজেলা/থানা দিয়ে সার্চ
        if ($request->filled('thana')) {
            $query->where('thana', 'like', '%' . $request->thana . '%');
        }

        $donors = $query->paginate(12);

        // ডোনারদের এলিজিবিলিটি চেক করা (৯০ দিন)
        $donors->getCollection()->transform(function ($donor) {
            if (!$donor->last_donation_date) {
                $donor->is_eligible = true;
                $donor->days_remaining = 0;
            } else {
                // 🚀 FIXED: হেল্পার ফাংশনের বদলে সরাসরি Carbon ব্যবহার করা হয়েছে
                $daysPassed = Carbon::parse($donor->last_donation_date)->diffInDays(Carbon::now());

                if ($daysPassed >= 90) {
                    $donor->is_eligible = true;
                    $donor->days_remaining = 0;
                } else {
                    $donor->is_eligible = false;
                    $donor->days_remaining = 90 - $daysPassed;
                }
            }
            return $donor;
        });

        // ফিল্টারের ড্রপডাউনের জন্য জেলার লিস্ট
        $districts = \App\Models\District::orderBy('name', 'asc')->get();

        return view('pages.blood_bank', compact('donors', 'districts'));
    }
    public function storeRequest(Request $request)
    {
        $validated = $request->validate([
            'donor_id' => 'required|exists:users,id',
            'requester_name' => 'required|string|max:255',
            'requester_phone' => 'required|string|max:20',
            'patient_name' => 'required|string|max:255',
            'hospital_name' => 'required|string|max:255',
            'bags_needed' => 'required|integer|min:1',
        ]);

        // রিকোয়েস্ট ডাটাবেসে সেভ করা হচ্ছে
        $bloodRequest = BloodRequest::create($validated);

        // অ্যাডমিন এবং ব্লাড সেক্রেটারিদের খুঁজে বের করা
        $notifyUsers = User::role(['super_admin', 'admin', 'blood_secretary'])
            ->whereNotNull('email')
            ->get();

        // 🚀 সরাসরি (Direct) ইমেইল পাঠানো হচ্ছে Try-Catch এর মাধ্যমে
        try {
            foreach ($notifyUsers as $user) {
                Mail::to($user->email)->send(new NewBloodRequestMail($bloodRequest));
            }
        } catch (\Exception $e) {
            // যদি ইমেইল পাঠাতে কোনো সমস্যা হয়, তবে সেটি log ফাইলে সেভ হবে কিন্তু পেজ ক্র্যাশ করবে না
            Log::error('Blood Request Mail Failed: ' . $e->getMessage());
        }

        // ডায়নামিক ব্লাড সেক্রেটারির নাম্বার
        $secretary = User::role('blood_secretary')->first();
        $secretaryPhone = $secretary && $secretary->phone ? $secretary->phone : 'সেক্রেটারি নাম্বার পাওয়া যায়নি';

        return response()->json([
            'success' => true,
            'message' => 'Request sent successfully',
            'secretary_phone' => $secretaryPhone
        ]);
    }
    // 🚀 NEW: AJAX কল রিসিভ করার জন্য
    public function getThanas($district_id)
    {
        $thanas = \App\Models\Upazila::where('district_id', $district_id)->orderBy('name')->pluck('name', 'name');
        return response()->json($thanas);
    }
}