<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit General Application') }}
        </h2>
    </x-slot>
    @php
    $skillsList = collect(
        old('skills')
            ? explode(',', old('skills'))
            : (isset($userApplication->skills) && $userApplication->skills !== null && $userApplication->skills !== ''
                ? explode(',', $userApplication->skills)
                : [])
    )->filter(function ($item) {
        return trim($item) !== '';
    })->values()->all();
    @endphp

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

                <form action="{{route('user.application.update', $userApplication)}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Personal Information -->
                    <h3 class="font-semibold text-lg text-gray-800 mb-3">Personal Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="first_name" :value="__('First Name')" />
                            <x-text-input type="text" name="first_name" id="first_name"
                                class="w-full border p-2 rounded" required
                                value="{{ old('first_name', $userApplication->first_name) }}" />
                            <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="last_name" :value="__('Last Name')" />
                            <x-text-input type="text" name="last_name" id="last_name"
                                class="w-full border p-2 rounded" required
                                value="{{ old('last_name', $userApplication->last_name) }}" />
                            <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                        </div>
                    </div>

                    <div class="mt-3">
                        <x-input-label for="email" :value="__('Email Address')" />
                        <x-text-input type="email" name="email" id="email"
                            class="w-full border p-2 rounded" required
                            value="{{ old('email', $userApplication->email) }}" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>
                    <div class="mt-3">
                        <x-input-label for="phone_number" :value="__('Phone Number')" />
                        <x-text-input type="tel" name="phone_number" id="phone_number"
                            class="w-full border p-2 rounded" required
                            value="{{ old('phone_number', $userApplication->phone_number) }}" />
                        <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
                    </div>
                    <div class="mt-3">
                        <x-input-label for="age" :value="__('Age')" />
                        <x-text-input type="number" name="age" id="age"
                            class="w-full border p-2 rounded" required min="18" max="120"
                            value="{{ old('age', $userApplication->age) }}" />
                        <x-input-error :messages="$errors->get('age')" class="mt-2" />
                    </div>

                    <!-- Education Section -->
                    <h3 class="font-semibold text-lg text-gray-800 mt-6">Education</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="education_level" :value="__('Education Level')" />
                            <select name="education_level" id="education_level" required
                                class="w-full border p-2 rounded">
                                <option value="no_formal_education" {{ old('education_level', $userApplication->education_level) == 'no_formal_education' ? 'selected' : '' }}>No Formal Education</option>
                                <option value="high_school" {{ old('education_level', $userApplication->education_level) == 'high_school' ? 'selected' : '' }}>High School</option>
                                <option value="some_college_university" {{ old('education_level', $userApplication->education_level) == 'some_college_university' ? 'selected' : '' }}>Some College/University</option>
                                <option value="others" {{ old('education_level', $userApplication->education_level) == 'others' ? 'selected' : '' }}>Others</option>
                            </select>
                        </div>
                        <div>
                            <x-input-label for="graduation_year" :value="__('Graduation Year')" />
                            <x-text-input type="number" name="graduation_year" id="graduation_year"
                                class="w-full border p-2 rounded" required
                                value="{{ old('graduation_year', $userApplication->graduation_year) }}" />
                            <x-input-error :messages="$errors->get('graduation_year')" class="mt-2" />
                        </div>
                    </div>

                    <div class="mt-3">
                        <x-input-label for="institution" :value="__('Institution Name')" />
                        <x-text-input type="text" name="institution" id="institution"
                            class="w-full border p-2 rounded" required
                            value="{{ old('institution', $userApplication->institution) }}" />
                        <x-input-error :messages="$errors->get('institution')" class="mt-2" />
                    </div>

                    <!-- Skills -->
                    <div class="mt-3">
                        <x-input-label for="skills" :value="__('Skills')" />
                        <div
                            x-data="{
                                skills: @json($skillsList),
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
                                @keydown.,.prevent="addSkill()"
                                placeholder="Type a skill and press Enter"
                                class="flex-1 outline-none border-none p-1 text-sm focus:outline-none"
                            />
                            <input type="hidden" name="skills" :value="skills.join(',')" />
                        </div>
                        <x-input-error :messages="$errors->get('skills')" class="mt-2" />
                        <p class="text-xs text-gray-400 mt-1">Press Enter, Tab, or comma to add a skill.</p>
                    </div>

                    <!-- Resume Upload -->
                    <h3 class="font-semibold text-lg text-gray-800 mt-6">Upload Resume</h3>
                    <div class="mt-3">
                        <input type="file" name="resume" id="resume" accept=".pdf,.doc,.docx"
                            class="w-full border p-2 rounded" />
                        @if (isset($userApplication->resume) && $userApplication->resume)
                            <p class="mt-2 text-sm text-gray-600">Existing Resume:
                                <a href="{{ Storage::url($userApplication->resume) }}" target="_blank" class="text-blue-600 hover:underline">Download Existing Resume</a>
                            </p>
                        @endif
                        <x-input-error :messages="$errors->get('resume')" class="mt-2" />
                    </div>

                    <!-- Submit Buttons -->
                    <div class="mt-6 flex space-x-4">
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md shadow-sm hover:bg-blue-700">
                            Update
                        </button>
                        <a href="{{ route('user.application.index') }}"
                            class="px-4 py-2 bg-gray-600 text-white rounded-md shadow-sm hover:bg-gray-700">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
