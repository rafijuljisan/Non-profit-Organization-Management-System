@extends('layouts.app')

@section('title', 'স্বেচ্ছাসেবক দল - ' . ($settings->site_name ?? config('app.name')))

@section('content')

{{-- Hero --}}
<section class="relative bg-gradient-to-br from-blue-950 via-blue-900 to-blue-700 text-white py-20 px-4 overflow-hidden">
    <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/asfalt-dark.png')]"></div>
    <div class="absolute top-0 right-0 w-80 h-80 bg-blue-400 opacity-10 rounded-full -translate-y-1/2 translate-x-1/3"></div>
    <div class="relative max-w-3xl mx-auto text-center">
        <p class="text-blue-300 uppercase tracking-widest text-sm font-semibold mb-4">আমাদের পরিবার</p>
        <h1 class="text-4xl md:text-5xl font-extrabold mb-5 leading-tight">আমাদের স্বেচ্ছাসেবক দল</h1>
        <p class="text-blue-100 text-lg max-w-2xl mx-auto leading-relaxed">
            এই নিবেদিতপ্রাণ মানুষগুলো তাদের সময়, শ্রম ও মেধা দিয়ে সমাজের জন্য কাজ করে যাচ্ছেন।
        </p>
    </div>
</section>

{{-- Team by Designation --}}
<div class="max-w-7xl mx-auto px-4 py-14">

    @forelse($volunteers as $designation => $members)
    <div class="mb-16">

        {{-- Designation Header --}}
        <div class="flex items-center gap-4 mb-8">
            <div class="h-8 w-1.5 bg-blue-600 rounded-full flex-shrink-0"></div>
            <h2 class="text-2xl font-extrabold text-blue-900">{{ $designation }}</h2>
            <span class="bg-blue-100 text-blue-700 text-xs font-bold px-3 py-1 rounded-full">
                {{ $members->count() }} জন
            </span>
        </div>

        {{-- Members Grid --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-5">
            @foreach($members as $member)
            <div class="bg-white border border-gray-100 rounded-2xl p-5 text-center shadow-sm hover:shadow-md transition group">

                {{-- Photo --}}
                <div class="w-20 h-20 mx-auto mb-4 rounded-full overflow-hidden border-2 border-blue-100 bg-blue-50">
                    @if($member->photo)
                        <img src="{{ asset('storage/' . $member->photo) }}"
                            alt="{{ $member->name }}"
                            class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-2xl font-extrabold text-blue-300">
                            {{ mb_substr($member->name, 0, 1) }}
                        </div>
                    @endif
                </div>

                {{-- Info --}}
                <p class="font-extrabold text-gray-800 text-sm leading-snug group-hover:text-blue-700 transition">
                    {{ $member->name }}
                </p>
                <p class="text-blue-600 text-xs font-semibold mt-1">{{ $member->designation }}</p>
                @if($member->district)
                <p class="text-gray-400 text-xs mt-1">{{ $member->district->name }}</p>
                @endif

                {{-- Member ID --}}
                @if($member->member_id)
                <p class="text-gray-300 text-xs mt-2 font-mono">{{ $member->member_id }}</p>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @empty
    {{-- No volunteers yet --}}
    @endforelse

    {{-- General Members (no designation) --}}
    @if($generalMembers->count() > 0)
    <div class="mb-16">
        <div class="flex items-center gap-4 mb-8">
            <div class="h-8 w-1.5 bg-gray-400 rounded-full flex-shrink-0"></div>
            <h2 class="text-2xl font-extrabold text-gray-700">সাধারণ সদস্যবৃন্দ</h2>
            <span class="bg-gray-100 text-gray-600 text-xs font-bold px-3 py-1 rounded-full">
                {{ $generalMembers->count() }} জন
            </span>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-5">
            @foreach($generalMembers as $member)
            <div class="bg-white border border-gray-100 rounded-2xl p-5 text-center shadow-sm hover:shadow-md transition group">
                <div class="w-20 h-20 mx-auto mb-4 rounded-full overflow-hidden border-2 border-gray-100 bg-gray-50">
                    @if($member->photo)
                        <img src="{{ asset('storage/' . $member->photo) }}"
                            alt="{{ $member->name }}"
                            class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-2xl font-extrabold text-gray-300">
                            {{ mb_substr($member->name, 0, 1) }}
                        </div>
                    @endif
                </div>
                <p class="font-extrabold text-gray-800 text-sm leading-snug group-hover:text-blue-700 transition">
                    {{ $member->name }}
                </p>
                @if($member->district)
                <p class="text-gray-400 text-xs mt-1">{{ $member->district->name }}</p>
                @endif
                @if($member->member_id)
                <p class="text-gray-300 text-xs mt-2 font-mono">{{ $member->member_id }}</p>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Empty state --}}
    @if($volunteers->isEmpty() && $generalMembers->count() === 0)
    <div class="text-center py-20 text-gray-400">
        <p class="text-5xl mb-4">👥</p>
        <p class="font-bold text-gray-500 text-lg">এখনো কোনো স্বেচ্ছাসেবক যোগ দেননি।</p>
    </div>
    @endif

    {{-- Join CTA --}}
    <div class="mt-6 bg-gradient-to-r from-blue-600 to-blue-800 rounded-3xl p-12 text-white text-center relative overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/asfalt-dark.png')]"></div>
        <div class="relative">
            <div class="text-5xl mb-5">🤝</div>
            <h2 class="text-3xl font-extrabold mb-4">আপনিও আমাদের দলে যোগ দিন</h2>
            <p class="text-blue-200 text-sm max-w-xl mx-auto mb-8 leading-relaxed">
                আপনার সময় ও দক্ষতা দিয়ে হাজারো মানুষের জীবনে পরিবর্তন আনুন।
                স্বেচ্ছাসেবক হিসেবে নিবন্ধন করুন এবং আমাদের পরিবারের অংশ হোন।
            </p>

            {{-- Volunteer Form --}}
            <form action="{{ route('inquiry.store') }}" method="POST"
                class="max-w-lg mx-auto bg-white bg-opacity-10 rounded-2xl p-6 text-left space-y-4">
                @csrf
                <input type="hidden" name="type" value="volunteer">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-blue-100 text-xs font-semibold mb-1">পূর্ণ নাম *</label>
                        <input type="text" name="name" required
                            placeholder="আপনার নাম"
                            class="w-full px-4 py-2.5 rounded-lg bg-white bg-opacity-20 border border-white border-opacity-30 text-white placeholder-blue-200 text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-opacity-50">
                    </div>
                    <div>
                        <label class="block text-blue-100 text-xs font-semibold mb-1">মোবাইল নম্বর *</label>
                        <input type="text" name="phone" required
                            placeholder="০১XXXXXXXXX"
                            class="w-full px-4 py-2.5 rounded-lg bg-white bg-opacity-20 border border-white border-opacity-30 text-white placeholder-blue-200 text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-opacity-50">
                    </div>
                </div>

                <div>
                    <label class="block text-blue-100 text-xs font-semibold mb-1">বার্তা</label>
                    <textarea name="message" rows="2"
                        placeholder="কেন আপনি স্বেচ্ছাসেবক হতে চান?"
                        class="w-full px-4 py-2.5 rounded-lg bg-white bg-opacity-20 border border-white border-opacity-30 text-white placeholder-blue-200 text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-opacity-50 resize-none"></textarea>
                </div>

                @if(session('success') && request()->get('from') === 'volunteer')
                <div class="bg-green-500 bg-opacity-30 text-white text-sm px-4 py-2 rounded-lg">
                    {{ session('success') }}
                </div>
                @endif

                <button type="submit"
                    class="w-full bg-white text-blue-700 font-extrabold py-3 rounded-xl hover:bg-blue-50 transition shadow-md text-sm">
                    স্বেচ্ছাসেবক হিসেবে আবেদন করুন
                </button>
            </form>

        </div>
    </div>
</div>

@endsection