@extends('layouts.app')
@section('title', 'My Dashboard')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-12">
    <div class="flex justify-between items-center mb-8 border-b pb-4">
        <h1 class="text-3xl font-bold text-blue-900">Hello, {{ $user->name }} 👋</h1>
        <form action="{{ route('logout') }}" method="POST">
            @csrf <button type="submit" class="bg-red-500 text-white px-5 py-2 rounded shadow hover:bg-red-600 transition">Logout</button>
        </form>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-md border-l-4 border-green-500 mb-8 max-w-sm">
        <p class="text-gray-500 font-semibold">Total Donated by You</p>
        <h2 class="text-4xl font-extrabold text-green-600 mt-2">৳{{ number_format($totalDonated) }}</h2>
    </div>

    <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
        <h3 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">My Donation History</h3>
        
        @if($donations->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="p-4">Date</th>
                            <th class="p-4">Receipt No.</th>
                            <th class="p-4">Project</th>
                            <th class="p-4">Amount</th>
                            <th class="p-4">Status</th>
                            <th class="p-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($donations as $donation)
                        <tr class="border-b last:border-0 hover:bg-gray-50">
                            <td class="p-4 text-gray-600">{{ $donation->created_at->format('d M, Y') }}</td>
                            <td class="p-4 font-mono text-sm">{{ $donation->receipt_no }}</td>
                            <td class="p-4">{{ $donation->project ? $donation->project->name : 'General Fund' }}</td>
                            <td class="p-4 font-bold text-green-600">৳{{ number_format($donation->amount) }}</td>
                            <td class="p-4">
                                @if(strtolower($donation->status) === 'completed')
                                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-bold">Completed</span>
                                @else
                                    <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-xs font-bold">{{ ucfirst($donation->status) }}</span>
                                @endif
                            </td>
                            <td class="p-4 text-right">
                                <a href="{{ route('donate.receipt', $donation->id) }}" class="text-indigo-600 border border-indigo-200 px-3 py-1 rounded hover:bg-indigo-600 hover:text-white transition text-sm">Download PDF</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500 py-4 text-center">You haven't made any donations yet.</p>
        @endif
    </div>
</div>
@endsection