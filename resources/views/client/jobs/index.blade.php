<x-client-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl md:text-2xl leading-tight">
            Jobs – {{ Auth::user()->name }}
        </h2>
    </x-slot>
    <div class="max-w-5xl mx-auto p-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-4">
            <h1 class="text-2xl font-bold text-gray-900">Job Listings</h1>
            <a href="{{ route('client.jobs.create')}}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-xl shadow hover:bg-blue-700 transition text-sm font-semibold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                New Job
            </a>
        </div>

        @if(session('success'))
            <div 
                x-data="{ show: true }" 
                x-init="setTimeout(() => show = false, 3000)" 
                x-show="show"
                x-transition.opacity.duration.1000ms
                class="bg-green-100 text-green-800 p-3 mb-4 rounded-xl shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-3">
            <input
                type="text"
                id="job-search"
                placeholder="Search jobs…"
                class="w-full sm:w-72 px-3 py-2 border rounded-lg text-sm focus:ring focus:ring-blue-200"
            />
        </div>

        <div class="bg-white shadow rounded-xl overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 sticky top-0 z-10">
                    <tr>
                        <th class="px-4 py-3 text-left"></th>
                        <th class="px-4 py-3 text-left">Job Title</th>
                        <th class="px-4 py-3 text-center">Candidates</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-4 py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody id="jobs-table">
                    @forelse($jobs as $job)
                    <tr class="border-t group hover:bg-blue-50 transition">
                        {{-- Organization --}}
                        <td class="px-4 py-2 flex items-center gap-2 whitespace-nowrap">
                            @if($job->organization->logo)
                                <img src="{{ asset('storage/'.$job->organization->logo) }}" alt="" class="w-7 h-7 rounded-full border object-cover bg-gray-50">
                            @else
                                <div class="w-7 h-7 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 text-sm font-bold">
                                    {{ strtoupper(substr($job->organization->name,0,1)) }}
                                </div>
                            @endif
                            <span class="truncate max-w-[110px] text-gray-800">{{ $job->organization->name }}</span>
                        </td>

                        {{-- Job Title --}}
                        <td class="px-4 py-2 font-medium">
                            <a href="{{ route('client.jobs.pipeline', $job) }}" class="hover:underline text-blue-700">
                                {{ $job->title }}
                            </a>
                        </td>

                        {{-- Candidates --}}
                        <td class="px-4 py-2 text-center">
                            <span class="inline-flex items-center justify-center min-w-[24px] px-2 py-0.5 rounded-full bg-blue-50 text-blue-700 font-semibold text-xs">
                                {{ $job->applications_count ?? ($job->applications()->count() ?? 0) }}
                            </span>
                        </td>

                        {{-- Status --}}
                        <td class="px-4 py-2 text-center">
                            <div class="relative inline-block w-28">
                                <select
                                    class="appearance-none w-full pl-3 pr-8 py-1.5 rounded-full border font-semibold text-xs transition-all
                                    {{ $job->status === 'open' ? 'bg-green-100 text-green-700 border-green-200' : ($job->status === 'closed' ? 'bg-red-100 text-red-700 border-red-200' : 'bg-gray-100 text-gray-600 border-gray-200') }}"
                                    onchange="updateJobStatus({{ $job->id }}, this.value, this)"
                                >
                                    <option value="open" {{ $job->status === 'open' ? 'selected' : '' }}>Open</option>
                                    <option value="closed" {{ $job->status === 'closed' ? 'selected' : '' }}>Closed</option>
                                    <option value="draft" {{ $job->status === 'draft' ? 'selected' : '' }}>Draft</option>
                                </select>
                                <svg class="w-4 h-4 absolute right-2 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </td>

                        {{-- Actions --}}
                        <td class="px-4 py-2 flex gap-1 flex-wrap justify-center">
                            <a href="{{ route('client.jobs.edit', $job) }}"
                               class="px-3 py-1 bg-gray-50 text-gray-700 rounded hover:bg-blue-100 hover:text-blue-700 text-xs font-semibold transition"
                               title="Edit this job">
                                Edit
                            </a>
                            <button onclick="openDeleteModal('{{ route('client.jobs.destroy', $job) }}')" 
                                class="px-3 py-1 bg-red-50 text-red-700 rounded hover:bg-red-100 text-xs font-semibold transition"
                                title="Delete this job">
                                Delete
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">
                            <div class="flex flex-col items-center justify-center py-12 text-gray-400">
                                <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10" />
                                    <path d="M9 12h6" />
                                </svg>
                                <span>No job listings found.</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Delete Modal --}}
        <div id="deleteModal" class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center opacity-0 invisible transition-opacity duration-300">
            <div class="bg-white rounded-xl shadow-lg p-7 max-w-sm w-full scale-95 transition-transform duration-300">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Delete Job?</h2>
                <p class="text-gray-600 mb-6">This action cannot be undone.</p>
                <form id="deleteForm" method="POST">
                    @csrf @method('DELETE')
                    <div class="flex justify-end gap-2">
                        <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 focus:ring-2 focus:ring-blue-300">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 focus:ring-2 focus:ring-red-300">
                            Delete
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div id="toast" style="display:none;" class="fixed bottom-6 right-6 px-4 py-2 bg-green-600 text-white rounded shadow transition-all duration-300 opacity-0"></div>
    </div>

    <script>
        // Search/filter jobs by title/org
        document.getElementById('job-search').addEventListener('input', function () {
            const search = this.value.toLowerCase();
            document.querySelectorAll('#jobs-table tr').forEach(row => {
                const title = row.querySelector('td:nth-child(2) a')?.innerText.toLowerCase() || '';
                const org = row.querySelector('td:nth-child(1) span')?.innerText.toLowerCase() || '';
                row.style.display = (title.includes(search) || org.includes(search)) ? '' : 'none';
            });
        });

        // Delete modal
        function openDeleteModal(url) {
            const modal = document.getElementById('deleteModal');
            document.getElementById('deleteForm').setAttribute('action', url);
            modal.classList.remove('opacity-0', 'invisible');
            modal.classList.add('opacity-100');
            setTimeout(() => modal.querySelector('button[type="button"]').focus(), 100);
        }
        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            modal.classList.remove('opacity-100');
            modal.classList.add('opacity-0');
            setTimeout(() => modal.classList.add('invisible'), 300);
        }
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeDeleteModal();
        });

        // Update job status with spinner feedback and smooth color change
        function updateJobStatus(jobId, status, selectEl) {
            selectEl.disabled = true;
            const prevClass = selectEl.className;
            selectEl.className += ' opacity-60';
            // Insert spinner
            const spinner = document.createElement('span');
            spinner.innerHTML = `<svg class="inline w-4 h-4 ml-1 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path></svg>`;
            selectEl.parentNode.appendChild(spinner);

            fetch(`/client/jobs/${jobId}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ status })
            })
            .then(r => r.json())
            .then(data => {
                spinner.remove();
                if (data.success) {
                    selectEl.className =
                        "appearance-none w-full pl-3 pr-8 py-1.5 rounded-full border font-semibold text-xs transition-all " +
                        (status === 'open'
                            ? 'bg-green-100 text-green-700 border-green-200'
                            : status === 'closed'
                            ? 'bg-red-100 text-red-700 border-red-200'
                            : 'bg-gray-100 text-gray-600 border-gray-200');
                    showToast('Status updated!');
                } else {
                    showToast('Failed to update status.');
                    selectEl.className = prevClass;
                }
                selectEl.disabled = false;
            }).catch(() => {
                showToast('Network error!');
                selectEl.className = prevClass;
                selectEl.disabled = false;
                spinner.remove();
            });
        }

        function showToast(msg) {
            var t = document.getElementById('toast');
            t.textContent = msg;
            t.style.display = '';
            t.classList.remove('opacity-0');
            t.classList.add('opacity-100');
            setTimeout(() => {
                t.classList.remove('opacity-100');
                t.classList.add('opacity-0');
                setTimeout(() => t.style.display = 'none', 400);
            }, 2000);
        }
    </script>
</x-client-layout>
