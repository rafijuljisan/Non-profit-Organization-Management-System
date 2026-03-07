@extends('layouts.app')

@section('title', 'স্বচ্ছতা পোর্টাল - ' . ($settings->site_name ?? 'একতা জনকল্যান ফাউন্ডেশন'))

@section('content')

{{-- ===== HERO ===== --}}
<section class="relative bg-gradient-to-br from-blue-900 via-blue-800 to-blue-600 text-white overflow-hidden">
    <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/asfalt-dark.png')]"></div>
    <div class="absolute bottom-0 left-0 right-0 h-16 bg-white" style="clip-path: ellipse(55% 100% at 50% 100%);"></div>

    <div class="relative max-w-5xl mx-auto px-4 py-24 text-center">
        @if($settings->site_logo ?? null)
            <img src="{{ asset('storage/' . $settings->site_logo) }}" alt="Logo" class="h-20 mx-auto mb-6 drop-shadow-lg">
        @endif
        <p class="text-blue-300 uppercase tracking-widest text-sm font-semibold mb-3">স্বচ্ছতা ও জবাবদিহিতা</p>
        <h1 class="text-4xl md:text-6xl font-extrabold mb-6 leading-tight drop-shadow">
            {{ $settings->site_name ?? 'একতা জনকল্যান ফাউন্ডেশন' }}
        </h1>
        <p class="text-blue-100 text-lg md:text-xl mb-10 max-w-2xl mx-auto leading-relaxed">
            {{ $settings->tagline ?? 'মানুষের পাশে থেকে একটি সুন্দর আগামী গড়ে তুলতে আমরা প্রতিশ্রুতিবদ্ধ।' }}
        </p>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="{{ route('donate.index') }}" class="bg-green-500 hover:bg-green-600 text-white font-bold px-8 py-3 rounded-full shadow-lg transition text-sm">
                💚 এখনই দান করুন
            </a>
            <a href="#dashboard" class="bg-white text-blue-800 hover:bg-blue-50 font-bold px-8 py-3 rounded-full shadow-lg transition text-sm">
                📊 স্বচ্ছতা ড্যাশবোর্ড
            </a>
            <a href="{{ route('volunteer') }}" class="border border-white text-white hover:bg-white hover:text-blue-800 font-bold px-8 py-3 rounded-full transition text-sm">
                🤝 স্বেচ্ছাসেবক হোন
            </a>
        </div>
    </div>
</section>

{{-- ===== LIVE STATS DASHBOARD ===== --}}
<section id="dashboard" class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-12">
            <p class="text-blue-500 uppercase tracking-widest text-sm font-semibold mb-2">রিয়েলটাইম তথ্য</p>
            <h2 class="text-3xl md:text-4xl font-extrabold text-blue-900">লাইভ স্বচ্ছতা ড্যাশবোর্ড</h2>
            <p class="text-gray-500 mt-3 text-sm">প্রতিটি টাকা, প্রতিটি প্রকল্প — সবকিছু আপনার সামনে উন্মুক্ত।</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <div class="bg-blue-50 border-t-4 border-blue-500 rounded-xl p-6 text-center shadow-sm hover:shadow-md transition">
                <div class="text-4xl mb-2">👥</div>
                <div class="text-3xl font-extrabold text-blue-700">{{ number_format($totalMembers) }}</div>
                <div class="text-gray-500 text-sm font-semibold mt-1 uppercase tracking-wide">সক্রিয় সদস্য</div>
            </div>
            <div class="bg-green-50 border-t-4 border-green-500 rounded-xl p-6 text-center shadow-sm hover:shadow-md transition">
                <div class="text-4xl mb-2">💰</div>
                <div class="text-3xl font-extrabold text-green-700">৳{{ number_format($totalFund) }}</div>
                <div class="text-gray-500 text-sm font-semibold mt-1 uppercase tracking-wide">মোট তহবিল</div>
            </div>
            <div class="bg-purple-50 border-t-4 border-purple-500 rounded-xl p-6 text-center shadow-sm hover:shadow-md transition">
                <div class="text-4xl mb-2">📋</div>
                <div class="text-3xl font-extrabold text-purple-700">{{ number_format($totalProjects) }}</div>
                <div class="text-gray-500 text-sm font-semibold mt-1 uppercase tracking-wide">মোট প্রকল্প</div>
            </div>
            <div class="bg-orange-50 border-t-4 border-orange-500 rounded-xl p-6 text-center shadow-sm hover:shadow-md transition">
                <div class="text-4xl mb-2">🏘️</div>
                <div class="text-3xl font-extrabold text-orange-700">{{ number_format($totalDistricts ?? 0) }}</div>
                <div class="text-gray-500 text-sm font-semibold mt-1 uppercase tracking-wide">জেলায় কার্যক্রম</div>
            </div>
        </div>

        {{-- Fund Breakdown --}}
        @if(isset($totalDonations) || isset($totalExpenses))
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
            <div class="bg-teal-50 border border-teal-200 rounded-xl p-6 flex items-center gap-5 shadow-sm">
                <div class="bg-teal-500 text-white rounded-full p-4 text-2xl">📥</div>
                <div>
                    <p class="text-gray-500 text-sm font-semibold uppercase tracking-wide">মোট অনুদান প্রাপ্তি</p>
                    <p class="text-2xl font-extrabold text-teal-700 mt-1">৳{{ number_format($totalDonations ?? 0) }}</p>
                </div>
            </div>
            <div class="bg-red-50 border border-red-200 rounded-xl p-6 flex items-center gap-5 shadow-sm">
                <div class="bg-red-400 text-white rounded-full p-4 text-2xl">📤</div>
                <div>
                    <p class="text-gray-500 text-sm font-semibold uppercase tracking-wide">মোট ব্যয়</p>
                    <p class="text-2xl font-extrabold text-red-600 mt-1">৳{{ number_format($totalExpenses ?? 0) }}</p>
                </div>
            </div>
        </div>
        @endif
    </div>
</section>

{{-- ===== ONGOING PROJECTS ===== --}}
<section id="projects" class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-12">
            <p class="text-blue-500 uppercase tracking-widest text-sm font-semibold mb-2">আমাদের কার্যক্রম</p>
            <h2 class="text-3xl md:text-4xl font-extrabold text-blue-900">চলমান প্রকল্পসমূহ</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @forelse($ongoingProjects as $project)
            <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-sm hover:shadow-lg transition group">
                <div class="bg-gradient-to-r from-blue-600 to-blue-400 h-2"></div>
                <div class="p-6">
                    <div class="flex justify-between items-start mb-3">
                        <span class="text-xs text-blue-600 font-bold bg-blue-50 px-3 py-1 rounded-full">
                            📍 {{ $project->district->name ?? 'কেন্দ্রীয়' }}
                        </span>
                        <span class="bg-green-100 text-green-700 text-xs font-bold px-3 py-1 rounded-full">চলমান</span>
                    </div>
                    <h3 class="text-lg font-extrabold text-gray-900 mb-2 group-hover:text-blue-700 transition">{{ $project->name }}</h3>
                    <p class="text-gray-500 text-sm mb-5 line-clamp-3 leading-relaxed">{{ $project->description ?? 'বিস্তারিত তথ্য শীঘ্রই যোগ করা হবে।' }}</p>

                    @if($project->target_budget)
                    <div class="mt-auto">
                        <div class="flex justify-between text-xs font-semibold text-gray-500 mb-1">
                            <span>লক্ষ্যমাত্রা</span>
                            <span>৳{{ number_format($project->target_budget) }}</span>
                        </div>
                        @php
                            $collected = $project->donations_sum_amount ?? 0;
                            $percent = $project->target_budget > 0 ? min(100, round(($collected / $project->target_budget) * 100)) : 0;
                        @endphp
                        <div class="w-full bg-gray-100 rounded-full h-2">
                            <div class="bg-green-500 h-2 rounded-full transition-all" style="width: {{ $percent }}%"></div>
                        </div>
                        <p class="text-xs text-gray-400 mt-1">সংগৃহীত: ৳{{ number_format($collected) }} ({{ $percent }}%)</p>
                    </div>
                    @endif
                </div>
            </div>
            @empty
            <div class="col-span-3 text-center text-gray-400 py-16">
                <div class="text-5xl mb-4">📭</div>
                <p class="font-semibold">বর্তমানে কোনো চলমান প্রকল্প নেই।</p>
            </div>
            @endforelse
        </div>

        @if($ongoingProjects->count() > 0)
        <div class="text-center mt-10">
            <a href="{{ route('projects.index') }}" class="inline-block bg-blue-600 text-white font-bold px-8 py-3 rounded-full hover:bg-blue-700 transition shadow-md text-sm">
                সকল প্রকল্প দেখুন →
            </a>
        </div>
        @endif
    </div>
</section>

{{-- ===== RECENT DONATIONS ===== --}}
@if(isset($recentDonations) && $recentDonations->count() > 0)
<section class="py-16 bg-white">
    <div class="max-w-4xl mx-auto px-4">
        <div class="text-center mb-12">
            <p class="text-green-500 uppercase tracking-widest text-sm font-semibold mb-2">স্বচ্ছ হিসাব</p>
            <h2 class="text-3xl md:text-4xl font-extrabold text-blue-900">সাম্প্রতিক অনুদান</h2>
            <p class="text-gray-500 mt-3 text-sm">আমাদের প্রতি আস্থা রেখে যারা সাহায্যের হাত বাড়িয়েছেন।</p>
        </div>

        <div class="space-y-4">
            @foreach($recentDonations as $donation)
            <div class="flex items-center justify-between bg-green-50 border border-green-100 rounded-xl px-6 py-4 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="bg-green-500 text-white rounded-full w-10 h-10 flex items-center justify-center font-bold text-lg">
                        {{ mb_substr($donation->donor_name ?? 'অ', 0, 1) }}
                    </div>
                    <div>
                        <p class="font-bold text-gray-800 text-sm">{{ $donation->donor_name ?? 'বেনামী দাতা' }}</p>
                        <p class="text-gray-400 text-xs">{{ $donation->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-green-700 font-extrabold text-lg">৳{{ number_format($donation->amount) }}</p>
                    @if($donation->project)
                        <p class="text-gray-400 text-xs">{{ $donation->project->name }}</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        <div class="text-center mt-8">
            <a href="{{ route('donate.index') }}" class="inline-block bg-green-500 text-white font-bold px-8 py-3 rounded-full hover:bg-green-600 transition shadow-md text-sm">
                💚 আপনিও দান করুন
            </a>
        </div>
    </div>
</section>
@endif

{{-- ===== HOW WE WORK ===== --}}
<section class="py-16 bg-blue-900 text-white">
    <div class="max-w-6xl mx-auto px-4">
        <div class="text-center mb-12">
            <p class="text-blue-300 uppercase tracking-widest text-sm font-semibold mb-2">আমাদের পদ্ধতি</p>
            <h2 class="text-3xl md:text-4xl font-extrabold">আমরা কীভাবে কাজ করি?</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 text-center">
            @foreach([
                ['🎯', 'সমস্যা চিহ্নিতকরণ', 'স্থানীয় প্রয়োজনীয়তা বিশ্লেষণ করে লক্ষ্য নির্ধারণ।'],
                ['📢', 'তহবিল সংগ্রহ', 'সদস্য ও দাতাদের সহযোগিতায় অর্থ সংগ্রহ।'],
                ['🔨', 'বাস্তবায়ন', 'দক্ষ দলের মাধ্যমে প্রকল্প বাস্তবায়ন।'],
                ['📊', 'জবাবদিহিতা', 'প্রতিটি ব্যয়ের স্বচ্ছ হিসাব প্রকাশ।'],
            ] as $i => [$icon, $title, $desc])
            <div class="relative">
                <div class="bg-blue-800 rounded-2xl p-6 hover:bg-blue-700 transition">
                    <div class="text-4xl mb-4">{{ $icon }}</div>
                    <div class="bg-blue-500 text-white text-xs font-bold w-6 h-6 rounded-full flex items-center justify-center mx-auto mb-3">{{ $i + 1 }}</div>
                    <h3 class="font-bold text-lg mb-2">{{ $title }}</h3>
                    <p class="text-blue-200 text-sm leading-relaxed">{{ $desc }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== IMPACT AREAS ===== --}}
<section class="py-16 bg-gray-50">
    <div class="max-w-6xl mx-auto px-4">
        <div class="text-center mb-12">
            <p class="text-blue-500 uppercase tracking-widest text-sm font-semibold mb-2">আমাদের কার্যক্ষেত্র</p>
            <h2 class="text-3xl md:text-4xl font-extrabold text-blue-900">যেসব ক্ষেত্রে আমরা কাজ করি</h2>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 text-center">
            @foreach([
                ['🎓', 'শিক্ষা', 'bg-yellow-50 border-yellow-200 text-yellow-700'],
                ['🏥', 'স্বাস্থ্যসেবা', 'bg-red-50 border-red-200 text-red-700'],
                ['🌾', 'কৃষি উন্নয়ন', 'bg-green-50 border-green-200 text-green-700'],
                ['💧', 'বিশুদ্ধ পানি', 'bg-blue-50 border-blue-200 text-blue-700'],
                ['🏠', 'আশ্রয়ণ', 'bg-purple-50 border-purple-200 text-purple-700'],
                ['👩‍💼', 'নারী উন্নয়ন', 'bg-pink-50 border-pink-200 text-pink-700'],
            ] as [$icon, $label, $cls])
            <div class="border {{ $cls }} rounded-xl p-5 hover:shadow-md transition">
                <div class="text-3xl mb-2">{{ $icon }}</div>
                <p class="font-bold text-sm">{{ $label }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== VOLUNTEER CTA ===== --}}
<section class="py-16 bg-white">
    <div class="max-w-5xl mx-auto px-4">
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-3xl p-10 md:p-14 text-white text-center shadow-xl relative overflow-hidden">
            <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/asfalt-dark.png')]"></div>
            <div class="relative">
                <div class="text-5xl mb-4">🤝</div>
                <h2 class="text-3xl md:text-4xl font-extrabold mb-4">স্বেচ্ছাসেবক হিসেবে যোগ দিন</h2>
                <p class="text-blue-200 max-w-xl mx-auto text-sm leading-relaxed mb-8">
                    আপনার সময় ও দক্ষতা দিয়ে হাজারো মানুষের জীবনে পরিবর্তন আনুন।
                    আমাদের সাথে যোগ দিন এবং একটি সুন্দর সমাজ গড়ে তুলুন।
                </p>
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="{{ route('volunteer') }}" class="bg-white text-blue-700 font-bold px-8 py-3 rounded-full hover:bg-blue-50 transition shadow-md text-sm">
                        স্বেচ্ছাসেবক ফর্ম পূরণ করুন
                    </a>
                    <a href="{{ route('contact') }}" class="border border-white text-white font-bold px-8 py-3 rounded-full hover:bg-white hover:text-blue-700 transition text-sm">
                        আমাদের সাথে কথা বলুন
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===== RECEIPT FINDER ===== --}}
<section class="py-12 bg-blue-50">
    <div class="max-w-3xl mx-auto px-4 text-center">
        <div class="text-4xl mb-4">🔍</div>
        <h2 class="text-2xl font-extrabold text-blue-900 mb-2">আপনার রসিদ খুঁজুন</h2>
        <p class="text-gray-500 text-sm mb-6">আপনার দানের রসিদ নম্বর বা ফোন নম্বর দিয়ে তাৎক্ষণিক রসিদ যাচাই করুন।</p>
        <a href="{{ route('receipt.search') }}" class="inline-block bg-blue-600 text-white font-bold px-8 py-3 rounded-full hover:bg-blue-700 transition shadow-md text-sm">
            রসিদ যাচাই করুন →
        </a>
    </div>
</section>

@endsection