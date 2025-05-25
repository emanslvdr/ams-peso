<x-admin-layout>
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold mb-4">Post new job</h2>
            <a href="{{route('jobs.index')}}" class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-700 text-white rounded-md">
                Back
            </a>
            </div>
        <form action="{{route('jobs.store')}}" method="POST" class="bg-white shadow rounded-lg p-6 space-y-4">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Job Title</label>
                <input type="text" name="title" required class="w-full px-3 py-2 border rounded">
            </div>

            <div>
    <label class="block mb-1 font-medium">Status</label>
    <select name="status" class="w-full border rounded p-2">
        <option value="open" selected>Open</option>
        <option value="closed">Closed</option>
        <option value="draft">Draft</option>
    </select>
</div>


            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" required class="w-full px-3 py-2 border rounded"></textarea>
            </div>

            <div class="mb-4">
    <label class="block text-sm font-medium text-gray-700">
        Required Skills <span class="text-xs text-gray-500">(comma-separated)</span>
    </label>
    <input type="text" name="skills"
        class="w-full px-3 py-2 border rounded"
        placeholder="e.g. PHP, Communication, Leadership"
        value="{{ old('skills') }}">
</div>


            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Organization</label>
                <select name="organization_id" required class="w-full px-3 py-2 border rounded">
                    <option value="">-- Select Organization --</option>
                    @foreach($organizations as $org)
                        <option value="{{ $org->id }}">{{ $org->name }}</option>
                    @endforeach
                </select>
            </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Post Job</button>
        </div>
        </form>
    </div>
</x-admin-layout>