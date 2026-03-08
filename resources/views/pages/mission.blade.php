@extends('layouts.app')

@section('title', 'মিশন ও ভিশন - ' . ($settings->site_name ?? config('app.name')))

@section('content')

{{-- Hero --}}
<section class="relative bg-gradient-to-br from-blue-950 via-blue-900 to-blue-700 text-white py-24 px-4 overflow-hidden">
    <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/asfalt-dark.png')]"></div>
    <div class="absolute top-0 right-0 w-96 h-96 bg-blue-400 opacity-10 rounded-full -translate-y-1/2 translate-x-1/3"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 bg-white opacity-5 rounded-full translate-y-1/2 -translate-x-1/3"></div>
    <div class="relative max-w-3xl mx-auto text-center">
        <p class="text-blue-300 uppercase tracking-widest text-sm font-semibold mb-4">আমাদের পথচলা</p>
        <h1 class="text-4xl md:text-6xl font-extrabold mb-6 leading-tight">মিশন ও ভিশন</h1>
        <p class="text-blue-100 text-lg max-w-2xl mx-auto leading-relaxed">
            আমরা কোথায় যেতে চাই এবং কীভাবে সেখানে পৌঁছাতে চাই — আমাদের লক্ষ্য ও স্বপ্নের কথা।
        </p>
    </div>
</section>

{{-- Mission & Vision Cards --}}
<section class="py-20 bg-white">
    <div class="max-w-6xl mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">

            {{-- Mission --}}
            <div class="relative bg-gradient-to-br from-blue-50 to-white border border-blue-100 rounded-3xl p-10 shadow-sm hover:shadow-lg transition">
                <div class="absolute top-8 right-8 text-6xl opacity-10">🎯</div>
                <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center text-3xl mb-6">🎯</div>
                <h2 class="text-3xl font-extrabold text-blue-900 mb-5">আমাদের মিশন</h2>
                <div class="space-y-4 text-gray-600 leading-relaxed">
                    <p>
                        সমাজের সুবিধাবঞ্চিত ও পিছিয়ে পড়া মানুষদের শিক্ষা, স্বাস্থ্য ও অর্থনৈতিক উন্নয়নে
                        সহায়তা প্রদান করা।
                    </p>
                    <p>
                        প্রতিটি মানুষের মৌলিক অধিকার নিশ্চিত করতে কমিউনিটি পর্যায়ে কার্যকর ও
                        টেকসই কর্মসূচি বাস্তবায়ন করা।
                    </p>
                    <p>
                        স্বচ্ছতা ও জবাবদিহিতার মাধ্যমে দাতাদের আস্থা অর্জন করে সমাজের ইতিবাচক
                        পরিবর্তনে অগ্রণী ভূমিকা পালন করা।
                    </p>
                </div>
            </div>

            {{-- Vision --}}
            <div class="relative bg-gradient-to-br from-green-50 to-white border border-green-100 rounded-3xl p-10 shadow-sm hover:shadow-lg transition">
                <div class="absolute top-8 right-8 text-6xl opacity-10">🌟</div>
                <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center text-3xl mb-6">🌟</div>
                <h2 class="text-3xl font-extrabold text-green-900 mb-5">আমাদের ভিশন</h2>
                <div class="space-y-4 text-gray-600 leading-relaxed">
                    <p>
                        এমন একটি সমাজ গড়ে তোলা যেখানে প্রতিটি মানুষ তার সম্পূর্ণ সম্ভাবনা বিকাশ
                        করতে পারে এবং মর্যাদার সাথে জীবনযাপন করতে পারে।
                    </p>
                    <p>
                        একটি ন্যায়সংগত, সমতাভিত্তিক ও মানবিক বাংলাদেশ বিনির্মাণে দৃঢ়ভাবে
                        প্রতিজ্ঞাবদ্ধ থাকা।
                    </p>
                    <p>
                        ২০৩০ সালের মধ্যে দেশের প্রতিটি জেলায় সক্রিয় কার্যক্রম পরিচালনার মাধ্যমে
                        লক্ষাধিক মানুষের জীবনে ইতিবাচক পরিবর্তন আনা।
                    </p>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- Core Values --}}
<section class="py-20 bg-gray-50">
    <div class="max-w-6xl mx-auto px-4">
        <div class="text-center mb-14">
            <p class="text-blue-500 uppercase tracking-widest text-sm font-semibold mb-3">আমরা যা বিশ্বাস করি</p>
            <h2 class="text-3xl md:text-4xl font-extrabold text-blue-900">আমাদের মূল মূল্যবোধ</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach([
                ['🤝', 'সততা ও নিষ্ঠা', 'আমরা বিশ্বাস করি যে সৎভাবে কাজ করা এবং প্রতিশ্রুতি রক্ষা করাই সফলতার মূল চাবিকাঠি।', 'blue'],
                ['❤️', 'মানবতা ও সহমর্মিতা', 'প্রতিটি মানুষের দুঃখ-কষ্টকে নিজের বলে অনুভব করা এবং সহায়তার হাত বাড়িয়ে দেওয়া।', 'red'],
                ['🔍', 'স্বচ্ছতা ও জবাবদিহিতা', 'প্রতিটি কাজের হিসাব উন্মুক্ত রাখা এবং দাতাদের কাছে সম্পূর্ণ জবাবদিহি নিশ্চিত করা।', 'green'],
                ['⚖️', 'ন্যায়বিচার ও সমতা', 'সমাজের সকল স্তরের মানুষের সমান অধিকার ও সুযোগ নিশ্চিত করতে কাজ করা।', 'yellow'],
                ['🌱', 'টেকসই উন্নয়ন', 'এমন কর্মসূচি গ্রহণ করা যা দীর্ঘমেয়াদে সমাজে ইতিবাচক পরিবর্তন আনতে সক্ষম।', 'teal'],
                ['🤲', 'অংশীদারিত্ব ও একতা', 'সকলের সম্মিলিত প্রচেষ্টায় একটি সুন্দর সমাজ গড়ে তোলায় বিশ্বাসী।', 'purple'],
            ] as [$icon, $title, $desc, $color])
            <div class="bg-white border border-{{ $color }}-100 rounded-2xl p-7 shadow-sm hover:shadow-md transition group">
                <div class="w-12 h-12 bg-{{ $color }}-50 rounded-xl flex items-center justify-center text-2xl mb-5 group-hover:scale-110 transition">
                    {{ $icon }}
                </div>
                <h3 class="text-lg font-extrabold text-gray-800 mb-3">{{ $title }}</h3>
                <p class="text-gray-500 text-sm leading-relaxed">{{ $desc }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Strategic Goals --}}
<section class="py-20 bg-white">
    <div class="max-w-6xl mx-auto px-4">
        <div class="text-center mb-14">
            <p class="text-blue-500 uppercase tracking-widest text-sm font-semibold mb-3">আমাদের পরিকল্পনা</p>
            <h2 class="text-3xl md:text-4xl font-extrabold text-blue-900">কৌশলগত লক্ষ্যমাত্রা</h2>
        </div>
        <div class="space-y-5">
            @foreach([
                ['০১', 'শিক্ষায় বিনিয়োগ', 'সুবিধাবঞ্চিত ১০,০০০ শিশুকে মানসম্মত শিক্ষার সুযোগ করে দেওয়া।'],
                ['০২', 'স্বাস্থ্যসেবা নিশ্চিত', 'প্রত্যন্ত অঞ্চলে বিনামূল্যে স্বাস্থ্যসেবা ক্যাম্প আয়োজন করা।'],
                ['০৩', 'নারী ক্ষমতায়ন', '৫,০০০ নারীকে আত্মনির্ভরশীল করে তুলতে দক্ষতা উন্নয়ন প্রশিক্ষণ প্রদান।'],
                ['০৪', 'দুর্যোগ প্রস্তুতি', 'প্রতিটি জেলায় দ্রুত সাড়া দেওয়ার সক্ষমতা গড়ে তোলা।'],
                ['০৫', 'পরিবেশ সংরক্ষণ', 'বৃক্ষরোপণ ও পরিবেশ সচেতনতা কার্যক্রমের মাধ্যমে সবুজ বাংলাদেশ গড়া।'],
            ] as [$num, $title, $desc])
            <div class="flex items-start gap-6 bg-gray-50 border border-gray-100 rounded-2xl p-6 hover:border-blue-200 hover:bg-blue-50 transition group">
                <div class="text-3xl font-extrabold text-blue-200 group-hover:text-blue-400 transition flex-shrink-0 w-12 text-center">
                    {{ $num }}
                </div>
                <div>
                    <h3 class="text-lg font-extrabold text-gray-800 mb-1 group-hover:text-blue-700 transition">{{ $title }}</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">{{ $desc }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="py-16 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4">
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-3xl p-12 text-white text-center relative overflow-hidden">
            <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/asfalt-dark.png')]"></div>
            <div class="relative">
                <h2 class="text-3xl font-extrabold mb-4">আমাদের স্বপ্নের অংশীদার হোন</h2>
                <p class="text-blue-200 text-sm max-w-xl mx-auto mb-8 leading-relaxed">
                    আপনার সহযোগিতাই আমাদের মিশন সফল করার শক্তি। একসাথে আমরা একটি সুন্দর বাংলাদেশ গড়তে পারি।
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