<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl md:text-2xl leading-tight">
            {{ $organization->name }} – Job Listings
        </h2>
    </x-slot>

    <div class="max-w-5xl mx-auto p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Jobs at {{ $organization->name }}</h1>
            <a href="{{ route('organizations.index') }}" class="text-sm text-blue-600 hover:underline">← Back to Organizations</a>
        </div>

        @if($jobs->isEmpty())
            <div class="text-gray-600 text-center py-12 border rounded bg-white shadow-sm">
                No job listings found for this organization.
            </div>
        @else
            <div class="grid md:grid-cols-2 gap-4">
                @foreach($jobs as $job)
                    <a href="{{ route('jobs.pipeline', $job) }}" class="block p-5 bg-white rounded-xl shadow hover:shadow-md transition duration-200 border hover:border-blue-500">
                        <h3 class="text-lg font-semibold text-gray-800">{{ $job->title }}</h3>
                        {{-- <p class="text-sm text-gray-500 mt-1">Posted on {{ $job->created_at->format('M d, Y') }}</p> --}}
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</x-admin-layout>
