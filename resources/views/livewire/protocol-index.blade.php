<div>
    <!-- Message Display -->
    @if (session('success'))
        <div class="bg-emerald-100 dark:bg-emerald-900/50 border border-emerald-400 dark:border-emerald-600 text-emerald-700 dark:text-emerald-300 px-4 py-3 rounded relative mb-6" role="alert">
            <strong class="font-bold">Success!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif
    
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-4xl font-bold text-slate-900 dark:text-white mb-2">
                @if ($isTherapist)
                    My Created Protocols
                @else
                    My Assigned Protocols
                @endif
            </h1>
            <p class="text-slate-600 dark:text-slate-400">
                @if ($isTherapist)
                    Manage, edit, and assign the therapy plans you have created.
                @else
                    View your assigned exercises and track your progress.
                @endif
            </p>
        </div>
        
        <div class="flex gap-2">
            @if ($isTherapist)
                <a href="{{ route('protocols.create') }}" class="px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white font-semibold rounded-lg shadow-sm transition text-sm">
                    + Create New Protocol
                </a>
            @endif
        </div>
    </div>

    <!-- Search Filter for Therapist -->
    @if ($isTherapist)
        <div class="mt-6 bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-4">
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1">
                    <input 
                        type="text" 
                        wire:model.live="search"
                        placeholder="Search protocols by title or description..." 
                        class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition dark:bg-slate-700 dark:text-white"
                    >
                </div>
                <div>
                    <select 
                        wire:model.live="sortBy"
                        class="px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition bg-white dark:bg-slate-700 dark:text-white cursor-pointer font-medium"
                    >
                        <option value="latest">Newest First</option>
                        <option value="oldest">Oldest First</option>
                        <option value="name">Alphabetical</option>
                    </select>
                </div>
            </div>
        </div>
    @endif

    <!-- Protocol Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-8">

        @forelse ($protocols as $protocol)
            @php
                $status = $protocol->pivot ? 'Active' : 'Created';
                $statusColor = $protocol->pivot ? 'bg-emerald-100 dark:bg-emerald-900/50 text-emerald-800 dark:text-emerald-300' : 'bg-indigo-100 dark:bg-indigo-900/50 text-indigo-800 dark:text-indigo-300';
                $iconColor = $protocol->pivot ? 'text-emerald-600 dark:text-emerald-400' : 'text-indigo-600 dark:text-indigo-400';
                $buttonColor = $protocol->pivot ? 'text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 dark:hover:text-emerald-300' : 'text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300';
                $therapist = $protocol->therapist;
                $initials = strtoupper(substr($therapist->name, 0, 1) . substr(strrchr($therapist->name, ' '), 1, 1));
                $progress = 0; 
                if (!$isTherapist) {
                    $progress = $protocol->pivot ? 65 : 0; 
                    $status = $protocol->pivot ? 'Active' : 'Pending Start';
                } else {
                    $status = $protocol->patients_count > 0 ? 'Assigned ('.$protocol->patients_count.')' : 'Draft';
                    $statusColor = $protocol->patients_count > 0 ? 'bg-amber-100 dark:bg-amber-900/50 text-amber-800 dark:text-amber-300' : 'bg-slate-100 dark:bg-slate-700 text-slate-800 dark:text-slate-300';
                }

            @endphp
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden hover:shadow-lg transition group">
                <div class="p-6 space-y-6">
                    <div class="flex justify-between items-start">
                        <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-900/50 rounded-xl flex items-center justify-center {{ $iconColor }} group-hover:scale-110 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColor }}">
                            {{ $status }}
                        </span>
                    </div>

                    <div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-1">{{ $protocol->title }}</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400">{{ $protocol->description }}</p>
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <div class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-xs font-bold text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-600">
                            {{ $initials }}
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Assigned by</p>
                            <p class="text-sm font-medium text-slate-900 dark:text-white">Dr. {{ $therapist->name }}</p>
                        </div>
                    </div>

                    @if (!$isTherapist)
                        <div class="space-y-2">
                            <div class="flex justify-between text-xs font-medium">
                                <span class="text-slate-600 dark:text-slate-400">Progress</span>
                                <span class="text-emerald-600 dark:text-emerald-400">{{ $progress }}%</span>
                            </div>
                            <div class="w-full bg-slate-100 dark:bg-slate-700 rounded-full h-2">
                                <div class="bg-gradient-to-r from-emerald-500 to-teal-500 h-2 rounded-full" style="width: {{ $progress }}%"></div>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="px-6 py-4 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-200 dark:border-slate-700 flex justify-between items-center">
                    <span class="text-xs font-medium text-slate-500 dark:text-slate-400">
                        @if ($isTherapist)
                            Created: {{ $protocol->created_at->format('M d, Y') }}
                        @else
                            Assigned: {{ optional($protocol->pivot)->created_at ? $protocol->pivot->created_at->diffForHumans() : 'N/A' }}
                        @endif
                    </span>
                    <div class="flex items-center gap-4">
                        @if ($isTherapist)
                            <a href="{{ route('protocols.edit', $protocol) }}" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300">Edit</a>
                        @endif
                        <a href="{{ route('protocols.show', $protocol) }}" class="text-sm font-semibold {{ $buttonColor }} hover:underline">
                            View Details →
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="md:col-span-3 text-center bg-white dark:bg-slate-800 p-10 rounded-xl border border-slate-200 dark:border-slate-700">
                <p class="text-xl font-semibold text-slate-600 dark:text-slate-400">
                    @if ($isTherapist)
                        You have not created any protocols. Use the "Create New Protocol" button to get started.
                    @else
                        No protocols have been assigned to you yet. Please contact your therapist.
                    @endif
                </p>
            </div>
        @endforelse
    </div>
</div>