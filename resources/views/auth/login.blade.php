@extends('layouts.app')

@section('content')
<div class="min-h-[calc(100vh-200px)] flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <!-- Improved login form with better typography and spacing -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-slate-900 mb-2">Welcome Back</h1>
            <p class="text-slate-600">Sign in to continue your recovery journey</p>
        </div>
    </div>

    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white rounded-2xl p-8 shadow-sm border border-slate-200">
            <!-- CRITICAL FIX: Update action to the login.store route -->
            <form class="space-y-5" action="{{ route('login.store') }}" method="POST">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-900 mb-2">Email address</label>
                    <input id="email" name="email" type="email" required class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition placeholder-slate-400">
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-900 mb-2">Password</label>
                    <input id="password" name="password" type="password" required class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition placeholder-slate-400">
                </div>

                <div class="flex items-center justify-between pt-2">
                    <div class="flex items-center gap-2">
                        <!-- CRITICAL FIX: name should be 'remember' for Laravel Auth -->
                        <input id="remember-me" name="remember" type="checkbox" class="w-4 h-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 cursor-pointer">
                        <label for="remember-me" class="text-sm text-slate-600">Remember me</label>
                    </div>
                    <a href="#" class="text-sm font-medium text-emerald-600 hover:text-emerald-700 transition">Forgot password?</a>
                </div>

                <button type="submit" class="w-full py-2.5 bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-semibold rounded-lg hover:from-emerald-600 hover:to-teal-700 transition shadow-sm mt-6">
                    Sign in
                </button>

                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-slate-200"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-slate-500">New to HomeHeal?</span>
                    </div>
                </div>

                <a href="{{ route('register') }}" class="w-full py-2.5 border-2 border-slate-300 text-slate-900 font-semibold rounded-lg hover:border-emerald-500 hover:bg-emerald-50 transition text-center block">
                    Create account
                </a>
            </form>
        </div>
    </div>
</div>
@endsection