@php
    // Prefer first_name/last_name/email if available, else fallback to user
    $name = trim(($app->first_name ?? '') . ' ' . ($app->last_name ?? '')) ?: ($app->user->name ?? '—');
    $email = $app->email ?? ($app->user->email ?? '—');
    $initials = collect(explode(' ', $name))->map(fn($part) => strtoupper(substr($part,0,1)))->join('');
    $bgColors = ['bg-blue-500','bg-green-500','bg-indigo-500','bg-pink-500','bg-purple-500','bg-yellow-500','bg-red-500'];
    $bgColor = $bgColors[ord($initials[0] ?? 'A') % count($bgColors)];
    $score = isset($app->match_score) ? (int)$app->match_score : null;

    // For color-coded badge
    $scoreColor = 'bg-gray-200 text-gray-500';
    if ($score >= 80) {
        $scoreColor = 'bg-green-100 text-green-700';
    } elseif ($score >= 50) {
        $scoreColor = 'bg-blue-100 text-blue-700';
    } elseif ($score > 0) {
        $scoreColor = 'bg-yellow-100 text-yellow-700';
    }
@endphp

<div class="bg-white rounded-lg border border-gray-100 shadow-sm flex items-center gap-3 px-3 py-2 min-h-[56px] hover:shadow transition cursor-pointer candidate-card draggable js-show-details relative"
    data-id="{{ $app->id }}"
    data-app-id="{{ $app->id }}"
    data-name="{{ $name }}"
    data-email="{{ $email }}"
    data-skills="{{ strtolower($app->skills ?? '') }}"
    data-score="{{ $score ?? 0 }}"
    data-stage="{{ $app->stage ?? 'pool' }}"
    tabindex="0"
>
    <input type="checkbox"
        class="absolute left-2 top-2 bulk-checkbox w-4 h-4 accent-blue-500 z-20"
        value="{{ $app->id }}"
        style="display:none"
        onclick="event.stopPropagation();"
    />
    
    {{-- Avatar --}}
    @if(isset($app->user->profile_photo) && $app->user->profile_photo)
        <img src="{{ asset('storage/' . $app->user->profile_photo) }}"
             class="w-9 h-9 rounded-full border object-cover shrink-0" alt="{{ $name }}">
    @else
        <div class="w-9 h-9 rounded-full flex items-center justify-center {{ $bgColor }} text-white font-bold text-base shrink-0">{{ $initials }}</div>
    @endif

{{-- Main Info --}}
<div class="flex flex-col min-w-0 flex-1">
    <div class="font-medium text-gray-900 truncate text-[15px] leading-none">{{ $name }}</div>
    <div class="text-xs text-gray-400 truncate leading-tight">{{ $email }}</div>
    @if(isset($app->skills))
        @foreach(explode(',', $app->skills) as $skill)
            <span class="skill-chip hidden">{{ trim($skill) }}</span>
        @endforeach
    @endif
    @if(isset($app->match_score))
        @php
            $score = $app->match_score;
            $badgeColor = $score >= 70 ? 'bg-green-100 text-green-700'
                        : ($score >= 40 ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700');
        @endphp
        <span class="match-score px-2 py-0.5 rounded-full text-xs font-bold {{ $badgeColor }}">
            Match: {{ $score }}%
        </span>
    @endif
</div>


    {{-- Status and Time --}}
    <div class="flex flex-col items-end min-w-[54px] ml-2">
        <span class="text-xs text-gray-500">{{ ucfirst($app->status ?? 'new') }}</span>
        <span class="text-[10px] text-gray-300">{{ \Carbon\Carbon::parse($app->updated_at)->diffForHumans() }}</span>
    </div>
</div>
