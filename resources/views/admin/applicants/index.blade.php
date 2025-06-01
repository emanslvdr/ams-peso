<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl md:text-2xl leading-tight">
            Applicants Management
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto p-6">

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

        <!-- Users with Applications -->
        <div class="bg-white shadow rounded-xl mb-8">
            <div class="flex justify-between items-center px-6 py-4 bg-gray-50 rounded-t-xl">
                <h2 class="text-lg font-semibold">Users with Applications</h2>
            </div>
            @if($applications->count())
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium"></th>
                            <th class="px-4 py-3 text-left font-medium">Name</th>
                            <th class="px-4 py-3 text-left font-medium">Email</th>
                            <th class="px-4 py-3 text-left font-medium">Resume</th>
                            <th class="px-4 py-3 text-left font-medium">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($applications as $application)
                        <tr class="border-t hover:bg-blue-50 transition">
                            <td class="px-4 py-2">
        <!-- Profile photo with fallback -->
        @php $user = $application->user ?? null; @endphp
        @if($user && $user->profile_photo)
            <img src="{{ Storage::url($user->profile_photo) }}" alt="Profile photo" class="w-10 h-10 rounded-full object-cover border shadow">
        @elseif($user)
            <span class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-base text-blue-600 font-bold">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </span>
        @else
            <span class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-base text-gray-500 font-bold">
                ?
            </span>
        @endif
    </td>
    <td class="px-4 py-2">{{ $application->first_name }} {{ $application->last_name }}</td>
    <td class="px-4 py-2">{{ $application->email }}</td>
                            <td class="px-4 py-2">
                                @if($application->resume)
                                    <a href="{{ asset('storage/' . $application->resume) }}" target="_blank" 
                                       class="text-blue-600 hover:underline flex items-center gap-1">
                                        <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 5v14m7-7H5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        Download
                                    </a>
                                @else
                                    <span class="text-gray-400 italic">No Resume</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 flex gap-2">
                                <button 
                                    class="flex items-center text-gray-700 hover:text-blue-600 text-xs gap-1"
                                    onclick="showApplicantModal(@js([
                                        'name' => $application->first_name . ' ' . $application->last_name,
                                        'email' => $application->email,
                                        'phone' => $application->phone ?? null,
                                        'resume' => $application->resume ? asset('storage/' . $application->resume) : null,
                                        'skills' => $application->skills ?? '',
                                        'job_title' => $application->job->title ?? null,
                                        'stage' => $application->stage ?? null,
                                        'applied_at' => $application->created_at ? \Carbon\Carbon::parse($application->created_at)->format('M d, Y') : null,
                                    ]))"    
                                    title="View Details"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="3"/>
                                        <path d="M2.05 12a10 10 0 0119.9 0 10 10 0 01-19.9 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    View
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="p-8 flex flex-col items-center text-gray-400">
                <svg class="w-12 h-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10" stroke-width="2" />
                    <path stroke-width="2" d="M9 12h6" />
                </svg>
                <span>No applicants found with applications.</span>
            </div>
            @endif
        </div>

        <!-- Users without Applications -->
        <div class="bg-white shadow rounded-xl">
            <div class="px-6 py-4 bg-gray-50 rounded-t-xl">
                <h2 class="text-lg font-semibold">Users without Applications</h2>
            </div>
            @if($usersWithoutApplications->count())
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium"></th>
                            <th class="px-4 py-3 text-left font-medium">Name</th>
                            <th class="px-4 py-3 text-left font-medium">Email</th>
                          
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($usersWithoutApplications as $user)
                        <tr class="border-t hover:bg-blue-50 transition">
                            <td class="px-4 py-2">
        @if($user->profile_photo)
            <img src="{{ Storage::url($user->profile_photo) }}" alt="Profile photo" class="w-10 h-10 rounded-full object-cover border shadow">
        @else
            <span class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-base text-blue-600 font-bold">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </span>
        @endif
    </td>
    <td class="px-4 py-2">{{ $user->name }}</td>
    <td class="px-4 py-2">{{ $user->email }}</td>
                           
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="p-8 flex flex-col items-center text-gray-400">
                <svg class="w-12 h-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10" stroke-width="2" />
                    <path stroke-width="2" d="M9 12h6" />
                </svg>
                <span>All users have applications.</span>
            </div>
            @endif
        </div>

        <!-- Applicant Details Modal -->
        <div id="applicantModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 opacity-0 invisible transition-opacity duration-300">
            <div class="bg-white rounded-xl shadow-2xl max-w-md w-full px-8 py-7 relative">
                <button onclick="closeApplicantModal()" class="absolute top-3 right-3 text-gray-400 hover:text-red-500" aria-label="Close modal">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                <h2 class="text-xl font-semibold mb-4">Applicant Details</h2>
                <div class="space-y-2 text-gray-700">
                    <div><span class="font-semibold">Name:</span> <span id="modalName"></span></div>
                    <div><span class="font-semibold">Email:</span> <span id="modalEmail"></span></div>
                    <div><span class="font-semibold">Phone:</span> <span id="modalPhone"></span></div>
                    <div>
                        <span class="font-semibold">Resume:</span>
                        <span id="modalResume"></span>
                    </div>
                    <div>
                        <span class="font-semibold">Skills:</span>
                        <span id="modalSkills"></span>
                    </div>
                    <div>
                        <span class="font-semibold">Job Applied:</span>
                        <span id="modalJobTitle"></span>
                    </div>
                    <div>
                        <span class="font-semibold">Application Stage:</span>
                        <span id="modalStage"></span>
                    </div>
                    <div>
                        <span class="font-semibold">Date Applied:</span>
                        <span id="modalAppliedAt"></span>
                    </div>
                </div>
            </div>
        </div>

    </div>

<script>
function showApplicantModal(data) {
    document.getElementById('modalName').innerText = data.name || '-';
    document.getElementById('modalEmail').innerText = data.email || '-';
    document.getElementById('modalPhone').innerText = data.phone || '-';
    let resumeSpan = document.getElementById('modalResume');
    if (data.resume) {
        resumeSpan.innerHTML = `<a href="${data.resume}" target="_blank" class="text-blue-600 hover:underline">Download Resume</a>`;
    } else {
        resumeSpan.innerHTML = `<span class="text-gray-400 italic">No Resume</span>`;
    }
    document.getElementById('modalSkills').innerText = data.skills ? data.skills : '-';
    document.getElementById('modalJobTitle').innerText = data.job_title || '-';
    document.getElementById('modalStage').innerText = data.stage || '-';
    document.getElementById('modalAppliedAt').innerText = data.applied_at || '-';

    const modal = document.getElementById('applicantModal');
    modal.classList.remove('opacity-0', 'invisible');
    modal.classList.add('opacity-100');
}
function closeApplicantModal() {
    const modal = document.getElementById('applicantModal');
    modal.classList.remove('opacity-100');
    modal.classList.add('opacity-0');
    setTimeout(() => modal.classList.add('invisible'), 200);
}
// Close modal when clicking outside content
document.getElementById('applicantModal').addEventListener('click', function(e) {
    if (e.target === this) closeApplicantModal();
});
</script>

</x-admin-layout>
