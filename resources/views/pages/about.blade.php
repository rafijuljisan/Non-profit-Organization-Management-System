@extends('layouts.app')

@section('title', 'আমাদের সম্পর্কে - ' . ($settings->site_name ?? config('app.name')))

@section('content')

{{-- Hero --}}
<section class="relative bg-gradient-to-br from-blue-950 via-blue-900 to-blue-700 text-white py-24 px-4 overflow-hidden">
    <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/asfalt-dark.png')]"></div>
    <div class="absolute top-0 right-0 w-96 h-96 bg-blue-500 opacity-10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 bg-white opacity-5 rounded-full translate-y-1/2 -translate-x-1/2"></div>
    <div class="relative max-w-4xl mx-auto text-center">
        <p class="text-blue-300 uppercase tracking-widest text-sm font-semibold mb-4">আমাদের পরিচয়</p>
        <h1 class="text-4xl md:text-6xl font-extrabold mb-6 leading-tight">
            {{ $settings->site_name ?? 'একতা জনকল্যান ফাউন্ডেশন' }}
        </h1>
        <p class="text-blue-100 text-lg md:text-xl max-w-2xl mx-auto leading-relaxed">
            {{ $settings->tagline ?? 'মানুষের পাশে থেকে একটি সুন্দর, ন্যায়সংগত ও মানবিক সমাজ গড়ে তোলার লক্ষ্যে আমরা অঙ্গীকারবদ্ধ।' }}
        </p>
    </div>
</section>

{{-- Who We Are --}}
<section class="py-20 bg-white">
    <div class="max-w-6xl mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div>
                <p class="text-blue-500 uppercase tracking-widest text-sm font-semibold mb-3">আমরা কারা</p>
                <h2 class="text-3xl md:text-4xl font-extrabold text-blue-900 mb-6 leading-snug">
                    একটি স্বচ্ছ ও জবাবদিহিমূলক সেবামূলক সংগঠন
                </h2>
                <div class="space-y-4 text-gray-600 text-base leading-relaxed">
                    <p>
                        <strong class="text-blue-800">{{ $settings->site_name ?? 'একতা জনকল্যান ফাউন্ডেশন' }}</strong> একটি অরাজনৈতিক, অলাভজনক সেবামূলক সংগঠন।
                        আমরা বিশ্বাস করি যে সমাজের প্রতিটি মানুষ একটি মর্যাদাপূর্ণ জীবনযাপনের অধিকারী।
                    </p>
                    <p>
                        শিক্ষা, স্বাস্থ্য, দারিদ্র্য বিমোচন ও দুর্যোগকালীন ত্রাণ কার্যক্রমের মাধ্যমে আমরা
                        সুবিধাবঞ্চিত মানুষদের জীবনমান উন্নয়নে নিরলসভাবে কাজ করে যাচ্ছি।
                    </p>
                    <p>
                        আমাদের প্রতিটি কার্যক্রম সম্পূর্ণ স্বচ্ছতার সাথে পরিচালিত হয় এবং
                        দাতাদের অর্থের সর্বোচ্চ সদ্ব্যবহার নিশ্চিত করা আমাদের প্রধান দায়িত্ব।
                    </p>
                </div>
                <div class="mt-8 flex flex-wrap gap-4">
                    <a href="{{ route('donate.index') }}"
                        class="bg-blue-600 text-white font-bold px-7 py-3 rounded-full hover:bg-blue-700 transition shadow-md text-sm">
                        দান করুন
                    </a>
                    <a href="{{ route('contact') }}"
                        class="border-2 border-blue-600 text-blue-600 font-bold px-7 py-3 rounded-full hover:bg-blue-50 transition text-sm">
                        যোগাযোগ করুন
                    </a>
                </div>
            </div>

            {{-- Stats --}}
            <div class="grid grid-cols-2 gap-5">
                <div class="bg-blue-50 border border-blue-100 rounded-2xl p-6 text-center hover:shadow-md transition">
                    <p class="text-4xl font-extrabold text-blue-700 mb-2">{{ $totalMembers ?? '০' }}+</p>
                    <p class="text-gray-600 font-semibold text-sm">সক্রিয় সদস্য</p>
                </div>
                <div class="bg-green-50 border border-green-100 rounded-2xl p-6 text-center hover:shadow-md transition">
                    <p class="text-4xl font-extrabold text-green-700 mb-2">{{ $totalProjects ?? '০' }}+</p>
                    <p class="text-gray-600 font-semibold text-sm">সম্পন্ন প্রকল্প</p>
                </div>
                <div class="bg-orange-50 border border-orange-100 rounded-2xl p-6 text-center hover:shadow-md transition">
                    <p class="text-4xl font-extrabold text-orange-600 mb-2">{{ $totalDistricts ?? '০' }}+</p>
                    <p class="text-gray-600 font-semibold text-sm">জেলায় কার্যক্রম</p>
                </div>
                <div class="bg-purple-50 border border-purple-100 rounded-2xl p-6 text-center hover:shadow-md transition">
                    <p class="text-4xl font-extrabold text-purple-700 mb-2">১০০%</p>
                    <p class="text-gray-600 font-semibold text-sm">স্বচ্ছতা নিশ্চিত</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Mission & Vision --}}
<section class="py-20 bg-gray-50">
    <div class="max-w-6xl mx-auto px-4">
        <div class="text-center mb-14">
            <p class="text-blue-500 uppercase tracking-widest text-sm font-semibold mb-3">আমাদের লক্ষ্য</p>
            <h2 class="text-3xl md:text-4xl font-extrabold text-blue-900">মিশন ও ভিশন</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white border border-blue-100 rounded-2xl p-8 shadow-sm hover:shadow-md transition">
                <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center text-2xl mb-5">🎯</div>
                <h3 class="text-xl font-extrabold text-blue-900 mb-4">আমাদের মিশন</h3>
                <p class="text-gray-600 leading-relaxed text-sm">
                    সমাজের সুবিধাবঞ্চিত ও পিছিয়ে পড়া মানুষদের শিক্ষা, স্বাস্থ্য ও অর্থনৈতিক উন্নয়নে
                    সহায়তা প্রদান করা। প্রতিটি মানুষের মৌলিক অধিকার নিশ্চিত করতে কমিউনিটি পর্যায়ে
                    কার্যকর ও টেকসই কর্মসূচি বাস্তবায়ন করা।
                </p>
            </div>
            <div class="bg-white border border-green-100 rounded-2xl p-8 shadow-sm hover:shadow-md transition">
                <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center text-2xl mb-5">🌟</div>
                <h3 class="text-xl font-extrabold text-green-900 mb-4">আমাদের ভিশন</h3>
                <p class="text-gray-600 leading-relaxed text-sm">
                    এমন একটি সমাজ গড়ে তোলা যেখানে প্রতিটি মানুষ তার সম্পূর্ণ সম্ভাবনা বিকাশ করতে পারে।
                    একটি ন্যায়সংগত, সমতাভিত্তিক ও মানবিক বাংলাদেশ বিনির্মাণে আমরা দৃঢ়প্রতিজ্ঞ।
                </p>
            </div>
        </div>
    </div>
</section>

{{-- Core Values --}}
<section class="py-20 bg-white">
    <div class="max-w-6xl mx-auto px-4">
        <div class="text-center mb-14">
            <p class="text-blue-500 uppercase tracking-widest text-sm font-semibold mb-3">আমরা যা বিশ্বাস করি</p>
            <h2 class="text-3xl md:text-4xl font-extrabold text-blue-900">আমাদের মূল মূল্যবোধ</h2>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-5 text-center">
            @foreach([
                ['🤝', 'সততা', 'bg-blue-50 border-blue-100 text-blue-700'],
                ['❤️', 'মানবতা', 'bg-red-50 border-red-100 text-red-700'],
                ['🔍', 'স্বচ্ছতা', 'bg-green-50 border-green-100 text-green-700'],
                ['⚖️', 'ন্যায়বিচার', 'bg-yellow-50 border-yellow-100 text-yellow-700'],
                ['🌱', 'টেকসই উন্নয়ন', 'bg-teal-50 border-teal-100 text-teal-700'],
                ['🤲', 'অংশীদারিত্ব', 'bg-purple-50 border-purple-100 text-purple-700'],
            ] as [$icon, $label, $cls])
            <div class="border {{ $cls }} rounded-xl p-5 hover:shadow-md transition">
                <div class="text-3xl mb-3">{{ $icon }}</div>
                <p class="font-bold text-sm">{{ $label }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- What We Do --}}
<section class="py-20 bg-blue-900 text-white">
    <div class="max-w-6xl mx-auto px-4">
        <div class="text-center mb-14">
            <p class="text-blue-300 uppercase tracking-widest text-sm font-semibold mb-3">কার্যক্ষেত্র</p>
            <h2 class="text-3xl md:text-4xl font-extrabold">আমরা যা করি</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach([
                ['🎓', 'শিক্ষা কার্যক্রম', 'সুবিধাবঞ্চিত শিশুদের বৃত্তি, বই ও শিক্ষা উপকরণ প্রদান করি।'],
                ['🏥', 'স্বাস্থ্যসেবা', 'বিনামূল্যে চিকিৎসা সেবা, ওষুধ ও স্বাস্থ্য সচেতনতা কার্যক্রম।'],
                ['💧', 'বিশুদ্ধ পানি', 'গ্রামাঞ্চলে নলকূপ স্থাপন ও বিশুদ্ধ পানি সরবরাহ নিশ্চিত করা।'],
                ['🌾', 'কৃষি সহায়তা', 'কৃষকদের প্রশিক্ষণ, বীজ ও কৃষি উপকরণ সহায়তা প্রদান।'],
                ['👩‍💼', 'নারী ক্ষমতায়ন', 'নারীদের দক্ষতা উন্নয়ন ও আয়বর্ধনমূলক প্রশিক্ষণ কার্যক্রম।'],
                ['🆘', 'দুর্যোগ ত্রাণ', 'প্রাকৃতিক দুর্যোগে ক্ষতিগ্রস্তদের তাৎক্ষণিক সহায়তা।'],
            ] as [$icon, $title, $desc])
            <div class="bg-blue-800 hover:bg-blue-700 rounded-2xl p-6 transition">
                <div class="text-3xl mb-4">{{ $icon }}</div>
                <h3 class="text-lg font-extrabold mb-2">{{ $title }}</h3>
                <p class="text-blue-200 text-sm leading-relaxed">{{ $desc }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Contact Info --}}
@if(($settings->address ?? null) || ($settings->phone ?? null) || ($settings->email ?? null))
<section class="py-20 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <p class="text-blue-500 uppercase tracking-widest text-sm font-semibold mb-3">আমাদের ঠিকানা</p>
        <h2 class="text-3xl font-extrabold text-blue-900 mb-10">আমাদের সাথে যোগাযোগ করুন</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @if($settings->address ?? null)
            <div class="bg-white border border-gray-100 rounded-xl p-6 shadow-sm">
                <div class="text-3xl mb-3">📍</div>
                <p class="font-bold text-gray-800 mb-1 text-sm">ঠিকানা</p>
                <p class="text-gray-500 text-sm leading-relaxed">{{ $settings->address }}</p>
            </div>
            @endif
            @if($settings->phone ?? null)
            <div class="bg-white border border-gray-100 rounded-xl p-6 shadow-sm">
                <div class="text-3xl mb-3">📞</div>
                <p class="font-bold text-gray-800 mb-1 text-sm">ফোন</p>
                <a href="tel:{{ $settings->phone }}" class="text-blue-600 font-bold text-sm hover:underline">{{ $settings->phone }}</a>
            </div>
            @endif
            @if($settings->email ?? null)
            <div class="bg-white border border-gray-100 rounded-xl p-6 shadow-sm">
                <div class="text-3xl mb-3">✉️</div>
                <p class="font-bold text-gray-800 mb-1 text-sm">ইমেইল</p>
                <a href="mailto:{{ $settings->email }}" class="text-blue-600 font-bold text-sm hover:underline">{{ $settings->email }}</a>
            </div>
            @endif
        </div>
    </div>
</section>
@endif

{{-- CTA --}}
<section class="py-16 bg-white">
    <div class="max-w-4xl mx-auto px-4">
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-3xl p-12 text-white text-center relative overflow-hidden">
            <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/asfalt-dark.png')]"></div>
            <div class="relative">
                <h2 class="text-3xl font-extrabold mb-4">আমাদের সাথে পরিবর্তনের অংশ হোন</h2>
                <p class="text-blue-200 text-sm max-w-xl mx-auto mb-8 leading-relaxed">
                    আপনার একটি ছোট্ট পদক্ষেপ হাজারো মানুষের জীবনে আলো জ্বালাতে পারে।
                    দান করুন অথবা স্বেচ্ছাসেবক হিসেবে যোগ দিন।
                </p>
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="{{ route('donate.index') }}"
                        class="bg-white text-blue-700 font-extrabold px-8 py-3 rounded-full hover:bg-blue-50 transition shadow-md text-sm">
                        💚 এখনই দান করুন
                    </a>
                    <a href="{{ route('volunteer') }}"
                        class="border-2 border-white text-white font-extrabold px-8 py-3 rounded-full hover:bg-white hover:text-blue-700 transition text-sm">
                        🤝 স্বেচ্ছাসেবক হোন
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection