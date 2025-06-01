<x-app-layout>
    <div x-data="jobModal()" class="max-w-5xl mx-auto px-4 py-8">
        <div class="grid gap-6 sm:grid-cols-2">
            @forelse($jobs as $job)
                <div
                    class="group bg-white shadow-md rounded-2xl p-6 flex flex-col border border-gray-100 hover:shadow-xl transition-all duration-200 relative overflow-hidden"
                >
                    {{-- Organization Logo --}}
                    <div class="flex items-center gap-3 mb-2">
                        @if(optional($job->organization)->logo)
                            <img src="{{ asset('storage/' . $job->organization->logo) }}"
                                 alt="Logo"
                                 class="w-10 h-10 rounded-full object-cover border border-gray-200 bg-gray-50 flex-shrink-0 shadow-sm" />
                        @else
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center font-bold text-lg text-blue-600 uppercase border border-gray-200">
                                {{ strtoupper(substr(optional($job->organization)->name ?? 'U', 0, 1)) }}
                            </div>
                        @endif
                        <div>
                            <div class="text-sm font-semibold text-gray-800 truncate">
                                {{ optional($job->organization)->name ?? 'Unknown Organization' }}
                            </div>
                        </div>
                    </div>
                    {{-- Job Title --}}
                    <h3 class="text-lg font-bold text-blue-800 mb-1 leading-tight">
                        {{ $job->title }}
                    </h3>
                    {{-- Short Description --}}
                    <div class="mb-3 text-gray-600 text-sm line-clamp-2">
                        {{ Str::limit($job->description, 100) }}
                    </div>
                    {{-- Skills --}}
                    @if(!empty($job->skills))
                        <div class="mb-3 flex flex-wrap gap-1">
                            @foreach(array_filter(explode(',', $job->skills)) as $skill)
                                <span class="bg-blue-50 text-blue-700 px-2 py-0.5 rounded-full text-xs border border-blue-100 font-medium">{{ trim($skill) }}</span>
                            @endforeach
                        </div>
                    @endif
                    <div class="mt-auto pt-3 flex justify-between items-center">
                        <span class="bg-green-100 text-green-800 text-xs px-2 py-0.5 rounded">{{ ucfirst($job->status) }}</span>
                        <div class="flex gap-2">
                            <!-- Modal trigger -->
                            <button
                                @click="open({
                                    title: @js($job->title),
                                    org: @js(optional($job->organization)->name ?? 'Unknown Organization'),
                                    logo: @js(optional($job->organization)->logo ? asset('storage/'.$job->organization->logo) : null),
                                    description: @js($job->description),
                                    skills: @js(array_filter(explode(',', $job->skills ?? ''))),
                                    status: @js($job->status),
                                    location: @js($job->location ?? null),
                                    salary: @js($job->salary ?? null),
                                    posted_at: @js(optional($job->created_at)->format('M d, Y'))
                                })"
                                class="px-3 py-1.5 rounded-lg bg-gray-100 text-blue-700 hover:bg-blue-100 transition"
                                title="View Details"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                            @if(in_array($job->id, $appliedJobIds))
                                <span class="inline-block px-4 py-1.5 rounded-lg bg-gray-200 text-gray-500 text-sm font-semibold cursor-not-allowed">
                                    Already Applied
                                </span>
                            @else
                                <a href="{{ route('user.application.create_for_job', $job->id)}}"
                                   class="inline-block px-4 py-1.5 rounded-lg bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 transition shadow">
                                    Apply
                                </a>
                            @endif
                        </div>
                    </div>
                    {{-- Soft background circle for design --}}
                    <span class="absolute -top-5 -right-5 w-20 h-20 bg-blue-50 opacity-30 rounded-full blur-2xl pointer-events-none"></span>
                </div>
            @empty
                <div class="col-span-2 text-center text-gray-400 py-8">
                    No jobs available at the moment.
                </div>
            @endforelse
        </div>

        <!-- Modal -->
        <div x-show="show" x-transition.opacity
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/40"
            style="display: none;">
            <div @click.away="show = false"
                 class="bg-white max-w-lg w-full rounded-2xl shadow-xl p-8 relative">
                <button @click="show = false" class="absolute top-2 right-2 text-gray-400 hover:text-red-500 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                <div class="flex items-center gap-4 mb-3">
                    <template x-if="job.logo">
                        <img :src="job.logo" class="w-12 h-12 rounded-full object-cover border" alt="Org Logo" />
                    </template>
                    <div>
                        <h3 class="text-xl font-bold text-blue-800" x-text="job.title"></h3>
                        <div class="text-sm text-gray-600" x-text="job.org"></div>
                        <div class="text-xs text-gray-400" x-text="job.location"></div>
                    </div>
                </div>
                <div class="mb-2">
                    <span class="bg-green-100 text-green-800 text-xs px-2 py-0.5 rounded" x-text="job.status"></span>
                    <span class="bg-gray-100 text-gray-700 text-xs px-2 py-0.5 rounded ml-2" x-text="job.posted_at"></span>
                </div>
                <div class="mb-3">
                    <span class="font-semibold text-gray-700">Description:</span>
                    <p class="text-gray-700 mt-1 whitespace-pre-line" x-text="job.description"></p>
                </div>
                <div class="mb-3" x-show="job.skills && job.skills.length">
                    <span class="font-semibold text-gray-700">Skills:</span>
                    <div class="flex flex-wrap gap-1 mt-1">
                        <template x-for="skill in job.skills" :key="skill">
                            <span class="bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full text-xs border border-blue-100 font-medium" x-text="skill"></span>
                        </template>
                    </div>
                </div>
                <div x-show="job.salary" class="mb-3">
                    <span class="font-semibold text-gray-700">Salary:</span>
                    <span class="ml-1 text-gray-800" x-text="job.salary"></span>
                </div>
                <div class="flex justify-end">
                    <button @click="show = false" class="px-4 py-2 bg-blue-600 text-white rounded shadow hover:bg-blue-700 transition">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- AlpineJS Modal script -->
    <script>
        function jobModal() {
            return {
                show: false,
                job: {},
                open(job) {
                    this.job = job;
                    this.show = true;
                }
            }
        }
    </script>
</x-app-layout>
