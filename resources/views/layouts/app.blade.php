<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HomeHeal - Rehab Tracker</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Dark Mode Initialization (prevent flash) -->
    <script>
        // Initialize dark mode
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }
        
        // Toggle function - make it globally accessible
        window.toggleDarkMode = function() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.theme = 'light';
            } else {
                document.documentElement.classList.add('dark');
                localStorage.theme = 'dark';
            }
        }
    </script>
</head>
<body class="bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-slate-100 transition-colors">

    <!-- Navigation with authentication state handling and mobile menu -->
    <nav class="bg-white dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700 shadow-sm sticky top-0 z-50 transition-colors" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-8">
                    <a href="/" class="flex items-center gap-2">
                        <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-lg">H</span>
                        </div>
                        <span class="text-xl font-bold bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent">HomeHeal</span>
                    </a>
                    
                    @auth
                        <!-- Desktop Menu -->
                        <div class="hidden md:flex gap-1">
                            <a href="{{ route('dashboard') }}" class="px-4 py-2 text-sm font-medium text-slate-600 dark:text-slate-300 hover:text-emerald-600 dark:hover:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-slate-700/50 rounded-lg transition">
                                Dashboard
                            </a>
                            @if(Auth::user()->role === 'therapist')
                                <a href="{{ route('protocols.index') }}" class="px-4 py-2 text-sm font-medium text-slate-600 dark:text-slate-300 hover:text-emerald-600 dark:hover:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-slate-700/50 rounded-lg transition">
                                    Protocols
                                </a>
                                <a href="{{ route('exercises.index') }}" class="px-4 py-2 text-sm font-medium text-slate-600 dark:text-slate-300 hover:text-emerald-600 dark:hover:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-slate-700/50 rounded-lg transition">
                                    Exercises
                                </a>
                            @elseif(Auth::user()->role === 'patient')
                                <a href="{{ route('sessions.create') }}" class="px-4 py-2 text-sm font-medium text-slate-600 dark:text-slate-300 hover:text-emerald-600 dark:hover:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-slate-700/50 rounded-lg transition">
                                    Log Session
                                </a>
                            @endif
                        </div>
                    @endauth
                </div>

                <div class="flex items-center gap-3">
                    <!-- Dark Mode Toggle -->
                    <div x-data="{ 
                        isDark: localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
                        toggle() {
                            this.isDark = !this.isDark;
                            if (this.isDark) {
                                document.documentElement.classList.add('dark');
                                localStorage.theme = 'dark';
                            } else {
                                document.documentElement.classList.remove('dark');
                                localStorage.theme = 'light';
                            }
                        }
                    }">
                        <button 
                            @click="toggle()"
                            class="p-2 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition"
                            title="Toggle dark mode"
                        >
                            <!-- Sun icon (shows when dark, click to switch to light) -->
                            <svg x-show="isDark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            <!-- Moon icon (shows when light, click to switch to dark) -->
                            <svg x-show="!isDark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                            </svg>
                        </button>
                    </div>

                    @guest
                        <a href="{{ route('login') }}" class="hidden sm:block px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 rounded-lg transition shadow-sm">Log in</a>
                        <a href="{{ route('register') }}" class="px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 rounded-lg transition shadow-sm">Sign up</a>
                    @else
                        <!-- Mobile menu button -->
                        <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>

                        <!-- Desktop User Menu -->
                        <div class="hidden md:block relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center gap-2 px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100 rounded-lg transition">
                                <div class="w-8 h-8 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-full flex items-center justify-center">
                                    <span class="text-white font-semibold text-xs">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                </div>
                                <div class="hidden sm:block text-left">
                                    <div class="text-sm font-semibold text-slate-900">{{ Auth::user()->name }}</div>
                                    <div class="text-xs text-slate-500 capitalize">{{ Auth::user()->role }}</div>
                                </div>
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            
                            <!-- Desktop Dropdown Menu -->
                            <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-slate-200 py-1">
                                <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">Dashboard</a>
                                <div class="border-t border-slate-200 my-1"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endguest
                </div>
            </div>

            <!-- Mobile Menu -->
            @auth
                <div x-show="mobileMenuOpen" x-transition class="md:hidden border-t border-slate-200 py-4">
                    <div class="space-y-1">
                        <!-- User info -->
                        <div class="px-4 py-3 border-b border-slate-200 mb-2">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-full flex items-center justify-center">
                                    <span class="text-white font-semibold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                </div>
                                <div>
                                    <div class="font-semibold text-slate-900">{{ Auth::user()->name }}</div>
                                    <div class="text-sm text-slate-500 capitalize">{{ Auth::user()->role }}</div>
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-slate-700 hover:bg-emerald-50 hover:text-emerald-600 rounded-lg mx-2">Dashboard</a>
                        
                        @if(Auth::user()->role === 'therapist')
                            <a href="{{ route('protocols.index') }}" class="block px-4 py-2 text-slate-700 hover:bg-emerald-50 hover:text-emerald-600 rounded-lg mx-2">Protocols</a>
                            <a href="{{ route('exercises.index') }}" class="block px-4 py-2 text-slate-700 hover:bg-emerald-50 hover:text-emerald-600 rounded-lg mx-2">Exercises</a>
                        @elseif(Auth::user()->role === 'patient')
                            <a href="{{ route('sessions.create') }}" class="block px-4 py-2 text-slate-700 hover:bg-emerald-50 hover:text-emerald-600 rounded-lg mx-2">Log Session</a>
                        @endif

                        <div class="border-t border-slate-200 my-2"></div>
                        
                        <form method="POST" action="{{ route('logout') }}" class="mx-2">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-red-600 hover:bg-red-50 rounded-lg">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            @endauth
        </div>
    </nav>

    <main class="max-w-7xl mx-auto py-8 sm:px-6 lg:px-8 px-4">
        <!-- Flash Messages -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-lg text-emerald-700 flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg text-red-700 flex items-center justify-between">
                <span>{{ session('error') }}</span>
                <button onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        @endif

        @yield('content')
    </main>
    
    @livewireScripts
</body>
</html>
