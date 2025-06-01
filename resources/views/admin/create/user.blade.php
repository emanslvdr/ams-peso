<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl md:text-2xl leading-tight">
            Admin
        </h2>
    </x-slot>

    <div class="flex items-center justify-center py-10">
        <div class="max-w-md w-full p-6 bg-white rounded-2xl shadow-lg">
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-1">Create User</h2>
                <p class="text-gray-500 text-sm">Choose the type of user to create:</p>
            </div>
            <div class="grid grid-cols-1 gap-5">
                <!-- Client (HR) Card -->
                <a href="{{ route('clients.index') }}"
                   class="group flex items-center gap-4 p-4 border border-blue-100 rounded-xl shadow-sm hover:shadow-md hover:bg-blue-50 transition cursor-pointer">
                    <div class="bg-blue-100 p-3 rounded-full">
                        <!-- User group SVG -->
                        <svg class="w-7 h-7 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M17 20h5v-2a4 4 0 00-3-3.87" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M9 20H4v-2a4 4 0 013-3.87" stroke-linecap="round" stroke-linejoin="round"/>
                            <circle cx="9" cy="7" r="4" />
                            <circle cx="17" cy="7" r="4" />
                        </svg>
                    </div>
                    <div>
                        <div class="font-semibold text-base text-blue-900">Client (HR)</div>
                        <div class="text-xs text-gray-500 group-hover:text-blue-700">
                            Manage job postings, screen applicants, and oversee hiring.
                        </div>
                    </div>
                </a>
                <!-- User (Applicant) Card -->
                <a href="{{ route('users.index') }}"
                   class="group flex items-center gap-4 p-4 border border-green-100 rounded-xl shadow-sm hover:shadow-md hover:bg-green-50 transition cursor-pointer">
                    <div class="bg-green-100 p-3 rounded-full">
                        <!-- User SVG -->
                        <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="12" cy="7" r="4" />
                            <path d="M6 21v-2a4 4 0 014-4h0a4 4 0 014 4v2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div>
                        <div class="font-semibold text-base text-green-900">User (Applicant)</div>
                        <div class="text-xs text-gray-500 group-hover:text-green-700">
                            Apply for jobs and track application status.
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</x-admin-layout>
