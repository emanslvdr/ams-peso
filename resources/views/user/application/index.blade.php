<x-app-layout>
    {{-- <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">
            My Job Applications
        </h2>
    </x-slot> --}}

    <div class="max-w-4xl mx-auto p-6">
     
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-semibold">My Applications</h1>
        <a href="{{ route('user.application.create') }}"
           class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
            + New Application
        </a>
    </div>
    @if(session('success'))
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" 
                 x-transition.opacity.duration.1000ms 
                 class="bg-green-100 text-green-800 p-3 mb-4 rounded">
                {{ session('success') }}
            </div>
        @endif
    <div class="bg-white shadow-lg rounded-xl p-5">
        <table class="min-w-full text-sm border-separate [border-spacing:0.5rem]">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-3 text-left">Name</th>
                    <th class="px-4 py-3 text-left">Email</th>
                    <th class="px-4 py-3 text-left">Skills</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-left">Resume</th>
                    <th class="px-4 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
            @foreach($applications as $application)
                <tr class="hover:bg-blue-50 border-t rounded group">
                    <!-- Name clickable for details modal -->
                    <td class="px-4 py-2 font-semibold">
                        <button
                            onclick="showAppDetailsModal({{ $application->id }})"
                            class="text-blue-700 hover:underline focus:outline-none"
                        >
                            {{ $application->first_name }} {{ $application->last_name }}
                        </button>
                    </td>
                    <td class="px-4 py-2">{{ $application->email }}</td>
                    <td class="px-4 py-2 max-w-xs">
                        @foreach(explode(',', $application->skills ?? '') as $skill)
                            @if(trim($skill))
                                <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-0.5 rounded mr-1">
                                    {{ trim($skill) }}
                                </span>
                            @endif
                        @endforeach
                    </td>
                    <td class="px-4 py-2">
                        <span class="inline-flex items-center px-2 py-1 rounded text-xs
                            {{ $application->status === 'new' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                            {{ ucfirst($application->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-2">
                        @if($application->resume)
                            <a href="{{ Storage::url($application->resume) }}" target="_blank"
                               class="text-blue-600 hover:underline flex items-center gap-1 group-hover:text-blue-800 transition">
                                <!-- Minimalist download SVG -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4" />
                                </svg>
                                <span class="sr-only">Download Resume</span>
                            </a>
                        @endif
                    </td>
                    <td class="px-4 py-2 flex gap-2">
                        <a href="{{ route('user.application.edit', $application) }}"
                           class="text-blue-600 hover:text-blue-900 transition" title="Edit">
                            <!-- Minimalist edit pencil SVG -->
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M16.862 4.487a2.096 2.096 0 1 1 2.964 2.964L7.25 20.025l-4.334.482.482-4.334L16.862 4.487Z"/>
                            </svg>
                        </a>
                        <button type="button" onclick="openDeleteModal('{{ route('user.application.destroy', $application) }}')"
                                class="text-red-600 hover:text-red-800 transition" title="Delete">
                            <!-- Minimalist trash SVG -->
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M6 7v14h12V7M4 7h16M10 11v6m4-6v6M9 7V4h6v3"/>
                            </svg>
                        </button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <!-- Delete Modal -->
<div id="deleteModal" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center opacity-0 invisible transition-opacity duration-300 ease-out">
    <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm w-full transform scale-95 transition-transform duration-300 ease-out">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Are you sure?</h2>
        <p class="text-gray-600 mb-6">This action cannot be undone.</p>
        <form id="deleteForm" method="POST">
            @csrf @method('DELETE')
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                    Delete
                </button>
            </div>
        </form>
    </div>
</div>

    </div>
</div>

    </div>
</x-app-layout>
