<x-client-layout>
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-semibold">Edit Job Posting</h1>
            <a href="{{ route('client.jobs.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-700 text-white rounded-md">
                Back
            </a>
        </div>
        <form action="{{ route('client.jobs.update', $job) }}" method="POST" class="bg-white shadow rounded-lg p-6 space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Job Title</label>
                <input type="text" name="title" value="{{ old('title', $job->title) }}" required
                       class="w-full px-3 py-2 border rounded focus:ring focus:ring-blue-100 focus:border-blue-400">
                @error('title')
                    <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status"
                        class="w-full border-gray-300 rounded p-2 focus:ring focus:ring-blue-100 focus:border-blue-400" required>
                    <option value="open" {{ old('status', $job->status) == 'open' ? 'selected' : '' }}>Open</option>
                    <option value="closed" {{ old('status', $job->status) == 'closed' ? 'selected' : '' }}>Closed</option>
                    <option value="draft" {{ old('status', $job->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                </select>
                @error('status')
                    <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="4" required
                          class="w-full border-gray-300 rounded p-2 focus:ring focus:ring-blue-100 focus:border-blue-400">{{ old('description', $job->description) }}</textarea>
                @error('description')
                    <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Required Skills <span class="text-xs text-gray-400">(comma-separated)</span>
                </label>
                <input type="text" name="skills"
                       class="w-full border-gray-300 rounded p-2 focus:ring focus:ring-blue-100 focus:border-blue-400"
                       placeholder="e.g. PHP, Communication, Leadership"
                       value="{{ old('skills', $job->skills) }}">
                @error('skills')
                    <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- Organization field is NOT shown for client! --}}

            <div>
                <button type="submit" class="w-full py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition">
                    Update Job
                </button>
            </div>
        </form>
    </div>
</x-client-layout>
