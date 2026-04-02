@extends('layouts.app')
@section('title', 'প্রোফাইল সম্পাদনা')

@section('content')
<style>
    body, h1, h2, h3, h4, h5, h6, p, span, a, label, input, select, button, div, th, td {
        font-family: 'SolaimanLipi', Arial, sans-serif !important;
    }
</style>

<div class="max-w-3xl mx-auto px-4 py-12">

    {{-- Success Message --}}
    @if(session('success'))
    <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-md shadow-sm">
        <div class="flex items-center gap-3">
            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <p class="text-base font-medium text-green-800">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    {{-- Page Header --}}
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-bold text-gray-800">প্রোফাইল সম্পাদনা</h1>
        <a href="{{ route('donor.dashboard') }}" class="text-blue-600 hover:underline text-sm font-semibold flex items-center gap-1">
            ← ড্যাশবোর্ডে ফিরুন
        </a>
    </div>

    <form action="{{ route('donor.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        {{-- Profile Photo --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-700 mb-4 pb-2 border-b">প্রোফাইল ছবি</h3>
            <div class="flex items-center gap-6">
                <img id="photoPreview"
                     src="{{ $user->photo ? asset('storage/' . $user->photo) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=e2e8f0&color=1e3a8a' }}"
                     alt="Profile" class="w-20 h-20 rounded-full border-2 border-blue-100 object-cover shadow-sm">
                <div class="flex-1">
                    <input type="file" name="photo" id="photoInput" accept="image/*"
                           class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700 file:font-semibold hover:file:bg-blue-100 cursor-pointer"
                           onchange="previewPhoto(this)">
                    <p class="text-xs text-gray-400 mt-1">JPG, PNG সর্বোচ্চ ২MB</p>
                    @error('photo') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        {{-- Personal Information --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-700 mb-4 pb-2 border-b">ব্যক্তিগত তথ্য</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">পূর্ণ নাম *</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-base">
                    @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">ইমেইল</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-base">
                    @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">ফোন নম্বর</label>
                    <input type="text" value="{{ $user->phone }}" disabled
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-lg bg-gray-50 text-gray-400 text-base cursor-not-allowed">
                    <p class="text-xs text-gray-400 mt-1">ফোন নম্বর পরিবর্তন করা যাবে না।</p>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">জাতীয় পরিচয়পত্র নম্বর (NID)</label>
                    <input type="text" name="nid_number" value="{{ old('nid_number', $user->nid_number) }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-base">
                    @error('nid_number') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                {{-- District --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">জেলা</label>
                    <select name="district_id" id="districtSelect"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-base">
                        <option value="">জেলা নির্বাচন করুন</option>
                        @foreach($districts as $district)
                            <option value="{{ $district->id }}" {{ $user->district_id == $district->id ? 'selected' : '' }}>
                                {{ $district->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Thana --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">উপজেলা / থানা</label>
                    <select name="thana" id="thanaSelect"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-base">
                        <option value="">থানা নির্বাচন করুন</option>
                        @if($user->thana)
                            <option value="{{ $user->thana }}" selected>{{ $user->thana }}</option>
                        @endif
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-1">নির্দিষ্ট এলাকা / ইউনিয়ন</label>
                    <input type="text" name="area" value="{{ old('area', $user->area) }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-base">
                </div>

            </div>
        </div>

        {{-- Change Password --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-700 mb-1 pb-2 border-b">পাসওয়ার্ড পরিবর্তন</h3>
            <p class="text-xs text-gray-400 mb-4">পাসওয়ার্ড না বদলাতে চাইলে এই ঘরগুলো ফাঁকা রাখুন।</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">নতুন পাসওয়ার্ড</label>
                    <input type="password" name="password" placeholder="সর্বনিম্ন ৬ অক্ষর"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-base">
                    @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">পাসওয়ার্ড নিশ্চিত করুন</label>
                    <input type="password" name="password_confirmation"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-base">
                </div>
            </div>
        </div>

        {{-- Submit --}}
        <div class="flex justify-end">
            <button type="submit"
                    class="bg-blue-600 text-white font-bold px-8 py-3 rounded-xl hover:bg-blue-700 transition shadow-md text-base">
                প্রোফাইল আপডেট করুন
            </button>
        </div>

    </form>
</div>

<script>
    // Photo preview
    function previewPhoto(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => document.getElementById('photoPreview').src = e.target.result;
            reader.readAsDataURL(input.files[0]);
        }
    }

    // District → Thana dynamic load
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
                            const selected = oldThana === data[key] ? 'selected' : '';
                            thanaSelect.innerHTML += `<option value="${data[key]}" ${selected}>${data[key]}</option>`;
                        }
                    });
            } else {
                thanaSelect.innerHTML = '<option value="">থানা নির্বাচন করুন</option>';
            }
        }

        districtSelect.addEventListener('change', function () {
            loadThanas(this.value);
        });

        // Load thanas on page load if district already selected
        if (districtSelect.value) {
            loadThanas(districtSelect.value);
        }
    });
</script>
@endsection