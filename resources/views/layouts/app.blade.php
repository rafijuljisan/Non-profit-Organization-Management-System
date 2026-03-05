<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'NGO Foundation')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans flex flex-col min-h-screen">

    <nav class="bg-blue-600 text-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <a href="{{ route('home') }}" class="text-2xl font-bold tracking-wider hover:text-gray-200 transition">NGO Foundation</a>
            
            <div class="hidden md:flex space-x-6 items-center text-sm font-semibold">
                <a href="{{ route('home') }}" class="hover:text-blue-200 transition">Home</a>
                <a href="{{ route('about') }}" class="hover:text-blue-200 transition">About</a>
                <a href="{{ route('mission') }}" class="hover:text-blue-200 transition">Mission & Vision</a>
                <a href="{{ route('projects.index') }}" class="hover:text-blue-200 transition">Projects</a>
                <a href="{{ route('transparency') }}" class="hover:text-blue-200 transition">Transparency</a>
                <a href="{{ route('volunteer') }}" class="hover:text-blue-200 transition">Volunteer</a>
                <a href="{{ route('contact') }}" class="hover:text-blue-200 transition">Contact</a>
                
                <a href="{{ route('donate.index') }}" class="bg-green-500 text-white px-5 py-2 rounded-full hover:bg-green-600 transition shadow-md">Donate Now</a>
                <a href="{{ url('/admin') }}" class="bg-white text-blue-600 px-4 py-2 rounded-md hover:bg-gray-100 transition shadow-sm">Login</a>
            </div>
        </div>
    </nav>

    <main class="flex-grow">
        @yield('content')
    </main>

    <footer class="bg-gray-900 text-gray-400 py-8 text-center mt-auto">
        <p>&copy; {{ date('Y') }} NGO Foundation. All rights reserved.</p>
    </footer>

</body>
</html>