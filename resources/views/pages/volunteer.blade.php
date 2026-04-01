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

{{-- Team Grid --}}
<div class="max-w-7xl mx-auto px-4 py-14">

    {{-- Single Grid for All Members --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-5 mb-16">

        {{-- Designated Members --}}
        @forelse($volunteers as $designation => $members)
            @foreach($members as $member)
            <div class="bg-white border border-gray-100 rounded-2xl p-5 text-center shadow-sm hover:shadow-md transition group">

                {{-- Photo --}}
                <div class="w-28 h-28 mx-auto mb-4 rounded-full overflow-hidden border-2 border-blue-100 bg-blue-50">
                    @if($member->photo)
                        <img src="{{ asset('storage/' . $member->photo) }}"
                            alt="{{ $member->name }}"
                            class="w-full h-full object-cover">
                    @else
                        <img src="{{ asset('images/avatar.png') }}" 
                            alt="avatar" 
                            class="w-full h-full object-cover">
                    @endif
                </div>

                {{-- Info --}}
                {{-- Info --}}
                <p class="font-extrabold text-gray-800 text-base leading-snug group-hover:text-blue-700 transition">
                    {{ $member->name }}
                </p>
                <p class="text-blue-600 text-sm font-semibold mt-1">{{ $designation }}</p>

                @if($member->phone)
                <a href="tel:{{ $member->phone }}" class="text-gray-500 text-sm mt-1 hover:text-blue-600 transition block">
                    📞 {{ $member->phone }}
                </a>
                @endif

                @if($member->district)
                <p class="text-gray-400 text-sm mt-1">{{ $member->district->name }}</p>
                @endif

                {{-- Member ID --}}
                @if($member->member_id)
                <p class="text-gray-300 text-sm mt-2 font-mono">{{ $member->member_id }}</p>
                @endif
            </div>
            @endforeach
        @empty
        @endforelse

        {{-- General Members --}}
        @if(isset($generalMembers) && $generalMembers->count() > 0)
            @foreach($generalMembers as $member)
            <div class="bg-white border border-gray-100 rounded-2xl p-5 text-center shadow-sm hover:shadow-md transition group">
                
                {{-- Photo --}}
                <div class="w-28 h-28 mx-auto mb-4 rounded-full overflow-hidden border-2 border-gray-100 bg-gray-50">
                    @if($member->photo)
                        <img src="{{ asset('storage/' . $member->photo) }}"
                            alt="{{ $member->name }}"
                            class="w-full h-full object-cover">
                    @else
                        <img src="{{ asset('images/avatar.png') }}" 
                            alt="avatar" 
                            class="w-full h-full object-cover">
                    @endif
                </div>

                {{-- Info --}}
                <p class="font-extrabold text-gray-800 text-sm leading-snug group-hover:text-blue-700 transition">
                    {{ $member->name }}
                </p>
                <p class="text-gray-500 text-xs font-semibold mt-1">সাধারণ সদস্য</p>
                
                @if($member->district)
                <p class="text-gray-400 text-xs mt-1">{{ $member->district->name }}</p>
                @endif
                
                @if($member->member_id)
                <p class="text-gray-300 text-xs mt-2 font-mono">{{ $member->member_id }}</p>
                @endif
            </div>
            @endforeach
        @endif

    </div>

    {{-- Empty state --}}
    @if((!isset($volunteers) || $volunteers->isEmpty()) && (!isset($generalMembers) || $generalMembers->count() === 0))
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

            <a href="{{ route('contact') }}"
                class="inline-block bg-white text-blue-700 font-extrabold px-10 py-4 rounded-full hover:bg-blue-50 transition shadow-md text-sm">
                🤝 যোগাযোগ করুন ও আবেদন করুন
            </a>

        </div>
    </div>
</div>

@endsection