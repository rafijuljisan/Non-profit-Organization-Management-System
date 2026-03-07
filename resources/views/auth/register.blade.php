@extends('layouts.app')
@section('title', 'Register - ' . $settings->site_name)

@section('content')
<div class="max-w-md mx-auto mt-16 p-8 bg-white rounded-xl shadow-md border border-gray-100 mb-16">
    <div class="text-center mb-8">
        <h2 class="text-3xl font-extrabold text-blue-900 mb-2">Create an Account</h2>
        <p class="text-gray-500">Join us to track your donations and impact.</p>
    </div>
    
    <form action="{{ route('register.post') }}" method="POST" class="space-y-5">
        @csrf
        
        <div>
            <label class="block text-gray-700 font-semibold mb-2">Full Name *</label>
            <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition">
            @error('name') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-gray-700 font-semibold mb-2">Phone Number *</label>
            <input type="text" name="phone" value="{{ old('phone') }}" placeholder="e.g. 01700000000" required class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition">
            @error('phone') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-gray-700 font-semibold mb-2">Password *</label>
            <input type="password" name="password" placeholder="Minimum 6 characters" required class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition">
            @error('password') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-gray-700 font-semibold mb-2">Confirm Password *</label>
            <input type="password" name="password_confirmation" required class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition">
        </div>

        <button type="submit" class="w-full bg-green-600 text-white font-bold py-3 rounded-lg hover:bg-green-700 transition shadow-md mt-6">
            Register Account
        </button>
    </form>
    
    <p class="text-center mt-6 text-gray-600">
        Already have an account? <a href="{{ route('login') }}" class="text-blue-600 font-bold hover:underline">Login here</a>
    </p>
</div>
@endsection