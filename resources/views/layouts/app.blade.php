<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', $settings->site_name ?? config('app.name'))</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-gray-50 text-gray-800 font-sans flex flex-col min-h-screen">

    <nav x-data="{ mobileMenuOpen: false }" class="bg-blue-600 text-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">

            <a href="{{ route('home') }}" class="flex items-center gap-2">
                @if($settings->site_logo ?? null)
                    <img src="{{ asset('storage/' . $settings->site_logo) }}" alt="Logo" class="h-10 w-auto">
                @else
                    <span
                        class="text-2xl font-bold tracking-wider hover:text-gray-200 transition">{{ $settings->site_name ?? config('app.name') }}</span>
                @endif
            </a>

            {{-- Desktop Nav --}}
            <div class="hidden md:flex space-x-5 lg:space-x-6 items-center text-sm font-semibold">
                <a href="{{ route('home') }}" class="hover:text-blue-200 transition">হোম</a>
                <a href="{{ route('about') }}" class="hover:text-blue-200 transition">আমাদের সম্পর্কে</a>
                <a href="{{ route('mission') }}" class="hover:text-blue-200 transition">মিশন ও ভিশন</a>
                <a href="{{ route('projects.index') }}" class="hover:text-blue-200 transition">প্রকল্পসমূহ</a>
                <a href="{{ route('transparency') }}" class="hover:text-blue-200 transition">স্বচ্ছতা</a>
                <a href="{{ route('volunteer') }}" class="hover:text-blue-200 transition">স্বেচ্ছাসেবক</a>
                <a href="{{ route('contact') }}" class="hover:text-blue-200 transition">যোগাযোগ</a>
                <a href="{{ route('receipt.search') }}" class="hover:text-blue-200 transition">রিসিপ্ট খুঁজুন</a>
                <a href="{{ route('donate.index') }}"
                    class="bg-green-500 text-white px-5 py-2 rounded-full hover:bg-green-600 transition shadow-md">দান করুন</a>
                @guest
                    <a href="{{ route('login') }}"
                        class="bg-white text-blue-600 px-4 py-2 rounded-md border border-blue-600 hover:bg-blue-50 transition shadow-sm font-bold">লগিন</a>
                @endguest
                @auth
                    <a href="{{ route('donor.dashboard') }}"
                        class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition shadow-sm font-bold">ড্যাশবোর্ড</a>
                @endauth
            </div>

            {{-- Mobile Hamburger --}}
            <div class="md:hidden flex items-center">
                <button @click="mobileMenuOpen = !mobileMenuOpen" type="button"
                    class="text-white hover:text-gray-200 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div x-show="mobileMenuOpen" class="md:hidden bg-blue-700 px-4 pt-2 pb-4 space-y-2 text-sm font-semibold"
            style="display: none;">
            <a href="{{ route('home') }}" class="block text-white py-2">হোম</a>
            <a href="{{ route('about') }}" class="block text-white py-2">আমাদের সম্পর্কে</a>
            <a href="{{ route('mission') }}" class="block text-white py-2">মিশন ও ভিশন</a>
            <a href="{{ route('projects.index') }}" class="block text-white py-2">প্রকল্পসমূহ</a>
            <a href="{{ route('transparency') }}" class="block text-white py-2">স্বচ্ছতা</a>
            <a href="{{ route('volunteer') }}" class="block text-white py-2">স্বেচ্ছাসেবক</a>
            <a href="{{ route('contact') }}" class="block text-white py-2">যোগাযোগ</a>
            <a href="{{ route('receipt.search') }}" class="block text-white py-2">রিসিপ্ট খুঁজুন</a>
            <a href="{{ route('donate.index') }}"
                class="block bg-green-500 text-center text-white mt-2 py-2 rounded">দান করুন</a>
            <a href="{{ route('login') }}" class="block bg-white text-blue-600 text-center mt-2 py-2 rounded">লগিন</a>
        </div>
    </nav>

    <main class="flex-grow">
        @yield('content')
    </main>

    <footer class="bg-gray-900 text-gray-300 py-12 mt-auto">
        <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 md:grid-cols-3 gap-8">

            <div>
                <div class="mb-4">
                    @if($settings->site_logo ?? null)
                        <img src="{{ asset('storage/' . $settings->site_logo) }}" alt="Logo" class="h-10 w-auto mb-2">
                    @else
                        <h3 class="text-xl font-bold text-white">{{ $settings->site_name ?? config('app.name') }}</h3>
                    @endif
                </div>
                <p class="text-sm leading-relaxed mb-4">
                    {{ $settings->about_footer ?? 'Empowering communities and building a better future.' }}
                </p>
                <div class="flex space-x-4">
                    @if($settings->facebook_url ?? null)
                        <a href="{{ $settings->facebook_url }}" target="_blank"
                            class="hover:text-blue-500 transition font-semibold">Facebook</a>
                    @endif
                    @if($settings->youtube_url ?? null)
                        <a href="{{ $settings->youtube_url }}" target="_blank"
                            class="hover:text-red-500 transition font-semibold">YouTube</a>
                    @endif
                </div>
            </div>

            <div>
                <h3 class="text-xl font-bold text-white mb-4">Quick Links</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('home') }}" class="hover:text-gray-400 transition">হোম</a></li>
                    <li><a href="{{ route('about') }}" class="hover:text-gray-400 transition">আমাদের সম্পর্কে</a></li>
                    <li><a href="{{ route('mission') }}" class="hover:text-gray-400 transition">মিশন ও ভিশন</a>
                    </li>
                    <li><a href="{{ route('projects.index') }}" class="hover:text-gray-400 transition">প্রকল্পসমূহ</a>
                    </li>
                    <li><a href="{{ route('transparency') }}" class="hover:text-gray-400 transition">স্বচ্ছতা</a>
                    </li>
                    <li><a href="{{ route('volunteer') }}" class="hover:text-gray-400 transition">স্বেচ্ছাসেবক</a>
                    </li>
                    <li><a href="{{ route('contact') }}" class="hover:text-gray-400 transition">যোগাযোগ</a></li>
                    <li><a href="{{ url('/admin') }}" class="hover:text-gray-400 transition">এডমিন প্যানেল</a></li>
                </ul>
            </div>

            <div>
                <h3 class="text-xl font-bold text-white mb-4">Contact Us</h3>
                <ul class="space-y-3 text-sm">
                    @if($settings->address ?? null)
                        <li class="flex items-start gap-2">
                            <span class="text-blue-500">📍</span> <span>{{ $settings->address }}</span>
                        </li>
                    @endif
                    @if($settings->phone ?? null)
                        <li class="flex items-center gap-2">
                            <span class="text-blue-500">📞</span> <span>{{ $settings->phone }}</span>
                        </li>
                    @endif
                    @if($settings->email ?? null)
                        <li class="flex items-center gap-2">
                            <span class="text-blue-500">✉️</span> <span>{{ $settings->email }}</span>
                        </li>
                    @endif
                </ul>
            </div>

        </div>

        <div class="border-t border-gray-800 mt-10 pt-6 text-center text-sm text-gray-500">
            <p>&copy; {{ date('Y') }} {{ $settings->site_name ?? config('app.name') }}. All rights reserved.</p>
        </div>
    </footer>

</body>

</html>