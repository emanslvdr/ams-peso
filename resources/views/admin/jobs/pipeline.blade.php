<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl md:text-2xl leading-tight">
            {{ $job->title }} – Candidate Pipeline
        </h2>
    </x-slot>

    <div class="p-6 max-w-7xl mx-auto space-y-8">

        {{-- 🔷 Pipeline Columns --}}
        <div class="overflow-x-auto">
            <div class="flex space-x-4 min-w-full" id="kanban-board">
                @foreach ($stages as $stage)
                    <div class="flex-1 min-w-[250px] bg-gray-50 border rounded-lg shadow-sm p-4">
                        <h3 class="text-md font-semibold mb-3 text-gray-700">{{ $stage }}</h3>

                        <div class="space-y-3 min-h-[150px] stage-column" 
                            id="stage-{{ Str::slug($stage) }}" 
                            data-stage="{{ $stage }}">
                            @foreach ($applications->get($stage, []) as $app)
                                <div class="bg-white rounded-lg p-4 shadow border border-gray-200 draggable" 
                                    draggable="true" 
                                    data-id="{{ $app->id }}">
                                    <div class="font-medium">{{ $app->user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $app->user->email }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- 🔻 Unassigned Candidate Pool --}}
        <div class="bg-white border shadow rounded-lg p-4">
            <h2 class="text-lg font-semibold text-gray-800 mb-3">Candidate Pool</h2>

            <div class="flex flex-wrap gap-4" id="unassigned-pool">
                @foreach ($unassigned as $app)
                    <div class="bg-gray-100 p-4 rounded shadow w-64 draggable"
                         draggable="true"
                         data-id="{{ $app->id }}">
                        <div class="font-medium text-gray-800">{{ $app->user->name }}</div>
                        <div class="text-sm text-gray-500">{{ $app->user->email }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- SortableJS CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Enable drag for each pipeline column
            document.querySelectorAll('.stage-column').forEach(column => {
                new Sortable(column, {
                    group: 'shared',
                    animation: 150,
                    onAdd: (evt) => {
                        const appId = evt.item.dataset.id;
                        const newStage = evt.to.dataset.stage;

                        // Update stage in backend
                        fetch(`/applications/${appId}/stage`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ stage: newStage })
                        });
                    }
                });
            });

            // Enable drag from pool to pipeline
            new Sortable(document.getElementById('unassigned-pool'), {
                group: 'shared',
                animation: 150
            });
        });
    </script>
</x-admin-layout>
