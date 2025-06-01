<x-admin-layout>
<div class="max-w-xl mx-auto p-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold mb-4">Edit Organization</h2>
        <a href="{{ url()->previous() }}"
           class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-700 text-white rounded-md">
            Back
        </a>
    </div>
    <form action="{{ route('organizations.update', $organization) }}" method="POST" enctype="multipart/form-data"
          class="bg-white shadow rounded-lg p-6 space-y-5">
        @csrf
        @method('PUT')
 <!-- Name -->
    <div>
        <label class="block mb-1 font-medium">Organization Name</label>
        <input type="text" name="name" class="w-full border rounded p-2" value="{{ old('name', $organization->name ?? '') }}" required>
        @error('name') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>

    <!-- Address -->
    <div>
        <label class="block mb-1 font-medium">Address</label>
        <input type="text" name="address" class="w-full border rounded p-2" value="{{ old('address', $organization->address ?? '') }}">
        @error('address') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>

    <!-- Phone -->
    <div>
        <label class="block mb-1 font-medium">Phone</label>
        <input type="text" name="phone" class="w-full border rounded p-2" value="{{ old('phone', $organization->phone ?? '') }}">
        @error('phone') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>

    <!-- Email -->
    <div>
        <label class="block mb-1 font-medium">Email</label>
        <input type="email" name="email" class="w-full border rounded p-2" value="{{ old('email', $organization->email ?? '') }}">
        @error('email') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>

    <!-- Website -->
    <div>
        <label class="block mb-1 font-medium">Website</label>
        <input type="url" name="website" class="w-full border rounded p-2" value="{{ old('website', $organization->website ?? '') }}">
        @error('website') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>

    <!-- Description -->
    <div>
        <label class="block mb-1 font-medium">Description</label>
        <textarea name="description" class="w-full border rounded p-2" rows="3">{{ old('description', $organization->description ?? '') }}</textarea>
        @error('description') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>

    <!-- Logo -->
    <div>
        <label class="block mb-1 font-medium">Logo</label>
        <input type="file" name="logo" accept="image/*" class="w-full border rounded p-2">
        @if (!empty($organization->logo))
            <img src="{{ asset('storage/' . $organization->logo) }}" alt="Logo" class="w-20 h-20 rounded-full mt-2 object-cover">
        @endif
        @error('logo') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>

    <div class="flex justify-end">
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            {{ isset($organization) ? 'Update' : 'Create' }}
        </button>
    </div>
    </form>
</div>
</x-admin-layout>
