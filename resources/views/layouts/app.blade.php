<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HomeHeal - Rehab Tracker</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-900">

    <!-- Improved navigation with better styling and visual hierarchy -->
    <nav class="bg-white border-b border-slate-200 shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-8">
                    <a href="/" class="flex items-center gap-2">
                        <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-lg">H</span>
                        </div>
                        <span class="text-xl font-bold bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent">HomeHeal</span>
                    </a>
                    <div class="hidden md:flex gap-1">
                        <a href="{{ route('dashboard') }}" class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition">
                            Dashboard
                        </a>
                        <a href="{{ route('protocols.index') }}" class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition">
                            My Protocols
                        </a>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('login') }}" class="hidden sm:block px-4 py-2 text-sm font-medium text-slate-600 hover:text-slate-900 transition">Log in</a>
                    <a href="{{ route('register') }}" class="px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 rounded-lg transition shadow-sm">Sign up</a>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto py-8 sm:px-6 lg:px-8 px-4">
        @yield('content')
    </main>

</body>
</html>
