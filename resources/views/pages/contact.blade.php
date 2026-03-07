@extends('layouts.app')

@section('title', 'Contact Us - NGO Foundation')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-16">
    <div class="text-center mb-10">
        <h1 class="text-4xl font-extrabold text-blue-900 mb-4">Get in Touch</h1>
        <p class="text-gray-600">Have any questions or want to collaborate? Send us a message!</p>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-8 rounded shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white p-8 rounded-xl shadow-md border border-gray-100">
        <form action="{{ route('inquiry.store') }}" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" name="type" value="contact">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Full Name *</label>
                    <input type="text" name="name" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Phone Number *</label>
                    <input type="text" name="phone" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
            </div>

            <div>
                <label class="block text-gray-700 font-bold mb-2">Email Address (Optional)</label>
                <input type="email" name="email" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <div>
                <label class="block text-gray-700 font-bold mb-2">Your Message *</label>
                <textarea name="message" rows="5" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"></textarea>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-blue-700 transition duration-300">
                Send Message
            </button>
        </form>
    </div>
</div>
@endsection