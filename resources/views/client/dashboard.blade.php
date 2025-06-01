<x-client-layout>
    <x-slot name="header">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            @if(isset($organization) && $organization->logo)
                <img src="{{ asset('storage/'.$organization->logo) }}"
                    class="w-10 h-10 rounded-full object-cover border"
                    alt="{{ $organization->name }}">
            @else
                <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-lg text-gray-600 font-bold border">
                    {{ strtoupper(substr($organization->name ?? 'O', 0, 1)) }}
                </div>
            @endif
        </div>
        <div class="text-sm text-right">
            <div class="text-gray-400">Welcome back</div>
            <div class="font-semibold text-blue-700">
                {{ Auth::user()->name }}
                <span class="text-gray-400 font-normal">|</span>
            </div>
        </div>
    </div>
</x-slot>


    <div class="max-w-7xl mx-auto p-6 space-y-10">
        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
            <div class="bg-white shadow-sm rounded-2xl p-6 flex flex-col items-center hover:bg-blue-50 transition group">
                <span class="mb-3">
                    <svg class="w-12 h-12 text-blue-500 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10" stroke-width="2"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6"/>
                    </svg>
                </span>
                <span class="font-extrabold text-3xl">{{ $applicantsCount }}</span>
                <span class="text-sm text-gray-500 mt-1">Applicants</span>
            </div>
            <div class="bg-white shadow-sm rounded-2xl p-6 flex flex-col items-center hover:bg-purple-50 transition group">
                <span class="mb-3">
                    <svg class="w-12 h-12 text-purple-500 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <rect width="20" height="12" x="2" y="7" rx="2" stroke="currentColor" stroke-width="1.5" fill="none"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7V5a4 4 0 00-8 0v2"/>
                    </svg>
                </span>
                <span class="font-extrabold text-3xl">{{ $jobsCount }}</span>
                <span class="text-sm text-gray-500 mt-1">Jobs Posted</span>
            </div>
           
            <div class="bg-white shadow-sm rounded-2xl p-6 flex flex-col items-center justify-center text-center">
                <span class="mb-2 text-lg text-gray-600 font-semibold">Active Organization</span>
                <span class="text-2xl font-bold text-blue-700">{{ $organization->name ?? 'N/A' }}</span>
            </div>
             <a href="{{ route('client.jobs.create') }}" class="bg-gradient-to-r from-blue-500 to-purple-500 shadow-sm rounded-2xl p-6 flex flex-col items-center text-white font-bold hover:from-blue-600 hover:to-purple-600 transition group">
                <span class="mb-3">
                    <svg class="w-12 h-12 text-white group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M12 4v16m8-8H4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
                <span class="text-lg font-extrabold">Post New Job</span>
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            {{-- Recent Applicants --}}
            <div class="bg-white rounded-2xl shadow-sm p-6 col-span-1">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-800">Recent Applicants</h3>
                    <a href="{{ route('client.applicants.index') }}" class="text-blue-600 text-sm hover:underline">View All</a>
                </div>
                <ul>
                    @forelse($recentApplicants as $application)
                        @php
                            $user = $application->user;
                            $initials = $user ? collect(explode(' ', $user->name))->map(fn($part) => strtoupper(substr($part,0,1)))->join('') : '?';
                            $bgColors = ['bg-blue-500','bg-green-500','bg-indigo-500','bg-pink-500','bg-purple-500','bg-yellow-500','bg-red-500'];
                            $bgColor = $bgColors[ord($initials[0] ?? 'A') % count($bgColors)];
                        @endphp
                        <li class="flex items-center py-3 border-b last:border-0">
                            @if($user && $user->profile_photo)
                                <img src="{{ asset('storage/' . $user->profile_photo) }}" class="w-10 h-10 rounded-full border object-cover mr-3" alt="{{ $user->name }}">
                            @else
                                <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $bgColor }} text-white font-bold mr-3 text-base">
                                    {{ $initials }}
                                </div>
                            @endif
                            <div class="flex flex-col">
                                <span class="font-medium text-gray-800">{{ $user->name ?? $application->first_name }}</span>
                                <span class="text-xs text-gray-400">{{ $application->email }}</span>
                            </div>
                            <span class="ml-auto text-gray-400 text-xs whitespace-nowrap">{{ $application->created_at ? $application->created_at->diffForHumans() : 'No date' }}</span>
                        </li>
                    @empty
                        <li class="py-6 text-gray-400 text-center">No recent applicants.</li>
                    @endforelse
                </ul>
            </div>

            {{-- Recent Jobs --}}
          <div class="bg-white rounded-2xl shadow-sm p-6 col-span-1">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-bold text-gray-800">Recent Jobs</h3>
        <a href="{{ route('client.jobs.index') }}" class="text-blue-600 text-sm hover:underline">View All</a>
    </div>
    <table class="min-w-full text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-2 text-left font-semibold">Job Title</th>
                <th class="px-4 py-2 text-center font-semibold">Candidates</th>
                <th class="px-4 py-2 text-left font-semibold">Status</th>
                <th class="px-4 py-2 text-left font-semibold">Posted</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recentJobs as $job)
                <tr class="border-b last:border-0 hover:bg-blue-50 transition group">
                    <!-- Pipeline link -->
                    <td class="px-4 py-2 font-medium flex items-center gap-2">
                        <a href="{{ route('client.jobs.pipeline', $job) }}"
                           class="text-blue-700 hover:underline flex items-center gap-1 group-hover:text-blue-900 transition">
                            <svg class="w-4 h-4 text-blue-400 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M9 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <span>{{ $job->title }}</span>
                        </a>
                    </td>
                    <!-- Show total candidates for this job -->
                    <td class="px-4 py-2 text-center">
                        <span class="inline-block rounded-full px-2 py-0.5 text-xs bg-blue-100 text-blue-800 font-semibold shadow-sm">
                            {{ $job->applications_count }}
                        </span>
                    </td>
                    <td class="px-4 py-2 capitalize">
                        <span class="inline-block px-2 py-0.5 rounded-full text-xs font-semibold
                            {{ $job->status === 'open' ? 'bg-green-100 text-green-700' : ($job->status === 'closed' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-600') }}">
                            {{ ucfirst($job->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-2 text-gray-600">
                        {{ $job->created_at ? $job->created_at->diffForHumans() : '-' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-4 py-6 text-center text-gray-400">No recent jobs posted.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>


        </div>
    </div>
</x-client-layout>
