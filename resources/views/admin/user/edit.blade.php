<x-admin-layout>
    <div class="max-w-xl mx-auto p-8">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold">Edit Account</h2>
            <a href="{{ url()->previous() }}" class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-700 text-white rounded-md shadow transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M15 19l-7-7 7-7" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Back
            </a>
        </div>
        <form action="{{ route('users.update', $user) }}" method="POST" enctype="multipart/form-data" class="space-y-6 bg-white rounded-xl shadow-lg p-6 border border-gray-50">
            @csrf
            @method('PUT')

            {{-- Profile photo preview and upload --}}
            <div class="flex flex-col items-center gap-2 mb-3">
                @php
                    $bgColors = ['bg-blue-500','bg-green-500','bg-indigo-500','bg-pink-500','bg-purple-500','bg-yellow-500','bg-red-500'];
                    $initials = collect(explode(' ', $user->name))->map(fn($part) => strtoupper(substr($part,0,1)))->join('');
                    $bgColor = $bgColors[ord($initials[0] ?? 'A') % count($bgColors)];
                @endphp

                @if($user->profile_photo ?? false)
                    <img src="{{ asset('storage/' . $user->profile_photo) }}"
                        class="w-20 h-20 rounded-full object-cover shadow border"
                        id="photo-preview"
                        alt="{{ $user->name }}'s photo">
                @else
                    <div id="photo-preview"
                        class="w-20 h-20 flex items-center justify-center rounded-full {{ $bgColor }} text-white font-bold text-2xl shadow border">
                        {{ $initials }}
                    </div>
                @endif
                <label class="block mt-2 font-medium text-gray-700 text-sm">Change Profile Photo</label>
                <input
                    type="file"
                    name="profile_photo"
                    class="w-full border px-3 py-2 rounded-lg focus:ring focus:ring-blue-200 focus:border-blue-400 text-sm"
                    accept="image/*"
                    onchange="previewPhoto(event)"
                >
                <div class="text-xs text-gray-400 mt-1">Leave empty to keep existing photo. Max 2MB. Allowed: jpg, png, jpeg, webp, etc.</div>
                @error('profile_photo')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block mb-1 font-medium">Name</label>
                <input type="text" name="name" class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-200 focus:border-blue-400" value="{{ old('name', $user->name) }}" required>
                @error('name') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block mb-1 font-medium">Email</label>
                <input type="email" name="email" class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-200 focus:border-blue-400" value="{{ old('email', $user->email) }}" required>
                @error('email') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 font-semibold shadow transition">
                    Update
                </button>
            </div>
        </form>
    </div>

    <script>
        function previewPhoto(event) {
            const preview = document.getElementById('photo-preview');
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    if (preview.tagName === 'IMG') {
                        preview.src = e.target.result;
                    } else {
                        // Replace div with img
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = preview.className;
                        img.id = 'photo-preview';
                        img.alt = 'Profile photo preview';
                        preview.parentNode.replaceChild(img, preview);
                    }
                };
                reader.readAsDataURL(file);
            }
        }
    </script>
</x-admin-layout>
