<div class="space-y-8">
    <div class="flex justify-between items-start">
        <div>
            <h1 class="text-4xl font-bold text-slate-900 mb-2">Therapist Management Hub</h1>
            <p class="text-slate-600">Welcome back, Dr. {{ $user->name ?? 'Therapist' }}. Here is an overview of your practice.</p>
        </div>
        <a href="{{ route('protocols.index') }}" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-md transition">
            Manage Protocols
        </a>
    </div>

    <!-- Key Metrics for Therapists -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-2xl p-6 border border-slate-200 hover:shadow-lg hover:border-indigo-200 transition">
            <p class="text-slate-600 text-sm font-medium mb-2">Total Patients Assigned</p>
            <span class="text-4xl font-bold text-slate-900">{{ $assignedPatientsCount }}</span>
        </div>
        <div class="bg-white rounded-2xl p-6 border border-slate-200 hover:shadow-lg hover:border-teal-200 transition">
            <p class="text-slate-600 text-sm font-medium mb-2">Protocols Created</p>
            <span class="text-4xl font-bold text-slate-900">{{ $createdProtocols->count() }}</span>
        </div>
        <div class="bg-white rounded-2xl p-6 border border-slate-200 hover:shadow-lg hover:border-emerald-200 transition">
            <p class="text-slate-600 text-sm font-medium mb-2">Recent Patient Logs</p>
            <span class="text-4xl font-bold text-slate-900">48</span>
        </div>
    </div>

    <!-- Protocols Management Overview -->
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
        <div class="px-8 py-6 border-b border-slate-200">
            <h2 class="text-xl font-bold text-slate-900">Your Protocols</h2>
            <p class="text-slate-600 text-sm mt-1">Protocols you have designed and assigned to patients.</p>
        </div>
        <div class="divide-y divide-slate-200">
            @forelse ($createdProtocols as $protocol)
                <div class="px-8 py-4 hover:bg-slate-50 transition flex justify-between items-center">
                    <div>
                        <h3 class="font-semibold text-slate-900">{{ $protocol->title }}</h3>
                        <p class="text-sm text-slate-500 mt-1">{{ $protocol->description }}</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-sm font-medium text-indigo-600">{{ $protocol->patients_count }} Patients</span>
                        <a href="#" class="text-sm font-medium text-emerald-600 hover:text-emerald-800">View/Edit</a>
                    </div>
                </div>
            @empty
                <div class="px-8 py-6 text-slate-500 text-center">
                    You have not created any protocols yet. Start managing your patients!
                </div>
            @endforelse
        </div>
    </div>

    <!-- Placeholder for Patient List and Red Flag Monitoring -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200">
        <h2 class="text-xl font-bold text-slate-900 mb-2">Patient Progress Monitoring</h2>
        <p class="text-slate-500">List of patients assigned to your protocols (to be implemented).</p>
    </div>
</div>