<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Apply for Job') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg p-6">
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                        <strong class="font-bold">Oops! Something went wrong.</strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(isset($job))
                <div class="mb-6 bg-blue-50 border border-blue-100 rounded p-4 flex items-center gap-3">
                    @if(optional($job->organization)->logo)
                        <img src="{{ asset('storage/'.$job->organization->logo) }}" class="w-10 h-10 rounded-full border" alt="Logo">
                    @endif
                    <div>
                        <div class="font-semibold text-blue-900">{{ $job->title }}</div>
                        <div class="text-xs text-gray-500">{{ optional($job->organization)->name }}</div>
                    </div>
                </div>
                @endif

                <form action="{{ route('user.application.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <input type="hidden" name="job_id" value="{{ $job->id }}">

                    {{-- Prefill logic --}}
                    @php
                        $prefill = $generalApplication ?? null;
                        $skillsPrefill = old('skills') ? explode(',', old('skills')) : ($prefill ? explode(',', $prefill->skills) : []);
                    @endphp

                    <h3 class="font-semibold text-lg text-gray-800">Personal Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="first_name" :value="__('First Name')" />
                            <x-text-input id="first_name" class="w-full border p-2 rounded"
                                type="text" name="first_name" required autofocus
                                value="{{ old('first_name', $prefill->first_name ?? '') }}" />
                        </div>
                        <div>
                            <x-input-label for="last_name" :value="__('Last Name')" />
                            <x-text-input id="last_name" class="w-full border p-2 rounded"
                                type="text" name="last_name" required
                                value="{{ old('last_name', $prefill->last_name ?? '') }}" />
                        </div>
                    </div>
                    <div>
                        <x-input-label for="email" :value="__('Email Address')" />
                        <x-text-input id="email" class="w-full border p-2 rounded"
                            type="email" name="email" required
                            value="{{ old('email', $prefill->email ?? '') }}" />
                    </div>
                    <div>
                        <x-input-label for="phone_number" :value="__('Phone Number')" />
                        <x-text-input id="phone_number" class="w-full border p-2 rounded"
                            type="tel" name="phone_number" required
                            value="{{ old('phone_number', $prefill->phone_number ?? '') }}" />
                    </div>
                    <div class="mt-1">
                        <x-input-label for="age" :value="__('Age')" />
                        <x-text-input id="age" class="w-full border p-2 rounded"
                            type="number" name="age" required
                            value="{{ old('age', $prefill->age ?? '') }}" />
                    </div>

                    <h3 class="font-semibold text-lg text-gray-800">Education</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="education_level" :value="__('Education Level')" />
                            <select id="education_level" name="education_level" class="w-full border p-2 rounded" required>
                                <option value="no_formal_education" {{ old('education_level', $prefill->education_level ?? '') == 'no_formal_education' ? 'selected' : '' }}>No Formal Education</option>
                                <option value="high_school" {{ old('education_level', $prefill->education_level ?? '') == 'high_school' ? 'selected' : '' }}>High School</option>
                                <option value="some_college_university" {{ old('education_level', $prefill->education_level ?? '') == 'some_college_university' ? 'selected' : '' }}>Some College/University</option>
                                <option value="others" {{ old('education_level', $prefill->education_level ?? '') == 'others' ? 'selected' : '' }}>Others</option>
                            </select>
                        </div>
                        <div>
                            <x-input-label for="graduation_year" :value="__('Graduation Year')" />
                            <x-text-input id="graduation_year" class="w-full border p-2 rounded"
                                type="number" name="graduation_year" required
                                value="{{ old('graduation_year', $prefill->graduation_year ?? '') }}" />
                        </div>
                    </div>
                    <div>
                        <x-input-label for="institution" :value="__('Institution Name')" />
                        <x-text-input id="institution" class="w-full border p-2 rounded"
                            type="text" name="institution" required
                            value="{{ old('institution', $prefill->institution ?? '') }}" />
                    </div>

                    <!-- Skills (AlpineJS, with prefill) -->
                  <div 
    x-data="{
        skills: [],
        skillInput: '',
        addSkill() {
            let s = this.skillInput.trim();
            if (s && !this.skills.includes(s)) {
                this.skills.push(s);
            }
            this.skillInput = '';
        },
        removeSkill(idx) {
            this.skills.splice(idx, 1);
        }
    }"
    class="w-full border rounded p-2 flex flex-wrap gap-2 bg-white"
>
    <template x-for="(skill, idx) in skills" :key="idx">
        <span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full flex items-center text-xs">
            <span x-text="skill"></span>
            <button type="button" class="ml-1" @click="removeSkill(idx)">&times;</button>
        </span>
    </template>
    <input
        x-ref="input"
        type="text"
        x-model="skillInput"
        @keydown.enter.prevent="addSkill()"
        @keydown.tab.prevent="addSkill()"
        @keydown.comma.prevent="addSkill()"
        placeholder="Type a skill and press Enter"
        class="flex-1 outline-none border-none p-1 text-sm focus:outline-none"
    />
    <input type="hidden" name="skills" :value="skills.join(',')" />
</div>



                    <h3 class="font-semibold text-lg text-gray-800">Work Experience</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="company_name" :value="__('Company Name')" />
                            <x-text-input id="company_name" class="w-full border p-2 rounded"
                                type="text" name="company_name"
                                value="{{ old('company_name', $prefill->company_name ?? '') }}" />
                        </div>
                        <div>
                            <x-input-label for="position" :value="__('Job Position')" />
                            <x-text-input id="position" class="w-full border p-2 rounded"
                                type="text" name="position"
                                value="{{ old('position', $prefill->position ?? '') }}" />
                        </div>
                        <div class="row">
                            <div class="col mt-1">
                                <x-input-label for="start_date" :value="__('Start Date')" />
                                <x-text-input class="w-full border p-2 rounded"
                                    type="date" name="start_date"
                                    value="{{ old('start_date', $prefill->start_date ?? '') }}" />
                            </div>
                            <div class="col mt-1">
                                <x-input-label for="end_date" :value="__('End Date')" />
                                <x-text-input class="w-full border p-2 rounded"
                                    type="date" name="end_date"
                                    value="{{ old('end_date', $prefill->end_date ?? '') }}" />
                            </div>
                        </div>
                    </div>

                    <h3 class="font-semibold text-lg text-gray-800">Upload Resume</h3>
                    <div>
                        <input type="file" id="resume" name="resume" class="w-full border p-2 rounded" accept=".pdf,.doc,.docx" required />
                        <p class="text-sm text-gray-500 mt-1">Accepted formats: PDF, DOC, DOCX. Max size: 5MB.</p>
                    </div>

                    <div class="flex justify-end gap-2">
                        <a href="{{ route('user.application.index') }}" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
