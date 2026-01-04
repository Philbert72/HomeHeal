@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <!-- Header with Create Button -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-4xl font-bold text-slate-900 dark:text-white mb-2">Exercise Library</h1>
            <p class="text-slate-600 dark:text-slate-400">Manage your rehabilitation exercises</p>
        </div>
        <a href="{{ route('exercises.create') }}" class="px-6 py-3 bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-semibold rounded-lg hover:from-emerald-600 hover:to-teal-700 transition shadow-sm">
            + New Exercise
        </a>
    </div>

    <!-- Search Bar -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6">
        <form method="GET" action="{{ route('exercises.index') }}" class="flex gap-3">
            <input 
                type="text" 
                name="search" 
                value="{{ request('search') }}"
                placeholder="Search exercises by name or instructions..." 
                class="flex-1 px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition dark:bg-slate-700 dark:text-white"
            >
            <button type="submit" class="px-6 py-2.5 bg-slate-900 dark:bg-slate-700 text-white font-semibold rounded-lg hover:bg-slate-800 dark:hover:bg-slate-600 transition">
                Search
            </button>
            @if(request('search'))
                <a href="{{ route('exercises.index') }}" class="px-6 py-2.5 border-2 border-slate-300 dark:border-slate-600 text-slate-900 dark:text-white font-semibold rounded-lg hover:border-slate-400 dark:hover:border-slate-500 transition">
                    Clear
                </a>
            @endif
        </form>
    </div>

    <!-- Exercises List -->
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($exercises as $exercise)
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 hover:shadow-lg hover:border-emerald-200 dark:hover:border-emerald-900 transition overflow-hidden">
                <div class="p-6 space-y-4">
                    <div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">{{ $exercise->name }}</h3>
                        <p class="text-slate-600 dark:text-slate-400 text-sm line-clamp-3">{{ Str::limit($exercise->instructions, 120) }}</p>
                    </div>

                    <div class="flex gap-2 pt-4 border-t border-slate-200 dark:border-slate-700">
                        <a href="{{ route('exercises.show', $exercise) }}" class="flex-1 px-4 py-2 bg-slate-100 dark:bg-slate-700 text-slate-900 dark:text-white font-medium rounded-lg hover:bg-slate-200 dark:hover:bg-slate-600 transition text-center text-sm">
                            View
                        </a>
                        <a href="{{ route('exercises.edit', $exercise) }}" class="flex-1 px-4 py-2 bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300 font-medium rounded-lg hover:bg-emerald-200 dark:hover:bg-emerald-800 transition text-center text-sm">
                            Edit
                        </a>
                        <form method="POST" action="{{ route('exercises.destroy', $exercise) }}" onsubmit="return confirm('Are you sure you want to delete this exercise?');" class="flex-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full px-4 py-2 bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-300 font-medium rounded-lg hover:bg-red-200 dark:hover:bg-red-800 transition text-sm">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-12 text-center">
                <svg class="w-16 h-16 text-slate-300 dark:text-slate-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">No exercises found</h3>
                <p class="text-slate-600 dark:text-slate-400 mb-4">
                    @if(request('search'))
                        No exercises match your search. Try different keywords.
                    @else
                        Get started by adding your first exercise.
                    @endif
                </p>
                @if(!request('search'))
                    <a href="{{ route('exercises.create') }}" class="inline-block px-6 py-3 bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-semibold rounded-lg hover:from-emerald-600 hover:to-teal-700 transition shadow-sm">
                        Create First Exercise
                    </a>
                @endif
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($exercises->hasPages())
        <div class="flex justify-center">
            {{ $exercises->links() }}
        </div>
    @endif
</div>
@endsection
