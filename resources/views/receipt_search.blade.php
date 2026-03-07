@extends('layouts.app')

@section('title', 'রসিদ খুঁজুন - ' . ($settings->site_name ?? config('app.name')))

@section('content')

{{-- Hero --}}
<section class="bg-gradient-to-br from-blue-900 to-blue-700 text-white py-16 px-4 text-center relative overflow-hidden">
    <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/asfalt-dark.png')]"></div>
    <div class="relative max-w-2xl mx-auto">
        <p class="text-blue-300 uppercase tracking-widest text-sm font-semibold mb-3">আপনার রসিদ</p>
        <h1 class="text-4xl md:text-5xl font-extrabold mb-4">দানের রসিদ খুঁজুন</h1>
        <p class="text-blue-100 text-lg">দান করার সময় ব্যবহৃত মোবাইল নম্বর দিয়ে আপনার সকল রসিদ খুঁজে বের করুন।</p>
    </div>
</section>

<div class="max-w-3xl mx-auto px-4 py-14">

    {{-- Search Box --}}
    <div class="bg-white rounded-2xl shadow-md p-8 border border-gray-100 mb-8">
        <h2 class="text-xl font-extrabold text-blue-900 mb-1 text-center">মোবাইল নম্বর দিয়ে অনুসন্ধান করুন</h2>
        <p class="text-center text-gray-400 text-sm mb-7">আপনার দানের সময় ব্যবহৃত নম্বরটি নিচে লিখুন।</p>

        <form action="{{ route('receipt.search') }}" method="GET">
            <div class="flex items-stretch border border-gray-200 rounded-xl overflow-hidden focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-transparent transition shadow-sm">
                <div class="px-4 text-gray-600 bg-gray-50 border-r border-gray-200 font-bold text-sm flex items-center">
                    {{ $settings->country_code ?? '+880' }}
                </div>
                <input
                    type="text"
                    name="phone"
                    value="{{ request('phone') }}"
                    placeholder="আপনার মোবাইল নম্বর লিখুন..."
                    required
                    class="w-full px-4 py-3 focus:outline-none text-gray-700 text-sm">
                <button type="submit"
                    class="bg-blue-600 text-white px-6 py-3 font-bold hover:bg-blue-700 transition text-sm whitespace-nowrap">
                    খুঁজুন
                </button>
            </div>
        </form>
    </div>

    {{-- Results --}}
    @if(request()->has('phone'))
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">

        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h3 class="font-extrabold text-gray-800">অনুসন্ধানের ফলাফল</h3>
                <p class="text-xs text-gray-400 mt-0.5">নম্বর: <span class="font-semibold text-gray-600">{{ request('phone') }}</span></p>
            </div>
            @if($donations && $donations->count() > 0)
            <span class="bg-blue-50 text-blue-700 text-xs font-bold px-3 py-1 rounded-full">
                {{ $donations->count() }}টি রসিদ পাওয়া গেছে
            </span>
            @endif
        </div>

        @if($donations && $donations->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wide">
                        <th class="px-5 py-3">তারিখ</th>
                        <th class="px-5 py-3">রসিদ নম্বর</th>
                        <th class="px-5 py-3">প্রকল্প</th>
                        <th class="px-5 py-3">পরিমাণ</th>
                        <th class="px-5 py-3">স্ট্যাটাস</th>
                        <th class="px-5 py-3 text-right">একশন</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($donations as $donation)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-4 text-gray-400 whitespace-nowrap">{{ $donation->created_at->format('d M, Y') }}</td>
                        <td class="px-5 py-4 font-mono font-bold text-xs text-gray-600">#{{ $donation->receipt_no }}</td>
                        <td class="px-5 py-4 text-gray-600 text-xs">{{ $donation->project->name ?? 'সাধারণ অনুদান' }}</td>
                        <td class="px-5 py-4 font-extrabold text-green-600 whitespace-nowrap">৳{{ number_format($donation->amount) }}</td>
                        <td class="px-5 py-4">
                            @if(strtolower($donation->status) === 'completed')
                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold">সম্পন্ন</span>
                            @elseif(strtolower($donation->status) === 'pending')
                                <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-bold">প্রক্রিয়াধীন</span>
                            @else
                                <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-xs font-bold">{{ ucfirst($donation->status) }}</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-right">
                            <a href="{{ route('donate.receipt', $donation->id) }}"
                                class="inline-block bg-blue-50 text-blue-600 border border-blue-200 px-4 py-1.5 rounded-lg font-bold hover:bg-blue-600 hover:text-white transition text-xs">
                                PDF ডাউনলোড
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @else
        <div class="text-center py-14 text-gray-400">
            <p class="text-4xl mb-3">🔍</p>
            <p class="font-semibold text-gray-500">এই নম্বরে কোনো দানের তথ্য পাওয়া যায়নি।</p>
            <p class="text-xs mt-2 text-gray-400">নম্বরটি সঠিক কিনা যাচাই করুন অথবা আমাদের সাথে যোগাযোগ করুন।</p>
            @if($settings->phone ?? null)
            <a href="tel:{{ $settings->phone }}"
                class="inline-block mt-5 bg-blue-600 text-white font-bold px-6 py-2 rounded-full text-sm hover:bg-blue-700 transition">
                যোগাযোগ করুন: {{ $settings->phone }}
            </a>
            @endif
        </div>
        @endif

    </div>
    @endif

    {{-- Helper note --}}
    <div class="mt-8 bg-blue-50 border border-blue-100 rounded-xl p-5 text-sm text-blue-800 text-center">
        রসিদ খুঁজে না পেলে আমাদের সাথে যোগাযোগ করুন —
        @if($settings->phone ?? null)
            <a href="tel:{{ $settings->phone }}" class="font-bold underline">{{ $settings->phone }}</a>
        @endif
        @if($settings->email ?? null)
            বা <a href="mailto:{{ $settings->email }}" class="font-bold underline">{{ $settings->email }}</a>
        @endif
    </div>

</div>
@endsection