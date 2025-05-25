<x-admin-layout>
    <div class="max-w-4xl mx-auto">
        <h1 class="text-2xl font-semibold mb-4">Edit Job Posting</h1>

        <form action="{{ route('jobs.update', $job) }}" method="POST" class="bg-white shadow rounded-lg p-6">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Job Title</label>
                <input type="text" name="title" value="{{ old('title', $job->title) }}" required class="w-full px-3 py-2 border rounded">
            </div>

            <div>
    <label class="block mb-1 font-medium">Status</label>
    <select name="status" class="w-full border rounded p-2">
        <option value="open" {{ $job->status === 'open' ? 'selected' : '' }}>Open</option>
        <option value="closed" {{ $job->status === 'closed' ? 'selected' : '' }}>Closed</option>
        <option value="draft" {{ $job->status === 'draft' ? 'selected' : '' }}>Draft</option>
    </select>
</div>


            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" required class="w-full px-3 py-2 border rounded">{{ old('description', $job->description) }}</textarea>
            </div>

            <div class="mb-4">
    <label class="block text-sm font-medium text-gray-700">
        Required Skills <span class="text-xs text-gray-500">(comma-separated)</span>
    </label>
    <input type="text" name="skills"
        class="w-full px-3 py-2 border rounded"
        placeholder="e.g. PHP, Communication, Leadership"
        value="{{ old('skills', $job->skills) }}">
</div>


            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Organization</label>
                <select name="organization_id" required class="w-full px-3 py-2 border rounded">
                    @foreach($organizations as $org)
                        <option value="{{ $org->id }}" @if($job->organization_id == $org->id) selected @endif>
                            {{ $org->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Update Job</button>
        </form>
    </div>
</x-admin-layout>
