@php
    $color = match(strtolower($status ?? '')) {
        'open' => 'bg-green-200 text-green-800',
        'closed' => 'bg-gray-300 text-gray-700',
        'draft' => 'bg-yellow-200 text-yellow-900',
        default => 'bg-gray-100 text-gray-700',
    };
@endphp
<span class="job-status-badge px-4 py-1 rounded-full font-bold text-base {{ $color }}">
    {{ ucfirst($status ?? 'Unknown') }}
</span>
