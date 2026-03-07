@extends('layouts.app')

@section('title', 'Find Your Receipts - NGO Foundation')

@section('content')
<div class="max-w-4xl mx-auto mt-10 p-6 mb-10">
    <div class="bg-white rounded-xl shadow-md p-8 border border-gray-100 mb-8">
        <h2 class="text-3xl font-bold text-center text-blue-900 mb-2">Find Your Donation Receipts</h2>
        <p class="text-center text-gray-500 mb-8">Enter the phone number you used while donating to download your previous receipts.</p>

        <form action="{{ route('receipt.search') }}" method="GET" class="max-w-md mx-auto">
            <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-transparent transition shadow-sm">
                <div class="px-4 text-gray-500 bg-gray-50 border-r border-gray-300 font-semibold">+880</div>
                <input type="text" name="phone" value="{{ request('phone') }}" placeholder="Enter your phone number..." required class="w-full px-4 py-3 focus:outline-none text-gray-700">
                <button type="submit" class="bg-blue-600 text-white px-6 py-3 font-bold hover:bg-blue-700 transition">Search</button>
            </div>
        </form>
    </div>

    @if(request()->has('phone'))
        <div class="bg-white rounded-xl shadow-md p-8 border border-gray-100">
            <h3 class="text-xl font-bold text-gray-800 mb-6 border-b pb-2">Search Results for "{{ request('phone') }}"</h3>
            
            @if($donations && $donations->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-600 text-sm uppercase tracking-wider">
                                <th class="p-4 border-b">Date</th>
                                <th class="p-4 border-b">Receipt No.</th>
                                <th class="p-4 border-b">Amount</th>
                                <th class="p-4 border-b">Status</th>
                                <th class="p-4 border-b text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700">
                            @foreach($donations as $donation)
                            <tr class="hover:bg-gray-50 border-b last:border-0 transition">
                                <td class="p-4">{{ $donation->created_at->format('d M, Y') }}</td>
                                <td class="p-4 font-mono text-sm">{{ $donation->receipt_no }}</td>
                                <td class="p-4 font-bold text-green-600">৳{{ number_format($donation->amount) }}</td>
                                <td class="p-4">
                                    @if(strtolower($donation->status) === 'completed')
                                        <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-bold shadow-sm">Completed</span>
                                    @else
                                        <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-xs font-bold shadow-sm">{{ ucfirst($donation->status) }}</span>
                                    @endif
                                </td>
                                <td class="p-4 text-right">
                                    <a href="{{ route('donate.receipt', $donation->id) }}" class="inline-block bg-indigo-50 text-indigo-600 border border-indigo-200 px-4 py-2 rounded font-semibold hover:bg-indigo-600 hover:text-white transition text-sm">
                                        Download PDF
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-10 bg-gray-50 rounded-lg">
                    <p class="text-gray-500 text-lg">No donations found with this phone number.</p>
                </div>
            @endif
        </div>
    @endif
</div>
@endsection