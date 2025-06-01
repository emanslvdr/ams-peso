<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl md:text-2xl leading-tight">
            Clients Management
        </h2>
    </x-slot>
    <div class="max-w-5xl mx-auto p-6">

        <div class="flex justify-between items-center mb-8">
            <h1 class="text-2xl font-bold flex items-center gap-2">
                <svg class="w-7 h-7 text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="7" r="4"/>
                    <path d="M16 21v-2a4 4 0 00-3-3.87" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Clients Account
            </h1>
            <a href="{{ route('clients.create') }}"
               class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-full font-semibold shadow hover:bg-blue-700 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Create Client
            </a>
        </div>

        @if(session('success'))
        <div 
            x-data="{ show: true }" 
            x-init="setTimeout(() => show = false, 3000)" 
            x-show="show"
            x-transition.opacity.duration.700ms
            class="bg-green-50 border border-green-200 text-green-800 p-3 mb-5 rounded-lg flex items-center gap-2"
        >
            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M5 13l4 4L19 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
        @endif

        <div class="bg-white shadow rounded-xl overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3"></th>
                        <th class="px-4 py-3 text-left font-semibold">Name</th>
                        <th class="px-4 py-3 text-left font-semibold">Email</th>
                        <th class="px-4 py-3 text-left font-semibold">Organization</th>
                        <th class="px-4 py-3 text-left font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clients as $client)
                    <tr class="border-t transition hover:bg-blue-50 even:bg-gray-50 group">
                        <td class="px-4 py-3">
                            @if($client->profile_photo)
                                <img src="{{ asset('storage/' . $client->profile_photo) }}"
                                    class="w-12 h-12 rounded-full object-cover border shadow transition hover:scale-105"
                                    alt="{{ $client->name }}'s photo">
                            @else
                                @php
                                    $initials = collect(explode(' ', $client->name))
                                        ->map(fn($part) => strtoupper(substr($part,0,1)))
                                        ->join('');
                                    $bgColors = [
                                        'bg-blue-500','bg-green-500','bg-indigo-500',
                                        'bg-pink-500','bg-purple-500','bg-yellow-500','bg-red-500'
                                    ];
                                    $bgColor = $bgColors[ord($initials[0] ?? 'A') % count($bgColors)];
                                @endphp
                                <div class="w-12 h-12 flex items-center justify-center rounded-full {{ $bgColor }} text-white font-bold text-lg shadow border transition hover:scale-105">
                                    {{ $initials }}
                                </div>
                            @endif
                        </td>
                        <td class="px-4 py-3 font-medium max-w-xs truncate" title="{{ $client->name }}">{{ $client->name }}</td>
                        <td class="px-4 py-3 max-w-xs truncate" title="{{ $client->email }}">{{ $client->email }}</td>
                        <td class="px-4 py-3 max-w-xs truncate" title="{{ $client->organization->name ?? '-' }}">
                            {{ $client->organization->name ?? '-' }}
                        </td>
                        <td class="px-4 py-3 flex gap-2 items-center">
                            <a href="{{ route('clients.edit', $client) }}"
                                class="p-2 rounded hover:bg-blue-100 group transition"
                                title="Edit Client">
                                <svg class="w-5 h-5 text-blue-500 group-hover:text-blue-700 transition" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536M9 13l6-6 3 3-6 6H9v-3z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 21h4a2 2 0 002-2v-4"/>
                                </svg>
                                <span class="sr-only">Edit</span>
                            </a>
                            <button onclick="openDeleteModal('{{ route('clients.destroy', $client) }}')"
                                class="p-2 rounded hover:bg-red-100 group transition"
                                title="Delete Client">
                                <svg class="w-5 h-5 text-red-400 group-hover:text-red-600 transition" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-3h4a2 2 0 012 2v2H7V6a2 2 0 012-2zm5 6v6m-4-6v6"/>
                                </svg>
                                <span class="sr-only">Delete</span>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-10 text-center text-gray-400">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 mb-2 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10" stroke-width="2" />
                                    <path stroke-width="2" d="M9 12h6" />
                                </svg>
                                <div>No clients found. <a href="{{ route('clients.create') }}" class="text-blue-600 underline">Create your first client</a></div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Delete Confirmation Modal --}}
        <div id="deleteModal" class="fixed inset-0 z-50 bg-black/30 flex items-center justify-center opacity-0 invisible transition-opacity duration-300 ease-out">
            <div class="bg-white rounded-xl shadow-2xl px-8 py-7 max-w-xs w-full scale-95 transition-transform duration-200 text-center">
                <h2 class="text-lg font-semibold text-gray-800 mb-2">Delete Client?</h2>
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
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) closeDeleteModal();
        });
    </script>
</x-admin-layout>
