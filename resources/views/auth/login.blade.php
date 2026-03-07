@extends('layouts.app')
@section('title', 'Login - ' . $settings->site_name)

@section('content')
<div class="max-w-md mx-auto mt-16 p-8 bg-white rounded-xl shadow-md border border-gray-100 mb-16">
    <h2 class="text-3xl font-bold text-center text-blue-900 mb-6">Welcome Back</h2>
    
    <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
        @csrf
        <div>
            <label class="block text-gray-700 font-semibold mb-2">Phone Number</label>
            <input type="text" name="phone" required class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
            @error('phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div>
            <label class="block text-gray-700 font-semibold mb-2">Password</label>
            <input type="password" name="password" required class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
        </div>
        <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 rounded-lg hover:bg-blue-700 transition shadow-md">Login</button>
    </form>
    
    <p class="text-center mt-6 text-gray-600">
        Don't have an account? <a href="{{ route('register') }}" class="text-blue-600 font-bold hover:underline">Register here</a>
    </p>
</div>
@endsection