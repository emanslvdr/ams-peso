<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Application Form') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg p-6">
                @if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
        <strong class="font-bold">Oops! Something went wrong.</strong>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
                <form action="{{ route('user.application.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    
                    <h3 class="font-semibold text-lg text-gray-800">Personal Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="first_name" :value="__('First Name')" />
                            <x-text-input id="first_name" class="w-full border p-2 rounded" type="text" name="first_name" required autofocus />
                            <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="last_name" :value="__('Last Name')" />
                            <x-text-input id="last_name" class="w-full border p-2 rounded" type="text" name="last_name" required />
                            <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                        </div>
                    </div>
                    
                    <div>
                        <x-input-label for="email" :value="__('Email Address')" />
                        <x-text-input id="email" class="w-full border p-2 rounded" type="email" name="email" required />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>
                    
                    <div>
                        <x-input-label for="phone_number" :value="__('Phone Number')" />
                        <x-text-input id="phone_number" class="w-full border p-2 rounded" type="tel" name="phone_number" required />
                        <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
                    </div>

                    <div class="mt-1">
                        <x-input-label for="age" :value="__('Age')" />
                        <x-text-input id="age" class="w-full border p-2 rounded" type="number"
                            name="age" required />
                        <x-input-error :messages="$errors->get('age')" class="mt-2" />
                    </div>
                    
                    <h3 class="font-semibold text-lg text-gray-800">Education</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="education_level" :value="__('Education Level')" />
                            <select id="education_level" name="education_level" class="w-full border p-2 rounded" required>
                                <option value="no_formal_education">No Formal Education</option>
                                <option value="high_school">High School</option>
                                <option value="some_college_university">Some College/University</option>
                                <option value="others">Others</option>
                            </select>
                        </div>
                        <div>
                            <x-input-label for="graduation_year" :value="__('Graduation Year')" />
                            <x-text-input id="graduation_year" class="w-full border p-2 rounded" type="number" name="graduation_year" required />
                        </div>
                    </div>
                    
                    <div>
                        <x-input-label for="institution" :value="__('Institution Name')" />
                        <x-text-input id="institution" class="w-full border p-2 rounded" type="text" name="institution" required />
                    </div>
                    
                    <h3 class="font-semibold text-lg text-gray-800">Work Experience</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="company_name" :value="__('Company Name')" />
                            <x-text-input id="company_name" class="w-full border p-2 rounded" type="text" name="company_name" />
                        </div>
                        <div>
                            <x-input-label for="position" :value="__('Job Position')" />
                            <x-text-input id="position" class="w-full border p-2 rounded" type="text" name="position" />
                        </div>
                        <div class="row">
                            <div class="col mt-1">
                                <x-input-label for="start_date" :value="__('Start Date')" />
                                <x-text-input class="w-full border p-2 rounded" type="date"
                                    name="start_date" />
                            </div>
                            <div class="col mt-1">
                                <x-input-label for="end_date" :value="__('End Date')" />
                                <x-text-input class="w-full border p-2 rounded" type="date"
                                    name="end_date" />
                            </div>
                        </div>
                    </div>
                    
                    {{-- <div>
                        <x-input-label for="job_description" :value="__('Job Description')" />
                        <textarea id="job_description" name="job_description" class="w-full border p-2 rounded h-24"></textarea>
                    </div> --}}
                    
                    <h3 class="font-semibold text-lg text-gray-800">Upload Resume</h3>
                    <div>
                        <input type="file" id="resume" name="resume" class="w-full border p-2 rounded" accept=".pdf,.doc,.docx" required />
                        <p class="text-sm text-gray-500 mt-1">Accepted formats: PDF, DOC, DOCX. Max size: 2MB.</p>
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