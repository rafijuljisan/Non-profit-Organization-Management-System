@extends('layouts.app')

@section('title', 'Our Projects - NGO Foundation')

@section('content')
<div class="bg-blue-50 py-12">
    <div class="max-w-7xl mx-auto px-4 text-center">
        <h1 class="text-4xl font-extrabold text-blue-900 mb-4">Our Initiatives & Projects</h1>
        <p class="text-lg text-gray-600 max-w-2xl mx-auto">Discover how we are making a difference across various districts. Your support makes these projects a reality.</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-12">
    
    <div class="mb-16">
        <h2 class="text-3xl font-bold text-gray-800 mb-8 border-b-2 border-blue-500 inline-block pb-2">Ongoing Projects</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($ongoingProjects as $project)
            <div class="bg-white border rounded-xl overflow-hidden shadow-sm hover:shadow-lg transition duration-300">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <span class="text-sm text-blue-600 font-bold bg-blue-100 px-3 py-1 rounded-full">
                            {{ $project->district->name ?? 'Central / Nationwide' }}
                        </span>
                        <span class="bg-green-100 text-green-800 text-xs font-bold px-3 py-1 rounded-full animate-pulse">Ongoing</span>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">{{ $project->name }}</h3>
                    <p class="text-gray-600 mb-6">{{ $project->description ?? 'Project details will be updated soon.' }}</p>
                    
                    <div class="border-t pt-4 flex justify-between items-center">
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-bold tracking-wider">Target Budget</p>
                            <p class="text-lg font-extrabold text-gray-900">৳{{ number_format($project->target_budget) }}</p>
                        </div>
                        <a href="{{ route('donate.index') }}?project_id={{ $project->id }}" class="bg-blue-600 text-white px-4 py-2 rounded-md font-semibold hover:bg-blue-700 transition">
                            Donate to this
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-12 bg-gray-50 rounded-lg border border-dashed">
                <p class="text-gray-500 text-lg">No ongoing projects at the moment.</p>
            </div>
            @endforelse
        </div>
    </div>

    <div>
        <h2 class="text-3xl font-bold text-gray-800 mb-8 border-b-2 border-green-500 inline-block pb-2">Completed Projects</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($completedProjects as $project)
            <div class="bg-gray-50 border rounded-xl overflow-hidden opacity-90 hover:opacity-100 transition duration-300">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <span class="text-sm text-gray-500 font-bold">
                            {{ $project->district->name ?? 'Central / Nationwide' }}
                        </span>
                        <span class="bg-gray-200 text-gray-700 text-xs font-bold px-3 py-1 rounded-full">Completed</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">{{ $project->name }}</h3>
                    <p class="text-gray-600 mb-4 line-clamp-3">{{ $project->description }}</p>
                    
                    <div class="border-t pt-4">
                        <p class="text-xs text-gray-500 uppercase font-bold tracking-wider">Fund Utilized</p>
                        <p class="text-md font-bold text-green-700">৳{{ number_format($project->target_budget) }}</p>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-12 bg-gray-50 rounded-lg border border-dashed">
                <p class="text-gray-500 text-lg">No completed projects yet.</p>
            </div>
            @endforelse
        </div>
    </div>

</div>
@endsection