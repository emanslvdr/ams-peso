<x-app-layout>
    <div class="max-w-4xl mx-auto px-3 py-8">
        {{-- General Application --}}
        <div class="mb-10">
            <h2 class="text-lg font-bold mb-3 text-gray-800 flex items-center gap-2">
                <span>General Application</span>
                @if($generalApplication)
                    <a href="{{ route('user.application.edit', $generalApplication) }}"
                        class="inline-flex items-center text-xs px-3 py-1 bg-blue-50 border border-blue-200 rounded-md hover:bg-blue-100 ml-2 text-blue-700 font-semibold transition">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536M4 13.414V19h5.586l10.707-10.707a2 2 0 0 0-2.828-2.828L4 13.414z" />
                        </svg>
                        Edit
                    </a>
                @endif
            </h2>
            <div class="bg-white shadow-md rounded-xl p-5 mb-3 flex items-center gap-6">
                <div class="flex-1">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center justify-center h-12 w-12 rounded-full bg-blue-200 text-2xl text-blue-700 font-bold">
                            {{ strtoupper(substr($generalApplication->first_name ?? 'U', 0, 1)) }}
                        </span>
                        <div>
                            <div class="font-bold text-lg text-blue-900">
                                {{ $generalApplication->first_name ?? '-' }} {{ $generalApplication->last_name ?? '' }}
                            </div>
                            <div class="text-gray-500 text-xs">{{ $generalApplication->email ?? '-' }}</div>
                            <div class="text-gray-400 text-xs">{{ $generalApplication->phone_number ?? '-' }}</div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="text-gray-700 text-xs font-medium">Skills:</span>
                        @if($generalApplication && $generalApplication->skills)
                            @foreach(explode(',', $generalApplication->skills) as $skill)
                                <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-0.5 rounded-full mr-1 mb-1">
                                    {{ trim($skill) }}
                                </span>
                            @endforeach
                        @else
                            <span class="text-gray-400 text-xs">None</span>
                        @endif
                    </div>
                </div>
                @if($generalApplication && $generalApplication->resume)
                    <a href="{{ Storage::url($generalApplication->resume) }}" target="_blank"
                        class="flex-shrink-0 px-4 py-2 rounded bg-gradient-to-r from-blue-500 to-blue-700 text-white text-xs font-bold shadow hover:from-blue-600 hover:to-blue-800 transition">
                        View Resume
                    </a>
                @endif
            </div>
            @unless($generalApplication)
                <div class="text-gray-400 text-sm italic">
                    No general application yet.
                    <a href="{{ route('user.application.create') }}" class="text-blue-600 underline font-semibold">Create one</a>.
                </div>
            @endunless
        </div>

        {{-- Job Applications Table --}}
        <div>
            <div class="flex justify-between items-center mb-3">
                <h2 class="text-lg font-bold text-gray-800">Job Applications</h2>
                <a href="{{ route('user.application.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg shadow hover:bg-blue-700 focus:ring-2 focus:ring-blue-300 transition">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    New Application
                </a>
            </div>
            @if($jobApplications->count())
                <div class="bg-white shadow-xl rounded-2xl overflow-x-auto">
                    <table class="w-full min-w-[800px] text-sm">
                        <thead class="sticky top-0 bg-blue-50/90 z-10">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold">Job Listing</th>
                                <th class="px-4 py-3 text-left font-semibold">Name</th>
                                <th class="px-4 py-3 text-left font-semibold">Skills</th>
                                <th class="px-4 py-3 text-left font-semibold">Status</th>
                                <th class="px-4 py-3 text-left font-semibold">Resume</th>
                                <th class="px-4 py-3 text-left font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($jobApplications as $application)
                                <tr class="border-t hover:bg-blue-50/80 transition">
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        @if($application->job)
                                            <div class="flex items-center gap-3">
                                                @if(optional($application->job->organization)->logo)
                                                    <img src="{{ asset('storage/' . $application->job->organization->logo) }}"
                                                        class="w-8 h-8 rounded-full border object-cover bg-gray-100"
                                                        alt="{{ $application->job->organization->name }} logo">
                                                @else
                                                    <span class="w-8 h-8 rounded-full bg-blue-200 text-blue-700 flex items-center justify-center font-bold">
                                                        {{ strtoupper(substr(optional($application->job->organization)->name ?? 'U', 0, 1)) }}
                                                    </span>
                                                @endif
                                                <div>
                                                    <div class="font-semibold text-gray-800 text-sm truncate">
                                                        {{ $application->job->title }}
                                                    </div>
                                                    <div class="text-xs text-gray-500 truncate">
                                                        {{ optional($application->job->organization)->name ?? 'Unknown Org' }}
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-gray-400 text-xs italic">—</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 font-medium text-blue-700 whitespace-nowrap">
                                        {{ $application->first_name }} {{ $application->last_name }}
                                        <div class="text-xs text-gray-400">{{ $application->email }}</div>
                                    </td>
                                    <td class="px-4 py-2 max-w-xs">
                                        @forelse(explode(',', $application->skills ?? '') as $skill)
                                            @if(trim($skill))
                                                <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-0.5 rounded-full mr-1 mb-1">
                                                    {{ trim($skill) }}
                                                </span>
                                            @endif
                                        @empty
                                            <span class="text-gray-400 text-xs">None</span>
                                        @endforelse
                                    </td>
                                    <td class="px-4 py-2">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold
                                            {{ $application->status === 'new' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700' }}">
                                            @if($application->status === 'new')
                                                <svg class="w-2 h-2 mr-1" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3" /></svg>
                                            @else
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                </svg>
                                            @endif
                                            {{ ucfirst($application->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2">
                                        @if($application->resume)
                                            <a href="{{ Storage::url($application->resume) }}" target="_blank"
                                                class="flex items-center gap-1 text-blue-700 hover:underline hover:text-blue-900 font-medium"
                                                title="Download Resume">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4" />
                                                </svg>
                                                <span class="hidden sm:inline">Download</span>
                                            </a>
                                        @else
                                            <span class="text-gray-400 text-xs">—</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2">
                                        <div class="flex gap-2">
                                            
                                            <button type="button" onclick="openDeleteModal('{{ route('user.application.destroy', $application) }}')"
                                                class="rounded-full bg-gray-100 p-1.5 hover:bg-red-100 text-red-600 hover:text-red-800 focus:outline-none"
                                                title="Delete Application">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M6 7v14h12V7M4 7h16M10 11v6m4-6v6M9 7V4h6v3"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="flex flex-col items-center py-10 text-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-14 w-14 mb-4 opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 9h10m-6 4h2m-7-8a9 9 0 112 0M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-lg font-semibold mb-2">No job applications yet</p>
                    <p class="text-sm mb-4">Start by applying to a job or creating a general application.</p>
                </div>
            @endif
        <!-- Delete Modal (same as before, for reusability) -->
        <div id="deleteModal" class="fixed inset-0 z-50 bg-black bg-opacity-40 flex items-center justify-center opacity-0 invisible transition-opacity duration-300 ease-out">
            <div class="bg-white rounded-xl shadow-2xl px-7 py-6 max-w-sm w-full transform scale-95 transition-transform duration-300 ease-out">
                <h2 class="text-lg font-semibold text-gray-800 mb-3">Delete Application?</h2>
                <p class="text-gray-500 mb-6 text-sm">This action cannot be undone.</p>
                <form id="deleteForm" method="POST">
                    @csrf @method('DELETE')
                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                            Delete
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
