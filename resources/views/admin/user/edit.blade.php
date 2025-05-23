<x-admin-layout>
    <div class="max-w-xl mx-auto p-6">
        <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold mb-4">Edit Account</h2>
        <a href="{{route('users.index')}}" class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-700 text-white rounded-md">
            Back
        </a>
        </div>
        <form action="{{route('users.update', $user)}}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
    
            <div>
                <label class="block mb-1 font-medium">Name</label>
                <input type="text" name="name" class="w-full border rounded p-2" value="{{ old('name', $user->name) }}" required>
                @error('name') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>
    
            <div>
                <label class="block mb-1 font-medium">Email</label>
                <input type="email" name="email" class="w-full border rounded p-2" value="{{ old('email', $user->email) }}" required>
                @error('email') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>
    
            <div class="flex justify-end">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update</button>
            </div>
        </form>
    </div>
    </x-admin-layout>