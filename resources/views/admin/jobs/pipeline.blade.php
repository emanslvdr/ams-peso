<x-admin-layout>

    @if($job->status === 'closed')
    <div class="w-full text-center py-4 bg-yellow-50 text-yellow-800 rounded m-2 font-semibold">
        This job is <span class="text-red-600">CLOSED</span>. No further changes allowed.
    </div>
@endif

@if(session('job_closed'))
    <div class="mt-4 p-3 rounded bg-green-50 text-green-700 font-semibold border border-green-100 text-center">
        The job has been closed. No further changes are allowed.
    </div>
@endif


<div class="max-w-6xl mx-auto px-2 pt-3">
     
    <div class="bg-white/95 rounded-2xl shadow-sm border border-gray-100 px-8 py-7  transition">
    {{-- Organization Header: Logo left, org info right --}}
    <div class="flex items-center justify-between mb-4">
        @if ($organization->logo)
            <img src="{{ asset('storage/' . $organization->logo) }}"
                alt="Logo"
                class="w-16 h-16 rounded-full object-cover border border-gray-200 bg-gray-50 shadow flex-shrink-0" />
        @else
            <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center font-bold text-2xl text-blue-600 uppercase border border-gray-200 shadow flex-shrink-0">
                {{ strtoupper(substr($organization->name, 0, 1)) }}
            </div>
        @endif
        <div class="text-right flex-1 ml-4">
            <div class="font-bold text-2xl text-gray-900">{{ $organization->name }}</div>
            @if ($organization->address)
                <div class="text-xs sm:text-sm text-gray-500">{{ $organization->address }}</div>
            @endif
            @if ($organization->website)
                <a href="{{ $organization->website }}" target="_blank"
                    class="text-xs sm:text-sm text-blue-600 hover:underline break-all">
                    {{ Str::limit($organization->website, 36) }}
                </a>
            @endif
        </div>
    </div>

    <hr class="my-3 border-gray-100">

    {{-- Job Header: Title left, controls right --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3 flex-wrap">
                <h1 class="text-4xl font-black text-gray-900">{{ $job->title }}</h1>
                <span class="text-gray-400 text-2xl font-medium">Pipeline</span>
                <span class="rounded-full px-4 py-1 text-sm font-semibold border border-gray-200
                    {{ $job->status === 'open' ? 'bg-green-100 text-green-700' : 'bg-yellow-50 text-yellow-700' }}">
                    {{ ucfirst($job->status) }}
                </span>
            </div>
            <p class="text-gray-500 text-xs mt-5">
                Drag & drop candidates between stages, or return to pool below.
            </p>
        </div>
        <div class="flex flex-col items-end gap-2 mb-2 md:mt-0">
            <a href="{{ route('organizations.jobs', $organization) }}"
                class="flex items-center text-xs text-blue-700 hover:text-blue-900 hover:underline font-semibold rounded focus:outline-none focus:ring-2 focus:ring-blue-200 px-2 py-1 transition">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
                Back to Job Listings
            </a>
        <button id="toggle-bulk-mode"
    class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-blue-500 hover:bg-blue-600 text-white font-medium text-sm shadow transition focus:outline-none focus:ring-2 focus:ring-blue-300 border border-transparent"
    type="button" aria-pressed="false">
    <span id="bulk-label">Bulk Select</span>
</button>

        </div>
    </div>
</div>


        {{-- KANBAN BOARD --}}
    <div class="overflow-x-auto">
    <div class="flex gap-4 py-2" id="kanban-board">
        @foreach($stages as $stage)
            <div class="flex-1 min-w-[240px] bg-white/80 border border-gray-100 rounded-xl shadow-sm p-3 flex flex-col hover:shadow-md transition-shadow">
                <div class="flex justify-between items-center mb-2">
                    <span class="font-semibold text-gray-800">{{ $stage }}</span>
                    <span class="bg-gray-100 text-xs text-gray-600 px-2 py-0.5 rounded-full">
                        {{ $jobApps->get($stage, collect())->count() }}
                    </span>
                </div>
                <div class="flex-1 flex flex-col gap-2 stage-column" id="stage-{{ Str::slug($stage) }}" data-stage="{{ $stage }}">
                    @foreach($jobApps->get($stage, collect()) as $app)
                        @include('admin.jobs.partials.pipeline-card', ['app' => $app])
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>


        {{-- search sort filter --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 sm:gap-4 my-3 ">
    <!-- Search -->
    <div class="relative flex-1 max-w-xs">
        <input type="text" id="candidate-search"
            placeholder="Search candidates…"
            class="pl-9 pr-3 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-200 text-sm bg-white shadow-sm w-full transition placeholder:text-gray-400" />
        <svg class="w-4 h-4 absolute left-2 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2"
            viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4-4"/></svg>
    </div>
    <!-- Sort -->
    <div class="flex-none">
        <select id="candidate-sort"
            class="rounded-lg border border-gray-200 px-3 py-2 text-sm bg-white shadow-sm focus:ring-2 focus:ring-blue-200 transition font-medium text-gray-700 min-w-[180px] hover:border-blue-400">
            <option value="recent">Sort by: Most Recent</option>
            <option value="score-desc">Sort by: Highest Match</option>
            <option value="score-asc">Sort by: Lowest Match</option>
            <option value="name-az">Sort by: Name (A-Z)</option>
            <option value="name-za">Sort by: Name (Z-A)</option>
        </select>
    </div>
</div>


        {{-- GENERAL POOL --}}
       <div class="bg-white border rounded-xl shadow p-5 ">
    <h2 class="text-lg font-semibold text-gray-800 mb-3 flex items-center gap-2">
        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2"
             viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>
        General Candidate Pool
    </h2>
    <div class="flex flex-wrap gap-3" id="general-pool" data-stage="pool">
        @foreach($generalPool as $app)
            @include('admin.jobs.partials.pipeline-card', ['app' => $app])
        @endforeach
    </div>
</div>
</div>

<div>
    {{-- modals --}}
    @include('admin.jobs.partials.candidate-modal')

    {{-- bulk action --}}
    <div id="bulk-action-bar"
    class="fixed bottom-4 left-1/2 -translate-x-1/2 bg-white shadow-lg rounded-xl px-6 py-3 flex items-center gap-4 z-50 border transition-all"
    style="display: none;">
    <span id="bulk-count" class="text-base font-semibold text-blue-700"></span>
    <button onclick="bulkMoveStage('Screening')" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-full text-xs font-semibold">
        Move to Screening
    </button>
    <button onclick="bulkMoveStage('Interview')" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-full text-xs font-semibold">
        Move to Interview
    </button>
    <button onclick="bulkMoveStage('Assessment')" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-full text-xs font-semibold">
        Move to Assessment
    </button>
    <button onclick="bulkMoveStage('pool')" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-3 py-1 rounded-full text-xs font-semibold">
        Return to Pool
    </button>
    <button onclick="disableBulkMode()" class="ml-2 text-gray-400 hover:text-red-500 text-xs px-2">Cancel</button>
</div>

<!-- Confirmation Modal -->
<div id="flash-confirm-modal"
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/30"
     style="display:none;">
    <div class="bg-white rounded-2xl shadow-2xl px-6 py-7 w-full max-w-md mx-auto flex flex-col items-center gap-5 border border-gray-100">
        <span id="flash-confirm-msg" class="text-base sm:text-lg font-semibold text-gray-800 text-center leading-relaxed"></span>
        <div class="flex gap-3 mt-2 w-full justify-center">
            <button id="flash-confirm-yes"
                class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-bold text-sm shadow transition focus:ring-2 focus:ring-blue-200">
                Confirm
            </button>
            <button id="flash-confirm-cancel"
                class="px-5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-lg font-semibold text-sm shadow transition focus:ring-2 focus:ring-gray-200">
                Cancel
            </button>
        </div>
    </div>
</div>


    {{-- SCRIPTS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Kanban columns and pool
    document.querySelectorAll('.stage-column, #general-pool').forEach(column => {
    new Sortable(column, {
        group: 'candidates',
        animation: 180,
        draggable: '.candidate-card',
        onAdd: function(evt) {
            const appId = evt.item.dataset.id;
            const targetStage = evt.to.dataset.stage;
            const fromColumn = evt.from;
            const fromIndex = evt.oldIndex;

            // Helper: Snap card back to its old column & position
            function snapBack() {
                if (fromColumn && evt.item) {
                    fromColumn.insertBefore(evt.item, fromColumn.children[fromIndex]);
                }
            }

            // Move to "Hired" needs confirmation
            if (targetStage === 'Hired') {
                const candidateName = evt.item.querySelector('.font-medium')?.textContent?.trim() || "this candidate";
                flashConfirm(`Are you sure you want to <b>hire</b> <span class="text-blue-700 font-semibold">${candidateName}</span> and close the job listing?<br><span class="text-xs text-gray-500">This cannot be undone.</span>`)
                    .then(ok => {
                        if (!ok) {
                            snapBack();
                            return;
                        }
                        fetch(`/applications/${appId}/hire`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        }).then(r => r.json()).then(() => location.reload());
                    });
                return; // Stop further processing until user confirms
            }

            // Move to Pool
            if (targetStage === 'pool') {
                fetch(`/applications/${appId}/stage`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ job_id: null, stage: null })
                }).then(r => r.json()).then(() => location.reload());
                return;
            }

            // Any other stage
            fetch(`/applications/${appId}/stage`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ job_id: {{ $job->id }}, stage: targetStage })
            }).then(r => r.json()).then(() => location.reload());
        }
    });
});

        // Candidate modal (universal handler)
        document.addEventListener('click', function(e) {
            let card = e.target.closest('.candidate-card');
            if (card && card.dataset.appId) {
                showAppDetailsModal(card.dataset.appId);
            }
        });
    });
    </script>
    {{-- modal js --}}
    <script>
window.currentJobId = {{ $job->id }};
</script>

<script>
function showAppDetailsModal(appId) {
    let modal = document.getElementById('candidate-modal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    let jobId = window.currentJobId; // See note above

    // Reset all fields
    document.getElementById('candidate-avatar').innerHTML = '';
    document.getElementById('candidate-resume-view').classList.add('hidden');
    document.getElementById('candidate-resume-download').classList.add('hidden');
    document.getElementById('candidate-no-resume').classList.add('hidden');
    document.getElementById('best-match-badge').classList.add('hidden');

    fetch(`/applications/${appId}/details?job_id=${jobId}`)
        .then(r => r.json())
        .then(data => {
            // Match Score
            let matchScore = (typeof data.match_score === 'undefined' ? 0 : data.match_score);
            document.getElementById('candidate-match-score').textContent = `Match: ${matchScore}%`;
            let progressBar = document.getElementById('candidate-match-progress');
            if (progressBar) {
                progressBar.style.width = `${matchScore}%`;
                if (matchScore >= 70) progressBar.style.background = 'linear-gradient(90deg, #4ade80, #16a34a)';
                else if (matchScore >= 40) progressBar.style.background = 'linear-gradient(90deg, #fbbf24, #f59e42)';
                else progressBar.style.background = 'linear-gradient(90deg, #fca5a5, #f43f5e)';
            }

            // Profile photo or initials fallback
            if (data.user_profile_photo) {
                document.getElementById('candidate-avatar').innerHTML =
                  `<img src="${data.user_profile_photo}" class="w-16 h-16 object-cover rounded-full border" alt="${data.user_name}">`;
            } else {
                document.getElementById('candidate-avatar').innerHTML =
                  `<div class="w-16 h-16 rounded-full bg-gradient-to-br from-blue-200 to-blue-400 flex items-center justify-center font-bold text-2xl text-blue-700">${data.user_initials || '?'}</div>`;
            }

            document.getElementById('candidate-name').textContent = data.user_name || `${data.first_name} ${data.last_name}`;
            document.getElementById('candidate-email').innerHTML = data.email ? `<a href="mailto:${data.email}" class="hover:underline">${data.email}</a>` : '';
            document.getElementById('candidate-phone').innerHTML = data.phone_number ? `<a href="tel:${data.phone_number}" class="hover:underline">${data.phone_number}</a>` : '';
            document.getElementById('candidate-age').textContent = data.age ? `Age: ${data.age}` : '';
            document.getElementById('candidate-status').textContent = data.status || 'new';
            document.getElementById('candidate-stage').textContent = data.stage || 'Pool';

            // Skills
            let skills = [];
            if (Array.isArray(data.skills)) {
                skills = data.skills;
            } else if (typeof data.skills === 'string') {
                skills = data.skills.split(',').map(s => s.trim()).filter(s => s.length > 0);
            }
            const skillsContainer = document.getElementById('candidate-skills');
            skillsContainer.innerHTML = '';
            if (skills.length) {
                skills.forEach(skill => {
                    const chip = document.createElement('span');
                    chip.className = "inline-block bg-blue-100 text-blue-700 px-3 py-0.5 rounded-full text-xs font-semibold";
                    chip.textContent = skill;
                    skillsContainer.appendChild(chip);
                });
            } else {
                skillsContainer.innerHTML = '<span class="text-gray-400 text-xs">None listed</span>';
            }

            document.getElementById('candidate-education').textContent = (data.education_level || '') + (data.institution ? ' @ ' + data.institution : '');
            document.getElementById('candidate-work').textContent = (data.company_name ? `${data.position} @ ${data.company_name}` : '');

            // Resume view/download
            if (data.resume) {
                const resumeURL = `/storage/${data.resume}`;
                document.getElementById('candidate-resume-view').href = resumeURL;
                document.getElementById('candidate-resume-download').href = resumeURL;
                document.getElementById('candidate-resume-view').classList.remove('hidden');
                document.getElementById('candidate-resume-download').classList.remove('hidden');
            } else {
                document.getElementById('candidate-no-resume').classList.remove('hidden');
            }

            // Dates
            if (data.created_at) {
                let createdAt = moment(data.created_at).fromNow();
                document.getElementById('candidate-created-at').textContent = createdAt;
            }
            if (data.updated_at) {
                let updatedAt = moment(data.updated_at).fromNow();
                document.getElementById('candidate-updated-at').textContent = updatedAt;
            }

            // Best Match
            if (data.isBest) document.getElementById('best-match-badge').classList.remove('hidden');
            else document.getElementById('best-match-badge').classList.add('hidden');
        });

    modal.onclick = function(e) {
        if (e.target === modal) closeCandidateModal();
    };
    document.getElementById('close-candidate-modal').onclick = closeCandidateModal;
}

// Close modal handler
function closeCandidateModal() {
    let modal = document.getElementById('candidate-modal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// Close modal on button click
document.addEventListener('DOMContentLoaded', function() {
    let btn = document.getElementById('close-candidate-modal');
    if (btn) btn.onclick = closeCandidateModal;

    // Also close on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeCandidateModal();
    });
});
</script>


{{-- bulk action js --}}
<script>
    let bulkMode = false;
let selectedBulkIds = [];


const toggleBtn = document.getElementById('toggle-bulk-mode');
const bulkLabel = document.getElementById('bulk-label');
const bulkIcon = document.getElementById('bulk-icon');
const bulkBar = document.getElementById('bulk-action-bar');
const countSpan = document.getElementById('bulk-count');

toggleBtn.onclick = function () {
    bulkMode = !bulkMode;
    document.querySelectorAll('.bulk-checkbox').forEach(cb => {
        cb.style.display = bulkMode ? '' : 'none';
        if (!bulkMode) cb.checked = false;
    });
    document.querySelectorAll('.candidate-card').forEach(card => card.classList.remove('ring-2', 'ring-blue-400', 'bg-blue-50'));
    selectedBulkIds = [];

    // Toggle styles
    toggleBtn.classList.toggle('bg-blue-600', bulkMode);
    toggleBtn.classList.toggle('text-white', bulkMode);
    toggleBtn.classList.toggle('border-blue-600', bulkMode);
    toggleBtn.classList.toggle('bg-blue-50', !bulkMode);
    toggleBtn.classList.toggle('text-blue-700', !bulkMode);
    toggleBtn.classList.toggle('border-blue-100', !bulkMode);
    bulkLabel.textContent = bulkMode ? "Bulk Mode ON" : "Bulk Select";
    // Icon: filled when on, outline when off
    bulkIcon.innerHTML = bulkMode
        ? `<rect x="4" y="4" width="16" height="16" rx="2" fill="currentColor" class="text-blue-200"/><rect x="4" y="4" width="16" height="16" rx="2" stroke="currentColor" fill="none"/>`
        : `<rect x="4" y="4" width="16" height="16" rx="2" />`;

    updateBulkBar();
};

// Checkbox select/deselect + card highlight
document.addEventListener('change', function(e) {
    if (!e.target.classList.contains('bulk-checkbox')) return;
    const id = e.target.value;
    const card = e.target.closest('.candidate-card');
    if (e.target.checked) {
        if (!selectedBulkIds.includes(id)) selectedBulkIds.push(id);
        card.classList.add('ring-2', 'ring-blue-400', 'bg-blue-50');
    } else {
        selectedBulkIds = selectedBulkIds.filter(x => x !== id);
        card.classList.remove('ring-2', 'ring-blue-400', 'bg-blue-50');
    }
    updateBulkBar();
});

// Prevent modal when bulk mode is on
document.addEventListener('click', function(e) {
    if (bulkMode && e.target.classList.contains('candidate-card')) return;
    if (!bulkMode) {
        let card = e.target.closest('.candidate-card');
        if (card && card.dataset.appId) showAppDetailsModal(card.dataset.appId);
    }
});

// Show/hide bulk action bar
function updateBulkBar() {
    if (bulkMode && selectedBulkIds.length > 0) {
        bulkBar.style.display = '';
        countSpan.textContent = `${selectedBulkIds.length} selected`;
    } else {
        bulkBar.style.display = 'none';
    }
}

// Cancel button
function disableBulkMode() {
    bulkMode = false;
    document.querySelectorAll('.bulk-checkbox').forEach(cb => {
        cb.style.display = 'none';
        cb.checked = false;
    });
    document.querySelectorAll('.candidate-card').forEach(card => card.classList.remove('ring-2', 'ring-blue-400', 'bg-blue-50'));
    selectedBulkIds = [];
    updateBulkBar();
}

// Flash confirmation (Promise-based)
function flashConfirm(message) {
    return new Promise((resolve) => {
        const modal = document.getElementById('flash-confirm-modal');
        const msg = document.getElementById('flash-confirm-msg');
        msg.innerHTML = message;
        modal.style.display = '';
        document.body.style.overflow = 'hidden'; // Prevent background scroll

        function cleanup(result) {
            modal.style.display = 'none';
            document.body.style.overflow = '';
            yesBtn.removeEventListener('click', onYes);
            cancelBtn.removeEventListener('click', onCancel);
            resolve(result);
        }

        const yesBtn = document.getElementById('flash-confirm-yes');
        const cancelBtn = document.getElementById('flash-confirm-cancel');

        function onYes() { cleanup(true); }
        function onCancel() { cleanup(false); }

        yesBtn.addEventListener('click', onYes);
        cancelBtn.addEventListener('click', onCancel);
    });
}


// Bulk move
async function bulkMoveStage(stage) {
    if (!selectedBulkIds.length) return;
    const ok = await flashConfirm(`Move ${selectedBulkIds.length} candidate${selectedBulkIds.length > 1 ? 's' : ''} to "${stage}"?`);
    if (!ok) return;

    fetch('/applications/bulk-update-stage', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            app_ids: selectedBulkIds,
            stage: stage === 'pool' ? null : stage,
            job_id: stage === 'pool' ? null : {{ $job->id }}
        })
    })
    .then(r => r.json())
    .then(() => location.reload());
}
</script>

{{-- search sort filter  --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const pool = document.getElementById('general-pool');
    if (!pool) return;

    const searchInput = document.getElementById('candidate-search');
    const sortSelect = document.getElementById('candidate-sort');

    // Capture the original order and data of all cards
    let allCards = Array.from(pool.querySelectorAll('.candidate-card')).map(card => ({
        node: card,
        name: card.querySelector('.font-medium')?.textContent.trim().toLowerCase() || '',
        email: card.querySelector('.text-xs.text-gray-400')?.textContent.trim().toLowerCase() || '',
        skills: Array.from(card.querySelectorAll('.skill-chip')).map(el => el.textContent.trim().toLowerCase()).join(' '),
        score: (() => {
            let scoreText = card.querySelector('.match-score')?.textContent || '';
            let m = scoreText.match(/(\d+)%/);
            return m ? parseInt(m[1]) : 0;
        })(),
        id: card.dataset.appId || ''
    }));

    function render(cards) {
        pool.innerHTML = '';
        cards.forEach(c => pool.appendChild(c.node));
    }

    function filterAndSort() {
        let term = (searchInput.value || '').toLowerCase().trim();
        let filtered = allCards.filter(c =>
            c.name.includes(term) ||
            c.email.includes(term) ||
            c.skills.includes(term)
        );
        // Sort
        let sort = sortSelect.value;
        if (sort === 'score-desc') {
            filtered.sort((a, b) => b.score - a.score);
        } else if (sort === 'score-asc') {
            filtered.sort((a, b) => a.score - b.score);
        } else if (sort === 'name-az') {
            filtered.sort((a, b) => a.name.localeCompare(b.name));
        } else if (sort === 'name-za') {
            filtered.sort((a, b) => b.name.localeCompare(a.name));
        }
        render(filtered);
    }

    searchInput.addEventListener('input', filterAndSort);
    sortSelect.addEventListener('change', filterAndSort);

    filterAndSort();
});
</script>

<script>
    @if($job->status === 'closed')
    // Disable drag-drop and bulk mode
    document.querySelectorAll('.stage-column, #general-pool').forEach(col => {
        col.style.pointerEvents = 'none';
        col.style.opacity = 0.6;
    });
    document.getElementById('toggle-bulk-mode').disabled = true;
@endif

</script>

</x-admin-layout>
