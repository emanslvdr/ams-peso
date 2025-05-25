<x-admin-layout>
    <x-slot name="header">
    <div class="flex items-center gap-3">
         <!-- Org Logo -->
        @if ($organization->logo)
            <img src="{{ asset('storage/' . $organization->logo) }}"
                 alt="Logo"
                 class="w-14 h-14 rounded-full object-cover border border-gray-200 bg-gray-50 flex-shrink-0" />
        @else
            <div class="w-14 h-14 rounded-full bg-blue-100 flex items-center justify-center font-bold text-2xl text-blue-600 uppercase border border-gray-200 flex-shrink-0">
                {{ strtoupper(substr($organization->name, 0, 1)) }}
            </div>
        @endif

        <!-- Organization Info -->
        <div class="flex-1 min-w-0">
            <div class="font-bold text-lg sm:text-xl text-gray-900 truncate">{{ $organization->name }}</div>
            @if ($organization->address)
                <div class="text-xs sm:text-sm text-gray-500 truncate">{{ $organization->address }}</div>
            @endif
            @if ($organization->website)
                <a href="{{ $organization->website }}" target="_blank" class="text-xs sm:text-sm text-blue-600 hover:underline break-all mt-0.5">
                    {{ Str::limit($organization->website, 36) }}
                </a>
            @endif
        </div>
    </div>
</x-slot>


 <div class="p-4 max-w-5xl mx-auto space-y-6">
    <div class="w-full bg-white rounded-xl shadow border border-gray-100 px-4 sm:px-8 py-4 mb-4 
    flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4">

    <div class="flex-1 flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4">
        <span class="font-bold text-lg md:text-xl tracking-tight text-gray-900">
            {{ $job->title }}
        </span>
        <span class="text-base font-normal text-gray-500 sm:ml-1">– Candidate Pipeline</span>
        @include('admin.jobs.partials.job-status-badge', ['status' => $job->status])
    </div>

    <a href="{{ route('organizations.jobs', $job->organization_id) }}"
       class="flex items-center text-sm text-blue-600 hover:text-blue-800 hover:underline transition whitespace-nowrap ml-auto">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
        </svg>
        Back to Job Listings
    </a>
</div>


        {{-- 🟦 Pipeline Columns --}}
        @if($job->status === 'Closed')
    <div class="flex items-center justify-center bg-gray-50 border border-gray-200 rounded-lg p-6 my-4">
        <span class="text-gray-500 text-lg font-medium">
            This job posting is <span class="font-bold text-gray-800">closed</span>.<br>
            No more candidates can be moved between stages.
        </span>
    </div>
@endif

        <div class="overflow-x-auto pb-2">
            <div class="flex gap-3 min-w-full" id="kanban-board">
@php
    $jobSkills = collect(explode(',', strtolower($job->skills ?? '')))
        ->map(fn($skill) => trim($skill))
        ->filter(); // removes empty strings
@endphp
@foreach ($stages as $stage)
      @php $count = $applications->get($stage, collect())->count(); @endphp
    <div class="flex-1 min-w-[220px] bg-white border rounded-lg p-3">
        <h3 class="text-base font-medium text-gray-700 mb-2 flex items-center">
            {{ $stage }}
            <span
                class="ml-2 px-2 py-0.5 rounded-full bg-gray-100 text-xs text-gray-600 font-semibold stage-count"
                data-stage-count="{{ Str::slug($stage) }}"
            >{{ $count }}</span>
        </h3>
        <div class="space-y-2 min-h-[80px] stage-column"
             id="stage-{{ Str::slug($stage) }}"
             data-stage="{{ $stage }}">
            @foreach ($applications->get($stage, []) as $app)
    @php
        $userSkills = collect(explode(',', strtolower($app->skills ?? '')))
            ->map(fn($skill) => trim($skill))
            ->filter();

        $matches = $jobSkills->intersect($userSkills)->count();
        $total = $jobSkills->count();

        $allSkills = collect(explode(',', $app->skills))->filter();
        $visibleSkills = $allSkills->take(2);
        $hasMoreSkills = $allSkills->count() > 2;
    @endphp
    <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm flex flex-col gap-1 min-h-[112px] hover:shadow-md transition draggable cursor-pointer"
         draggable="true"
         data-id="{{ $app->id }}"
         onclick="showAppDetailsModal({{ $app->id }})"
    >
        <div class="flex items-center gap-2 justify-between">
            <span class="font-semibold truncate text-gray-800 text-[15px]">{{ $app->user->name }}</span>
            @if ($total > 0)
                @if ($matches === $total && $matches > 0)
                    <span class="text-[11px] px-2 py-0.5 rounded bg-yellow-50 text-yellow-700 font-bold">★ Best Match</span>
                @elseif ($matches > 0)
                    <span class="text-[11px] px-2 py-0.5 rounded bg-green-50 text-green-700">{{ $matches }}/{{ $total }} match</span>
                @endif
            @endif
        </div>
        <div class="text-xs text-gray-500 truncate">{{ $app->user->email }}</div>
        <div class="flex flex-wrap gap-1.5 mt-1 mb-1">
            @foreach($visibleSkills as $skill)
                <span class="bg-blue-50 text-blue-700 px-2 py-0.5 rounded-full text-[11px] border border-blue-100">{{ trim($skill) }}</span>
            @endforeach
            @if($hasMoreSkills)
                <span class="bg-gray-200 text-gray-500 px-2 py-0.5 rounded-full text-[11px] border border-gray-200">...</span>
            @endif
        </div>
    </div>
    @endforeach
        </div>
    </div>
@endforeach
            </div>
        </div>

        {{-- 🟫 Unassigned Candidate Pool --}}
<div class="bg-white border rounded-lg p-3">
    <div class="flex items-center justify-between mb-2 gap-2 flex-wrap">
    <h2 class="text-base font-semibold text-gray-800">Candidate Pool</h2>
    <input
        type="text"
        id="search-candidate-pool"
        placeholder="Search by name, email, or skill..."
        class="border px-2 py-1 rounded text-sm focus:ring focus:ring-blue-200"
        style="min-width:180px;"
    />
    <select id="filter-sort-pool" class="border px-2 py-1 rounded text-sm focus:ring focus:ring-blue-200">
        <option value="all">Show All</option>
        <option value="best">Only Best Match</option>
        <option value="matched">With Any Match</option>
        <option value="sort">Sort by Best Match</option>
    </select>
</div>
    <form id="bulk-assign-form">
        <div class="flex flex-wrap gap-3" id="unassigned-pool">
           @foreach ($unassigned as $app)
                     @php
        $userSkills = collect(explode(',', strtolower($app->skills ?? '')))
            ->map(fn($skill) => trim($skill))
            ->filter();
        $matches = $jobSkills->intersect($userSkills)->count();
        $total = $jobSkills->count();
        $isBest = $matches === $total && $matches > 0;
    @endphp
    <div 
    class="flex items-start bg-white p-4 rounded-2xl border border-gray-100 shadow-sm w-64 min-h-[116px] text-[15px] text-gray-800 candidate-card transition-all hover:shadow-md"
    draggable="true"
    data-id="{{ $app->id }}"
    data-app-id="{{ $app->id }}"
    data-matches="{{ $matches }}"
    data-total="{{ $total }}"
    data-best="{{ $isBest ? '1' : '0' }}"
>
    <input type="checkbox" class="bulk-checkbox accent-blue-600 mt-1 mr-2" value="{{ $app->id }}">
    <div class="flex-1 flex flex-col gap-1 min-w-0">
        
         <div class="flex items-center gap-2">
            <span class="font-semibold truncate">{{ $app->user->name }}</span>
            @if ($total > 0)
                @if ($matches === $total && $matches > 0)
                    <span class="text-[11px] px-2 py-0.5 rounded bg-yellow-100 text-yellow-700 font-bold whitespace-nowrap">★ Best Match</span>
                @elseif ($matches > 0)
                    <span class="text-[11px] px-2 py-0.5 rounded bg-green-50 text-green-700 whitespace-nowrap">{{ $matches }}/{{ $total }} match</span>
                @endif
            @endif
        </div>
        <div class="text-xs text-gray-400 truncate">{{ $app->user->email }}</div>
        
        @php
        $allSkills = collect(explode(',', $app->skills))->filter();
        $visibleSkills = $allSkills->take(2);
        $hasMoreSkills = $allSkills->count() > 2;
        @endphp
        <div class="flex flex-wrap gap-1.5 mt-1">
            @foreach($visibleSkills as $skill)
                <span class="bg-blue-50 text-blue-700 px-2 py-0.5 rounded-full text-xs border border-blue-100">{{ trim($skill) }}</span>
            @endforeach
            @if($hasMoreSkills)
                <span class="bg-gray-100 text-gray-400 px-2 py-0.5 rounded-full text-xs border border-gray-200">...</span>
            @endif
        </div>
        {{-- Status Badge --}}
        @if($app->status)
            <span class="inline-block text-xs mt-1 px-2 py-0.5 rounded 
                {{ $app->status == 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                {{ ucfirst($app->status) }}
            </span>
        @endif

        {{-- Activity: show updated_at or last_active_at --}}
        <span class="inline-block text-xs text-gray-400 mt-0.5">
            Updated: {{ \Carbon\Carbon::parse($app->updated_at)->diffForHumans() }}
        </span>
    </div>
</div>

@endforeach
        </div>
        <button type="button" id="bulk-assign-btn"
                class="mt-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            Add to "New"
        </button>
    </form>
</div>

<!-- Job Close Confirmation Modal -->
<div id="closeJobModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/30 hidden">
    <div class="bg-white rounded-xl shadow-lg px-8 py-6 max-w-sm w-full relative">
        <h3 class="text-lg font-semibold mb-2 text-gray-800">Close This Job?</h3>
        <div class="text-gray-500 mb-4">
            Someone has been marked as "Hired" for this job.<br>
            Do you want to close this job posting?
        </div>
        <div class="flex gap-2 justify-end">
            <button onclick="closeCloseJobModal()" class="px-4 py-1 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 text-sm font-medium transition">Cancel</button>
            <button id="confirmCloseJobBtn" class="px-4 py-1 bg-red-600 text-white rounded hover:bg-red-700 text-sm font-medium transition">Close Job</button>
        </div>
    </div>
</div>


<!-- Minimalist Notification Modal -->
<div id="notification-modal"
     class="fixed inset-0 z-50 bg-black/20 flex items-center justify-center transition-opacity duration-200 invisible opacity-0">
    <div class="bg-white rounded-xl shadow-lg px-8 py-6 max-w-xs w-full text-center scale-95 transition-transform duration-200">
        <div id="notification-message" class="mb-2 text-gray-800 text-base"></div>
        <button onclick="closeNotificationModal()"
                class="mt-2 px-4 py-1 bg-gray-800 text-white rounded hover:bg-gray-700 text-sm">OK</button>
    </div>
</div>

<!-- Application Details Modal -->
<div id="appDetailsModal" class="fixed inset-0 z-50 bg-black/30 flex items-center justify-center transition-opacity duration-200 invisible opacity-0">
    <div class="bg-white rounded-xl shadow-lg px-8 py-6 max-w-lg w-full relative scale-95 transition-transform duration-200">
        <button onclick="closeAppDetailsModal()" class="absolute right-3 top-3 text-gray-400 hover:text-gray-800 text-xl">&times;</button>
        <div id="appDetailsContent">
            <!-- Dynamic Content Here -->
        </div>
    </div>
</div>

<!-- Minimalist Remove Modal -->
<div id="remove-confirm-modal"
     class="fixed inset-0 z-50 bg-black/20 flex items-center justify-center transition-opacity duration-200 invisible opacity-0">
    <div class="bg-white rounded-xl shadow-lg px-6 py-6 max-w-xs w-full text-center scale-95 transition-transform duration-200">
        <div class="mb-4 text-gray-800 text-base font-semibold">Remove applicant from this job?</div>
        <div class="flex gap-2 justify-center">
            <button id="confirm-remove-btn"
                class="px-4 py-1 bg-red-600 text-white rounded hover:bg-red-700 text-sm font-medium transition">Remove</button>
            <button onclick="closeRemoveConfirmModal()"
                class="px-4 py-1 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 text-sm font-medium transition">Cancel</button>
        </div>
    </div>
</div>



    {{-- SortableJS CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('unassigned-pool').addEventListener('click', function(e) {
    // Don't open modal if clicking on a checkbox!
    if (e.target.matches('.bulk-checkbox')) return;
    let card = e.target.closest('.candidate-card');
    if (card && card.dataset.appId) {
        showAppDetailsModal(card.dataset.appId);
    }
});

document.querySelectorAll('.stage-column').forEach(column => {
    new Sortable(column, {
        group: 'shared',
        animation: 150,
        onAdd: function(evt) {
            const appId = evt.item.dataset.id;
            const newStage = evt.to.dataset.stage;

            fetch(`/applications/${appId}/stage`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    stage: newStage,
                    job_id: {{ $job->id }}
                })
            })
            .then(response => response.json())
            .then(data => {
                // Remove from pool if it exists there
                const pool = document.getElementById('unassigned-pool');
                if (pool && pool.contains(evt.item)) {
                    evt.item.remove();
                }
                evt.item.classList.remove('candidate-card');
                updateStageCounts();

                // 🔴 Show the close job modal ONLY if dropped to Hired
                if (newStage === 'Hired') {
                    showCloseJobModal({{ $job->id }});
                }
            });
        }
    });
});

//kanban live counting
function updateStageCounts() {
    document.querySelectorAll('.stage-column').forEach(column => {
        const stage = column.dataset.stage;
        const count = column.querySelectorAll('.draggable').length;
        const badge = document.querySelector(`[data-stage-count="${stage.toLowerCase().replace(/\s+/g, '-')}"]`);
        if (badge) badge.innerText = count;
    });
}


    // Candidate pool drag settings
new Sortable(document.getElementById('unassigned-pool'), {
    group: 'shared',
    animation: 150,
    onAdd: function(evt) {
        const appId = evt.item.dataset.id;
        // Candidate pool has no stage!
        fetch(`/applications/${appId}/stage`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                stage: null,
                job_id: null
            })
        }).then(response => response.json())
          .then(data => {
              evt.item.classList.add('candidate-card');
              updateStageCounts();
          });
    }
});


});
document.getElementById('bulk-assign-btn').addEventListener('click', function(e) {
    e.preventDefault();

    const selectedIds = Array.from(document.querySelectorAll('.bulk-checkbox:checked'))
        .map(cb => cb.value);

if (selectedIds.length === 0) {
    showNotificationModal('Select at least one candidate!');
    return;
}


    fetch('/applications/bulk-assign', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            ids: selectedIds,
            job_id: {{ $job->id }},
            stage: "New"
        })
    }).then(r => r.json())
      .then(data => {
    if (data.success) {
        showNotificationModal('Assigned!');
        setTimeout(() => location.reload(), 1200); // optional: reload after a second
    }
});

});

//modal assign bulk
function showNotificationModal(message) {
    document.getElementById('notification-message').innerText = message;
    const modal = document.getElementById('notification-modal');
    modal.classList.remove('invisible', 'opacity-0');
    modal.classList.add('opacity-100');
    modal.querySelector('button').focus();
}
function closeNotificationModal() {
    const modal = document.getElementById('notification-modal');
    modal.classList.remove('opacity-100');
    modal.classList.add('opacity-0');
    setTimeout(() => {
        modal.classList.add('invisible');
    }, 200);
}

function showAppDetailsModal(appId) {
     // Show loading spinner
    document.getElementById('appDetailsContent').innerHTML = `
        <div class="flex justify-center items-center h-24">
            <svg class="animate-spin h-7 w-7 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
            </svg>
        </div>
    `;
    // Fetch details with AJAX (expects a route returning JSON)
    fetch(`/applications/${appId}/details`)
        .then(r => r.json())
        .then(data => {
           let content = `
            <div class="space-y-2">
                <div class="flex flex-col items-center mb-3">
                    <div class="text-lg font-bold text-gray-800">${data.user.name}</div>
                    <div class="text-sm text-gray-400">${data.user.email}</div>
                </div>
                <div class="grid grid-cols-1 gap-y-2 text-[15px] text-gray-700">
                    <div>
                        <span class="font-medium">Stage:</span>
                        <span class="text-gray-500 ml-1">${data.stage || '—'}</span>
                    </div>
                    <div>
                        <span class="font-medium">Status:</span>
                        <span class="ml-1 inline-block px-2 py-0.5 rounded-full bg-gray-100 text-xs text-gray-700">${data.status ? data.status : '—'}</span>
                    </div>
                    <div>
                        <span class="font-medium">Phone:</span>
                        <span class="text-gray-500 ml-1">${data.phone_number || '—'}</span>
                    </div>
                    <div>
                        <span class="font-medium">Last Updated:</span>
                        <span class="text-gray-500 ml-1">${data.updated_at ? new Date(data.updated_at).toLocaleString() : '—'}</span>
                    </div>
                    <div>
                        <span class="font-medium">Education:</span>
                        <span class="ml-1">${data.institution || '—'}</span>
                        <span class="ml-1 text-gray-400">${data.graduation_year ? `(${data.graduation_year})` : ''}</span>
                    </div>
                    <div>
                        <span class="font-medium">Work Experience:</span>
                        <div class="ml-2">
                            <span>${data.position || '—'} <span class="text-gray-400">at</span> ${data.company_name || '—'}</span>
                            ${(data.start_date || data.end_date)
                                ? `<span class="ml-1 text-xs text-gray-400">(${data.start_date || '?'} — ${data.end_date || 'Present'})</span>`
                                : ''
                            }
                        </div>
                    </div>
                    <div>
                        <span class="font-medium">Skills:</span>
                        <div class="flex flex-wrap gap-1 mt-1">
                            ${
                                data.skills && data.skills.length
                                    ? data.skills.map(s =>
                                        `<span class="bg-blue-50 text-blue-700 px-2 py-0.5 rounded-full text-xs border border-blue-100">${s.trim()}</span>`
                                      ).join('')
                                    : '<span class="text-gray-400 ml-1">—</span>'
                            }
                        </div>
                    </div>
                    <div>
                        <span class="font-medium">Resume:</span>
                        ${data.resume
                            ? `<a class="text-blue-600 underline ml-1" href="/storage/${data.resume}" target="_blank" download>Download</a>`
                            : '<span class="text-gray-400 ml-1">—</span>'
                        }
                    </div>
                </div>
                <div class="text-xs text-gray-400 mt-4 text-right">Submitted: ${data.created_at ? new Date(data.created_at).toLocaleString() : '—'}</div>

                <div class="flex justify-end gap-2 mt-6 border-t pt-4">
     <a href="/admin/applicants/${data.id}/edit"
        class="px-3 py-1 bg-blue-50 text-blue-700 rounded-md text-xs font-semibold hover:bg-blue-100 transition">
        Edit
     </a>
     <button onclick="removeFromPipeline(${data.id})"
        class="px-3 py-1 bg-gray-50 text-gray-600 rounded-md text-xs font-semibold hover:bg-gray-100 transition">
        Remove from Job
     </button>
            </div>
        `;

            document.getElementById('appDetailsContent').innerHTML = content;
            const modal = document.getElementById('appDetailsModal');
            modal.classList.remove('invisible', 'opacity-0');
            modal.classList.add('opacity-100');
        });
}

let pendingRemoveAppId = null;

// Open confirm modal for this application
function removeFromPipeline(appId) {
    pendingRemoveAppId = appId;
    showRemoveConfirmModal();
}

// Show the modal
function showRemoveConfirmModal() {
    const modal = document.getElementById('remove-confirm-modal');
    modal.classList.remove('invisible', 'opacity-0');
    modal.classList.add('opacity-100');
    // Focus confirm button for accessibility
    setTimeout(() => document.getElementById('confirm-remove-btn').focus(), 100);
}

// Hide the modal
function closeRemoveConfirmModal() {
    const modal = document.getElementById('remove-confirm-modal');
    modal.classList.remove('opacity-100');
    modal.classList.add('opacity-0');
    setTimeout(() => modal.classList.add('invisible'), 200);
    pendingRemoveAppId = null;
}

// Confirm removal handler (reloads page after)
document.getElementById('confirm-remove-btn').onclick = function () {
    if (!pendingRemoveAppId) return;
    fetch(`/applications/${pendingRemoveAppId}/stage`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ stage: null, job_id: null })
    })
    .then(r => r.json())
    .then(() => {
        closeRemoveConfirmModal();
        closeAppDetailsModal && closeAppDetailsModal();
        // Reload after a short delay for UX smoothness
        setTimeout(() => location.reload(), 400);
    });
};


document.getElementById('appDetailsModal').addEventListener('click', function(e) {
    if (e.target === this) closeAppDetailsModal();
});

function closeAppDetailsModal() {
    const modal = document.getElementById('appDetailsModal');
    modal.classList.remove('invisible', 'opacity-0');
modal.classList.add('opacity-100');
    setTimeout(() => {
        modal.classList.add('invisible');
    }, 200);
}

</script>

{{-- // search filter --}}
    <script>
document.getElementById('filter-sort-pool').addEventListener('change', function() {
    const value = this.value;
    const cards = Array.from(document.querySelectorAll('.candidate-card'));
    if (value === 'all') {
        cards.forEach(card => card.classList.remove('hidden'));
    } else if (value === 'best') {
        cards.forEach(card => {
            card.classList.toggle('hidden', card.dataset.best !== '1');
        });
    } else if (value === 'matched') {
        cards.forEach(card => {
            card.classList.toggle('hidden', parseInt(card.dataset.matches) < 1);
        });
    } else if (value === 'sort') {
        // Sort by matches descending
        const pool = document.getElementById('unassigned-pool');
        cards.sort((a, b) => parseInt(b.dataset.matches) - parseInt(a.dataset.matches));
        cards.forEach(card => pool.appendChild(card));
    }
});

document.getElementById('search-candidate-pool').addEventListener('input', function() {
    const search = this.value.trim().toLowerCase();
    const cards = document.querySelectorAll('.candidate-card');
    cards.forEach(card => {
        const name = card.querySelector('.font-semibold')?.innerText.toLowerCase() || '';
        const email = card.querySelector('.text-xs.text-gray-400')?.innerText.toLowerCase() || '';
        // Gather skills text from chip badges (optional, make sure your chips have the right selector)
        const skills = Array.from(card.querySelectorAll('.bg-blue-50')).map(el => el.innerText.toLowerCase()).join(' ');
        card.classList.toggle('hidden',
            !(
                name.includes(search) ||
                email.includes(search) ||
                skills.includes(search)
            )
        );
    });
});

//job status confirmation modal
function showCloseJobModal(jobId) {
    window.closingJobId = jobId; // Save for later use
    document.getElementById('closeJobModal').classList.remove('hidden');
}
function closeCloseJobModal() {
    document.getElementById('closeJobModal').classList.add('hidden');
}

document.getElementById('confirmCloseJobBtn').onclick = function() {
    const jobId = window.closingJobId;
    fetch(`/admin/jobs/${jobId}/close`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            // Update UI: badge, modal, maybe disable further movement
            document.querySelectorAll('.job-status-badge').forEach(badge => {
                badge.innerHTML = 'Closed';
                badge.className = 'job-status-badge px-3 py-1 bg-gray-200 text-gray-700 rounded-full font-semibold text-base'; // adjust classes as needed
            });
            closeCloseJobModal();
            // Optional: toast notification
            alert('Job is now closed!');
        }
    });
};

</script>

</x-admin-layout>
