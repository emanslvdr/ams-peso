<x-admin-layout>
    <div class="max-w-xl mx-auto p-6">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-2xl font-bold">Edit Client</h2>
                <div class="text-gray-500 text-sm mt-1">Update client/HR info & organization.</div>
            </div>
            <a href="{{ url()->previous() }}"
               class="inline-flex items-center px-4 py-2 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition group">
                <svg class="w-4 h-4 mr-1 group-hover:-translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M15 19l-7-7 7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Back
            </a>
        </div>

        <form action="{{ route('clients.update', $client) }}" method="POST"
              enctype="multipart/form-data"
              class="bg-white shadow-lg rounded-xl p-6 space-y-5 border border-gray-50">
            @csrf
            @method('PUT')

            {{-- Profile photo preview --}}
            <div class="flex flex-col items-center gap-2">
                <img
                    src="{{ $client->profile_photo
                        ? asset('storage/' . $client->profile_photo)
                        : 'https://ui-avatars.com/api/?name='.urlencode($client->name).'&background=F3F4F6&color=374151&size=128' }}"
                    class="w-20 h-20 rounded-full object-cover shadow border"
                    id="photo-preview"
                    alt="Profile photo preview"
                >
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
                <label class="block mb-1 font-medium text-gray-700">Name</label>
                <input type="text" name="name" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-200"
                       value="{{ old('name', $client->name) }}" required>
                @error('name') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block mb-1 font-medium text-gray-700">Email</label>
                <input type="email" name="email" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-200"
                       value="{{ old('email', $client->email) }}" required>
                @error('email') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block mb-1 font-medium text-gray-700">Organization</label>
                <select name="organization_id"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-200" required>
                    <option value="">-- Select Organization --</option>
                    @foreach($organizations as $org)
                        <option value="{{ $org->id }}" {{ $client->organization_id == $org->id ? 'selected' : '' }}>
                            {{ $org->name }}
                        </option>
                    @endforeach
                </select>
                @error('organization_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex justify-end mt-6">
                <button type="submit"
                        class="flex items-center gap-2 bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 font-semibold shadow transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M5 13l4 4L19 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Update Client
                </button>
            </div>
        </form>
    </div>

    <script>
        function previewPhoto(event) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('photo-preview').src = e.target.result;
            }
            if (event.target.files[0]) {
                reader.readAsDataURL(event.target.files[0]);
            }
        }
    </script>
</x-admin-layout>
