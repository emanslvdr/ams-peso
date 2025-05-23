<x-app-layout>
    {{-- <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">
            My Job Applications
        </h2>
    </x-slot> --}}

    <div class="max-w-4xl mx-auto p-6">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-semibold">User Applications</h1>
            <a href="{{ route('user.application.create')}}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">+ New Application</a>
        </div>

        @if(session('success'))
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" 
                 x-transition.opacity.duration.1000ms 
                 class="bg-green-100 text-green-800 p-3 mb-4 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white shadow rounded-lg p-4">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left">Name</th>
                        <th class="px-4 py-3 text-left">Email</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($applications as $application)
                        <tr class="border-t">
                            <td class="px-4 py-2">{{ $application->first_name }} {{ $application->last_name }}</td>
                            <td class="px-4 py-2">{{ $application->email }}</td>
                            <td class="px-4 py-2">
                                <span class="px-2 py-1 text-xs font-medium rounded {{ $application->status === 'new' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                    {{ ucfirst($application->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-2 flex gap-2">
                                <a href="{{route('user.application.edit', $application)}}" class="text-blue-600 hover:underline">Edit</a>
                                <button onclick="openDeleteModal('{{route('user.application.destroy', $application)}}')" class="text-red-600 hover:underline">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
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
            </table>
        </div>
    </div>
</x-app-layout>
