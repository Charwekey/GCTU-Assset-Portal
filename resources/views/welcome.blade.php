<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50 dark:bg-slate-950">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>GCTU Asset & Procurement Hub</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body {
                font-family: 'Inter', sans-serif;
            }
            h1, h2, h3, h4, h5, h6 {
                font-family: 'Outfit', sans-serif;
            }
        </style>
    </head>
    <body class="h-full antialiased text-slate-900 dark:text-slate-100 bg-slate-50 dark:bg-slate-950 flex flex-col justify-between">
        
        <!-- Navbar -->
        <header class="w-full max-w-7xl mx-auto px-6 py-5 flex items-center justify-between border-b border-gray-100 dark:border-slate-800">
            <div class="flex items-center gap-2">
                <span class="bg-gradient-to-tr from-blue-600 to-indigo-500 p-2 rounded-lg text-white font-black tracking-tight shadow-md">G</span>
                <span class="font-extrabold text-lg tracking-tight text-slate-800 dark:text-white">GCTU Asset Hub</span>
            </div>
            
            <nav class="flex items-center gap-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-1.5 text-xs font-bold bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-4 py-2 rounded-xl shadow-md transition">
                        Dashboard
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-xs font-bold text-slate-700 dark:text-slate-300 hover:text-indigo-600 transition">
                        Log In
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="text-xs font-bold bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 hover:bg-gray-50 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 px-4 py-2 rounded-xl transition">
                            Register
                        </a>
                    @endif
                @endauth
            </nav>
        </header>

        <!-- Hero Panel -->
        <main class="flex-1 max-w-7xl w-full mx-auto px-6 py-12 flex flex-col lg:flex-row items-center gap-12">
            
            <!-- Left: Hero Text & Call to Action -->
            <div class="flex-1 space-y-6">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 border border-blue-100 dark:border-blue-900/30">
                    Ghana Communication Technology University
                </span>
                
                <h1 class="text-4xl sm:text-5xl font-black tracking-tight text-slate-800 dark:text-white leading-none">
                    Enterprise Asset & <br />
                    <span class="bg-gradient-to-r from-blue-600 to-indigo-500 bg-clip-text text-transparent">Procurement Hub</span>
                </h1>
                
                <p class="text-sm text-gray-500 dark:text-slate-400 max-w-lg leading-relaxed">
                    Track university assets, manage departmental procurement requests, check budget limitations, log maintenance schedules, and monitor project expenditures from a centralized dashboard.
                </p>

                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 text-sm font-bold bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-6 py-3.5 rounded-xl shadow-md transition">
                            Go to Portal Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-sm font-bold bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-6 py-3.5 rounded-xl shadow-md transition">
                            Log In to Portal
                        </a>
                    @endauth
                </div>
            </div>

            <!-- Right: Testing Credentials Panel (WOW factor & easy test access) -->
            <div class="w-full lg:max-w-md bg-white dark:bg-slate-900 rounded-3xl border border-gray-100 dark:border-slate-800 shadow-xl shadow-slate-100 dark:shadow-none p-6 space-y-6">
                <div>
                    <h3 class="font-extrabold text-base text-slate-850 dark:text-slate-200">Test Credentials & Roles</h3>
                    <p class="text-xs text-gray-400 mt-1">Use these seeded accounts to test different permission scopes:</p>
                </div>
                
                <div class="space-y-3.5">
                    <!-- Admin -->
                    <div class="flex justify-between items-center bg-gray-50/50 dark:bg-slate-950 p-3 rounded-xl border border-gray-100 dark:border-slate-900">
                        <div class="min-w-0">
                            <span class="text-xs font-bold text-slate-800 dark:text-slate-200">System Administrator</span>
                            <span class="text-[10px] text-gray-400 block truncate">admin@gctu.edu.gh</span>
                        </div>
                        <span class="text-[9px] uppercase font-black tracking-wider px-2 py-0.5 bg-blue-100 dark:bg-blue-950/40 text-blue-700 dark:text-blue-400 rounded">Admin</span>
                    </div>

                    <!-- Manager -->
                    <div class="flex justify-between items-center bg-gray-50/50 dark:bg-slate-950 p-3 rounded-xl border border-gray-100 dark:border-slate-900">
                        <div class="min-w-0">
                            <span class="text-xs font-bold text-slate-800 dark:text-slate-200">Department Manager</span>
                            <span class="text-[10px] text-gray-400 block truncate">manager@gctu.edu.gh</span>
                        </div>
                        <span class="text-[9px] uppercase font-black tracking-wider px-2 py-0.5 bg-indigo-100 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-400 rounded">Manager</span>
                    </div>

                    <!-- Officer -->
                    <div class="flex justify-between items-center bg-gray-50/50 dark:bg-slate-950 p-3 rounded-xl border border-gray-100 dark:border-slate-900">
                        <div class="min-w-0">
                            <span class="text-xs font-bold text-slate-800 dark:text-slate-200">Department Officer</span>
                            <span class="text-[10px] text-gray-400 block truncate">officer@gctu.edu.gh</span>
                        </div>
                        <span class="text-[9px] uppercase font-black tracking-wider px-2 py-0.5 bg-amber-100 dark:bg-amber-950/40 text-amber-700 dark:text-amber-400 rounded">Officer</span>
                    </div>

                    <!-- Auditor -->
                    <div class="flex justify-between items-center bg-gray-50/50 dark:bg-slate-950 p-3 rounded-xl border border-gray-100 dark:border-slate-900">
                        <div class="min-w-0">
                            <span class="text-xs font-bold text-slate-800 dark:text-slate-200">Internal Auditor</span>
                            <span class="text-[10px] text-gray-400 block truncate">auditor@gctu.edu.gh</span>
                        </div>
                        <span class="text-[9px] uppercase font-black tracking-wider px-2 py-0.5 bg-teal-100 dark:bg-teal-950/40 text-teal-700 dark:text-teal-400 rounded">Auditor</span>
                    </div>
                </div>

                <!-- Shared Password -->
                <div class="pt-4 border-t border-gray-100 dark:border-slate-800 text-center flex items-center justify-between text-xs">
                    <span class="text-gray-400">Password for all accounts:</span>
                    <code class="bg-gray-100 dark:bg-slate-950 px-2.5 py-1 rounded font-mono font-bold text-indigo-600 dark:text-indigo-400">password</code>
                </div>
            </div>

        </main>

        <!-- Footer -->
        <footer class="w-full border-t border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-900 py-6 px-6 text-center text-xs text-gray-500 dark:text-slate-400">
            &copy; 2026 Ghana Communication Technology University. All rights reserved. Powered by Laravel 13 & Tailwind CSS.
        </footer>
    </body>
</html>
