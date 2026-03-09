@extends('layouts.app')
@section('title', 'My Dashboard')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-12">
    <div class="flex flex-col md:flex-row justify-between items-center mb-10 border-b border-gray-200 pb-6 gap-4">
        <div class="flex items-center gap-4">
            <img src="{{ $user->photo ? asset('storage/' . $user->photo) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=e2e8f0&color=1e3a8a' }}" 
                 alt="Profile" class="w-16 h-16 rounded-full border-2 border-blue-100 object-cover shadow-sm">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Hello, {{ $user->name }} 👋</h1>
                <p class="text-gray-500 text-sm mt-1">Member ID: <span class="font-bold text-blue-600">{{ $user->member_id ?? 'Pending' }}</span></p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('member.id-card', $user->id) }}" target="_blank" class="bg-blue-600 text-white px-5 py-2.5 rounded-lg shadow-sm hover:bg-blue-700 transition font-medium flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                ID Card
            </a>
            
            <form action="{{ route('logout') }}" method="POST">
                @csrf 
                <button type="submit" class="bg-red-50 text-red-600 px-5 py-2.5 rounded-lg border border-red-100 shadow-sm hover:bg-red-500 hover:text-white transition font-medium">Logout</button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 border-l-4 border-l-green-500 hover:shadow-md transition">
            <p class="text-gray-500 font-semibold text-sm uppercase tracking-wider">Total Donated by You</p>
            <h2 class="text-3xl font-extrabold text-green-600 mt-2">৳{{ number_format($totalDonated) }}</h2>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 border-l-4 border-l-blue-500 hover:shadow-md transition">
            <p class="text-gray-500 font-semibold text-sm uppercase tracking-wider">Account Status</p>
            <div class="mt-2">
                @if(strtolower($user->status) === 'active')
                    <span class="bg-blue-100 text-blue-800 px-4 py-1.5 rounded-full text-sm font-bold">Active Member</span>
                @else
                    <span class="bg-yellow-100 text-yellow-800 px-4 py-1.5 rounded-full text-sm font-bold">{{ ucfirst($user->status) }}</span>
                @endif
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm p-0 border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 bg-gray-50">
            <h3 class="text-xl font-bold text-gray-800">My Donation History</h3>
        </div>
        
        @if($donations->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-white text-gray-500 text-sm uppercase tracking-wider border-b border-gray-200">
                        <tr>
                            <th class="p-4 font-semibold">Date</th>
                            <th class="p-4 font-semibold">Receipt No.</th>
                            <th class="p-4 font-semibold">Project</th>
                            <th class="p-4 font-semibold">Amount</th>
                            <th class="p-4 font-semibold">Status</th>
                            <th class="p-4 font-semibold text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @foreach($donations as $donation)
                        <tr class="border-b border-gray-50 last:border-0 hover:bg-slate-50 transition">
                            <td class="p-4 text-gray-600">{{ $donation->created_at->format('d M, Y') }}</td>
                            <td class="p-4 font-mono text-gray-500">{{ $donation->receipt_no }}</td>
                            <td class="p-4 font-medium text-gray-800">{{ $donation->project ? $donation->project->name : 'General Fund' }}</td>
                            <td class="p-4 font-bold text-gray-800">৳{{ number_format($donation->amount) }}</td>
                            <td class="p-4">
                                @if(in_array(strtolower($donation->status), ['completed', 'approved']))
                                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold inline-flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Completed</span>
                                @elseif(strtolower($donation->status) === 'pending')
                                    <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-bold inline-flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-yellow-500"></span> Pending</span>
                                @else
                                    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-bold inline-flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> {{ ucfirst($donation->status) }}</span>
                                @endif
                            </td>
                            <td class="p-4 text-right">
                                <a href="{{ route('donate.receipt', $donation->id) }}" class="inline-flex items-center gap-1 text-indigo-600 bg-indigo-50 px-3 py-1.5 rounded hover:bg-indigo-600 hover:text-white transition font-medium">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                    PDF
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-10 text-center flex flex-col items-center">
                <div class="bg-gray-100 p-4 rounded-full mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4M8 16l-4-4 4-4"></path></svg>
                </div>
                <p class="text-gray-500 font-medium">You haven't made any donations yet.</p>
                <a href="/donate" class="mt-4 text-blue-600 font-semibold hover:underline">Make your first donation</a>
            </div>
        @endif
    </div>
</div>
@endsection