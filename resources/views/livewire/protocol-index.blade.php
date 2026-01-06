<div>
    <!-- Success/Error Message Display -->
    @if (session('success'))
        <div class="bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 rounded relative mb-6" role="alert">
            <strong class="font-bold">Success!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif
    
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-4xl font-bold text-slate-900 mb-2">
                @if ($isTherapist)
                    My Created Protocols
                @else
                    My Assigned Protocols
                @endif
            </h1>
            <p class="text-slate-600">
                @if ($isTherapist)
                    Manage, edit, and assign the therapy plans you have created.
                @else
                    View your assigned exercises and track your progress.
                @endif
            </p>
        </div>
        
        <div class="flex gap-2">
            <!-- Therapist action button -->
            @if ($isTherapist)
                <a href="{{ route('protocols.create') }}" class="px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white font-semibold rounded-lg shadow-sm transition text-sm">
                    + Create New Protocol
                </a>
            @endif
        </div>
    </div>

    <!-- Protocol Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-8">

        @forelse ($protocols as $protocol)
            @php
                $therapist = $protocol->therapist;
                $initials = strtoupper(substr($therapist->name, 0, 1) . substr(strrchr($therapist->name, ' '), 1, 1));
                
                // --- PROGRESS LOGIC FIX ---
                $progress = 0; 
                if (!$isTherapist) {
                    // CRITICAL FIX: Get dynamic progress from the Model accessor
                    $progress = $protocol->current_progress; 
                    $status = $protocol->pivot ? 'Active' : 'Pending Start';
                } else {
                    $status = $protocol->patients_count > 0 ? 'Assigned ('.$protocol->patients_count.')' : 'Draft';
                }
                
                // Status Styling based on current $status value
                $statusColor = 'bg-slate-100 text-slate-800';
                if (str_contains($status, 'Active')) {
                    $statusColor = 'bg-emerald-100 text-emerald-800';
                } elseif (str_contains($status, 'Draft')) {
                    $statusColor = 'bg-slate-100 text-slate-800';
                } elseif (str_contains($status, 'Assigned')) {
                    $statusColor = 'bg-amber-100 text-amber-800';
                }


            @endphp
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-lg transition group">
                <div class="p-6 space-y-6">
                    <div class="flex justify-between items-start">
                        <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center text-emerald-600 group-hover:scale-110 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColor }}">
                            {{ $status }}
                        </span>
                    </div>

                    <div>
                        <h3 class="text-xl font-bold text-slate-900 mb-1">{{ $protocol->title }}</h3>
                        <p class="text-sm text-slate-500">{{ $protocol->description }}</p>
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-xs font-bold text-slate-600 border border-slate-200">
                            {{ $initials }}
                        </div>
                        <div>
                            <p class="text-xs text-slate-500">Assigned by</p>
                            <p class="text-sm font-medium text-slate-900">Dr. {{ $therapist->name }}</p>
                        </div>
                    </div>

                    @if (!$isTherapist)
                        <div class="space-y-2">
                            <div class="flex justify-between text-xs font-medium">
                                <span class="text-slate-600">Progress</span>
                                <span class="text-emerald-600">{{ $progress }}%</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2">
                                <div class="bg-gradient-to-r from-emerald-500 to-teal-500 h-2 rounded-full" style="width: {{ $progress }}%"></div>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-between items-center">
                    <span class="text-xs font-medium text-slate-500">
                        @if ($isTherapist)
                            Created: {{ $protocol->created_at->format('M d, Y') }}
                        @else
                            Assigned: {{ optional($protocol->pivot)->created_at ? $protocol->pivot->created_at->diffForHumans() : 'N/A' }}
                        @endif
                    </span>
                    <div class="flex items-center gap-4">
                        <!-- Therapist Edit Link -->
                        @if ($isTherapist)
                            <a href="{{ route('protocols.edit', $protocol) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">Edit</a>
                        @endif
                        
                        <!-- All users can view details -->
                        <a href="{{ route('protocols.show', $protocol) }}" class="text-sm font-semibold text-emerald-600 hover:text-emerald-700 hover:underline">
                            View Details →
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="md:col-span-3 text-center bg-white p-10 rounded-xl border border-slate-200">
                <p class="text-xl font-semibold text-slate-600">
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