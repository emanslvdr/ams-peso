<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800">Applicants Management</h2>
    </x-slot>
    <div class="max-w-4xl mx-auto p-6">
        {{-- Success Message --}}
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 p-3 mb-6 rounded-lg flex items-center gap-2">
                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M5 13l4 4L19 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="flex flex-col md:flex-row items-center justify-between gap-4 mb-6">
            <h1 class="text-xl font-bold text-gray-900">Applicants</h1>
            <form method="GET" action="{{ route('users.index') }}" class="flex items-center w-full md:w-auto">
                <div class="relative w-full md:w-72">
                    <input
                        type="text"
                        name="search"
                        value="{{ old('search', $search) }}"
                        class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-200 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-200 focus:border-blue-400 text-sm"
                        placeholder="Search applicants…"
                        autocomplete="off"
                    >
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <circle cx="11" cy="11" r="7" />
                            <line x1="21" y1="21" x2="16.65" y2="16.65" stroke-linecap="round" />
                        </svg>
                    </span>
                </div>
                @if($search)
                    <a href="{{ route('users.index') }}" class="ml-2 text-sm text-gray-400 hover:text-red-400 transition">Clear</a>
                @endif
            </form>
            <a href="{{ route('users.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-full font-semibold shadow hover:bg-blue-700 transition text-base">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M12 4v16m8-8H4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span>Add New</span>
            </a>
        </div>

        <div class="bg-white shadow rounded-xl overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-left">
                        <th class="py-3 px-4 font-semibold text-gray-600">Applicant</th>
                        <th class="py-3 px-4 font-semibold text-gray-600">Email</th>
                        <th class="py-3 px-4 font-semibold text-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($users as $user)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="py-3 px-4 flex items-center gap-3">
                            @if($user->profile_photo)
                                <img src="{{ Storage::url($user->profile_photo) }}" alt="" class="w-8 h-8 rounded-full object-cover border border-gray-200">
                            @else
                                <span class="w-8 h-8 flex items-center justify-center rounded-full bg-blue-100 text-blue-700 font-bold">{{ strtoupper(substr($user->name,0,1)) }}</span>
                            @endif
                            <span class="font-medium text-gray-800">{{ $user->name }}</span>
                        </td>
                        <td class="py-3 px-4 text-gray-700">{{ $user->email }}</td>
                        <td class="py-3 px-4 flex gap-2">
                            <a href="{{ route('users.edit', $user) }}"
                               class="inline-flex items-center px-2 py-1 text-blue-600 hover:bg-blue-50 rounded transition"
                               title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M15.232 5.232l3.536 3.536M9 11l6-6 3 3-6 6M3 17v4h4l10.292-10.292a1 1 0 0 0 0-1.415l-4.585-4.585a1 1 0 0 0-1.415 0L3 17z" />
                                </svg>
                            </a>
                            <button type="button"
                                    onclick="openDeleteModal('{{ route('users.destroy', $user) }}')"
                                    class="inline-flex items-center px-2 py-1 text-red-600 hover:bg-red-50 rounded transition"
                                    title="Delete">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="py-6 px-4 text-gray-400 text-center">No applicants found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6">
            {{ $users->links() }}
        </div>

        <!-- Delete Modal -->
        <div id="deleteModal" class="fixed inset-0 z-50 bg-black/30 flex items-center justify-center opacity-0 invisible transition-opacity duration-300 ease-out">
            <div class="bg-white rounded-xl shadow-2xl px-8 py-7 max-w-xs w-full scale-95 transition-transform duration-200 text-center relative">
                <button type="button" onclick="closeDeleteModal()" class="absolute top-3 right-3 text-gray-400 hover:text-red-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                <h2 class="text-lg font-semibold text-gray-800 mb-2">Delete User?</h2>
                <p class="text-gray-500 mb-6">This action cannot be undone.</p>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="flex justify-center space-x-2">
                        <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 bg-gray-100 text-gray-600 rounded hover:bg-gray-200">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 font-semibold shadow">Delete</button>
                    </div>
                </form>
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
    </div>
</x-admin-layout>
