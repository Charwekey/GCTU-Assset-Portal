<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50 dark:bg-slate-950">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'GCTU Asset Hub') }}</title>

        <!-- Fonts (Outfit & Inter) -->
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
    <body class="h-full antialiased text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-slate-950 font-sans" x-data="{ sidebarOpen: false }">
        
        <!-- Mobile Sidebar Backdrop -->
        <div class="fixed inset-0 z-40 bg-gray-900/80 backdrop-blur-sm lg:hidden" 
             x-show="sidebarOpen" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="sidebarOpen = false"
             style="display: none;"></div>

        <!-- Mobile Sidebar Panel -->
        <div class="fixed inset-y-0 left-0 z-50 flex w-72 flex-col bg-white dark:bg-slate-900 lg:hidden"
             x-show="sidebarOpen"
             x-transition:enter="transition ease-in-out duration-300 transform"
             x-transition:enter-start="-translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in-out duration-300 transform"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="-translate-x-full"
             style="display: none;">
            
            <!-- Logo & Close -->
            <div class="flex h-16 shrink-0 items-center justify-between px-6 border-b border-gray-100 dark:border-slate-800">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                    <span class="bg-gradient-to-tr from-blue-600 to-indigo-600 p-1.5 rounded-lg text-white font-bold tracking-tight shadow-md">GCTU</span>
                    <span class="font-extrabold text-lg tracking-tight bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">Asset Hub</span>
                </a>
                <button type="button" @click="sidebarOpen = false" class="-m-2.5 p-2.5 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-white">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Navigation -->
            @include('layouts.sidebar-navigation')
        </div>

        <!-- Desktop Sidebar -->
        <div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-72 lg:flex-col">
            <div class="flex grow flex-col gap-y-5 overflow-y-auto border-r border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-900 px-6 pb-4">
                <div class="flex h-16 shrink-0 items-center border-b border-gray-100 dark:border-slate-800 mt-2">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                        <span class="bg-gradient-to-tr from-blue-600 to-indigo-500 p-2 rounded-lg text-white font-extrabold tracking-tight shadow-lg shadow-indigo-500/20">G</span>
                        <div class="flex flex-col">
                            <span class="font-black text-base tracking-tight leading-none text-slate-800 dark:text-slate-100">GCTU</span>
                            <span class="font-semibold text-xs text-gray-400 dark:text-slate-400">Asset & Procurement</span>
                        </div>
                    </a>
                </div>
                
                @include('layouts.sidebar-navigation')
            </div>
        </div>

        <!-- Main Workspace Area -->
        <div class="lg:pl-72 flex flex-col min-h-screen">
            <!-- Topbar Header -->
            <header class="sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-4 border-b border-gray-200 dark:border-slate-800 bg-white/70 dark:bg-slate-900/70 backdrop-blur-md px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8">
                <button type="button" @click="sidebarOpen = true" class="-m-2.5 p-2.5 text-gray-700 dark:text-gray-200 lg:hidden">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>

                <!-- Separator -->
                <div class="h-6 w-px bg-gray-200 dark:bg-slate-800 lg:hidden" aria-hidden="true"></div>

                <!-- Page Header Title -->
                <div class="flex-1 flex justify-start items-center">
                    @isset($header)
                        <div class="font-bold text-lg text-slate-800 dark:text-white">
                            {{ $header }}
                        </div>
                    @endisset
                </div>

                <!-- Profile and Alerts -->
                <div class="flex items-center gap-x-4 lg:gap-x-6">
                    
                    <!-- Notification Badge -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="p-1.5 rounded-full text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 focus:outline-none hover:bg-gray-100 dark:hover:bg-slate-800 transition">
                            <span class="sr-only">View notifications</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                            </svg>
                            <!-- Pulse Dot if warning exists -->
                            @php
                                $overdueCount = \App\Models\Asset::where('status', 'maintenance')->count();
                            @endphp
                            @if($overdueCount > 0)
                                <span class="absolute top-1 right-1 flex h-2.5 w-2.5">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-500"></span>
                                </span>
                            @endif
                        </button>
                        
                        <!-- Notifications Dropdown -->
                        <div x-show="open" @click.outside="open = false" 
                             class="absolute right-0 mt-2.5 z-10 w-80 origin-top-right rounded-xl bg-white dark:bg-slate-900 py-2 shadow-lg ring-1 ring-gray-900/5 focus:outline-none border border-gray-100 dark:border-slate-800"
                             style="display: none;"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95">
                            <div class="px-4 py-2 border-b border-gray-100 dark:border-slate-800">
                                <h3 class="font-bold text-sm text-slate-800 dark:text-slate-200">Alerts & Notifications</h3>
                            </div>
                            <div class="max-h-64 overflow-y-auto px-2 py-1">
                                @if($overdueCount > 0)
                                    <a href="{{ route('assets.index', ['status' => 'maintenance']) }}" class="flex items-start gap-2 p-2 hover:bg-gray-50 dark:hover:bg-slate-800 rounded-lg transition mt-1">
                                        <span class="p-1 rounded-full bg-amber-100 text-amber-600 dark:bg-amber-950 dark:text-amber-400">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                        </span>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs font-semibold text-slate-800 dark:text-slate-200">Maintenance Warning</p>
                                            <p class="text-xxs text-gray-500 dark:text-gray-400 truncate">{{ $overdueCount }} assets are currently in maintenance.</p>
                                        </div>
                                    </a>
                                @else
                                    <div class="text-center py-4 text-xs text-gray-500 dark:text-gray-400">
                                        No new notifications
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- User Profile Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center gap-x-2.5 p-1.5 text-sm font-semibold leading-6 text-gray-900 dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-slate-800 rounded-full transition">
                            <span class="bg-gradient-to-r from-blue-600 to-indigo-600 h-8 w-8 rounded-full text-white flex items-center justify-center font-bold uppercase shadow-sm">
                                {{ substr(Auth::user()->name, 0, 2) }}
                            </span>
                            <span class="hidden md:flex md:items-center">
                                <span class="text-sm font-medium" aria-hidden="true">{{ Auth::user()->name }}</span>
                                <svg class="ml-1 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/>
                                </svg>
                            </span>
                        </button>
                        
                        <div x-show="open" @click.outside="open = false" 
                             class="absolute right-0 mt-2.5 z-10 w-48 origin-top-right rounded-xl bg-white dark:bg-slate-900 py-1 shadow-lg ring-1 ring-gray-900/5 focus:outline-none border border-gray-100 dark:border-slate-800"
                             style="display: none;"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95">
                            
                            <div class="px-4 py-2 border-b border-gray-100 dark:border-slate-800">
                                <p class="text-xs text-gray-500 dark:text-gray-400">Signed in as</p>
                                <p class="text-xs font-semibold text-slate-800 dark:text-slate-200 truncate">{{ Auth::user()->email }}</p>
                                <p class="text-[10px] uppercase font-black bg-blue-100 dark:bg-slate-800 text-blue-800 dark:text-blue-400 inline-block px-1.5 py-0.5 rounded mt-1">{{ Auth::user()->role }}</p>
                            </div>

                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-800 transition">Your Profile</a>
                            
                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-800 transition">
                                    Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 py-8 px-4 sm:px-6 lg:px-8">
                
                <!-- Success/Error Alert Banners -->
                @if (session('success'))
                    <div class="mb-6 rounded-xl bg-emerald-50 dark:bg-emerald-950/30 p-4 border border-emerald-200 dark:border-emerald-900/50 flex items-start gap-3 shadow-sm" x-data="{ show: true }" x-show="show">
                        <span class="p-1 rounded-full bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-300">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </span>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-emerald-800 dark:text-emerald-300">{{ session('success') }}</p>
                        </div>
                        <button type="button" @click="show = false" class="text-emerald-500 hover:text-emerald-700 dark:hover:text-emerald-400">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 rounded-xl bg-rose-50 dark:bg-rose-950/30 p-4 border border-rose-200 dark:border-rose-900/50 flex items-start gap-3 shadow-sm" x-data="{ show: true }" x-show="show">
                        <span class="p-1 rounded-full bg-rose-100 text-rose-800 dark:bg-rose-900 dark:text-rose-300">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </span>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-rose-800 dark:text-rose-300">{{ session('error') }}</p>
                        </div>
                        <button type="button" @click="show = false" class="text-rose-500 hover:text-rose-700 dark:hover:text-rose-400">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                @endif

                {{ $slot }}
            </main>

            <!-- Premium Footer -->
            <footer class="mt-auto border-t border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-900 py-4 px-4 sm:px-6 lg:px-8 text-center text-xs text-gray-500 dark:text-slate-400">
                &copy; 2026 Ghana Communication Technology University Asset & Procurement Hub. All rights reserved.
            </footer>
        </div>
    </body>
</html>
