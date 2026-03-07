@extends('layouts.app')

@section('title', 'Make a Donation - NGO Foundation')

@section('content')
<div class="max-w-3xl mx-auto mt-10 p-6 bg-white rounded-lg shadow-md mb-10">
    <h2 class="text-3xl font-bold text-center text-blue-600 mb-2">Make a Donation</h2>
    <p class="text-center text-gray-500 mb-8">Your contribution helps us build a better future.</p>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-6 rounded relative mb-8 text-center shadow-sm">
            <h3 class="text-xl font-bold mb-2">{{ session('success') }}</h3>
            
            @if(session('donation_id'))
                <p class="mb-4 text-sm font-medium">You can now download your official donation receipt.</p>
                <a href="{{ route('donate.receipt', session('donation_id')) }}" class="inline-block bg-indigo-600 text-white font-bold py-2 px-6 rounded-lg hover:bg-indigo-700 transition shadow-md">
                    📄 Download PDF Receipt
                </a>
            @endif
        </div>
    @endif

    <form action="{{ route('donate.store') }}" method="POST" class="space-y-6">
        @csrf 
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Full Name *</label>
                <input type="text" name="donor_name" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Phone Number *</label>
                <input type="text" name="donor_phone" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Amount (৳) *</label>
                <input type="number" name="amount" min="10" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Select Project (Optional)</label>
                <select name="project_id" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                    <option value="">General Donation</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Payment Method *</label>
                <select name="payment_method" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                    <option value="bkash">bKash</option>
                    <option value="nagad">Nagad</option>
                    <option value="bank">Bank Transfer</option>
                </select>
            </div>
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Transaction ID / Receipt No *</label>
                <input type="text" name="receipt_no" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g. TRX12345678">
                @error('receipt_no') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="text-center pt-4">
            <button type="submit" class="bg-blue-600 text-white font-bold py-3 px-8 rounded-full hover:bg-blue-700 transition w-full md:w-auto shadow-lg">
                Submit Donation
            </button>
        </div>
    </form>
</div>
@endsection