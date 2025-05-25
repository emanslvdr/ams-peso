<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg md:text-xl">
            {{ $organization->name }} – Job Listings
        </h2>
    </x-slot>

    <div class="max-w-3xl mx-auto p-4">
         <!-- Back Button as Icon+Text, Separated Row -->
    <div class="flex items-center gap-2 mb-2">
        <a href="{{ route('organizations.index') }}"
           class="inline-flex items-center px-3 py-1.5 bg-white border border-gray-200 rounded-full shadow hover:bg-blue-50 hover:text-blue-700 focus:ring-2 focus:ring-blue-200 transition text-sm font-medium"
           aria-label="Back to Organizations">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
            Back
        </a>
    </div>

    <!-- Organization Profile Card -->
        <div class="flex flex-col md:flex-row md:items-center gap-4 bg-white rounded-xl shadow-sm p-6 mb-8 border border-gray-100">
            <div class="flex-shrink-0">
                @if ($organization->logo)
                    <img src="{{ asset('storage/' . $organization->logo) }}"
                        alt="Logo"
                        class="w-16 h-16 rounded-full object-cover border border-gray-200 bg-gray-50 shadow" />
                @else
                    <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center font-bold text-2xl text-blue-600 uppercase border border-gray-200">
                        {{ strtoupper(substr($organization->name, 0, 1)) }}
                    </div>
                @endif
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex flex-col md:flex-row md:items-center md:gap-4">
                    <div class="font-bold text-xl text-gray-900 truncate">
                        {{ $organization->name }}
                    </div>
                    <div class="mt-1 md:mt-0 text-xs bg-gray-100 text-gray-600 rounded-full px-3 py-0.5 font-medium w-max">
                        {{ $organization->jobs->count() }} Job{{ $organization->jobs->count() === 1 ? '' : 's' }}
                    </div>
                </div>
                @if ($organization->description)
                    <div class="text-gray-500 text-sm mt-1 line-clamp-2">
                        {{ $organization->description }}
                    </div>
                @endif
                <div class="flex flex-col gap-0.5 text-sm text-gray-500 mt-2">
                    @if($organization->address)
                        <span class="flex items-center">
                            <svg class="inline w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c1.38 0 2.5-1.12 2.5-2.5S13.38 6 12 6 9.5 7.12 9.5 8.5 10.62 11 12 11z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21c-4.97-4.8-8-7.83-8-11.5A8.5 8.5 0 0112 1a8.5 8.5 0 018 8.5c0 3.67-3.03 6.7-8 11.5z"/>
                            </svg>
                            {{ $organization->address }}
                        </span>
                    @endif
                    @if($organization->phone)
                        <span class="flex items-center">
                            <svg class="inline w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a2 2 0 011.64.89l1.1 1.64a2 2 0 01-.45 2.68l-.87.66a11.06 11.06 0 005.34 5.34l.66-.87a2 2 0 012.68-.45l1.64 1.1A2 2 0 0121 17.72V20a2 2 0 01-2 2h-1C9.61 22 2 14.39 2 6V5z"/>
                            </svg>
                            {{ $organization->phone }}
                        </span>
                    @endif
                    @if($organization->email)
                        <span class="flex items-center">
                            <svg class="inline w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12l-4 4m0 0l-4-4m4 4V8"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12a8 8 0 10-16 0 8 8 0 0016 0z"/>
                            </svg>
                            {{ $organization->email }}
                        </span>
                    @endif
                    @if($organization->website)
                        <span class="flex items-center">
                            <svg class="inline w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 17v.01M12 21c-4.418 0-8-3.582-8-8s3.582-8 8-8 8 3.582 8 8-3.582 8-8 8zm0 0c1.657 0 3-3.582 3-8s-1.343-8-3-8-3 3.582-3 8 1.343 8 3 8z"/>
                            </svg>
                            <a href="{{ $organization->website }}" target="_blank" class="underline hover:text-blue-700 break-all">{{ $organization->website }}</a>
                        </span>
                    @endif
                </div>
            </div>
        </div>
<div class="mb-8">
    <!-- Header Row: Title, Search, Back -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4">
        <h1 class="text-lg font-bold text-gray-800 flex-shrink-0">Jobs at {{ $organization->name }}</h1>
        <div class="flex items-center gap-2 w-full md:w-auto">
    <input
        id="job-search"
        type="text"
        class="border px-3 py-2 rounded w-full max-w-xs text-sm"
        placeholder="Search jobs..."
        oninput="filterJobList()"
    />
    <button
        onclick="clearJobSearch()"
        class="text-xs text-gray-500 hover:text-blue-600 px-2 py-1 rounded transition hidden"
        id="clear-search-btn"
    >
        Clear
    </button>
    <select
        id="job-sort"
        class="border px-2 py-2 rounded text-sm"
        onchange="filterJobList()"
    >
        <option value="latest">Latest</option>
        <option value="oldest">Oldest</option>
        <option value="title-asc">Title A-Z</option>
        <option value="title-desc">Title Z-A</option>
    </select>
</div>
  
    </div>

    <!-- Empty State -->
    @if($jobs->isEmpty())
        <div class="text-gray-400 flex flex-col items-center justify-center py-8 border rounded bg-gray-50">
            <svg class="w-12 h-12 mb-2 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10" stroke-width="2" />
                <path stroke-width="2" d="M9 12h6" />
            </svg>
            <span>No job listings found.</span>
        </div>
    @else
        <!-- Jobs List -->
        <div id="job-list" class="grid gap-2">
            @foreach($jobs as $job)
                <a href="{{ route('jobs.pipeline', $job) }}"
                   class="group flex items-center justify-between px-4 py-3 bg-white border rounded-lg hover:bg-blue-50 transition job-item"
                    data-title="{{ strtolower($job->title) }}"
                    data-date="{{ $job->created_at ? $job->created_at->timestamp : '' }}">
                    <div>
                        <h3 class="text-base font-medium text-gray-900 flex items-center gap-2 truncate">
                        {{ $job->title }}
                {{-- Status Badge --}}
                @if ($job->status === 'open')
                    <span class="ml-2 px-2 py-0.5 rounded-full bg-green-100 text-green-700 text-xs font-semibold">Open</span>
                @elseif ($job->status === 'closed')
                    <span class="ml-2 px-2 py-0.5 rounded-full bg-red-100 text-red-700 text-xs font-semibold">Closed</span>
                @elseif ($job->status === 'draft')
                    <span class="ml-2 px-2 py-0.5 rounded-full bg-gray-200 text-gray-600 text-xs font-semibold">Draft</span>
                @endif
            </h3>
                        <span class="block text-xs text-gray-400 mt-0.5">
                            Created
                            @if($job->created_at)
                                {{ $job->created_at->format('M d, Y') }}
                            @else
                                <span class="italic text-gray-300">Unknown</span>
                            @endif
                        </span>
                    </div>
                    <svg class="w-5 h-5 text-gray-300 group-hover:text-blue-500 ml-3 flex-shrink-0"
                         fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            @endforeach
        </div>
    @endif
</div>

<script>
function filterJobList() {
    const search = document.getElementById('job-search').value.trim().toLowerCase();
    const sort = document.getElementById('job-sort').value;
    const items = Array.from(document.querySelectorAll('.job-item'));

    // Filter
    items.forEach(item => {
        const title = item.getAttribute('data-title');
        item.style.display = title.includes(search) ? '' : 'none';
    });

    // Sort
    let sorted = items.slice();
    if (sort === 'latest') {
        sorted.sort((a, b) => {
            return (parseInt(b.dataset.date || 0)) - (parseInt(a.dataset.date || 0));
        });
    } else if (sort === 'oldest') {
        sorted.sort((a, b) => {
            return (parseInt(a.dataset.date || 0)) - (parseInt(b.dataset.date || 0));
        });
    } else if (sort === 'title-asc') {
        sorted.sort((a, b) => a.dataset.title.localeCompare(b.dataset.title));
    } else if (sort === 'title-desc') {
        sorted.sort((a, b) => b.dataset.title.localeCompare(a.dataset.title));
    }

    // Render in new order
    const list = document.getElementById('job-list');
    sorted.forEach(item => list.appendChild(item));
}

function clearJobSearch() {
    document.getElementById('job-search').value = '';
    filterJobList();
}

// Auto-show clear button for search
document.getElementById('job-search').addEventListener('input', function () {
    const btn = document.getElementById('clear-search-btn');
    btn.classList.toggle('hidden', !this.value.length);
});

// Initial sort/filter on page load
document.addEventListener('DOMContentLoaded', () => {
    filterJobList();
});
</script>


</x-admin-layout>
