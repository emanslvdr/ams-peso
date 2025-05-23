<x-admin-layout>
<div class="max-w-xl mx-auto p-6">
    <div class="flex justify-between items-center mb-4">
    <h2 class="text-2xl font-bold mb-4">Edit Organization</h2>
    <a href="{{route('organizations.index')}}" class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-700 text-white rounded-md">
        Back
    </a>
    </div>
    <form action="{{ route('organizations.update', $organization) }}" method="POST" class="space-y-4">
        @csrf @method('PUT')

        <div>
            <label class="block mb-1 font-medium">Organization Name</label>
            <input type="text" name="name" class="w-full border rounded p-2" value="{{ old('name', $organization->name) }}" required>
            @error('name') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update</button>
        </div>
    </form>
</div>
</x-admin-layout>
