<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @method('PATCH')

    <!-- Profile Photo Upload -->
    <div class="flex items-center gap-4 mb-4">
        @if($user->profile_photo)
            <img src="{{ Storage::url($user->profile_photo) }}" alt="Profile photo" class="w-20 h-20 rounded-full object-cover border shadow" id="photoPreview">
        @else
            <span class="w-20 h-20 rounded-full bg-blue-100 flex items-center justify-center text-2xl text-blue-600 font-bold" id="photoPreview">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </span>
        @endif
        <div>
            <label class="block">
                <span class="text-sm text-gray-600">Change Photo</span>
                <input type="file" name="profile_photo" accept="image/*" class="block w-full text-sm mt-1" />
            </label>
            @if($user->profile_photo)
                <label class="flex items-center mt-2 gap-2 text-red-500 cursor-pointer">
                    <input type="checkbox" name="remove_profile_photo" value="1" class="accent-red-500">
                    Remove Photo
                </label>
            @endif
            @error('profile_photo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>
    </div>

    <!-- Name -->
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
        <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
        @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
    </div>

    <!-- Email -->
    <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
        <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
        @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
    </div>

    <div>
        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
            Save
        </button>
    </div>
</form>
<script>
    document.querySelector('input[name="profile_photo"]').addEventListener('change', function(e) {
        if (e.target.files && e.target.files[0]) {
            const reader = new FileReader();
            reader.onload = (e) => document.getElementById('photoPreview').src = e.target.result;
            reader.readAsDataURL(e.target.files[0]);
        }
    });
</script>

</section>
