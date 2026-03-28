@extends('layouts.app')
@section('title', 'My Dashboard')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-12">
    
    @if(session('success'))
    <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-md shadow-sm">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            </div>
            <div class="ml-3"><p class="text-sm font-medium text-green-800">{{ session('success') }}</p></div>
        </div>
    </div>
    @endif

    <div class="flex flex-col md:flex-row justify-between items-center mb-10 border-b border-gray-200 pb-6 gap-4">
        <div class="flex items-center gap-4">
            <img src="{{ $user->photo ? asset('storage/' . $user->photo) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=e2e8f0&color=1e3a8a' }}" 
                 alt="Profile" class="w-16 h-16 rounded-full border-2 border-blue-100 object-cover shadow-sm">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Hello, {{ $user->name }} 👋</h1>
                <p class="text-gray-500 text-sm mt-1">Member ID: <span class="font-bold text-blue-600">{{ $user->member_id ?? 'Pending' }}</span></p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('member.id-card', $user->id) }}" target="_blank" class="bg-blue-600 text-white px-5 py-2.5 rounded-lg shadow-sm hover:bg-blue-700 transition font-medium flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                ID Card
            </a>
            
            <form action="{{ route('logout') }}" method="POST">
                @csrf 
                <button type="submit" class="bg-red-50 text-red-600 px-5 py-2.5 rounded-lg border border-red-100 shadow-sm hover:bg-red-500 hover:text-white transition font-medium">Logout</button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 border-l-4 border-l-green-500 hover:shadow-md transition">
            <p class="text-gray-500 font-semibold text-sm uppercase tracking-wider">Total Donated by You</p>
            <h2 class="text-3xl font-extrabold text-green-600 mt-2">৳{{ number_format($totalDonated) }}</h2>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 border-l-4 border-l-blue-500 hover:shadow-md transition">
            <p class="text-gray-500 font-semibold text-sm uppercase tracking-wider">Account Status</p>
            <div class="mt-2">
                @if(strtolower($user->status) === 'active')
                    <span class="bg-blue-100 text-blue-800 px-4 py-1.5 rounded-full text-sm font-bold">Active Member</span>
                @else
                    <span class="bg-yellow-100 text-yellow-800 px-4 py-1.5 rounded-full text-sm font-bold">{{ ucfirst($user->status) }}</span>
                @endif
            </div>
        </div>

        @php
            $isEligible = false;
            $daysPassed = 0;
            $daysRemaining = 0;
            $progressPercent = 0;
            
            if($user->is_blood_donor) {
                if(!$user->last_donation_date) {
                    $isEligible = true;
                    $progressPercent = 100;
                } else {
                    // 🚀 FIXED: diffInDays() এর মাধ্যমে শুধু পূর্ণসংখ্যা (Integer) দিন বের করা হচ্ছে
                    $daysPassed = \Carbon\Carbon::parse($user->last_donation_date)->startOfDay()->diffInDays(\Carbon\Carbon::now()->startOfDay());
                    
                    if($daysPassed >= 90) { 
                        $isEligible = true; 
                        $progressPercent = 100;
                    } else { 
                        $daysRemaining = 90 - $daysPassed; 
                        $progressPercent = round(($daysPassed / 90) * 100); // 🚀 প্রগ্রেস বারের পার্সেন্টেজ হিসাব
                    }
                }
            }
        @endphp
        
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 border-l-4 border-l-red-500 hover:shadow-md transition flex flex-col justify-center">
            <p class="text-gray-500 font-semibold text-sm uppercase tracking-wider mb-3">Blood Donor Status</p>
            
            @if(!$user->is_blood_donor)
                <div><span class="bg-gray-100 text-gray-600 px-4 py-1.5 rounded-full text-sm font-bold">Not a Donor</span></div>
            @elseif($isEligible)
                <div>
                    <span class="bg-green-100 text-green-700 px-4 py-1.5 rounded-full text-sm font-bold flex items-center inline-flex gap-1 w-max">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                        Ready to Donate
                    </span>
                </div>
            @else
                <div class="w-full">
                    <div class="flex justify-between text-xs mb-1">
                        <span class="font-bold text-gray-700">Day {{ $daysPassed }}</span>
                        <span class="font-bold text-red-600">{{ $daysRemaining }} Days Left</span>
                    </div>
                    <div class="w-full bg-red-100 rounded-full h-2.5 overflow-hidden">
                        <div class="bg-red-500 h-2.5 rounded-full transition-all duration-1000 ease-out" style="width: 0%" id="bloodProgressBar"></div>
                    </div>
                    <p class="text-xs text-gray-500 mt-2 text-center">Next donation on: <span class="font-bold">{{ \Carbon\Carbon::parse($user->last_donation_date)->addDays(90)->format('d M, Y') }}</span></p>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        setTimeout(() => {
                            let progressBar = document.getElementById('bloodProgressBar');
                            if(progressBar) {
                                progressBar.style.width = '{{ $progressPercent }}%';
                            }
                        }, 300); // হালকা ডিলের পর অ্যানিমেশন শুরু হবে
                    });
                </script>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden self-start">
            <div class="p-6 border-b border-gray-100 bg-gray-50">
                <h3 class="text-xl font-bold text-gray-800">My Donation History</h3>
            </div>
            
            @if($donations->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-white text-gray-500 text-sm uppercase tracking-wider border-b border-gray-200">
                            <tr>
                                <th class="p-4 font-semibold">Date</th>
                                <th class="p-4 font-semibold">Receipt No.</th>
                                <th class="p-4 font-semibold">Project</th>
                                <th class="p-4 font-semibold">Amount</th>
                                <th class="p-4 font-semibold">Status</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            @foreach($donations as $donation)
                            <tr class="border-b border-gray-50 last:border-0 hover:bg-slate-50 transition">
                                <td class="p-4 text-gray-600">{{ $donation->created_at->format('d M, Y') }}</td>
                                <td class="p-4 font-mono text-gray-500">{{ $donation->receipt_no }}</td>
                                <td class="p-4 font-medium text-gray-800">{{ $donation->project ? $donation->project->name : 'General Fund' }}</td>
                                <td class="p-4 font-bold text-gray-800">৳{{ number_format($donation->amount) }}</td>
                                <td class="p-4">
                                    @if(in_array(strtolower($donation->status), ['completed', 'approved']))
                                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold">Completed</span>
                                    @elseif(strtolower($donation->status) === 'pending')
                                        <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-bold">Pending</span>
                                    @else
                                        <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-bold">{{ ucfirst($donation->status) }}</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-10 text-center flex flex-col items-center">
                    <div class="bg-gray-100 p-4 rounded-full mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4M8 16l-4-4 4-4"></path></svg>
                    </div>
                    <p class="text-gray-500 font-medium">You haven't made any donations yet.</p>
                    <a href="/donate" class="mt-4 text-blue-600 font-semibold hover:underline">Make your first donation</a>
                </div>
            @endif
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden self-start">
            <div class="p-6 border-b border-gray-100 bg-red-50 flex items-center gap-2">
                <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z" /></svg>
                <h3 class="text-xl font-bold text-red-800">Blood Profile</h3>
            </div>
            
            <div class="p-6">
                <form action="{{ route('donor.update_blood_profile') }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <label class="flex items-center cursor-pointer mb-2">
                        <div class="relative">
                            <input type="checkbox" name="is_blood_donor" class="sr-only" {{ $user->is_blood_donor ? 'checked' : '' }} onchange="toggleForm(this)">
                            <div class="block bg-gray-200 w-10 h-6 rounded-full transition-colors duration-300" id="toggleBg"></div>
                            <div class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition transform duration-300" id="toggleDot"></div>
                        </div>
                        <div class="ml-3 text-gray-700 font-medium">I want to be a Blood Donor</div>
                    </label>

                    <div id="bloodFormFields" class="{{ $user->is_blood_donor ? 'block' : 'hidden' }} space-y-4 transition-all mt-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Blood Group</label>
                            <select name="blood_group" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500 outline-none">
                                <option value="">Select Group</option>
                                @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bg)
                                    <option value="{{ $bg }}" {{ $user->blood_group == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">District</label>
                            <select name="district_id" id="districtSelect" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500 outline-none">
                                <option value="">Select District</option>
                                @foreach($districts as $district)
                                    <option value="{{ $district->id }}" {{ $user->district_id == $district->id ? 'selected' : '' }}>{{ $district->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Upazila / Thana</label>
                            <select name="thana" id="thanaSelect" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500 outline-none">
                                <option value="">Select Thana</option>
                                @if($user->thana)
                                    <option value="{{ $user->thana }}" selected>{{ $user->thana }}</option>
                                @endif
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Last Donation Date</label>
                            <input type="date" name="last_donation_date" value="{{ $user->last_donation_date ? $user->last_donation_date->format('Y-m-d') : '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500 outline-none max-w-full">
                            <p class="text-xs text-gray-500 mt-1">Leave empty if you haven't donated yet.</p>
                        </div>
                    </div>

                    <button type="submit" class="w-full mt-4 bg-red-600 text-white font-bold py-2.5 rounded-lg hover:bg-red-700 transition shadow-sm">Save Profile</button>
                </form>
            </div>
        </div>

    </div>
</div>

<style>
    input:checked ~ #toggleBg { background-color: #ef4444; } /* Red-500 */
    input:checked ~ #toggleDot { transform: translateX(100%); }
</style>

<script>
    // Toggle Form visibility
    function toggleForm(checkbox) {
        const fields = document.getElementById('bloodFormFields');
        if(checkbox.checked) {
            fields.classList.remove('hidden');
            fields.classList.add('block');
        } else {
            fields.classList.remove('block');
            fields.classList.add('hidden');
        }
    }

    // Dynamic Thana AJAX
    document.addEventListener("DOMContentLoaded", function() {
        const districtSelect = document.getElementById('districtSelect');
        const thanaSelect = document.getElementById('thanaSelect');
        const oldThana = "{{ $user->thana }}"; 

        function loadThanas(districtId) {
            thanaSelect.innerHTML = '<option value="">Loading...</option>';
            if(districtId) {
                fetch(`/get-thanas/${districtId}`)
                    .then(response => response.json())
                    .then(data => {
                        thanaSelect.innerHTML = '<option value="">Select Thana</option>';
                        for(let key in data) {
                            let selected = (oldThana === data[key]) ? 'selected' : '';
                            thanaSelect.innerHTML += `<option value="${data[key]}" ${selected}>${data[key]}</option>`;
                        }
                    });
            } else {
                thanaSelect.innerHTML = '<option value="">Select Thana</option>';
            }
        }

        districtSelect.addEventListener('change', function() {
            loadThanas(this.value);
        });

        // Load initially if district is present but thana is empty (first load)
        if(districtSelect.value && thanaSelect.options.length <= 2) {
            loadThanas(districtSelect.value);
        }
    });
</script>
@endsection