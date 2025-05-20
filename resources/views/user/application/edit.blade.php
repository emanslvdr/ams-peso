<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Application') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg p-6">
                <form action="{{route('user.application.update', $userApplication)}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Personal Information -->
                    <h3 class="font-semibold text-lg text-gray-800 mb-3">Personal Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                            <input type="text" name="first_name" id="first_name" value="{{ $userApplication->first_name }}" required 
                                class="mt-1 block w-full p-2 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                            <input type="text" name="last_name" id="last_name" value="{{ $userApplication->last_name }}" required 
                                class="mt-1 block w-full p-2 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>
                    <div class="mt-3">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                        <input type="email" name="email" id="email" value="{{ $userApplication->email }}" required 
                            class="mt-1 block w-full p-2 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div class="mt-3">
                        <label for="phone_number" class="block text-sm font-medium text-gray-700">Phone Number</label>
                        <input type="tel" name="phone_number" id="phone_number" value="{{ $userApplication->phone_number }}" required 
                            class="mt-1 block w-full p-2 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div class="mt-3">
                        <label for="age" class="block text-sm font-medium text-gray-700">Age</label>
                        <input type="number" name="age" id="age" value="{{ $userApplication->age }}" required min="18" max="120"
                            class="mt-1 block w-full p-2 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <!-- Education Section -->
                    <h3 class="font-semibold text-lg text-gray-800 mt-6">Education</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="education_level" class="block text-sm font-medium text-gray-700">Education Level</label>
                            <select name="education_level" id="education_level" required
                                class="mt-1 block w-full p-2 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="high_school" {{ $userApplication->education_level == 'high_school' ? 'selected' : '' }}>High School</option>
                                <option value="college" {{ $userApplication->education_level == 'college' ? 'selected' : '' }}>College</option>
                                <option value="others" {{ $userApplication->education_level == 'others' ? 'selected' : '' }}>Others</option>
                            </select>
                        </div>
                        <div>
                            <label for="graduation_year" class="block text-sm font-medium text-gray-700">Graduation Year</label>
                            <input type="number" name="graduation_year" id="graduation_year" value="{{ $userApplication->graduation_year }}" required 
                                class="mt-1 block w-full p-2 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>

                    <div class="mt-3">
                        <label for="institution" class="block text-sm font-medium text-gray-700">Institution Name</label>
                        <input type="text" name="institution" id="institution" value="{{ $userApplication->institution }}" required 
                            class="mt-1 block w-full p-2 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <!-- Resume Upload -->
                    <h3 class="font-semibold text-lg text-gray-800 mt-6">Upload Resume</h3>
                    <div class="mt-3">
                        <input type="file" name="resume" id="resume" accept=".pdf,.docx" 
                            class="mt-1 block w-full p-2 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @if (isset($userApplication->resume) && $userApplication->resume)
                            <p class="mt-2 text-sm text-gray-600">Existing Resume: <strong>{{ $userApplication->resume }}</strong></p>
                            <a href="{{ Storage::url('public/resumes/' . $userApplication->resume) }}" target="_blank" class="text-blue-600 hover:underline">Download Existing Resume</a>
                        @endif
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