<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl md:text-2xl leading-tight">
            Jobs Management
        </h2>
    </x-slot>
    <div class="max-w-5xl mx-auto p-6">

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Job Listings</h1>
            <a href="{{ route('jobs.create') }}"
               class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-full font-semibold shadow hover:bg-blue-700 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Create Job
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

        <div class="bg-white shadow rounded-lg">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left">Job Title</th>
                        <th class="px-4 py-3 text-left">Organization</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jobs as $job)
                    <tr class="border-t hover:bg-blue-50 transition">
                        <td class="px-4 py-2 font-medium flex items-center gap-2">
                            <a href="{{ route('jobs.pipeline', $job) }}" class="hover:underline">{{ $job->title }}</a>
                         
                        </td>
                        <td class="px-4 py-2">{{ $job->organization->name }}</td>
                        <td class="px-4 py-2">
                            @if($job->status === 'open')
                                <span class="px-2 py-0.5 rounded-full bg-green-100 text-green-700 text-xs font-semibold">Open</span>
                            @elseif($job->status === 'closed')
                                <span class="px-2 py-0.5 rounded-full bg-red-100 text-red-700 text-xs font-semibold">Closed</span>
                            @else
                                <span class="px-2 py-0.5 rounded-full bg-gray-100 text-gray-600 text-xs font-semibold">Draft</span>
                            @endif
                        </td>
                        <td class="px-4 py-2 flex gap-2">
                            <a href="{{ route('jobs.edit', $job) }}" class="px-2 py-1 text-xs bg-blue-50 text-blue-700 rounded hover:bg-blue-100 font-semibold"><svg class="w-4 h-4 text-blue-600 group-hover:text-blue-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536M9 13l6-6 3 3-6 6H9v-3z"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 21h4a2 2 0 002-2v-4"/>
        </svg>
        <span class="sr-only">Edit</span></a>
                            <button onclick="openDeleteModal('{{ route('jobs.destroy', $job) }}')" class="px-2 py-1 text-xs bg-red-50 text-red-700 rounded hover:bg-red-100 font-semibold"><svg class="w-4 h-4 text-red-500 group-hover:text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-3h4a2 2 0 012 2v2H7V6a2 2 0 012-2zm5 6v6m-4-6v6"/>
        </svg>
        <span class="sr-only">Delete</span></button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-10 text-center text-gray-400">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 mb-2 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10" stroke-width="2" />
                                    <path stroke-width="2" d="M9 12h6" />
                                </svg>
                                <div>No jobs found. <a href="{{ route('jobs.create') }}" class="text-blue-600 underline">Create your first job</a></div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 z-50 bg-black/30 flex items-center justify-center opacity-0 invisible transition-opacity duration-300 ease-out">
    <div class="bg-white rounded-xl shadow-2xl px-8 py-7 max-w-xs w-full scale-95 transition-transform duration-200 text-center">
        <h2 class="text-lg font-semibold text-gray-800 mb-2">Delete Job?</h2>
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
    // Open modal: remove invisible/opacity-0, add opacity-100
    function openDeleteModal(action) {
        document.getElementById('deleteForm').setAttribute('action', action);
        const modal = document.getElementById('deleteModal');
        modal.classList.remove('invisible', 'opacity-0');
        modal.classList.add('opacity-100');
    }

    // Close modal: add opacity-0, then invisible after animation
    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        modal.classList.remove('opacity-100');
        modal.classList.add('opacity-0');
        setTimeout(() => modal.classList.add('invisible'), 300); // matches transition duration
    }

    // Optional: Click outside modal to close
    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) closeDeleteModal();
    });

    // Optional: ESC key closes modal
    document.addEventListener('keydown', function(e) {
        if (e.key === "Escape") closeDeleteModal();
    });
</script>


</x-admin-layout>
