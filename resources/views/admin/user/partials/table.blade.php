<table class="min-w-full text-sm">
    <thead class="bg-gray-100 text-gray-600 sticky top-0 z-10">
        <tr>
            <th class="px-4 py-3 text-left font-semibold">Applicant</th>
            <th class="px-4 py-3 text-left font-semibold">Email</th>
            <th class="px-4 py-3 text-left font-semibold">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($users as $user)
        <tr class="border-t last:border-b hover:bg-blue-50 transition">
            <td class="px-4 py-3 font-medium flex items-center gap-3 min-w-[180px]">
                {{-- Profile photo or initials --}}
                @php
                    $bgColors = ['bg-blue-500','bg-green-500','bg-indigo-500','bg-pink-500','bg-purple-500','bg-yellow-500','bg-red-500'];
                    $initials = collect(explode(' ', $user->name))
                        ->map(fn($part) => strtoupper(substr($part,0,1)))
                        ->join('');
                    $bgColor = $bgColors[ord($initials[0] ?? 'A') % count($bgColors)];
                @endphp
                @if($user->profile_photo ?? false)
                    <img src="{{ asset('storage/' . $user->profile_photo) }}"
                        class="w-9 h-9 rounded-full object-cover border shadow"
                        alt="{{ $user->name }}'s photo">
                @else
                    <div class="w-9 h-9 flex items-center justify-center rounded-full {{ $bgColor }} text-white font-bold text-base shadow border">
                        {{ $initials }}
                    </div>
                @endif
                <span class="truncate max-w-[110px]" title="{{ $user->name }}">{{ $user->name }}</span>
            </td>
            <td class="px-4 py-3 text-gray-700">{{ $user->email }}</td>
            <td class="px-4 py-3 flex gap-1 items-center">
                <a href="{{ route('users.edit', $user) }}"
                    class="p-2 rounded-full hover:bg-blue-100 group transition"
                    title="Edit User"
                >
                    <svg class="w-5 h-5 text-blue-600 group-hover:text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536M9 13l6-6 3 3-6 6H9v-3z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 21h4a2 2 0 002-2v-4"/>
                    </svg>
                    <span class="sr-only">Edit</span>
                </a>
                <button
                    onclick="openDeleteModal('{{ route('users.destroy', $user) }}')"
                    class="p-2 rounded-full hover:bg-red-100 group transition"
                    title="Delete User"
                >
                    <svg class="w-5 h-5 text-red-500 group-hover:text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-3h4a2 2 0 012 2v2H7V6a2 2 0 012-2zm5 6v6m-4-6v6"/>
                    </svg>
                    <span class="sr-only">Delete</span>
                </button>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="3" class="py-10 text-center text-gray-400">
                <div class="flex flex-col items-center">
                    <svg class="w-12 h-12 mb-2 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10" stroke-width="2" />
                        <path stroke-width="2" d="M9 12h6" />
                    </svg>
                    <div>No applicants found. <a href="{{ route('users.create') }}" class="text-blue-600 underline">Create your first user</a></div>
                </div>
            </td>
        </tr>
        @endforelse
    </tbody>
</table>
@if($users->hasPages())
    <div class="mt-6 px-6 pb-6">
        {{ $users->links() }}
    </div>
@endif
