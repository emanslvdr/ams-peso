<x-client-layout>
    <div class="max-w-lg mx-auto my-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl md:text-2xl font-bold">Post New Job</h2>
            <a href="{{ route('client.jobs.index') }}"
                class="inline-flex items-center px-4 py-2 border border-blue-600 text-blue-700 rounded-lg hover:bg-blue-50 font-semibold text-sm transition">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"/></svg>
                Back
            </a>
        </div>

        <form action="{{ route('client.jobs.store') }}" method="POST" class="bg-white p-8 shadow-lg rounded-xl border space-y-6">
            @csrf

            <div>
                <label class="block font-medium mb-1">Job Title</label>
                <input type="text" name="title" value="{{ old('title') }}" required
                    class="w-full border-gray-300 rounded focus:ring focus:ring-blue-100 focus:border-blue-400 p-2">
                @error('title')
                    <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label class="block font-medium mb-1">Status</label>
                <select name="status" class="w-full border-gray-300 rounded p-2 focus:ring focus:ring-blue-100 focus:border-blue-400" required>
                    <option value="open" {{ old('status')=='open' ? 'selected' : '' }}>Open</option>
                    <option value="closed" {{ old('status')=='closed' ? 'selected' : '' }}>Closed</option>
                    <option value="draft" {{ old('status')=='draft' ? 'selected' : '' }}>Draft</option>
                </select>
                @error('status')
                    <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label class="block font-medium mb-1">Description</label>
                <textarea name="description" rows="4" required
                    class="w-full border-gray-300 rounded p-2 focus:ring focus:ring-blue-100 focus:border-blue-400">{{ old('description') }}</textarea>
                @error('description')
                    <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label class="block font-medium mb-1">
                    Required Skills <span class="text-xs text-gray-400">(comma-separated)</span>
                </label>
                <input type="text" name="skills" value="{{ old('skills') }}"
                    class="w-full border-gray-300 rounded p-2 focus:ring focus:ring-blue-100 focus:border-blue-400"
                    placeholder="e.g. PHP, Communication, Leadership">
                @error('skills')
                    <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- No organization select needed! --}}

            <div>
                <button type="submit" class="w-full py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition">
                    Post Job
                </button>
            </div>
        </form>
    </div>
</x-client-layout>
