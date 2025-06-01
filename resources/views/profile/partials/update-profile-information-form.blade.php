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

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

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
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)"
                required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <!-- Email -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)"
                required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}
                        <button form="send-verification"
                            class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>
                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>
            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600">{{ __('Saved.') }}</p>
            @endif
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
