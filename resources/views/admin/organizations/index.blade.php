<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl md:text-2xl leading-tight">
            {{ Auth::user()->name }}
        </h2>
    </x-slot>
    <div class="max-w-4xl mx-auto p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold tracking-tight text-gray-800">Organizations</h1>
            <a href="{{ route('organizations.create') }}" class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-full px-4 py-2 shadow transition">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Add
            </a>
        </div>

        @if(session('success'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 2500)" x-show="show"
             x-transition.opacity.duration.700ms
             class="bg-green-100 text-green-800 p-3 mb-5 rounded shadow-md flex items-center gap-2">
            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('success') }}
        </div>
        @endif

        <div class="bg-white shadow-lg rounded-xl overflow-hidden transition-all duration-300">
            @if($organizations->count() === 0)
                <div class="p-6 text-center text-gray-400 text-lg">
                    No organizations found.<br>
                    <span class="text-base text-gray-500">Click <span class="font-bold">Add</span> to create one.</span>
                </div>
            @else
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-5 py-3 text-left font-semibold text-gray-700">Logo</th>
                        <th class="px-5 py-3 text-left font-semibold text-gray-700">Name</th>
                        <th class="px-5 py-3 text-left font-semibold text-gray-700">Listings</th>
                        <th class="px-5 py-3 text-left font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($organizations as $organization)
                    <tr class="border-b hover:bg-gray-50 group transition">
                        <!-- Logo/Initials -->
<td class="px-5 py-3">
    @if ($organization->logo)
        <img src="{{ asset('storage/' . $organization->logo) }}"
             alt="Logo"
             class="w-8 h-8 rounded-full object-cover border border-gray-200 shadow-sm" />
    @else
        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center font-bold text-blue-600 text-lg uppercase">
            {{ strtoupper(substr($organization->name, 0, 1)) }}
        </div>
    @endif
</td>

                        <td class="px-5 py-3">{{ $organization->name }}</td>
                        <td class="px-5 py-3">{{ $organization->jobs_count }}</td>
                       <td class="px-5 py-3 flex gap-1 items-center">
    <!-- Open -->
    <a href="{{ route('organizations.jobs', $organization) }}" title="Open" target="_blank"
       class="group p-1 rounded-full hover:bg-green-50 transition flex items-center">
        <svg class="w-4 h-4 text-green-600 group-hover:text-green-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
        </svg>
        <span class="sr-only">Open</span>
    </a>
    <!-- Edit -->
    <a href="{{ route('organizations.edit', $organization) }}" title="Edit"
       class="group p-1 rounded-full hover:bg-blue-50 transition flex items-center">
        <svg class="w-4 h-4 text-blue-600 group-hover:text-blue-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536M9 13l6-6 3 3-6 6H9v-3z"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 21h4a2 2 0 002-2v-4"/>
        </svg>
        <span class="sr-only">Edit</span>
    </a>
    <!-- Delete -->
    <button onclick="openDeleteModal('{{ route('organizations.destroy', $organization) }}')" title="Delete"
        class="group p-1 rounded-full hover:bg-red-50 transition flex items-center">
        <svg class="w-4 h-4 text-red-500 group-hover:text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-3h4a2 2 0 012 2v2H7V6a2 2 0 012-2zm5 6v6m-4-6v6"/>
        </svg>
        <span class="sr-only">Delete</span>
    </button>
</td>

                    </tr>
                @endforeach
                </tbody>
            </table>
            @endif

            <!-- Pagination (if needed) -->
            <div class="py-4">
                {{ $organizations->links() }}
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div id="deleteModal" class="fixed inset-0 z-50 bg-black/30 flex items-center justify-center opacity-0 invisible transition-opacity duration-300 ease-out">
            <div class="bg-white rounded-xl shadow-2xl px-8 py-7 max-w-xs w-full scale-95 transition-transform duration-200 text-center">
                <h2 class="text-lg font-semibold text-gray-800 mb-2">Delete Organization?</h2>
                <p class="text-gray-500 mb-6">This action cannot be undone.</p>
                <form id="deleteForm" method="POST">
                    @csrf @method('DELETE')
                    <div class="flex justify-center space-x-2">
                        <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 bg-gray-100 text-gray-600 rounded hover:bg-gray-200">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 font-semibold shadow">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openDeleteModal(action) {
            document.getElementById('deleteForm').setAttribute('action', action);
            const modal = document.getElementById('deleteModal');
            modal.classList.remove('invisible', 'opacity-0');
            modal.classList.add('opacity-100');
        }
        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            modal.classList.remove('opacity-100');
            modal.classList.add('opacity-0');
            setTimeout(() => modal.classList.add('invisible'), 200);
        }
    </script>
</x-admin-layout>
