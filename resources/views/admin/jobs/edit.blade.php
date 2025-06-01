<x-admin-layout>
    <div class="max-w-lg mx-auto my-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl md:text-2xl font-bold">Edit Job Posting</h2>
            <a href="{{ url()->previous() }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded hover:bg-blue-50 text-gray-700 text-sm">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"/></svg>
                Back
            </a>
        </div>

        <form action="{{ route('jobs.update', $job) }}" method="POST" class="bg-white p-8 shadow-lg rounded-xl border space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block font-medium mb-1">Job Title</label>
                <input type="text" name="title" value="{{ old('title', $job->title) }}" required
                    class="w-full border-gray-300 rounded focus:ring focus:ring-blue-100 focus:border-blue-400 p-2">
                @error('title')
                    <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label class="block font-medium mb-1">Status</label>
                <select name="status" class="w-full border-gray-300 rounded p-2 focus:ring focus:ring-blue-100 focus:border-blue-400" required>
                    <option value="open" {{ old('status', $job->status) == 'open' ? 'selected' : '' }}>Open</option>
                    <option value="closed" {{ old('status', $job->status) == 'closed' ? 'selected' : '' }}>Closed</option>
                    <option value="draft" {{ old('status', $job->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                </select>
                @error('status')
                    <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label class="block font-medium mb-1">Description</label>
                <textarea name="description" rows="4" required
                    class="w-full border-gray-300 rounded p-2 focus:ring focus:ring-blue-100 focus:border-blue-400">{{ old('description', $job->description) }}</textarea>
                @error('description')
                    <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label class="block font-medium mb-1">
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

            <div>
                <label class="block font-medium mb-1">Organization</label>
                <select name="organization_id" required
                    class="w-full border-gray-300 rounded p-2 focus:ring focus:ring-blue-100 focus:border-blue-400">
                    @foreach($organizations as $org)
                        <option value="{{ $org->id }}"
                            {{ old('organization_id', $job->organization_id) == $org->id ? 'selected' : '' }}>
                            {{ $org->name }}
                        </option>
                    @endforeach
                </select>
                @error('organization_id')
                    <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <button type="submit"
                    class="w-full py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition">
                    Update Job
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>
