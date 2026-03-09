@extends('layouts.app')

@section('title', 'আর্থিক স্বচ্ছতা - ' . ($settings->site_name ?? config('app.name', 'Foundation')))

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<section class="bg-gradient-to-br from-blue-900 via-blue-800 to-blue-900 text-white py-20 px-4 text-center relative overflow-hidden">
    <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/asfalt-dark.png')]"></div>
    <div class="relative max-w-3xl mx-auto">
        <span class="bg-blue-800/50 text-blue-200 border border-blue-700/50 uppercase tracking-widest text-xs font-bold px-4 py-1.5 rounded-full mb-6 inline-block">
            ১০০% স্বচ্ছতা
        </span>
        <h1 class="text-4xl md:text-5xl font-extrabold mb-5 tracking-tight">আর্থিক স্বচ্ছতা পোর্টাল</h1>
        <p class="text-blue-100 text-lg max-w-2xl mx-auto font-light leading-relaxed">
            আমরা বিশ্বাস করি স্বচ্ছতায়। ফাউন্ডেশনের প্রতিটি টাকার হিসাব সবার জন্য উন্মুক্ত।
        </p>
    </div>
</section>

<div class="max-w-7xl mx-auto px-4 py-16 -mt-10 relative z-10">

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-16">
        <div class="bg-white rounded-2xl shadow-lg shadow-gray-200/50 p-6 border border-gray-100 hover:-translate-y-1 transition duration-300 group">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 font-bold mb-1 text-xs uppercase tracking-wider">মোট তহবিল সংগ্রহ</p>
                    <h2 class="text-3xl font-extrabold text-green-600">৳{{ number_format($totalIncome) }}</h2>
                    <p class="text-xs text-gray-400 mt-2">সকল অনুদান ও সদস্য চাঁদা</p>
                </div>
                <div class="bg-green-100 p-3 rounded-xl text-green-600 group-hover:bg-green-600 group-hover:text-white transition">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg shadow-gray-200/50 p-6 border border-gray-100 hover:-translate-y-1 transition duration-300 group">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 font-bold mb-1 text-xs uppercase tracking-wider">মোট ব্যয়</p>
                    <h2 class="text-3xl font-extrabold text-red-500">৳{{ number_format($totalExpense) }}</h2>
                    <p class="text-xs text-gray-400 mt-2">প্রকল্প ও পরিচালনা ব্যয়</p>
                </div>
                <div class="bg-red-100 p-3 rounded-xl text-red-500 group-hover:bg-red-500 group-hover:text-white transition">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path></svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg shadow-gray-200/50 p-6 border border-gray-100 hover:-translate-y-1 transition duration-300 group">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 font-bold mb-1 text-xs uppercase tracking-wider">বর্তমান ব্যালেন্স</p>
                    <h2 class="text-3xl font-extrabold text-blue-600">৳{{ number_format($currentBalance) }}</h2>
                    <p class="text-xs text-gray-400 mt-2">সংগ্রহ বিয়োগ ব্যয়</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-xl text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path></svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Chart + Expenses --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-16">

        {{-- Doughnut Chart --}}
        <div class="lg:col-span-1 bg-white p-8 rounded-2xl shadow-sm border border-gray-100 flex flex-col items-center">
            <h3 class="text-lg font-extrabold text-gray-800 mb-6 w-full text-center border-b border-gray-100 pb-4">তহবিল বরাদ্দ</h3>
            
            <div class="relative w-full max-w-[220px] mx-auto">
                <canvas id="financeChart"></canvas>
                <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                    <span class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-2">মোট আয়</span>
                    <span class="text-sm font-extrabold text-gray-800">৳{{ number_format($totalIncome / 1000, 1) }}k</span>
                </div>
            </div>

            <div class="mt-8 w-full space-y-3">
                <div class="flex justify-between items-center text-sm px-3py-2 bg-red-50 rounded-lg p-2 border border-red-100">
                    <span class="flex items-center gap-2 font-semibold text-red-700">
                        <span class="inline-block w-2.5 h-2.5 rounded-full bg-red-400"></span> মোট ব্যয়
                    </span>
                    <span class="font-bold text-red-600">৳{{ number_format($totalExpense) }}</span>
                </div>
                <div class="flex justify-between items-center text-sm bg-blue-50 rounded-lg p-2 border border-blue-100">
                    <span class="flex items-center gap-2 font-semibold text-blue-800">
                        <span class="inline-block w-2.5 h-2.5 rounded-full bg-blue-500"></span> অবশিষ্ট ব্যালেন্স
                    </span>
                    <span class="font-bold text-blue-600">৳{{ number_format($currentBalance) }}</span>
                </div>

                @if($totalIncome > 0)
                <div class="mt-4 pt-4 border-t border-dashed border-gray-200">
                    <div class="flex justify-between text-xs text-gray-500 mb-1.5">
                        <span class="font-semibold">ব্যয়ের হার</span>
                        <span class="font-bold text-gray-700">{{ number_format(($totalExpense / $totalIncome) * 100, 1) }}%</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                        <div class="bg-gradient-to-r from-red-400 to-red-500 h-2 rounded-full transition-all duration-1000" style="width: {{ min(100, ($totalExpense / $totalIncome) * 100) }}%"></div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Recent Expenses --}}
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                <h3 class="text-lg font-extrabold text-gray-800">সাম্প্রতিক ব্যয়সমূহ</h3>
                <span class="bg-red-100 text-red-600 text-xs font-bold px-3 py-1 rounded-full">সর্বশেষ ৫টি</span>
            </div>

            @if($recentExpenses->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="bg-white text-gray-400 text-xs uppercase tracking-wider border-b border-gray-100">
                            <th class="p-4 font-semibold">তারিখ</th>
                            <th class="p-4 font-semibold">প্রকল্প</th>
                            <th class="p-4 font-semibold">বিবরণ</th>
                            <th class="p-4 font-semibold text-right">পরিমাণ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentExpenses as $expense)
                        <tr class="border-b border-gray-50 last:border-0 hover:bg-slate-50 transition">
                            <td class="p-4 text-gray-500 whitespace-nowrap">{{ $expense->created_at->format('d M, Y') }}</td>
                            <td class="p-4 font-bold text-gray-700">{{ $expense->project->name ?? 'সাধারণ ব্যয়' }}</td>
                            <td class="p-4 text-gray-600">{{ Str::limit($expense->description ?? '—', 35) }}</td>
                            <td class="p-4 text-right font-extrabold text-red-500 whitespace-nowrap">- ৳{{ number_format($expense->amount) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-16">
                <div class="bg-gray-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4M8 16l-4-4 4-4"></path></svg>
                </div>
                <p class="text-gray-500 font-medium">এখনো কোনো ব্যয়ের তথ্য নেই।</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Recent Donations --}}
    @if(isset($recentDonations) && $recentDonations->count() > 0)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-16">
        <div class="p-6 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
            <h3 class="text-lg font-extrabold text-gray-800">সাম্প্রতিক অনুদান</h3>
            <span class="bg-green-100 text-green-600 text-xs font-bold px-3 py-1 rounded-full">সর্বশেষ ৫টি</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="bg-white text-gray-400 text-xs uppercase tracking-wider border-b border-gray-100">
                        <th class="p-4 font-semibold">তারিখ</th>
                        <th class="p-4 font-semibold">দাতার নাম</th>
                        <th class="p-4 font-semibold">প্রকল্প</th>
                        <th class="p-4 font-semibold">মাধ্যম</th>
                        <th class="p-4 font-semibold text-right">পরিমাণ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentDonations as $donation)
                    <tr class="border-b border-gray-50 last:border-0 hover:bg-slate-50 transition">
                        <td class="p-4 text-gray-500 whitespace-nowrap">{{ $donation->created_at->format('d M, Y') }}</td>
                        <td class="p-4 font-bold text-gray-800 flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs font-bold">
                                {{ mb_substr($donation->donor_name ?? 'B', 0, 1) }}
                            </div>
                            {{ $donation->donor_name ?? 'বেনামী' }}
                        </td>
                        <td class="p-4 font-medium text-gray-600">{{ $donation->project->name ?? 'সাধারণ অনুদান' }}</td>
                        <td class="p-4">
                            <span class="bg-gray-100 text-gray-600 border border-gray-200 text-xs font-bold px-2.5 py-1 rounded uppercase tracking-wide">
                                {{ $donation->payment_method ?? '—' }}
                            </span>
                        </td>
                        <td class="p-4 text-right font-extrabold text-green-600 whitespace-nowrap">+ ৳{{ number_format($donation->amount) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- Trust Strip --}}
    <div class="bg-gradient-to-r from-blue-900 to-blue-800 rounded-3xl p-10 text-center shadow-xl relative overflow-hidden">
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-white opacity-5 rounded-full blur-2xl"></div>
        <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-white opacity-5 rounded-full blur-2xl"></div>
        
        <p class="text-blue-300 uppercase tracking-widest text-xs font-bold mb-3 relative z-10">আমাদের অর্জন</p>
        <h3 class="text-3xl md:text-4xl font-extrabold text-white mb-10 relative z-10">আপনাদের বিশ্বাসই আমাদের মূলধন</h3>
        
        <div class="flex justify-center gap-12 md:gap-24 flex-wrap relative z-10">
            <div>
                <p class="text-4xl font-black text-white">১০০%</p>
                <p class="text-blue-200 font-medium mt-2">স্বচ্ছ হিসাব</p>
            </div>
            <div>
                <p class="text-4xl font-black text-white">৳{{ number_format($totalIncome / 1000, 1) }}k+</p>
                <p class="text-blue-200 font-medium mt-2">মোট সংগ্রহ</p>
            </div>
            <div>
                <p class="text-4xl font-black text-white">{{ $activeMembers ?? 0 }}+</p>
                <p class="text-blue-200 font-medium mt-2">সক্রিয় সদস্য</p>
            </div>
        </div>
    </div>

</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const ctx = document.getElementById('financeChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['মোট ব্যয়', 'অবশিষ্ট ব্যালেন্স'],
                datasets: [{
                    data: [{{ $totalExpense }}, {{ max(0, $currentBalance) }}],
                    backgroundColor: ['#f87171', '#3b82f6'],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 12,
                        titleFont: { size: 13 },
                        bodyFont: { size: 14, weight: 'bold' },
                        callbacks: {
                            label: function(context) {
                                return ' ৳ ' + context.parsed.toLocaleString();
                            }
                        }
                    }
                },
                cutout: '75%', // ডোনাট আরেকটু চিকন করা হয়েছে যাতে ভেতরের টেক্সট সুন্দর লাগে
                animation: { animateScale: true, animateRotate: true }
            }
        });
    });
</script>

@endsection