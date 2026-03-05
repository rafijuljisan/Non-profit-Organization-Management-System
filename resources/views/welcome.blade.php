@extends('layouts.app')

@section('title', 'Transparency Portal - NGO System')

@section('content')
    <header class="bg-white py-20 text-center shadow-sm">
        <div class="max-w-4xl mx-auto px-4">
            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-6">Building a Better Future, Together</h1>
            <p class="text-lg text-gray-600 mb-8">100% Transparency in every donation, every project, and every life we touch.</p>
            <a href="#projects" class="bg-blue-600 text-white px-8 py-3 rounded-full text-lg font-bold hover:bg-blue-700 transition shadow-md">See Our Work</a>
        </div>
    </header>

    <section class="py-16 bg-blue-50 text-center">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-3xl font-bold text-blue-900 mb-10">Live Transparency Dashboard</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                <div class="bg-white p-8 rounded-xl shadow-md border-t-4 border-blue-500">
                    <div class="text-4xl font-extrabold text-blue-600 mb-2">{{ $totalMembers }}</div>
                    <div class="text-gray-500 uppercase font-semibold tracking-wider">Active Members</div>
                </div>

                <div class="bg-white p-8 rounded-xl shadow-md border-t-4 border-green-500">
                    <div class="text-4xl font-extrabold text-green-600 mb-2">৳ {{ number_format($totalFund) }}</div>
                    <div class="text-gray-500 uppercase font-semibold tracking-wider">Total Fund Collected</div>
                </div>

                <div class="bg-white p-8 rounded-xl shadow-md border-t-4 border-purple-500">
                    <div class="text-4xl font-extrabold text-purple-600 mb-2">{{ $totalProjects }}</div>
                    <div class="text-gray-500 uppercase font-semibold tracking-wider">Projects Initiated</div>
                </div>

            </div>
        </div>
    </section>

    <section id="projects" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-3xl font-bold text-gray-800 mb-10 text-center">Ongoing Projects</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @forelse($ongoingProjects as $project)
                <div class="bg-gray-50 border rounded-lg overflow-hidden shadow-sm hover:shadow-md transition">
                    <div class="p-6">
                        <div class="text-sm text-blue-600 font-bold mb-1">{{ $project->district->name ?? 'Central' }}</div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">{{ $project->name }}</h3>
                        <p class="text-gray-600 mb-4 line-clamp-3">{{ $project->description ?? 'No description provided.' }}</p>
                        <div class="flex justify-between items-center text-sm font-semibold">
                            <span class="text-gray-500">Target: ৳{{ number_format($project->target_budget) }}</span>
                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full">Ongoing</span>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-3 text-center text-gray-500 py-10">
                    No ongoing projects at the moment.
                </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection