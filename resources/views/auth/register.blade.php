@extends('layouts.app')

@section('content')
<div class="min-h-[calc(100vh-200px)] flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <!-- Improved registration form with clearer hierarchy -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-slate-900 mb-2">Get Started</h1>
            <p class="text-slate-600">Join HomeHeal today and start tracking your recovery</p>
        </div>
    </div>

    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white rounded-2xl p-8 shadow-sm border border-slate-200">
            <form class="space-y-5" action="{{ route('register.store') }}" method="POST">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-semibold text-slate-900 mb-2">Full Name</label>
                    <input id="name" name="name" type="text" required class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition placeholder-slate-400">
                </div>

                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-900 mb-2">Email address</label>
                    <input id="email" name="email" type="email" required class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition placeholder-slate-400">
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-900 mb-2">Password</label>
                    <input id="password" name="password" type="password" required class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition placeholder-slate-400">
                </div>

                <div>
                    <label for="role" class="block text-sm font-semibold text-slate-900 mb-2">I am a...</label>
                    <select id="role" name="role" class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition bg-white cursor-pointer">
                        <option value="patient">Patient (Recovering from injury)</option>
                        <option value="therapist">Therapist (Provider)</option>
                    </select>
                </div>

                <button type="submit" class="w-full py-2.5 bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-semibold rounded-lg hover:from-emerald-600 hover:to-teal-700 transition shadow-sm mt-6">
                    Create account
                </button>

                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-slate-200"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-slate-500">Already have an account?</span>
                    </div>
                </div>

                <a href="{{ route('login') }}" class="w-full py-2.5 border-2 border-slate-300 text-slate-900 font-semibold rounded-lg hover:border-emerald-500 hover:bg-emerald-50 transition text-center block">
                    Sign in
                </a>
            </form>
        </div>
    </div>
</div>
@endsection
