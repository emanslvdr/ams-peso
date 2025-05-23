<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl md:text-2xl leading-tight">
            {{ Auth::user()->name }}
        </h2>
    </x-slot>
<div class="max-w-7xl mx-auto p-6">

    @if(session('success'))
    <div 
        x-data="{ show: true }" 
        x-init="setTimeout(() => show = false, 3000)" 
        x-show="show"
        x-transition.opacity.duration.1000ms.ease-out
        class="bg-green-100 text-green-800 p-3 mb-4 rounded shadow-lg">
        {{ session('success') }}
    </div>
@endif

<!-- Table for users who have applications -->
<div class="bg-white shadow rounded-lg transition-all duration-500 ease-out mb-6">
    <h2 class="text-lg font-semibold px-4 py-3 bg-gray-100">Users with Applications</h2>
    <table class="min-w-full text-sm">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-3 text-left">Name</th>
                <th class="px-4 py-3 text-left">Email</th>
                <th class="px-4 py-3 text-left">Resume</th>
                <th class="px-4 py-3 text-left">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($applications as $application)
                <tr class="border-t">
                    <td class="px-4 py-2">{{ $application->first_name }} {{ $application->last_name }}</td>
                    <td class="px-4 py-2">{{ $application->email }}</td>
                    <td class="px-4 py-2">
                        @if($application->resume)
                            <a href="{{ asset('storage/' . $application->resume) }}" 
                               target="_blank" 
                               class="text-blue-600 hover:underline">Download</a>
                        @else
                            <span class="text-gray-500">No Resume</span>
                        @endif
                    </td>
                    <td class="px-4 py-2 flex gap-2">
                        <a href="" class="text-blue-600 hover:underline">Edit</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Table for users without applications -->
<div class="bg-white shadow rounded-lg transition-all duration-500 ease-out">
    <h2 class="text-lg font-semibold px-4 py-3 bg-gray-100">Users without Applications</h2>
    <table class="min-w-full text-sm">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-3 text-left">Name</th>
                <th class="px-4 py-3 text-left">Email</th>
                <th class="px-4 py-3 text-left">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($usersWithoutApplications as $user)
                <tr class="border-t">
                    <td class="px-4 py-2">{{ $user->name }}</td>
                    <td class="px-4 py-2">{{ $user->email }}</td>
                    <td class="px-4 py-2 flex gap-2">
                        <a href="" class="text-blue-600 hover:underline">Invite to Apply</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
</x-admin-layout>
