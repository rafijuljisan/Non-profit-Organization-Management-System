@extends('layouts.app')
@section('title', 'আমার ড্যাশবোর্ড')

@section('content')

<style>
    body, h1, h2, h3, h4, h5, h6, p, span, a, label, input, select, button, div, th, td {
        font-family: 'SolaimanLipi', Arial, sans-serif !important;
    }

    /* Toggle Switch CSS */
    input:checked ~ #toggleBg { background-color: #ef4444; }
    input:checked ~ #toggleDot { transform: translateX(100%); }

    /* Tab active state */
    .tab-btn.active {
        background-color: #2563eb;
        color: #ffffff;
        border-color: #2563eb;
        box-shadow: 0 2px 8px rgba(37,99,235,0.15);
    }
    .tab-btn {
        transition: all 0.2s ease;
    }
    .tab-panel { display: none; }
    .tab-panel.active { display: block; }
</style>

<div class="max-w-6xl mx-auto px-4 py-10">

    {{-- ── Success Alert ── --}}
    @if(session('success'))
    <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-md shadow-sm">
        <div class="flex items-center gap-3">
            <svg class="h-5 w-5 text-green-400 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <p class="text-base font-medium text-green-800">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    {{-- ── Header ── --}}
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 border-b border-gray-200 pb-6 gap-4">
        <div class="flex items-center gap-4">
            <img src="{{ $user->photo ? asset('storage/' . $user->photo) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=e2e8f0&color=1e3a8a' }}"
                 alt="Profile" class="w-16 h-16 rounded-full border-2 border-blue-100 object-cover shadow-sm">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">স্বাগতম, {{ $user->name }} 👋</h1>
                <p class="text-gray-500 text-sm mt-1">মেম্বার আইডি:
                    <span class="font-bold text-blue-600">{{ $user->member_id ?? 'পেন্ডিং' }}</span>
                </p>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('donor.profile.edit') }}"
               class="bg-gray-100 text-gray-700 px-4 py-2.5 rounded-lg border border-gray-200 shadow-sm hover:bg-gray-200 transition font-medium text-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                প্রোফাইল এডিট
            </a>
            <a href="{{ route('member.id-card', $user->id) }}" target="_blank"
               class="bg-blue-600 text-white px-4 py-2.5 rounded-lg shadow-sm hover:bg-blue-700 transition font-medium text-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                </svg>
                আইডি কার্ড
            </a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                        class="bg-red-50 text-red-600 px-4 py-2.5 rounded-lg border border-red-100 shadow-sm hover:bg-red-500 hover:text-white transition font-medium text-sm">
                    লগআউট
                </button>
            </form>
        </div>
    </div>

    {{-- ── 3 Stats Cards ── --}}
    @php
        $isEligible     = false;
        $daysPassed     = 0;
        $daysRemaining  = 0;
        $progressPercent = 0;

        if ($user->is_blood_donor) {
            if (!$user->last_donation_date) {
                $isEligible      = true;
                $progressPercent = 100;
            } else {
                $daysPassed = \Carbon\Carbon::parse($user->last_donation_date)
                    ->startOfDay()->diffInDays(\Carbon\Carbon::now()->startOfDay());
                if ($daysPassed >= 90) {
                    $isEligible      = true;
                    $progressPercent = 100;
                } else {
                    $daysRemaining   = 90 - $daysPassed;
                    $progressPercent = round(($daysPassed / 90) * 100);
                }
            }
        }
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">

        {{-- Total Donations --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 border-l-4 border-l-green-500 hover:shadow-md transition">
            <p class="text-gray-500 font-semibold text-xs uppercase tracking-wider">আপনার মোট অনুদান</p>
            <h2 class="text-3xl font-extrabold text-green-600 mt-2">৳{{ number_format($totalDonated) }}</h2>
        </div>

        {{-- Account Status --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 border-l-4 border-l-blue-500 hover:shadow-md transition">
            <p class="text-gray-500 font-semibold text-xs uppercase tracking-wider">অ্যাকাউন্ট স্ট্যাটাস</p>
            <div class="mt-3">
                @if(strtolower($user->status) === 'active')
                    <span class="bg-blue-100 text-blue-800 px-4 py-1.5 rounded-full text-sm font-bold">সক্রিয় সদস্য</span>
                @else
                    <span class="bg-yellow-100 text-yellow-800 px-4 py-1.5 rounded-full text-sm font-bold">অপেক্ষমান (Pending)</span>
                @endif
            </div>
        </div>

        {{-- Blood Donor Status --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 border-l-4 border-l-red-500 hover:shadow-md transition flex flex-col justify-center">
            <p class="text-gray-500 font-semibold text-xs uppercase tracking-wider mb-3">রক্তদাতা স্ট্যাটাস</p>
            @if(!$user->is_blood_donor)
                <span class="bg-gray-100 text-gray-600 px-4 py-1.5 rounded-full text-sm font-bold w-max">রক্তদাতা নন</span>
            @elseif($isEligible)
                <span class="bg-green-100 text-green-700 px-4 py-1.5 rounded-full text-sm font-bold inline-flex items-center gap-1 w-max">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    রক্তদানে প্রস্তুত
                </span>
            @else
                <div class="w-full">
                    <div class="flex justify-between text-xs mb-1">
                        <span class="font-bold text-gray-700">দিন {{ $daysPassed }}</span>
                        <span class="font-bold text-red-600">আর {{ $daysRemaining }} দিন বাকি</span>
                    </div>
                    <div class="w-full bg-red-100 rounded-full h-2.5 overflow-hidden">
                        <div class="bg-red-500 h-2.5 rounded-full transition-all duration-1000 ease-out"
                             style="width: 0%" id="bloodProgressBar"></div>
                    </div>
                    <p class="text-xs text-gray-500 mt-2 text-center">
                        পরবর্তী রক্তদান:
                        <span class="font-bold">
                            {{ \Carbon\Carbon::parse($user->last_donation_date)->addDays(90)->format('d M, Y') }}
                        </span>
                    </p>
                </div>
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        setTimeout(() => {
                            const bar = document.getElementById('bloodProgressBar');
                            if (bar) bar.style.width = '{{ $progressPercent }}%';
                        }, 300);
                    });
                </script>
            @endif
        </div>

    </div>

    {{-- ══════════════════════════════════════════
         TAB NAVIGATION
    ══════════════════════════════════════════ --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        {{-- Tab Buttons --}}
        <div class="flex items-center gap-2 p-4 border-b border-gray-100 bg-gray-50 overflow-x-auto">

            <button onclick="switchTab('profile')" id="tab-btn-profile"
                    class="tab-btn active flex items-center gap-2 px-5 py-2.5 rounded-lg border border-gray-200 bg-white text-gray-600 font-semibold text-sm whitespace-nowrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                প্রোফাইল
            </button>

            <button onclick="switchTab('donations')" id="tab-btn-donations"
                    class="tab-btn flex items-center gap-2 px-5 py-2.5 rounded-lg border border-gray-200 bg-white text-gray-600 font-semibold text-sm whitespace-nowrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                অনুদান
                @if($donations->count() > 0)
                    <span class="bg-blue-100 text-blue-700 text-xs font-bold px-2 py-0.5 rounded-full">
                        {{ $donations->count() }}
                    </span>
                @endif
            </button>

            <button onclick="switchTab('blood')" id="tab-btn-blood"
                    class="tab-btn flex items-center gap-2 px-5 py-2.5 rounded-lg border border-gray-200 bg-white text-gray-600 font-semibold text-sm whitespace-nowrap">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"/>
                </svg>
                ব্লাড প্রোফাইল
                @if($user->is_blood_donor)
                    <span class="bg-red-100 text-red-600 text-xs font-bold px-2 py-0.5 rounded-full">সক্রিয়</span>
                @endif
            </button>

        </div>

        {{-- ══ TAB 1: Personal Profile ══ --}}
        <div id="tab-profile" class="tab-panel active p-6">

            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-gray-800">ব্যক্তিগত তথ্য</h3>
                <a href="{{ route('donor.profile.edit') }}"
                   class="text-sm text-blue-600 font-semibold hover:underline flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    এডিট করুন
                </a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">

                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">পূর্ণ নাম</p>
                    <p class="text-base font-bold text-gray-800">{{ $user->name }}</p>
                </div>

                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">ফোন নম্বর</p>
                    <p class="text-base font-bold text-gray-800">{{ $user->phone }}</p>
                </div>

                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">ইমেইল</p>
                    <p class="text-base font-bold text-gray-800 break-all">{{ $user->email ?? '—' }}</p>
                </div>

                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">NID নম্বর</p>
                    <p class="text-base font-bold text-gray-800">{{ $user->nid_number ?? '—' }}</p>
                </div>

                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">জেলা</p>
                    <p class="text-base font-bold text-gray-800">{{ $user->district->name ?? '—' }}</p>
                </div>

                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">উপজেলা / থানা</p>
                    <p class="text-base font-bold text-gray-800">{{ $user->thana ?? '—' }}</p>
                </div>

                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">নির্দিষ্ট এলাকা</p>
                    <p class="text-base font-bold text-gray-800">{{ $user->area ?? '—' }}</p>
                </div>

                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">পদবি</p>
                    <p class="text-base font-bold text-gray-800">{{ $user->designation ?? '—' }}</p>
                </div>

                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">রক্তের গ্রুপ</p>
                    @if($user->blood_group)
                        <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm font-bold inline-block mt-1">
                            {{ $user->blood_group }}
                        </span>
                    @else
                        <p class="text-base font-bold text-gray-800">—</p>
                    @endif
                </div>

                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">মাসিক ফি</p>
                    <p class="text-base font-bold text-gray-800">৳{{ number_format($user->monthly_fee ?? 0) }}</p>
                </div>

            </div>
        </div>

        {{-- ══ TAB 2: Donation History ══ --}}
        <div id="tab-donations" class="tab-panel">

            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-800">আমার অনুদানের ইতিহাস</h3>
                @if($donations->count() > 0)
                    <span class="text-sm text-gray-500 font-medium">মোট {{ $donations->count() }}টি লেনদেন</span>
                @endif
            </div>

            @if($donations->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-4 font-semibold">তারিখ</th>
                                <th class="px-6 py-4 font-semibold">রসিদ নং</th>
                                <th class="px-6 py-4 font-semibold">প্রকল্প</th>
                                <th class="px-6 py-4 font-semibold">পরিমাণ</th>
                                <th class="px-6 py-4 font-semibold">স্ট্যাটাস</th>
                            </tr>
                        </thead>
                        <tbody class="text-base divide-y divide-gray-50">
                            @foreach($donations as $donation)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-4 text-gray-600 text-sm">
                                    {{ $donation->created_at->format('d M, Y') }}
                                </td>
                                <td class="px-6 py-4 font-mono text-gray-500 text-sm">
                                    {{ $donation->receipt_no }}
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-800">
                                    {{ $donation->project ? $donation->project->name : 'সাধারণ তহবিল' }}
                                </td>
                                <td class="px-6 py-4 font-bold text-gray-900">
                                    ৳{{ number_format($donation->amount) }}
                                </td>
                                <td class="px-6 py-4">
                                    @if(in_array(strtolower($donation->status), ['completed', 'approved']))
                                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold">সম্পন্ন</span>
                                    @elseif(strtolower($donation->status) === 'pending')
                                        <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-bold">অপেক্ষমান</span>
                                    @else
                                        <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-bold">{{ ucfirst($donation->status) }}</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Summary footer --}}
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                    <span class="text-sm text-gray-500 font-medium">মোট সম্পন্ন অনুদান</span>
                    <span class="text-lg font-extrabold text-green-600">৳{{ number_format($totalDonated) }}</span>
                </div>

            @else
                <div class="py-16 text-center flex flex-col items-center">
                    <div class="bg-gray-100 p-5 rounded-full mb-4">
                        <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <p class="text-gray-500 font-medium text-base mb-1">আপনি এখনও কোনো অনুদান করেননি।</p>
                    <p class="text-gray-400 text-sm mb-4">আপনার প্রথম অনুদানের মাধ্যমে পরিবর্তন আনুন।</p>
                    <a href="/donate"
                       class="bg-blue-600 text-white px-6 py-2.5 rounded-lg font-semibold hover:bg-blue-700 transition text-sm">
                        এখনই অনুদান করুন
                    </a>
                </div>
            @endif
        </div>

        {{-- ══ TAB 3: Blood Profile ══ --}}
        <div id="tab-blood" class="tab-panel">
            <div class="p-6 border-b border-gray-100 flex items-center gap-2">
                <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"/>
                </svg>
                <h3 class="text-lg font-bold text-red-800">ব্লাড প্রোফাইল</h3>
            </div>

            <div class="p-6 max-w-lg">
                <form action="{{ route('donor.update_blood_profile') }}" method="POST" class="space-y-5">
                    @csrf

                    {{-- Toggle --}}
                    <label class="flex items-center cursor-pointer">
                        <div class="relative">
                            <input type="checkbox" name="is_blood_donor" class="sr-only"
                                   {{ $user->is_blood_donor ? 'checked' : '' }}
                                   onchange="toggleBloodForm(this)">
                            <div class="block bg-gray-200 w-11 h-6 rounded-full transition-colors duration-300" id="toggleBg"></div>
                            <div class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition transform duration-300" id="toggleDot"></div>
                        </div>
                        <span class="ml-3 text-gray-700 font-bold text-base">আমি রক্তদাতা হতে চাই</span>
                    </label>

                    <div id="bloodFormFields" class="{{ $user->is_blood_donor ? 'block' : 'hidden' }} space-y-4">

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">রক্তের গ্রুপ</label>
                            <select name="blood_group"
                                    class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 outline-none text-base">
                                <option value="">গ্রুপ নির্বাচন করুন</option>
                                @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bg)
                                    <option value="{{ $bg }}" {{ $user->blood_group == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">জেলা</label>
                            <select name="district_id" id="districtSelect"
                                    class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 outline-none text-base">
                                <option value="">জেলা নির্বাচন করুন</option>
                                @foreach($districts as $district)
                                    <option value="{{ $district->id }}"
                                            {{ $user->district_id == $district->id ? 'selected' : '' }}>
                                        {{ $district->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">উপজেলা / থানা</label>
                            <select name="thana" id="thanaSelect"
                                    class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 outline-none text-base">
                                <option value="">থানা নির্বাচন করুন</option>
                                @if($user->thana)
                                    <option value="{{ $user->thana }}" selected>{{ $user->thana }}</option>
                                @endif
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">সর্বশেষ রক্তদানের তারিখ</label>
                            <input type="date" name="last_donation_date"
                                   value="{{ $user->last_donation_date ? $user->last_donation_date->format('Y-m-d') : '' }}"
                                   class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 outline-none text-base">
                            <p class="text-xs text-gray-400 mt-1">আগে রক্ত না দিয়ে থাকলে ফাঁকা রাখুন।</p>
                        </div>

                    </div>

                    <button type="submit"
                            class="bg-red-600 text-white font-bold px-8 py-2.5 rounded-lg hover:bg-red-700 transition shadow-sm text-base">
                        প্রোফাইল সেভ করুন
                    </button>
                </form>
            </div>
        </div>

    </div>{{-- end tab container --}}

</div>{{-- end max-w --}}

<script>
    // ── Tab Switcher ──
    function switchTab(name) {
        // Hide all panels
        document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
        // Remove active from all buttons
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        // Show selected
        document.getElementById('tab-' + name).classList.add('active');
        document.getElementById('tab-btn-' + name).classList.add('active');
    }

    // ── Blood form toggle ──
    function toggleBloodForm(checkbox) {
        const fields = document.getElementById('bloodFormFields');
        fields.classList.toggle('hidden', !checkbox.checked);
        fields.classList.toggle('block', checkbox.checked);
    }

    // ── District → Thana dynamic load ──
    document.addEventListener('DOMContentLoaded', function () {
        const districtSelect = document.getElementById('districtSelect');
        const thanaSelect    = document.getElementById('thanaSelect');
        const oldThana       = "{{ $user->thana }}";

        function loadThanas(districtId) {
            thanaSelect.innerHTML = '<option value="">লোডিং হচ্ছে...</option>';
            if (districtId) {
                fetch(`/get-thanas/${districtId}`)
                    .then(r => r.json())
                    .then(data => {
                        thanaSelect.innerHTML = '<option value="">থানা নির্বাচন করুন</option>';
                        for (let key in data) {
                            const sel = oldThana === data[key] ? 'selected' : '';
                            thanaSelect.innerHTML += `<option value="${data[key]}" ${sel}>${data[key]}</option>`;
                        }
                    });
            } else {
                thanaSelect.innerHTML = '<option value="">থানা নির্বাচন করুন</option>';
            }
        }

        if (districtSelect) {
            districtSelect.addEventListener('change', function () { loadThanas(this.value); });
            if (districtSelect.value) loadThanas(districtSelect.value);
        }
    });
</script>
@endsection