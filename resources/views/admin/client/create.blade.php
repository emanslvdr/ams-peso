<x-admin-layout>
    <div class="max-w-lg mx-auto p-8">
        <div class="flex items-center gap-2 mb-6">
            <a href="{{ url()->previous() }}"
                class="inline-flex items-center px-3 py-1.5 bg-white border border-gray-200 rounded-full shadow hover:bg-blue-50 hover:text-blue-700 focus:ring-2 focus:ring-blue-200 transition text-sm font-medium"
                aria-label="Back to Clients">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
                Back
            </a>
            <h2 class="text-2xl font-bold flex items-center gap-2 text-gray-800">
                <svg class="w-7 h-7 text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M16 21v-2a4 4 0 00-3-3.87" stroke-linecap="round" stroke-linejoin="round"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
                Add New Client (HR)
            </h2>
        </div>

        <form action="{{ route('clients.store') }}" method="POST" enctype="multipart/form-data"
      class="bg-white shadow-xl rounded-2xl p-8 space-y-5 border border-gray-100">
            @csrf

            <div>
                <label class="block mb-1 font-medium text-gray-700">Full Name</label>
                <input type="text" name="name"
                       class="w-full border px-3 py-2 rounded-lg focus:ring focus:ring-blue-200 focus:border-blue-400"
                       value="{{ old('name') }}" required autocomplete="off" placeholder="HR's full name">
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block mb-1 font-medium text-gray-700">Email Address</label>
                <input type="email" name="email"
                       class="w-full border px-3 py-2 rounded-lg focus:ring focus:ring-blue-200 focus:border-blue-400"
                       value="{{ old('email') }}" required autocomplete="off" placeholder="e.g. hr@company.com">
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block mb-1 font-medium text-gray-700">Password</label>
                <div class="relative">
                    <input type="password" name="password" id="password"
                        class="w-full border px-3 py-2 rounded-lg focus:ring focus:ring-blue-200 focus:border-blue-400 pr-10"
                        required autocomplete="off" placeholder="Choose a strong password">
                    <button type="button" tabindex="-1"
                        class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-blue-600"
                        onclick="togglePassword('password')">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-.376 1.275-.97 2.47-1.757 3.54"/>
                        </svg>
                    </button>
                </div>
                <div class="text-xs text-gray-400 mt-1">Minimum 8 characters, use strong password.</div>
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block mb-1 font-medium text-gray-700">Confirm Password</label>
                <div class="relative">
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        class="w-full border px-3 py-2 rounded-lg focus:ring focus:ring-blue-200 focus:border-blue-400 pr-10"
                        required autocomplete="off" placeholder="Repeat password">
                    <button type="button" tabindex="-1"
                        class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-blue-600"
                        onclick="togglePassword('password_confirmation')">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-.376 1.275-.97 2.47-1.757 3.54"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div>
                <label class="block mb-1 font-medium text-gray-700">Organization</label>
                <select name="organization_id"
                        class="w-full border px-3 py-2 rounded-lg focus:ring focus:ring-blue-200 focus:border-blue-400"
                        required>
                    <option value="">-- Select Organization --</option>
                    @foreach($organizations as $org)
                        <option value="{{ $org->id }}" {{ old('organization_id') == $org->id ? 'selected' : '' }}>
                            {{ $org->name }}
                        </option>
                    @endforeach
                </select>
                <div class="text-xs text-gray-400 mt-1">Which organization will this HR manage?</div>
                @error('organization_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

             <div>
        <label class="block mb-1 font-medium text-gray-700">Profile Photo (optional)</label>
        <input type="file" name="profile_photo"
               class="w-full border px-3 py-2 rounded-lg focus:ring focus:ring-blue-200 focus:border-blue-400"
               accept="image/*">
        <div class="text-xs text-gray-400 mt-1">Max 2MB. Allowed: jpg, png, jpeg, webp, etc.</div>
        @error('profile_photo')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

            <div class="flex justify-end">
                <button type="submit"
                        class="bg-blue-600 text-white px-5 py-2.5 rounded-lg hover:bg-blue-700 font-semibold shadow">
                    Create Client
                </button>
            </div>
        </form>
    </div>

    <script>
        function togglePassword(id) {
            const input = document.getElementById(id);
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    </script>
</x-admin-layout>
