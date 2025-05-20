<x-admin-layout>
<div class="max-w-xl mx-auto p-6">
    <div class="flex justify-between items-center mb-4">
    <h2 class="text-2xl font-bold mb-4">Add New Client</h2>
    <a href="{{route('clients.index')}}" class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-700 text-white rounded-md">
        Back
    </a>
    </div>
    <form action="{{ route('clients.store') }}" method="POST" class="bg-white shadow rounded-lg p-6 space-y-4">
        @csrf

        <div>
            <label class="block mb-1 font-medium">Name</label>
            <input type="text" name="name" class="w-full border rounded p-2" value="{{ old('name') }}" required>
            @error('name') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block mb-1 font-medium">Email</label>
            <input type="email" name="email" class="w-full border rounded p-2" value="{{ old('email') }}" required>
            @error('email') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block mb-1 font-medium">Password</label>
            <input type="password" name="password" class="w-full border rounded p-2" required>
            @error('password') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block mb-1 font-medium">Confirm Password</label>
            <input type="password" name="password_confirmation" class="w-full border rounded p-2" required>
        </div>

        <div>
            <label class="block mb-1 font-medium">Organization</label>
            <select name="organization_id" class="w-full border rounded p-2" required>
                <option value="">-- Select Organization --</option>
                @foreach($organizations as $org)
                    <option value="{{ $org->id }}" {{ old('organization_id') == $org->id ? 'selected' : '' }}>
                        {{ $org->name }}
                    </option>
                @endforeach
            </select>
            @error('organization_id') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Create</button>
        </div>
    </form>
</div>
</x-admin-layout>
