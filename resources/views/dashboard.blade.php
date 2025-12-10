@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    
    <div class="md:flex md:items-center md:justify-between mb-6">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Dashboard
            </h2>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4">
            <a href="{{ route('sessions.create') }}" class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none">
                Log New Session
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-3 mb-8">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <dt class="text-sm font-medium text-gray-500 truncate">Average Pain Score</dt>
                <dd class="mt-1 text-3xl font-semibold text-gray-900">3.2 <span class="text-sm text-green-600 font-normal">(-0.8 this week)</span></dd>
            </div>
        </div>
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <dt class="text-sm font-medium text-gray-500 truncate">Sessions Completed</dt>
                <dd class="mt-1 text-3xl font-semibold text-gray-900">12</dd>
            </div>
        </div>
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <dt class="text-sm font-medium text-gray-500 truncate">Next Protocol</dt>
                <dd class="mt-1 text-lg font-semibold text-gray-900">ACL Rehab - Phase 2</dd>
            </div>
        </div>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Recent Session Logs</h3>
        </div>
        <ul role="list" class="divide-y divide-gray-200">
            <li>
                <a href="#" class="block hover:bg-gray-50">
                    <div class="px-4 py-4 sm:px-6">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-red-600 truncate">Morning Routine - Leg Day</p>
                            <div class="ml-2 flex-shrink-0 flex">
                                <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Completed</p>
                            </div>
                        </div>
                        <div class="mt-2 sm:flex sm:justify-between">
                            <div class="sm:flex">
                                <p class="flex items-center text-sm text-gray-500">
                                    Pain Score: 4/10
                                </p>
                            </div>
                            <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                <p>Logged 2 days ago</p>
                            </div>
                        </div>
                    </div>
                </a>
            </li>
            <li>
                <a href="#" class="block hover:bg-gray-50">
                    <div class="px-4 py-4 sm:px-6">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-red-600 truncate">Evening Stretch</p>
                            <div class="ml-2 flex-shrink-0 flex">
                                <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Completed</p>
                            </div>
                        </div>
                        <div class="mt-2 sm:flex sm:justify-between">
                            <div class="sm:flex">
                                <p class="flex items-center text-sm text-gray-500">
                                    Pain Score: 2/10
                                </p>
                            </div>
                            <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                <p>Logged 3 days ago</p>
                            </div>
                        </div>
                    </div>
                </a>
            </li>
        </ul>
    </div>
</div>
@endsection