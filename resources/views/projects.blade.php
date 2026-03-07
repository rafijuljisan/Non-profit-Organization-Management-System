@extends('layouts.app')

@section('title', 'আমাদের প্রকল্পসমূহ - ' . ($settings->site_name ?? config('app.name')))

@section('content')

{{-- Hero --}}
<section class="bg-gradient-to-br from-blue-900 to-blue-700 text-white py-16 px-4 text-center relative overflow-hidden">
    <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/asfalt-dark.png')]"></div>
    <div class="relative max-w-3xl mx-auto">
        <p class="text-blue-300 uppercase tracking-widest text-sm font-semibold mb-3">আমাদের কার্যক্রম</p>
        <h1 class="text-4xl md:text-5xl font-extrabold mb-4 leading-tight">আমাদের প্রকল্পসমূহ</h1>
        <p class="text-blue-100 text-lg max-w-2xl mx-auto">বিভিন্ন জেলায় আমরা কীভাবে পরিবর্তন আনছি তা জানুন। আপনার সহযোগিতাই এই প্রকল্পগুলো সম্ভব করে।</p>
    </div>
</section>

{{-- Stats --}}
<div class="bg-white border-b border-gray-100 shadow-sm">
    <div class="max-w-5xl mx-auto px-4 py-6 grid grid-cols-3 divide-x divide-gray-100 text-center">
        <div class="px-4">
            <p class="text-2xl font-extrabold text-blue-700">{{ $ongoingProjects->count() }}</p>
            <p class="text-xs text-gray-500 uppercase tracking-wide mt-1">চলমান প্রকল্প</p>
        </div>
        <div class="px-4">
            <p class="text-2xl font-extrabold text-green-700">{{ $completedProjects->count() }}</p>
            <p class="text-xs text-gray-500 uppercase tracking-wide mt-1">সম্পন্ন প্রকল্প</p>
        </div>
        <div class="px-4">
            <p class="text-2xl font-extrabold text-purple-700">{{ $ongoingProjects->count() + $completedProjects->count() }}</p>
            <p class="text-xs text-gray-500 uppercase tracking-wide mt-1">মোট প্রকল্প</p>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-14">

    {{-- ===== ONGOING ===== --}}
    <div class="mb-16">
        <div class="flex items-center gap-3 mb-8">
            <div class="h-8 w-1.5 bg-blue-500 rounded-full"></div>
            <h2 class="text-2xl font-extrabold text-gray-800">চলমান প্রকল্পসমূহ</h2>
            <span class="bg-blue-100 text-blue-700 text-xs font-bold px-3 py-1 rounded-full">{{ $ongoingProjects->count() }}টি</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($ongoingProjects as $project)
            <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-sm hover:shadow-lg transition group">
                <div class="bg-gradient-to-r from-blue-600 to-blue-400 h-1.5"></div>
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <span class="text-xs text-blue-600 font-bold bg-blue-50 px-3 py-1 rounded-full">
                            📍 {{ $project->district->name ?? 'কেন্দ্রীয়' }}
                        </span>
                        <span class="bg-green-100 text-green-700 text-xs font-bold px-3 py-1 rounded-full animate-pulse">
                            চলমান
                        </span>
                    </div>

                    <h3 class="text-xl font-extrabold text-gray-900 mb-3 group-hover:text-blue-700 transition leading-snug">
                        {{ $project->name }}
                    </h3>
                    <p class="text-gray-500 text-sm mb-5 line-clamp-3 leading-relaxed">
                        {{ $project->description ?? 'প্রকল্পের বিস্তারিত তথ্য শীঘ্রই যোগ করা হবে।' }}
                    </p>

                    {{-- Progress bar --}}
                    @if($project->target_budget > 0)
                    @php
                        $collected = $project->donations_sum_amount ?? 0;
                        $percent = min(100, round(($collected / $project->target_budget) * 100));
                    @endphp
                    <div class="mb-4">
                        <div class="flex justify-between text-xs text-gray-500 mb-1">
                            <span>সংগৃহীত: ৳{{ number_format($collected) }}</span>
                            <span>{{ $percent }}%</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2">
                            <div class="bg-green-500 h-2 rounded-full transition-all" style="width: {{ $percent }}%"></div>
                        </div>
                    </div>
                    @endif

                    <div class="border-t pt-4 flex justify-between items-center">
                        <div>
                            <p class="text-xs text-gray-400 uppercase font-bold tracking-wider">লক্ষ্যমাত্রা</p>
                            <p class="text-lg font-extrabold text-gray-900">৳{{ number_format($project->target_budget) }}</p>
                        </div>
                        <a href="{{ route('donate.index') }}?project_id={{ $project->id }}"
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg font-bold text-sm hover:bg-blue-700 transition shadow-sm">
                            দান করুন
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-14 bg-gray-50 rounded-2xl border border-dashed border-gray-200">
                <p class="text-4xl mb-3">📋</p>
                <p class="text-gray-500 font-semibold">বর্তমানে কোনো চলমান প্রকল্প নেই।</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- ===== COMPLETED ===== --}}
    <div>
        <div class="flex items-center gap-3 mb-8">
            <div class="h-8 w-1.5 bg-green-500 rounded-full"></div>
            <h2 class="text-2xl font-extrabold text-gray-800">সম্পন্ন প্রকল্পসমূহ</h2>
            <span class="bg-green-100 text-green-700 text-xs font-bold px-3 py-1 rounded-full">{{ $completedProjects->count() }}টি</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($completedProjects as $project)
            <div class="bg-gray-50 border border-gray-200 rounded-2xl overflow-hidden hover:shadow-md transition group">
                <div class="bg-gradient-to-r from-green-500 to-green-400 h-1.5"></div>
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <span class="text-xs text-gray-500 font-bold bg-gray-100 px-3 py-1 rounded-full">
                            📍 {{ $project->district->name ?? 'কেন্দ্রীয়' }}
                        </span>
                        <span class="bg-gray-200 text-gray-600 text-xs font-bold px-3 py-1 rounded-full">
                            সম্পন্ন ✓
                        </span>
                    </div>

                    <h3 class="text-xl font-extrabold text-gray-800 mb-3 group-hover:text-green-700 transition leading-snug">
                        {{ $project->name }}
                    </h3>
                    <p class="text-gray-500 text-sm mb-5 line-clamp-3 leading-relaxed">
                        {{ $project->description ?? 'প্রকল্পটি সফলভাবে সম্পন্ন হয়েছে।' }}
                    </p>

                    <div class="border-t pt-4 flex justify-between items-center">
                        <div>
                            <p class="text-xs text-gray-400 uppercase font-bold tracking-wider">ব্যয়িত তহবিল</p>
                            <p class="text-lg font-extrabold text-green-700">৳{{ number_format($project->target_budget) }}</p>
                        </div>
                        @if($project->completed_at ?? null)
                        <span class="text-xs text-gray-400 font-semibold">
                            {{ \Carbon\Carbon::parse($project->completed_at)->format('M Y') }}
                        </span>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-14 bg-gray-50 rounded-2xl border border-dashed border-gray-200">
                <p class="text-4xl mb-3">🏁</p>
                <p class="text-gray-500 font-semibold">এখনো কোনো সম্পন্ন প্রকল্প নেই।</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- CTA --}}
    <div class="mt-16 bg-gradient-to-r from-blue-600 to-blue-800 rounded-2xl p-10 text-white text-center relative overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/asfalt-dark.png')]"></div>
        <div class="relative">
            <h3 class="text-2xl font-extrabold mb-3">একটি প্রকল্পে সহযোগী হোন</h3>
            <p class="text-blue-200 text-sm mb-6 max-w-xl mx-auto">আপনার ছোট্ট অবদান অনেক মানুষের জীবন বদলে দিতে পারে।</p>
            <a href="{{ route('donate.index') }}"
                class="inline-block bg-white text-blue-700 font-extrabold px-8 py-3 rounded-full hover:bg-blue-50 transition shadow-md text-sm">
                এখনই দান করুন →
            </a>
        </div>
    </div>

</div>
@endsection