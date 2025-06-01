@props(['job'])

<div x-data="{ open: false, status: '{{ $job->status }}' }" class="relative inline-block">
    <button @click="open = !open"
            type="button"
            class="px-2 py-0.5 rounded-full text-xs font-semibold capitalize focus:outline-none focus:ring-2 focus:ring-blue-200
                transition
                {{ $job->status === 'open' ? 'bg-green-100 text-green-700' :
                   ($job->status === 'closed' ? 'bg-red-100 text-red-700' : 'bg-gray-200 text-gray-600') }}">
        <span x-text="status"></span>
        <svg class="w-3 h-3 ml-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path d="M19 9l-7 7-7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </button>
    <div x-show="open"
         @click.away="open = false"
         class="absolute mt-1 left-0 w-28 bg-white border rounded shadow z-20"
         x-cloak>
        <button type="button" class="block w-full text-left px-3 py-1.5 text-xs hover:bg-green-50"
                :class="{ 'font-bold': status === 'open' }"
                @click="status = 'open'; open = false; $dispatch('changestatus', { id: {{ $job->id }}, status: 'open' })">
            Open
        </button>
        <button type="button" class="block w-full text-left px-3 py-1.5 text-xs hover:bg-red-50"
                :class="{ 'font-bold': status === 'closed' }"
                @click="status = 'closed'; open = false; $dispatch('changestatus', { id: {{ $job->id }}, status: 'closed' })">
            Closed
        </button>
        <button type="button" class="block w-full text-left px-3 py-1.5 text-xs hover:bg-gray-100"
                :class="{ 'font-bold': status === 'draft' }"
                @click="status = 'draft'; open = false; $dispatch('changestatus', { id: {{ $job->id }}, status: 'draft' })">
            Draft
        </button>
    </div>
</div>
