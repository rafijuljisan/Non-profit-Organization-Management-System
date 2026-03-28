@extends('layouts.app')

@section('title', $project->name . ' - ' . ($settings->site_name ?? config('app.name')))

@section('content')
<style>
    body, h1, h2, h3, h4, p, span, div, li, summary, details { font-family: 'SolaimanLipi', sans-serif !important; }
    .prose h2 { font-size: 1.5rem; font-weight: bold; margin-top: 1.5em; margin-bottom: 0.5em; color: #1f2937; }
    .prose p { margin-bottom: 1em; line-height: 1.7; color: #4b5563; font-size: 1.1rem; }
    
    /* FAQ Accordion Styling Native */
    details > summary { list-style: none; cursor: pointer; transition: 0.3s; }
    details > summary::-webkit-details-marker { display: none; }
    details[open] summary { background-color: #f1f5f9; color: #d32f2f; }
</style>

<div class="bg-gray-50 py-10">
    <div class="max-w-6xl mx-auto px-4">
        
        <a href="{{ route('projects.index') }}" class="inline-flex items-center text-blue-600 font-bold mb-6 hover:text-blue-800 transition text-lg">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            সকল প্রকল্পে ফিরে যান
        </a>

        <div class="mb-8">
            <h1 class="text-3xl md:text-5xl font-extrabold text-gray-900 mb-4 leading-tight">{{ $project->name }}</h1>
            <div class="flex gap-3">
                <span class="bg-gray-200 text-gray-700 text-sm font-bold px-4 py-1.5 rounded-full">
                    📍 {{ $project->project_area ?? ($project->district->name ?? 'সারা বাংলাদেশ') }}
                </span>
                @if($project->status == 'ongoing')
                    <span class="bg-blue-100 text-blue-700 text-sm font-bold px-4 py-1.5 rounded-full animate-pulse">চলমান</span>
                @elseif($project->status == 'completed')
                    <span class="bg-green-100 text-green-700 text-sm font-bold px-4 py-1.5 rounded-full">সম্পন্ন ✓</span>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-2 space-y-8">
                
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4 border-b pb-2">প্রকল্পের বিবরণ</h2>
                    <p class="text-lg text-gray-600 mb-6 leading-relaxed">{{ $project->description }}</p>
                    
                    @if($project->details)
                        <div class="prose max-w-none text-gray-700">
                            {!! $project->details !!}
                        </div>
                    @endif
                </div>

                @if($project->objectives && count($project->objectives) > 0)
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4 border-b pb-2">প্রকল্পের লক্ষ্য-উদ্দেশ্য</h2>
                    <ul class="space-y-3">
                        @foreach($project->objectives as $objective)
                            <li class="flex items-start gap-3 text-lg text-gray-700">
                                <svg class="w-6 h-6 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span>{{ $objective }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @if($project->gallery && count($project->gallery) > 0)
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4 border-b pb-2">গ্যালারি</h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($project->gallery as $image)
                            <a href="{{ asset('storage/' . $image) }}" target="_blank" class="block overflow-hidden rounded-xl border border-gray-200 hover:shadow-md transition">
                                <img src="{{ asset('storage/' . $image) }}" class="w-full h-32 md:h-48 object-cover hover:scale-105 transition-transform duration-500" alt="Gallery Image">
                            </a>
                        @endforeach
                    </div>
                </div>
                @endif

                @if($project->faqs && count($project->faqs) > 0)
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-2">FAQ বা সাধারণ জিজ্ঞাসা</h2>
                    <div class="space-y-4">
                        @foreach($project->faqs as $faq)
                            <details class="group border border-gray-200 rounded-xl bg-white overflow-hidden">
                                <summary class="flex justify-between items-center font-bold text-lg p-5 text-gray-800 bg-gray-50 group-open:bg-blue-50 group-open:text-blue-700 transition-colors">
                                    {{ $faq['question'] }}
                                    <span class="transition group-open:rotate-180">
                                        <svg fill="none" height="24" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="24"><polyline points="6 9 12 15 18 9"></polyline></svg>
                                    </span>
                                </summary>
                                <div class="text-gray-600 text-base p-5 border-t border-gray-200 leading-relaxed bg-white">
                                    {{ $faq['answer'] }}
                                </div>
                            </details>
                        @endforeach
                    </div>
                </div>
                @endif

            </div>

            <div class="lg:col-span-1">
                <div class="sticky top-24 space-y-6">
                    
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                        @if($project->target_budget > 0)
                            @php
                                $collected = $project->collected_amount ?? 0;
                                $percent = min(100, round(($collected / $project->target_budget) * 100));
                            @endphp
                            <div class="mb-4">
                                <div class="flex justify-between text-sm font-bold text-gray-500 mb-2">
                                    <span>সংগৃহীত: <span class="text-green-600 text-lg">৳{{ number_format($collected) }}</span></span>
                                    <span>লক্ষ্য: ৳{{ number_format($project->target_budget) }}</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-3">
                                    <div class="bg-green-500 h-3 rounded-full" style="width: {{ $percent }}%"></div>
                                </div>
                            </div>
                        @endif

                        @if($project->status != 'completed')
                        <a href="{{ route('donate.index') }}?project_id={{ $project->id }}" class="block w-full bg-red-600 text-white text-center font-extrabold text-xl px-6 py-4 rounded-xl hover:bg-red-700 transition shadow-md mt-4">
                            হাত বাড়ান দুর্গতের প্রতি<br>
                            <span class="text-sm font-normal">এখনই দান করুন</span>
                        </a>
                        @else
                        <div class="bg-green-50 text-green-700 text-center font-bold text-lg px-6 py-4 rounded-xl border border-green-200 mt-4">
                            প্রকল্পটি সফলভাবে সম্পন্ন হয়েছে
                        </div>
                        @endif
                    </div>

                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                        <h3 class="text-xl font-bold text-gray-800 mb-5 border-b pb-2">প্রকল্পের সারসংক্ষেপ</h3>
                        
                        <ul class="space-y-4">
                            @if($project->beneficiaries)
                            <li>
                                <span class="block text-sm text-gray-500 font-bold mb-1">উপকারভোগী:</span>
                                <span class="text-base text-gray-800 font-semibold">{{ $project->beneficiaries }}</span>
                            </li>
                            @endif

                            @if($project->project_area)
                            <li>
                                <span class="block text-sm text-gray-500 font-bold mb-1">প্রকল্পের এলাকা:</span>
                                <span class="text-base text-gray-800 font-semibold">{{ $project->project_area }}</span>
                            </li>
                            @endif

                            @if($project->duration)
                            <li>
                                <span class="block text-sm text-gray-500 font-bold mb-1">মেয়াদ:</span>
                                <span class="text-base text-gray-800 font-semibold">{{ $project->duration }}</span>
                            </li>
                            @endif

                            @if($project->expense_sectors && count($project->expense_sectors) > 0)
                            <li>
                                <span class="block text-sm text-gray-500 font-bold mb-1">ব্যয়ের খাত:</span>
                                <div class="flex flex-wrap gap-2 mt-1">
                                    @foreach($project->expense_sectors as $sector)
                                        <span class="bg-gray-100 border border-gray-200 text-gray-700 text-sm px-3 py-1 rounded-md">{{ $sector }}</span>
                                    @endforeach
                                </div>
                            </li>
                            @endif
                        </ul>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection