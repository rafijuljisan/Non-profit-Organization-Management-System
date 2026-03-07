@extends('layouts.app')

@section('title', 'আর্থিক স্বচ্ছতা - ' . ($settings->site_name ?? config('app.name')))

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<section class="bg-gradient-to-br from-blue-900 to-blue-700 text-white py-16 px-4 text-center relative overflow-hidden">
    <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/asfalt-dark.png')]"></div>
    <div class="relative max-w-3xl mx-auto">
        <p class="text-blue-300 uppercase tracking-widest text-sm font-semibold mb-3">১০০% স্বচ্ছতা</p>
        <h1 class="text-4xl md:text-5xl font-extrabold mb-4">আর্থিক স্বচ্ছতা পোর্টাল</h1>
        <p class="text-blue-100 text-lg max-w-2xl mx-auto">আমরা বিশ্বাস করি স্বচ্ছতায়। প্রতিটি টাকার হিসাব এখানে উন্মুক্ত।</p>
    </div>
</section>

<div class="max-w-7xl mx-auto px-4 py-14">

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-14">
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500 hover:shadow-lg transition">
            <p class="text-gray-500 font-semibold mb-1 text-sm uppercase tracking-wide">মোট তহবিল সংগ্রহ</p>
            <h2 class="text-3xl font-extrabold text-green-600">৳{{ number_format($totalIncome) }}</h2>
            <p class="text-xs text-gray-400 mt-2">সকল অনুদান ও সদস্য চাঁদা</p>
        </div>
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-red-500 hover:shadow-lg transition">
            <p class="text-gray-500 font-semibold mb-1 text-sm uppercase tracking-wide">মোট ব্যয়</p>
            <h2 class="text-3xl font-extrabold text-red-500">৳{{ number_format($totalExpense) }}</h2>
            <p class="text-xs text-gray-400 mt-2">প্রকল্প ও পরিচালনা ব্যয়</p>
        </div>
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500 hover:shadow-lg transition">
            <p class="text-gray-500 font-semibold mb-1 text-sm uppercase tracking-wide">বর্তমান ব্যালেন্স</p>
            <h2 class="text-3xl font-extrabold text-blue-600">৳{{ number_format($currentBalance) }}</h2>
            <p class="text-xs text-gray-400 mt-2">সংগ্রহ বিয়োগ ব্যয়</p>
        </div>
    </div>

    {{-- Chart + Expenses --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-14">

        {{-- Doughnut Chart --}}
        <div class="lg:col-span-1 bg-white p-6 rounded-xl shadow-md border border-gray-100 flex flex-col items-center">
            <h3 class="text-lg font-extrabold text-gray-800 mb-4 w-full text-center border-b pb-3">তহবিল বরাদ্দ</h3>
            <div class="w-full max-w-[220px] mx-auto">
                <canvas id="financeChart"></canvas>
            </div>
            <div class="mt-6 w-full space-y-2">
                <div class="flex justify-between items-center text-sm">
                    <span class="flex items-center gap-2">
                        <span class="inline-block w-3 h-3 rounded-full bg-red-400"></span> মোট ব্যয়
                    </span>
                    <span class="font-bold text-red-500">৳{{ number_format($totalExpense) }}</span>
                </div>
                <div class="flex justify-between items-center text-sm">
                    <span class="flex items-center gap-2">
                        <span class="inline-block w-3 h-3 rounded-full bg-blue-500"></span> অবশিষ্ট ব্যালেন্স
                    </span>
                    <span class="font-bold text-blue-600">৳{{ number_format($currentBalance) }}</span>
                </div>
                @if($totalIncome > 0)
                <div class="mt-3 pt-3 border-t border-dashed border-gray-200">
                    <div class="flex justify-between text-xs text-gray-500">
                        <span>ব্যয়ের হার</span>
                        <span class="font-bold">{{ number_format(($totalExpense / $totalIncome) * 100, 1) }}%</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-1.5 mt-1">
                        <div class="bg-red-400 h-1.5 rounded-full" style="width: {{ min(100, ($totalExpense / $totalIncome) * 100) }}%"></div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Recent Expenses --}}
        <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-md border border-gray-100">
            <h3 class="text-lg font-extrabold text-gray-800 mb-4 border-b pb-3">সাম্প্রতিক ব্যয়সমূহ</h3>

            @if($recentExpenses->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wide">
                            <th class="p-3 rounded-tl-lg">তারিখ</th>
                            <th class="p-3">প্রকল্প</th>
                            <th class="p-3">বিবরণ</th>
                            <th class="p-3 text-right rounded-tr-lg">পরিমাণ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentExpenses as $expense)
                        <tr class="border-b last:border-0 hover:bg-gray-50 transition">
                            <td class="p-3 text-gray-400 whitespace-nowrap">{{ $expense->created_at->format('d M, Y') }}</td>
                            <td class="p-3 font-semibold text-blue-700">{{ $expense->project->name ?? 'সাধারণ' }}</td>
                            <td class="p-3 text-gray-600">{{ Str::limit($expense->description ?? '—', 40) }}</td>
                            <td class="p-3 text-right font-extrabold text-red-500 whitespace-nowrap">- ৳{{ number_format($expense->amount) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-10 text-gray-400">
                <p class="text-4xl mb-3">📭</p>
                <p class="font-semibold">এখনো কোনো ব্যয়ের তথ্য নেই।</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Recent Donations --}}
    @if(isset($recentDonations) && $recentDonations->count() > 0)
    <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100 mb-14">
        <h3 class="text-lg font-extrabold text-gray-800 mb-4 border-b pb-3">সাম্প্রতিক অনুদান</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wide">
                        <th class="p-3 rounded-tl-lg">তারিখ</th>
                        <th class="p-3">দাতার নাম</th>
                        <th class="p-3">প্রকল্প</th>
                        <th class="p-3">পেমেন্ট</th>
                        <th class="p-3 text-right rounded-tr-lg">পরিমাণ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentDonations as $donation)
                    <tr class="border-b last:border-0 hover:bg-gray-50 transition">
                        <td class="p-3 text-gray-400 whitespace-nowrap">{{ $donation->created_at->format('d M, Y') }}</td>
                        <td class="p-3 font-semibold text-gray-800">{{ $donation->donor_name ?? 'বেনামী' }}</td>
                        <td class="p-3 text-gray-600">{{ $donation->project->name ?? 'সাধারণ অনুদান' }}</td>
                        <td class="p-3">
                            <span class="bg-green-100 text-green-700 text-xs font-bold px-2 py-0.5 rounded-full uppercase">
                                {{ $donation->payment_method ?? '—' }}
                            </span>
                        </td>
                        <td class="p-3 text-right font-extrabold text-green-600 whitespace-nowrap">+ ৳{{ number_format($donation->amount) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- Trust Strip --}}
    <div class="bg-blue-50 border border-blue-100 rounded-2xl px-8 py-10 text-center">
        <p class="text-blue-400 uppercase tracking-widest text-xs font-semibold mb-2">আমাদের প্রতিশ্রুতি</p>
        <h3 class="text-2xl font-extrabold text-blue-900 mb-6">প্রতিটি টাকার হিসাব আমরা রাখি</h3>
        <div class="flex justify-center gap-10 flex-wrap">
            <div>
                <p class="text-3xl font-extrabold text-blue-700">১০০%</p>
                <p class="text-gray-500 text-sm mt-1">স্বচ্ছ হিসাব</p>
            </div>
            <div>
                <p class="text-3xl font-extrabold text-blue-700">৳{{ number_format($totalIncome) }}</p>
                <p class="text-gray-500 text-sm mt-1">মোট সংগ্রহ</p>
            </div>
            <div>
                <p class="text-3xl font-extrabold text-blue-700">{{ $recentExpenses->count() }}+</p>
                <p class="text-gray-500 text-sm mt-1">সাম্প্রতিক ব্যয়</p>
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
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return ' ৳' + context.parsed.toLocaleString();
                            }
                        }
                    }
                },
                cutout: '72%'
            }
        });
    });
</script>

@endsection