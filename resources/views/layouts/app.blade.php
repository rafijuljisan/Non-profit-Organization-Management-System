<!DOCTYPE html>
<html lang="bn">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', $settings->site_name ?? config('app.name'))</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&family=Tiro+Bangla:ital@0;1&display=swap" rel="stylesheet">
    @if($settings->google_site_verification)
        <meta name="google-site-verification" content="{{ $settings->google_site_verification }}" />
    @endif
    <style>
        @font-face {
            font-family: 'SolaimanLipi';
            src: url('/fonts/SolaimanLipi.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
            font-display: swap;
        }

        :root {
            /* Topbar: Professional Blue */
            --primary-dark: #1e40af; 
            
            /* Main Menu: Soft slate/off-white (not pure white) */
            --primary: #f8fafc; 
            
            /* Body background */
            --surface: #ffffff; 
            
            /* Text colors for light backgrounds */
            --text: #1e293b; /* Dark slate for high contrast */
            --text-muted: #475569; /* Medium slate for secondary text */
            
            /* Logo's vibrant orange */
            --accent: #e8a020; 
            --accent-dark: #c4851a; 
        }

        body {
            font-family: 'SolaimanLipi', 'Hind Siliguri', sans-serif;
            color: var(--text);
            background: var(--surface);
            font-size: 18px;
            line-height: 1.75;
        }

        /* ── Topbar (Blue Background, White Text) ── */
        .topbar { 
            background: var(--primary-dark); 
            color: #ffffff; /* Forces white text on the blue background */
            font-size: 14px; 
        }
        .topbar a { transition: color .2s; }
        .topbar a:hover { color: var(--accent); }

        /* ── Nav (Light Background, Dark Text) ── */
        .main-nav { 
            background: var(--primary); 
            box-shadow: 0 2px 15px rgba(0,0,0,0.08); 
        }
        .nav-link {
            position: relative;
            padding-bottom: 2px;
            font-size: 18px;
            color: var(--text);
            transition: color .2s;
            font-weight: 600;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -2px; left: 0;
            width: 0; height: 2px;
            background: var(--accent);
            transition: width .25s ease;
        }
        /* Hover turns text to orange */
        .nav-link:hover, .nav-link.active { color: var(--accent); }
        .nav-link:hover::after, .nav-link.active::after { width: 100%; }

        /* Donate CTA pulse */
        @keyframes glow-pulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(232,160,32,.5); }
            50% { box-shadow: 0 0 0 6px rgba(232,160,32,0); }
        }
        .btn-donate { animation: glow-pulse 2.5s infinite; }

        /* ── Footer (Light Gray Backgrounds, Dark Text) ── */
        .footer-top { 
            background: #f3f4f6; /* Light gray */
            border-top: 1px solid #e5e7eb;
            color: var(--text);
        }
        .footer-bottom { 
            background: #e5e7eb; /* Slightly darker gray for contrast */
            color: var(--text-muted);
        }
        .footer-heading { 
            font-size: 18px; 
            color: var(--text);
            font-weight: 700;
        }
        .footer-body { 
            font-size: 18px; 
            color: var(--text-muted);
        }
        .footer-link { 
            color: var(--text-muted);
            transition: color .2s, padding-left .2s; 
            font-size: 18px; 
        }
        .footer-link:hover { 
            color: var(--accent); 
            padding-left: 6px; 
        }
        
        /* Social Buttons (Dark borders/icons, turns orange on hover) */
        .social-btn {
            width: 40px; height: 40px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            border: 1px solid #cbd5e1;
            color: var(--text-muted);
            transition: all .25s;
        }
        .social-btn:hover { 
            background: var(--accent); 
            border-color: var(--accent); 
            color: #ffffff; 
            transform: translateY(-3px); 
        }

        /* Mobile menu */
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="flex flex-col min-h-screen">

{{-- ══════════════════════════════════════════
     TOP BAR (not sticky)
══════════════════════════════════════════ --}}
<div class="topbar text-white">
    <div class="max-w-7xl mx-auto px-4 py-2 flex flex-wrap items-center justify-between gap-2">

        {{-- Left: contact info --}}
        <div class="flex items-center gap-5 text-gray-300">
            @if($settings->phone ?? null)
            <a href="tel:{{ $settings->phone }}" class="flex items-center gap-1.5">
                <svg class="w-4 h-4 text-[#e8a020] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                {{ $settings->phone }}
            </a>
            @endif
            @if($settings->email ?? null)
            <a href="mailto:{{ $settings->email }}" class="hidden sm:flex items-center gap-1.5">
                <svg class="w-4 h-4 text-[#e8a020] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                {{ $settings->email }}
            </a>
            @endif
            @if($settings->address ?? null)
            <span class="hidden lg:flex items-center gap-1.5">
                <svg class="w-4 h-4 text-[#e8a020] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                {{ Str::limit($settings->address, 55) }}
            </span>
            @endif
        </div>

        {{-- Right: social + login --}}
        <div class="flex items-center gap-4 text-gray-300">
            @if($settings->facebook_url ?? null)
            <a href="{{ $settings->facebook_url }}" target="_blank" class="hover:text-[#e8a020]" title="Facebook">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
            </a>
            @endif
            @if($settings->youtube_url ?? null)
            <a href="{{ $settings->youtube_url }}" target="_blank" class="hover:text-[#e8a020]" title="YouTube">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
            </a>
            @endif
            <span class="text-gray-600">|</span>
            @guest
            <a href="{{ route('login') }}" class="hover:text-[#e8a020] font-semibold">লগিন</a>
            @endguest
            @auth
            <a href="{{ route('donor.dashboard') }}" class="hover:text-[#e8a020] font-semibold">ড্যাশবোর্ড</a>
            @endauth
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════
     MAIN NAV (sticky)
══════════════════════════════════════════ --}}
<nav x-data="{ mobileMenuOpen: false }" class="main-nav sticky top-0 z-50 text-gray-900">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-center justify-between h-[68px]">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-3 shrink-0">
                @if($settings->site_logo ?? null)
                    <img src="{{ asset('storage/' . $settings->site_logo) }}" alt="Logo" class="h-11 w-auto">
                @else
                    <div class="flex flex-col leading-tight">
                        <span class="text-xl font-bold tracking-wide text-white">
                            {{ $settings->site_name ?? config('app.name') }}
                        </span>
                        @if($settings->tagline ?? null)
                        <span class="text-[11px] text-blue-200 font-normal tracking-widest uppercase">{{ $settings->tagline }}</span>
                        @endif
                    </div>
                @endif
            </a>

            {{-- Desktop Links --}}
            @php $currentRoute = request()->route()?->getName(); @endphp
            <div class="hidden lg:flex items-center gap-0.5 font-semibold">
                @foreach([
                    ['home', 'হোম'],
                    ['about', 'আমাদের সম্পর্কে'],
                    ['mission', 'মিশন ও ভিশন'],
                    ['projects.index', 'প্রকল্পসমূহ'],
                    ['gallery', 'গ্যালারি'],
                    ['transparency', 'স্বচ্ছতা'],
                    ['volunteer', 'স্বেচ্ছাসেবক'],
                    ['contact', 'যোগাযোগ'],
                ] as [$routeName, $label])
                <a href="{{ route($routeName) }}"
                    class="nav-link px-3 py-2 rounded-sm {{ $currentRoute === $routeName ? 'active' : '' }}">
                    {{ $label }}
                </a>
                @endforeach

                <div class="flex items-center gap-2 ml-3">
                    <a href="{{ route('receipt.search') }}"
                        class="px-4 py-2 rounded-full border border-blue-300 text-blue-900 hover:border-[#e8a020] hover:text-[#fcd77a] transition"
                        style="font-size:14px;">
                        রিসিপ্ট খুঁজুন
                    </a>
                    <a href="{{ route('donate.index') }}"
                        class="btn-donate px-5 py-2 rounded-full bg-[#e8a020] text-white font-bold hover:bg-[#c4851a] transition shadow-lg"
                        style="font-size:15px;">
                        দান করুন
                    </a>
                </div>
            </div>

            {{-- Mobile Hamburger --}}
            <button @click="mobileMenuOpen = !mobileMenuOpen"
                class="lg:hidden p-2 rounded-md hover:bg-blue-700 transition">
                <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg x-show="mobileMenuOpen" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div x-show="mobileMenuOpen" x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="lg:hidden bg-gray-50 border-t border-blue-800 px-4 py-3">
        <div class="space-y-0.5 font-semibold" style="font-size:15px;">
            @foreach([
                ['home', 'হোম'],
                ['about', 'আমাদের সম্পর্কে'],
                ['mission', 'মিশন ও ভিশন'],
                ['projects.index', 'প্রকল্পসমূহ'],
                ['gallery', 'গ্যালারি'],
                ['transparency', 'স্বচ্ছতা'],
                ['volunteer', 'স্বেচ্ছাসেবক'],
                ['contact', 'যোগাযোগ'],
                ['receipt.search', 'রিসিপ্ট খুঁজুন'],
            ] as [$routeName, $label])
            <a href="{{ route($routeName) }}"
                class="block px-3 py-3 rounded-md text-gray-800 hover:bg-blue-700 hover:text-white transition {{ $currentRoute === $routeName ? 'bg-blue-700 text-white' : '' }}">
                {{ $label }}
            </a>
            @endforeach
            <div class="pt-3 border-t border-blue-800 flex flex-col gap-2">
                <a href="{{ route('donate.index') }}"
                    class="block text-center py-3 rounded-full bg-[#e8a020] text-white font-bold hover:bg-[#c4851a] transition">
                    দান করুন
                </a>
                @guest
                <a href="{{ route('login') }}"
                    class="block text-center py-3 rounded-full border border-blue-400 text-blue-200 hover:bg-blue-700 transition">
                    লগিন
                </a>
                @endguest
                @auth
                <a href="{{ route('donor.dashboard') }}"
                    class="block text-center py-3 rounded-full border border-blue-400 text-blue-200 hover:bg-blue-700 transition">
                    ড্যাশবোর্ড
                </a>
                @endauth
            </div>
        </div>
    </div>
</nav>

{{-- ══════════════════════════════════════════
     MAIN CONTENT
══════════════════════════════════════════ --}}
<main class="flex-grow">
    @yield('content')
</main>

{{-- ══════════════════════════════════════════
     FOOTER
══════════════════════════════════════════ --}}
<footer>

    {{-- Top Footer --}}
    <div class="footer-top text-gray-900 pt-16 pb-12">
        <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">

            {{-- Col 1: Brand --}}
            <div class="lg:col-span-1">
                @if($settings->site_logo ?? null)
                    <img src="{{ asset('storage/' . $settings->site_logo) }}" alt="Logo" class="h-14 w-auto mb-4">
                @else
                    <h3 class="text-2xl font-bold text-gray-900 mb-1">
                        {{ $settings->site_name ?? config('app.name') }}
                    </h3>
                    @if($settings->tagline ?? null)
                    <p class="text-[#e8a020] text-sm uppercase tracking-widest mb-3">{{ $settings->tagline }}</p>
                    @endif
                @endif
                <p class="footer-body text-gray-900 leading-relaxed mt-3">
                    {{ $settings->about_footer ?? 'সমাজের উন্নয়নে নিবেদিত একটি মানবসেবী প্রতিষ্ঠান।' }}
                </p>

                {{-- Social Icons --}}
                <div class="flex gap-3 mt-6">
                    @if($settings->facebook_url ?? null)
                    <a href="{{ $settings->facebook_url }}" target="_blank" class="social-btn text-gray-900" title="Facebook">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                    @endif
                    @if($settings->youtube_url ?? null)
                    <a href="{{ $settings->youtube_url }}" target="_blank" class="social-btn text-gray-900" title="YouTube">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                    </a>
                    @endif
                    @if($settings->phone ?? null)
                    <a href="tel:{{ $settings->phone }}" class="social-btn text-gray-900" title="Call Us">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    </a>
                    @endif
                </div>
            </div>

            {{-- Col 2: Quick Links --}}
            <div>
                <h4 class="footer-heading text-gray-900 font-bold uppercase tracking-widest mb-5 flex items-center gap-2">
                    <span class="inline-block w-6 h-0.5 bg-[#e8a020]"></span>
                    দ্রুত লিংক
                </h4>
                <ul class="space-y-3 text-gray-300">
                    @foreach([
                        ['home', 'হোম'],
                        ['about', 'আমাদের সম্পর্কে'],
                        ['mission', 'মিশন ও ভিশন'],
                        ['projects.index', 'প্রকল্পসমূহ'],
                        ['gallery', 'গ্যালারি'],
                        ['transparency', 'স্বচ্ছতা'],
                        ['volunteer', 'স্বেচ্ছাসেবক'],
                    ] as [$routeName, $label])
                    <li>
                        <a href="{{ route($routeName) }}" class="footer-link flex items-center gap-2">
                            <span class="text-[#e8a020]">›</span> {{ $label }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- Col 3: Support --}}
            <div>
                <h4 class="footer-heading text-gray-900 font-bold uppercase tracking-widest mb-5 flex items-center gap-2">
                    <span class="inline-block w-6 h-0.5 bg-[#e8a020]"></span>
                    সাপোর্ট
                </h4>
                <ul class="space-y-3 text-gray-300">
                    <li><a href="{{ route('donate.index') }}" class="footer-link flex items-center gap-2"><span class="text-[#e8a020]">›</span> দান করুন</a></li>
                    <li><a href="{{ route('receipt.search') }}" class="footer-link flex items-center gap-2"><span class="text-[#e8a020]">›</span> রিসিপ্ট খুঁজুন</a></li>
                    <li><a href="{{ route('contact') }}" class="footer-link flex items-center gap-2"><span class="text-[#e8a020]">›</span> যোগাযোগ করুন</a></li>
                    <li><a href="{{ url('/admin') }}" class="footer-link flex items-center gap-2"><span class="text-[#e8a020]">›</span> এডমিন প্যানেল</a></li>
                    @guest
                    <li><a href="{{ route('login') }}" class="footer-link flex items-center gap-2"><span class="text-[#e8a020]">›</span> লগিন</a></li>
                    @endguest
                </ul>
            </div>

            {{-- Col 4: Contact --}}
            <div>
                <h4 class="footer-heading text-gray-900 font-bold uppercase tracking-widest mb-5 flex items-center gap-2">
                    <span class="inline-block w-6 h-0.5 bg-[#e8a020]"></span>
                    যোগাযোগ
                </h4>
                <ul class="space-y-4 text-gray-300">
                    @if($settings->address ?? null)
                    <li class="flex items-start gap-3">
                        <span class="mt-1 w-8 h-8 rounded-full bg-white/10 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-[#e8a020]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </span>
                        <span class="footer-body leading-relaxed">{{ $settings->address }}</span>
                    </li>
                    @endif
                    @if($settings->phone ?? null)
                    <li class="flex items-center gap-3">
                        <span class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-[#e8a020]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        </span>
                        <a href="tel:{{ $settings->phone }}" class="footer-body hover:text-[#e8a020] transition">{{ $settings->phone }}</a>
                    </li>
                    @endif
                    @if($settings->email ?? null)
                    <li class="flex items-center gap-3">
                        <span class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-[#e8a020]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </span>
                        <a href="mailto:{{ $settings->email }}" class="footer-body hover:text-[#e8a020] transition">{{ $settings->email }}</a>
                    </li>
                    @endif
                </ul>

                <div class="mt-7">
                    <a href="{{ route('donate.index') }}"
                        class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-[#e8a020] text-white font-bold hover:bg-[#c4851a] transition shadow-lg"
                        style="font-size:15px;">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        এখনই দান করুন
                    </a>
                </div>
            </div>

        </div>
    </div>

    {{-- Bottom Bar --}}
    <div class="footer-bottom text-gray-500" style="font-size:13px;">
        <div class="max-w-7xl mx-auto px-4 py-4 flex flex-col sm:flex-row items-center justify-between gap-2">
            <p>&copy; {{ date('Y') }} {{ $settings->site_name ?? config('app.name') }}. সর্বস্বত্ব সংরক্ষিত।</p>
            <div class="flex items-center gap-4">
                <a href="{{ route('about') }}" class="hover:text-gray-300 transition">আমাদের সম্পর্কে</a>
                <span class="text-gray-700">•</span>
                <a href="{{ route('contact') }}" class="hover:text-gray-300 transition">যোগাযোগ</a>
                <span class="text-gray-700">•</span>
                <a href="{{ route('transparency') }}" class="hover:text-gray-300 transition">স্বচ্ছতা</a>
            </div>
        </div>
    </div>

</footer>

</body>
</html>