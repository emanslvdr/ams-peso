<x-admin-layout>
   <x-slot name="header">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
           {{-- Admin Profile Photo --}}
            @if(Auth::user()->profile_photo)
                <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}"
                     class="w-10 h-10 rounded-full object-cover border shadow"
                     alt="{{ Auth::user()->name }}">
            @else
                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-lg text-blue-700 font-bold border shadow">
                    {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                </div>
            @endif
            <span class="text-lg font-semibold text-gray-800">
                Admin Dashboard
            </span>
        </div>
        <div class="flex items-center gap-3">
          
            <div class="text-sm text-right">
                <div class="text-gray-400">Welcome back</div>
                <div class="font-semibold text-blue-700">
                    {{ Auth::user()->name }}
                    <span class="text-gray-400 font-normal">|</span>
                </div>
            </div>
        </div>
    </div>
</x-slot>



    <div class="max-w-7xl mx-auto p-6 space-y-10">

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <a href="{{ route('users.index') }}" class="bg-white shadow-sm rounded-2xl p-6 flex flex-col items-center hover:bg-blue-50 transition group">
                <span class="mb-3">
                    <svg class="w-12 h-12 text-blue-500 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2h5m6 0v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2h5"/>
                        <circle cx="12" cy="7" r="4" stroke="currentColor" stroke-width="1.5" fill="none"/>
                    </svg>
                </span>
                <span class="font-extrabold text-3xl">{{ $applicantsCount }}</span>
                <span class="text-sm text-gray-500 mt-1">Applicants</span>
            </a>
            <a href="{{ route('clients.index') }}" class="bg-white shadow-sm rounded-2xl p-6 flex flex-col items-center hover:bg-green-50 transition group">
                <span class="mb-3">
                    <svg class="w-12 h-12 text-green-500 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM6 21v-2a4 4 0 014-4h0a4 4 0 014 4v2"/>
                    </svg>
                </span>
                <span class="font-extrabold text-3xl">{{ $clientsCount }}</span>
                <span class="text-sm text-gray-500 mt-1">Clients</span>
            </a>
            <a href="{{ route('jobs.index') }}" class="bg-white shadow-sm rounded-2xl p-6 flex flex-col items-center hover:bg-purple-50 transition group">
                <span class="mb-3">
                    <svg class="w-12 h-12 text-purple-500 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <rect width="20" height="12" x="2" y="7" rx="2" stroke="currentColor" stroke-width="1.5" fill="none"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7V5a4 4 0 00-8 0v2"/>
                    </svg>
                </span>
                <span class="font-extrabold text-3xl">{{ $jobsCount }}</span>
                <span class="text-sm text-gray-500 mt-1">Jobs</span>
            </a>
            <a href="{{ route('organizations.index') }}" class="bg-white shadow-sm rounded-2xl p-6 flex flex-col items-center hover:bg-yellow-50 transition group">
                <span class="mb-3">
                    <svg class="w-12 h-12 text-yellow-500 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <rect x="3" y="7" width="18" height="13" rx="2" stroke="currentColor" stroke-width="1.5" fill="none"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 21V9h6v12"/>
                    </svg>
                </span>
                <span class="font-extrabold text-3xl">{{ $organizationsCount }}</span>
                <span class="text-sm text-gray-500 mt-1">Organizations</span>
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Recent Applicants --}}
            <div class="bg-white rounded-2xl shadow-sm p-6 col-span-1">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-800">Recent Applicants</h3>
                    <a href="{{ route('users.index') }}" class="text-blue-600 text-sm hover:underline">View All</a>
                </div>
                <ul>
                    @forelse($recentApplicants as $user)
                        <li class="flex items-center py-3 border-b last:border-0">
                            @php
                                $initials = collect(explode(' ', $user->name))->map(fn($part) => strtoupper(substr($part,0,1)))->join('');
                                $bgColors = ['bg-blue-500','bg-green-500','bg-indigo-500','bg-pink-500','bg-purple-500','bg-yellow-500','bg-red-500'];
                                $bgColor = $bgColors[ord($initials[0] ?? 'A') % count($bgColors)];
                            @endphp
                            @if($user->profile_photo)
                                <img src="{{ asset('storage/' . $user->profile_photo) }}" class="w-10 h-10 rounded-full border object-cover mr-3" alt="{{ $user->name }}">
                            @else
                                <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $bgColor }} text-white font-bold mr-3 text-base">
                                    {{ $initials }}
                                </div>
                            @endif
                            <div class="flex flex-col">
                                <span class="font-medium text-gray-800">{{ $user->name }}</span>
                                <span class="text-xs text-gray-400">{{ $user->email }}</span>
                            </div>
                            <span class="ml-auto text-gray-400 text-xs whitespace-nowrap">{{ $user->created_at ? $user->created_at->diffForHumans() : 'No date' }}</span>
                        </li>
                    @empty
                        <li class="py-6 text-gray-400 text-center">No recent applicants.</li>
                    @endforelse
                </ul>
            </div>

            {{-- Organizations with Job Counts --}}
            <div class="bg-white rounded-2xl shadow-sm p-6 col-span-2">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-800">Organizations & Job Listings</h3>
                    <a href="{{ route('organizations.index') }}" class="text-blue-600 text-sm hover:underline">View All</a>
                </div>
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left font-semibold">Organization</th>
                            <th class="px-4 py-2 text-left font-semibold">Jobs</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topOrganizations as $org)
                            <tr class="border-b last:border-0 hover:bg-gray-50">
                                <td class="px-4 py-2">
                                    <a href="{{ route('organizations.jobs', $org) }}" class="flex items-center gap-2 hover:underline">
                                        @if($org->logo)
                                            <img src="{{ asset('storage/' . $org->logo) }}" class="w-10 h-10 rounded-full border object-cover" alt="{{ $org->name }}">
                                        @else
                                            <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center text-gray-500 font-bold text-base">{{ strtoupper(substr($org->name,0,1)) }}</div>
                                        @endif
                                        <span class="font-medium text-gray-800">{{ $org->name }}</span>
                                    </a>
                                </td>
                                <td class="px-4 py-2 font-semibold text-gray-700">{{ $org->jobs_count }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="px-4 py-6 text-center text-gray-400">No organizations yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>


    </div>
</x-admin-layout>
