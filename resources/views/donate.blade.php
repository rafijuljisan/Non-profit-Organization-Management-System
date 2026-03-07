@extends('layouts.app')

@section('title', 'দান করুন - ' . ($settings->site_name ?? config('app.name')))

@section('content')

{{-- Hero --}}
<section class="bg-gradient-to-br from-green-700 to-green-900 text-white py-16 px-4 text-center relative overflow-hidden">
    <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/asfalt-dark.png')]"></div>
    <div class="relative max-w-3xl mx-auto">
        <p class="text-green-300 uppercase tracking-widest text-sm font-semibold mb-3">আপনার সাহায্য</p>
        <h1 class="text-4xl md:text-5xl font-extrabold mb-4 leading-tight">একটি দান, একটি জীবন বদলায়</h1>
        <p class="text-green-100 text-lg max-w-xl mx-auto">আপনার প্রতিটি অবদান হাজারো মানুষের মুখে হাসি ফোটায়। আজই এগিয়ে আসুন।</p>
    </div>
</section>

{{-- Pass payment data to JS --}}
<script>
    const paymentInfo = {
        bkash: {
            number: "{{ $settings->bkash_number ?? '' }}",
            type: "{{ $settings->bkash_account_type ?? 'Personal' }}",
            instruction: "{{ addslashes($settings->bkash_instruction ?? 'bKash নম্বরে Send Money করুন, তারপর ট্রানজেকশন আইডি নিচে লিখুন।') }}",
            color: "text-pink-600",
            bg: "bg-pink-50 border-pink-200",
        },
        nagad: {
            number: "{{ $settings->nagad_number ?? '' }}",
            type: "{{ $settings->nagad_account_type ?? 'Personal' }}",
            instruction: "{{ addslashes($settings->nagad_instruction ?? 'Nagad নম্বরে Send Money করুন, তারপর ট্রানজেকশন আইডি নিচে লিখুন।') }}",
            color: "text-orange-600",
            bg: "bg-orange-50 border-orange-200",
        },
        rocket: {
            number: "{{ $settings->rocket_number ?? '' }}",
            type: "Personal",
            instruction: "{{ addslashes($settings->rocket_instruction ?? 'Rocket নম্বরে Send Money করুন, তারপর ট্রানজেকশন আইডি নিচে লিখুন।') }}",
            color: "text-purple-600",
            bg: "bg-purple-50 border-purple-200",
        },
        bank: {
            number: "{{ addslashes($settings->bank_info ?? '') }}",
            type: "Bank Transfer",
            instruction: "{{ addslashes($settings->bank_instruction ?? 'ব্যাংক ট্রান্সফার করুন, তারপর রেফারেন্স নম্বর নিচে লিখুন।') }}",
            color: "text-blue-700",
            bg: "bg-blue-50 border-blue-200",
        },
        cash: {
            number: "",
            type: "",
            instruction: "সরাসরি আমাদের অফিসে এসে দান করুন অথবা আমাদের প্রতিনিধির কাছে প্রদান করুন।",
            color: "text-gray-700",
            bg: "bg-gray-50 border-gray-200",
        },
    };

    function onPaymentChange(val) {
        const box = document.getElementById('payment-info-box');
        const numEl = document.getElementById('pi-number');
        const typeEl = document.getElementById('pi-type');
        const instrEl = document.getElementById('pi-instruction');
        const trxLabel = document.getElementById('trx-label');

        if (!val || !paymentInfo[val]) {
            box.classList.add('hidden');
            return;
        }

        const info = paymentInfo[val];

        // Update box style
        box.className = 'border rounded-xl p-4 mt-3 ' + info.bg;

        // Number
        if (info.number) {
            numEl.textContent = info.number;
            numEl.className = 'text-xl font-extrabold ' + info.color;
            numEl.parentElement.classList.remove('hidden');
        } else {
            numEl.parentElement.classList.add('hidden');
        }

        // Type
        typeEl.textContent = info.type || '';

        // Instruction
        instrEl.textContent = info.instruction;

        // TrxID label
        if (val === 'bank') {
            trxLabel.textContent = 'রেফারেন্স / ট্রানজেকশন নম্বর';
        } else if (val === 'cash') {
            trxLabel.textContent = 'রসিদ নম্বর (যদি থাকে)';
        } else {
            trxLabel.textContent = 'ট্রানজেকশন আইডি (TrxID)';
        }

        box.classList.remove('hidden');
    }
</script>

<div class="max-w-5xl mx-auto px-4 py-14">

    {{-- Success --}}
    @if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 rounded-xl p-6 mb-10 text-center shadow-sm">
        <div class="text-4xl mb-3">🎉</div>
        <h3 class="text-xl font-extrabold text-green-800 mb-2">{{ session('success') }}</h3>
        @if(session('donation_id'))
            <p class="text-sm text-green-700 mb-4">আপনার দানের অফিসিয়াল রসিদ ডাউনলোড করুন।</p>
            <a href="{{ route('donate.receipt', session('donation_id')) }}"
               class="inline-block bg-green-600 text-white font-bold py-2 px-8 rounded-full hover:bg-green-700 transition shadow-md text-sm">
                রসিদ ডাউনলোড করুন (PDF)
            </a>
        @endif
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

        {{-- LEFT: Why Donate --}}
        <div class="space-y-5">
            <div>
                <h2 class="text-2xl font-extrabold text-green-900 mb-1">কেন দান করবেন?</h2>
                <p class="text-gray-500 text-sm">আপনার অবদান সরাসরি মানুষের কাছে পৌঁছায়।</p>
            </div>

            <div class="border bg-yellow-50 border-yellow-200 rounded-xl p-4 flex gap-3 items-start">
                <div class="text-2xl mt-0.5">🎓</div>
                <div>
                    <p class="font-bold text-gray-800 text-sm">শিক্ষা সহায়তা</p>
                    <p class="text-gray-500 text-xs mt-0.5 leading-relaxed">সুবিধাবঞ্চিত শিশুদের শিক্ষার সুযোগ করে দিন।</p>
                </div>
            </div>
            <div class="border bg-red-50 border-red-200 rounded-xl p-4 flex gap-3 items-start">
                <div class="text-2xl mt-0.5">🏥</div>
                <div>
                    <p class="font-bold text-gray-800 text-sm">স্বাস্থ্যসেবা</p>
                    <p class="text-gray-500 text-xs mt-0.5 leading-relaxed">দরিদ্র পরিবারের চিকিৎসা নিশ্চিত করুন।</p>
                </div>
            </div>
            <div class="border bg-orange-50 border-orange-200 rounded-xl p-4 flex gap-3 items-start">
                <div class="text-2xl mt-0.5">🍱</div>
                <div>
                    <p class="font-bold text-gray-800 text-sm">খাদ্য সহায়তা</p>
                    <p class="text-gray-500 text-xs mt-0.5 leading-relaxed">ক্ষুধার্ত মানুষের পাশে থাকুন।</p>
                </div>
            </div>
            <div class="border bg-blue-50 border-blue-200 rounded-xl p-4 flex gap-3 items-start">
                <div class="text-2xl mt-0.5">🏠</div>
                <div>
                    <p class="font-bold text-gray-800 text-sm">আশ্রয়ণ</p>
                    <p class="text-gray-500 text-xs mt-0.5 leading-relaxed">গৃহহীনদের মাথার ছাদ দিন।</p>
                </div>
            </div>
        </div>

        {{-- RIGHT: Form --}}
        <div class="lg:col-span-2">
            <div class="bg-white border border-gray-100 rounded-2xl shadow-md p-8">

                <h2 class="text-2xl font-extrabold text-green-900 mb-1">দান ফর্ম পূরণ করুন</h2>
                <p class="text-gray-400 text-sm mb-8">সকল তথ্য সঠিকভাবে পূরণ করুন। দান সম্পন্ন হলে রসিদ পাবেন।</p>

                <form action="{{ route('donate.store') }}" method="POST" class="space-y-5">
                    @csrf

                    {{-- Quick amounts --}}
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2 text-sm">দ্রুত পরিমাণ নির্বাচন করুন</label>
                        <div class="grid grid-cols-4 gap-2">
                            <button type="button" onclick="document.querySelector('[name=amount]').value='500'"
                                class="border-2 border-green-200 text-green-700 font-bold py-2 rounded-lg text-sm hover:bg-green-50 hover:border-green-400 transition">৳৫০০</button>
                            <button type="button" onclick="document.querySelector('[name=amount]').value='1000'"
                                class="border-2 border-green-200 text-green-700 font-bold py-2 rounded-lg text-sm hover:bg-green-50 hover:border-green-400 transition">৳১০০০</button>
                            <button type="button" onclick="document.querySelector('[name=amount]').value='2000'"
                                class="border-2 border-green-200 text-green-700 font-bold py-2 rounded-lg text-sm hover:bg-green-50 hover:border-green-400 transition">৳২০০০</button>
                            <button type="button" onclick="document.querySelector('[name=amount]').value='5000'"
                                class="border-2 border-green-200 text-green-700 font-bold py-2 rounded-lg text-sm hover:bg-green-50 hover:border-green-400 transition">৳৫০০০</button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2 text-sm">পূর্ণ নাম <span class="text-red-500">*</span></label>
                            <input type="text" name="donor_name" value="{{ old('donor_name') }}" required
                                placeholder="আপনার নাম লিখুন"
                                class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 text-sm @error('donor_name') border-red-400 @enderror">
                            @error('donor_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2 text-sm">মোবাইল নম্বর <span class="text-red-500">*</span></label>
                            <input type="text" name="donor_phone" value="{{ old('donor_phone') }}" required
                                placeholder="০১XXXXXXXXX"
                                class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 text-sm @error('donor_phone') border-red-400 @enderror">
                            @error('donor_phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2 text-sm">দানের পরিমাণ (৳) <span class="text-red-500">*</span></label>
                            <input type="number" name="amount" value="{{ old('amount') }}" min="10" required
                                placeholder="ন্যূনতম ১০ টাকা"
                                class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 text-sm @error('amount') border-red-400 @enderror">
                            @error('amount')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2 text-sm">প্রকল্প নির্বাচন <span class="text-gray-400 font-normal">(ঐচ্ছিক)</span></label>
                            <select name="project_id"
                                class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 bg-white text-sm">
                                <option value="">সাধারণ অনুদান</option>
                                @foreach($projects as $project)
                                    <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                        {{ $project->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Payment Method + Dynamic Info --}}
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2 text-sm">পেমেন্ট পদ্ধতি <span class="text-red-500">*</span></label>
                        <select name="payment_method" required
                            onchange="onPaymentChange(this.value)"
                            class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 bg-white text-sm @error('payment_method') border-red-400 @enderror">
                            <option value="">-- পেমেন্ট পদ্ধতি নির্বাচন করুন --</option>
                            @if($settings->bkash_number ?? null)
                            <option value="bkash"  {{ old('payment_method') == 'bkash'  ? 'selected' : '' }}>bKash</option>
                            @endif
                            @if($settings->nagad_number ?? null)
                            <option value="nagad"  {{ old('payment_method') == 'nagad'  ? 'selected' : '' }}>Nagad</option>
                            @endif
                            @if($settings->rocket_number ?? null)
                            <option value="rocket" {{ old('payment_method') == 'rocket' ? 'selected' : '' }}>Rocket</option>
                            @endif
                            @if($settings->bank_info ?? null)
                            <option value="bank"   {{ old('payment_method') == 'bank'   ? 'selected' : '' }}>Bank Transfer</option>
                            @endif
                            <option value="cash"   {{ old('payment_method') == 'cash'   ? 'selected' : '' }}>নগদ হাতে</option>
                        </select>
                        @error('payment_method')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror

                        {{-- Dynamic Payment Info Box --}}
                        <div id="payment-info-box" class="hidden border rounded-xl p-4 mt-3">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-bold uppercase tracking-widest text-gray-400" id="pi-type"></span>
                            </div>
                            <div class="mb-2">
                                <p class="text-xs text-gray-400 mb-0.5 font-semibold uppercase tracking-wide">নম্বর</p>
                                <p id="pi-number" class="text-xl font-extrabold"></p>
                            </div>
                            <div class="mt-2 pt-2 border-t border-dashed border-gray-200">
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">নির্দেশনা</p>
                                <p id="pi-instruction" class="text-sm text-gray-700 leading-relaxed"></p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-2 text-sm" id="trx-label">ট্রানজেকশন আইডি (TrxID) <span class="text-red-500">*</span></label>
                        <input type="text" name="receipt_no" value="{{ old('receipt_no') }}" required
                            placeholder="যেমন: TRX12345678"
                            class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 text-sm @error('receipt_no') border-red-400 @enderror">
                        @error('receipt_no')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <button type="submit"
                        class="w-full bg-green-600 hover:bg-green-700 text-white font-extrabold py-3 px-6 rounded-xl transition shadow-md text-sm tracking-wide">
                        দান জমা দিন
                    </button>

                </form>
            </div>
        </div>

    </div>

    {{-- Google Map --}}
    @if($settings->google_map_url ?? null)
    <div class="mt-14">
        <h3 class="text-xl font-extrabold text-blue-900 mb-4 text-center">আমাদের অবস্থান</h3>
        <div class="rounded-2xl overflow-hidden shadow-md border border-gray-200">
            <iframe src="{{ $settings->google_map_url }}"
                width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>
    @endif

    {{-- Trust Banner --}}
    <div class="mt-14 bg-green-50 border border-green-100 rounded-2xl px-8 py-10 text-center">
        <p class="text-green-400 uppercase tracking-widest text-xs font-semibold mb-2">আমাদের প্রতিশ্রুতি</p>
        <h3 class="text-2xl font-extrabold text-green-900 mb-3">আপনার দান সঠিক হাতে পৌঁছাবে</h3>
        <p class="text-gray-500 text-sm max-w-xl mx-auto leading-relaxed mb-8">
            আমরা প্রতিটি দানের স্বচ্ছ হিসাব রাখি এবং নিয়মিত প্রতিবেদন প্রকাশ করি।
        </p>
        <div class="flex justify-center gap-10 flex-wrap">
            <div>
                <p class="text-3xl font-extrabold text-green-700">১০০%</p>
                <p class="text-gray-500 text-sm mt-1">স্বচ্ছ হিসাব</p>
            </div>
            <div>
                <p class="text-3xl font-extrabold text-green-700">২৪ ঘণ্টা</p>
                <p class="text-gray-500 text-sm mt-1">রসিদ প্রদান</p>
            </div>
            <div>
                <p class="text-3xl font-extrabold text-green-700">০%</p>
                <p class="text-gray-500 text-sm mt-1">লুকানো চার্জ</p>
            </div>
        </div>
    </div>

</div>

{{-- Auto-show payment info if old value exists --}}
@if(old('payment_method'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        onPaymentChange('{{ old('payment_method') }}');
    });
</script>
@endif

@endsection