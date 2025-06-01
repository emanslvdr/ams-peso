<x-admin-layout>
    <div class="flex items-center justify-center min-h-screen py-10 bg-gray-50">
        <div class="w-full max-w-md bg-white shadow-2xl rounded-2xl p-8 relative">
            <div class="flex items-center gap-2 mb-6">
                <a href="{{ url()->previous() }}"
                   class="inline-flex items-center px-3 py-1.5 bg-blue-50 hover:bg-blue-100 rounded-lg text-blue-700 text-sm font-medium transition">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M15 19l-7-7 7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Back
                </a>
                <h1 class="text-2xl font-bold tracking-tight ml-2">Add New User</h1>
            </div>
            <p class="text-gray-500 mb-6 text-sm">Fill out the form to create a new user account.</p>
            
            <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf

                {{-- Profile Photo Preview --}}
                <div class="flex flex-col items-center gap-2">
                    <img id="photo-preview"
                         src="https://ui-avatars.com/api/?name={{ urlencode(old('name')) }}&background=F3F4F6&color=6366F1&size=128"
                         class="w-20 h-20 rounded-full object-cover border shadow mb-1"
                         alt="Profile Photo Preview">
                    <label class="block font-medium text-gray-700 text-sm">Profile Photo (optional)</label>
                    <input type="file" name="profile_photo"
                           class="w-full border px-3 py-2 rounded-lg focus:ring focus:ring-blue-200 focus:border-blue-400 text-sm"
                           accept="image/*"
                           onchange="previewPhoto(event)">
                    <div class="text-xs text-gray-400 mt-1">Max 2MB. Allowed: jpg, png, jpeg, webp, etc.</div>
                    @error('profile_photo')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-1" for="name">Full Name</label>
                    <input type="text" id="name" name="name"
                           class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition placeholder-gray-400"
                           placeholder="e.g. John Doe" value="{{ old('name') }}" required autocomplete="off">
                    @error('name')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-1" for="email">Email Address</label>
                    <input type="email" id="email" name="email"
                           class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition placeholder-gray-400"
                           placeholder="e.g. user@email.com" value="{{ old('email') }}" required autocomplete="off">
                    @error('email')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-1" for="password">Password</label>
                    <input type="password" id="password" name="password"
                           class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition placeholder-gray-400"
                           placeholder="Create a password" required autocomplete="new-password">
                    <span class="text-gray-400 text-xs">Minimum 8 characters, use strong password.</span>
                    @error('password')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-1" for="password_confirmation">Confirm Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                           class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition placeholder-gray-400"
                           placeholder="Repeat password" required autocomplete="off">
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-lg transition shadow focus:ring-2 focus:ring-blue-300 focus:outline-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M5 13l4 4L19 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Create User
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Live preview for profile photo
        function previewPhoto(event) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('photo-preview').src = e.target.result;
            }
            if (event.target.files[0]) {
                reader.readAsDataURL(event.target.files[0]);
            }
        }
        // Set default avatar if name is changed and no photo is uploaded
        document.getElementById('name').addEventListener('input', function(e) {
            if (!document.querySelector('input[type="file"]').value) {
                document.getElementById('photo-preview').src = 
                    'https://ui-avatars.com/api/?name=' + encodeURIComponent(e.target.value) + '&background=F3F4F6&color=6366F1&size=128';
            }
        });
    </script>
</x-admin-layout>
