<div class="space-y-8">
    <div class="flex justify-between items-start">
        <div>
            <h1 class="text-4xl font-bold text-slate-900 dark:text-white mb-2">Therapist Management Hub</h1>
            <p class="text-slate-600 dark:text-slate-400">Welcome back, Dr. {{ $user->name ?? 'Therapist' }}. Here is an overview of your practice.</p>
        </div>
        
        <!-- Updated Link: Points to the index page, where the 'Create' button belongs -->
        <a href="{{ route('protocols.index') }}" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-md transition">
            Manage Protocols
        </a>
    </div>

    <!-- Key Metrics for Therapists -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 hover:shadow-lg hover:border-indigo-200 dark:hover:border-indigo-900 transition">
            <p class="text-slate-600 dark:text-slate-400 text-sm font-medium mb-2">Total Patients Assigned</p>
            <span class="text-4xl font-bold text-slate-900 dark:text-white">{{ $assignedPatientsCount }}</span>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 hover:shadow-lg hover:border-teal-200 dark:hover:border-teal-900 transition">
            <p class="text-slate-600 dark:text-slate-400 text-sm font-medium mb-2">Protocols Created</p>
            <span class="text-4xl font-bold text-slate-900 dark:text-white">{{ $createdProtocols->count() }}</span>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 hover:shadow-lg hover:border-emerald-200 dark:hover:border-emerald-900 transition">
            <p class="text-slate-600 dark:text-slate-400 text-sm font-medium mb-2">Recent Patient Logs</p>
            <span class="text-4xl font-bold text-slate-900 dark:text-white">48</span>
        </div>
    </div>

    <!-- Protocols Management Overview -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden">
        <div class="px-8 py-6 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold text-slate-900 dark:text-white">Your Protocols</h2>
                <p class="text-slate-600 dark:text-slate-400 text-sm mt-1">Protocols you have designed and assigned to patients.</p>
            </div>
            
            <!-- NEW ACTION BUTTON: Direct link to create a new protocol -->
            <a href="{{ route('protocols.create') }}" class="px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white font-semibold rounded-lg shadow-sm transition text-sm">
                + Create New Protocol
            </a>
        </div>
        <div class="divide-y divide-slate-200 dark:divide-slate-700">
            @forelse ($createdProtocols as $protocol)
                <div class="px-8 py-4 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition flex justify-between items-center">
                    <div>
                        <h3 class="font-semibold text-slate-900 dark:text-white">{{ $protocol->title }}</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ $protocol->description }}</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-sm font-medium text-indigo-600 dark:text-indigo-400">{{ $protocol->patients_count }} Patients</span>
                        <a href="{{ route('protocols.edit', $protocol) }}" class="text-sm font-medium text-emerald-600 dark:text-emerald-400 hover:text-emerald-800 dark:hover:text-emerald-300">View/Edit</a>
                    </div>
                </div>
            @empty
                <div class="px-8 py-6 text-slate-500 dark:text-slate-400 text-center">
                    You have not created any protocols yet. Use the button above to get started!
                </div>
            @endforelse
        </div>
    </div>

    <!-- Patient Progress Monitoring -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden">
        <div class="px-8 py-6 border-b border-slate-200 dark:border-slate-700">
            <h2 class="text-xl font-bold text-slate-900 dark:text-white">Patient Progress Monitoring</h2>
            <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Overview of patients assigned to your protocols.</p>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-700/50 border-b border-slate-200 dark:border-slate-700">
                        <th class="px-8 py-4 text-sm font-semibold text-slate-600 dark:text-slate-300">Patient</th>
                        <th class="px-8 py-4 text-sm font-semibold text-slate-600 dark:text-slate-300">Assigned Protocols</th>
                        <th class="px-8 py-4 text-sm font-semibold text-slate-600 dark:text-slate-300">Last Session</th>
                        <th class="px-8 py-4 text-sm font-semibold text-slate-600 dark:text-slate-300 text-center">Avg Pain (Last 5)</th>
                        <th class="px-8 py-4 text-sm font-semibold text-slate-600 dark:text-slate-300 text-center">Sessions (7 Days)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($patients as $patient)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition">
                            <td class="px-8 py-4">
                                <div class="font-medium text-slate-900 dark:text-white">{{ $patient['name'] }}</div>
                                <div class="text-sm text-slate-500 dark:text-slate-400">{{ $patient['email'] }}</div>
                            </td>
                            <td class="px-8 py-4">
                                <div class="text-sm text-slate-600 dark:text-slate-300 max-w-xs truncate" title="{{ $patient['protocols'] }}">
                                    {{ $patient['protocols'] }}
                                </div>
                            </td>
                            <td class="px-8 py-4">
                                <div class="text-sm text-slate-600 dark:text-slate-300">
                                    {{ $patient['last_session'] }}
                                </div>
                            </td>
                            <td class="px-8 py-4 text-center">
                                @if(is_numeric($patient['avg_pain']))
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        {{ $patient['avg_pain'] > 5 ? 'bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300' : 'bg-emerald-100 dark:bg-emerald-900/50 text-emerald-800 dark:text-emerald-300' }}">
                                        {{ $patient['avg_pain'] }}
                                    </span>
                                @else
                                    <span class="text-slate-400 dark:text-slate-500 text-sm">N/A</span>
                                @endif
                            </td>
                            <td class="px-8 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 dark:bg-indigo-900/50 text-indigo-800 dark:text-indigo-300">
                                    {{ $patient['sessions_last_week'] }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-8 text-center text-slate-500 dark:text-slate-400">
                                No patients found assigned to your protocols.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>