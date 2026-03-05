<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'NGO Foundation')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans flex flex-col min-h-screen">

    <nav class="bg-blue-600 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <a href="{{ url('/') }}" class="text-2xl font-bold tracking-wider hover:text-gray-200 transition">NGO Foundation</a>
            <div class="space-x-4">
                <a href="{{ route('projects.index') }}" class="text-white hover:text-blue-200 font-semibold transition">Projects</a>
                <a href="{{ route('donate.index') }}" class="text-white hover:underline font-semibold">Donate Now</a>
                <a href="{{ url('/admin') }}" class="bg-white text-blue-600 px-4 py-2 rounded-md font-semibold hover:bg-gray-100 transition">Admin Login</a>
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