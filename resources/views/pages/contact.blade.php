@extends('layouts.app')

@section('title', 'যোগাযোগ করুন - ' . ($settings->site_name ?? config('app.name')))

@section('content')

{{-- Hero Section --}}
<section class="bg-gradient-to-br from-blue-700 to-blue-900 text-white py-20 px-4 text-center relative overflow-hidden">
    <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/asfalt-dark.png')]"></div>
    <div class="relative max-w-3xl mx-auto">
        <p class="text-blue-300 uppercase tracking-widest text-sm font-semibold mb-3">আমাদের সাথে থাকুন</p>
        <h1 class="text-4xl md:text-5xl font-extrabold mb-4 leading-tight">আমাদের সাথে যোগাযোগ করুন</h1>
        <p class="text-blue-200 text-lg">আপনার যেকোনো প্রশ্ন, পরামর্শ বা সহযোগিতার প্রস্তাব নিয়ে আমরা সর্বদা আপনার পাশে আছি।</p>
    </div>
</section>

<div class="max-w-6xl mx-auto px-4 py-16">

    {{-- Success Message --}}
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-800 p-5 mb-10 rounded-lg shadow-sm flex items-start gap-3">
            <span class="text-2xl">✅</span>
            <div>
                <p class="font-bold">বার্তা পাঠানো সম্পন্ন হয়েছে!</p>
                <p class="text-sm mt-1">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

        {{-- Contact Info Cards --}}
        <div class="lg:col-span-1 space-y-6">

            <div>
                <h2 class="text-2xl font-bold text-blue-900 mb-2">আমাদের তথ্য</h2>
                <p class="text-gray-500 text-sm">নিচের যেকোনো মাধ্যমে আমাদের সাথে সরাসরি যোগাযোগ করতে পারেন।</p>
            </div>

            @if($settings->address ?? null)
            <div class="bg-white border border-gray-100 rounded-xl p-5 shadow-sm flex items-start gap-4">
                <div class="bg-blue-100 text-blue-600 rounded-full p-3 text-xl">📍</div>
                <div>
                    <p class="font-bold text-gray-800 mb-1">ঠিকানা</p>
                    <p class="text-gray-600 text-sm leading-relaxed">{{ $settings->address }}</p>
                </div>
            </div>
            @endif

            @if($settings->phone ?? null)
            <div class="bg-white border border-gray-100 rounded-xl p-5 shadow-sm flex items-start gap-4">
                <div class="bg-green-100 text-green-600 rounded-full p-3 text-xl">📞</div>
                <div>
                    <p class="font-bold text-gray-800 mb-1">ফোন নম্বর</p>
                    <a href="tel:{{ $settings->phone }}" class="text-green-600 font-semibold hover:underline text-sm">{{ $settings->phone }}</a>
                </div>
            </div>
            @endif

            @if($settings->email ?? null)
            <div class="bg-white border border-gray-100 rounded-xl p-5 shadow-sm flex items-start gap-4">
                <div class="bg-purple-100 text-purple-600 rounded-full p-3 text-xl">✉️</div>
                <div>
                    <p class="font-bold text-gray-800 mb-1">ইমেইল</p>
                    <a href="mailto:{{ $settings->email }}" class="text-purple-600 font-semibold hover:underline text-sm">{{ $settings->email }}</a>
                </div>
            </div>
            @endif

            @if(($settings->facebook_url ?? null) || ($settings->youtube_url ?? null))
            <div class="bg-white border border-gray-100 rounded-xl p-5 shadow-sm">
                <p class="font-bold text-gray-800 mb-3">সোশ্যাল মিডিয়া</p>
                <div class="flex gap-3">
                    @if($settings->facebook_url ?? null)
                    <a href="{{ $settings->facebook_url }}" target="_blank"
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-700 transition">
                        Facebook
                    </a>
                    @endif
                    @if($settings->youtube_url ?? null)
                    <a href="{{ $settings->youtube_url }}" target="_blank"
                        class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-red-700 transition">
                        YouTube
                    </a>
                    @endif
                </div>
            </div>
            @endif

        </div>

        {{-- Contact Form --}}
        <div class="lg:col-span-2">
            <div class="bg-white p-8 rounded-2xl shadow-md border border-gray-100">

                <h2 class="text-2xl font-bold text-blue-900 mb-1">বার্তা পাঠান</h2>
                <p class="text-gray-500 text-sm mb-8">নিচের ফর্মটি পূরণ করুন, আমরা যত দ্রুত সম্ভব আপনার সাথে যোগাযোগ করব।</p>

                <form action="{{ route('inquiry.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <input type="hidden" name="type" value="contact">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2 text-sm">পূর্ণ নাম <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                placeholder="আপনার নাম লিখুন"
                                class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none text-sm transition @error('name') border-red-400 @enderror">
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2 text-sm">মোবাইল নম্বর <span class="text-red-500">*</span></label>
                            <input type="text" name="phone" value="{{ old('phone') }}" required
                                placeholder="০১XXXXXXXXX"
                                class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none text-sm transition @error('phone') border-red-400 @enderror">
                            @error('phone')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-2 text-sm">ইমেইল ঠিকানা <span class="text-gray-400 font-normal">(ঐচ্ছিক)</span></label>
                        <input type="email" name="email" value="{{ old('email') }}"
                            placeholder="example@email.com"
                            class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none text-sm transition @error('email') border-red-400 @enderror">
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-2 text-sm">বিষয় <span class="text-gray-400 font-normal">(ঐচ্ছিক)</span></label>
                        <input type="text" name="subject" value="{{ old('subject') }}"
                            placeholder="কী বিষয়ে যোগাযোগ করছেন?"
                            class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none text-sm transition">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-2 text-sm">আপনার বার্তা <span class="text-red-500">*</span></label>
                        <textarea name="message" rows="5" required
                            placeholder="আপনার বার্তা বিস্তারিত লিখুন..."
                            class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none text-sm transition resize-none @error('message') border-red-400 @enderror">{{ old('message') }}</textarea>
                        @error('message')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                        class="w-full bg-blue-600 text-white font-bold py-3 px-6 rounded-lg hover:bg-blue-700 active:scale-95 transition duration-200 shadow-md text-sm tracking-wide">
                        ✉️ &nbsp; বার্তা পাঠান
                    </button>

                </form>
            </div>
        </div>

    </div>

    {{-- Bottom Trust Banner --}}
    <div class="mt-16 bg-blue-50 border border-blue-100 rounded-2xl px-8 py-10 text-center">
        <p class="text-blue-400 text-sm uppercase tracking-widest font-semibold mb-2">আমাদের প্রতিশ্রুতি</p>
        <h3 class="text-2xl font-extrabold text-blue-900 mb-3">আপনার বিশ্বাসই আমাদের শক্তি</h3>
        <p class="text-gray-600 max-w-2xl mx-auto text-sm leading-relaxed">
            আমরা একটি স্বচ্ছ ও জবাবদিহিমূলক সংগঠন। আপনার প্রতিটি বার্তা আমাদের কাছে অত্যন্ত গুরুত্বপূর্ণ।
            আমরা সর্বদা সততা, মানবতা ও সেবার মনোভাব নিয়ে কাজ করি।
        </p>
        <div class="flex justify-center gap-8 mt-8 text-center flex-wrap">
            <div>
                <p class="text-3xl font-extrabold text-blue-700">২৪ ঘণ্টা</p>
                <p class="text-gray-500 text-sm mt-1">সাড়া দেওয়ার লক্ষ্যমাত্রা</p>
            </div>
            <div class="border-l border-blue-200 hidden sm:block"></div>
            <div>
                <p class="text-3xl font-extrabold text-blue-700">১০০%</p>
                <p class="text-gray-500 text-sm mt-1">স্বচ্ছতা নিশ্চিত</p>
            </div>
            <div class="border-l border-blue-200 hidden sm:block"></div>
            <div>
                <p class="text-3xl font-extrabold text-blue-700">বিনামূল্যে</p>
                <p class="text-gray-500 text-sm mt-1">পরামর্শ ও সহায়তা</p>
            </div>
        </div>
    </div>

</div>
@endsection