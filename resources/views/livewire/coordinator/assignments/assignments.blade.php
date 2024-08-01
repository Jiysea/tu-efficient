<div class="lg:ml-64">
    <div class="p-2 min-h-screen">
        <div class="relative grid w-full gap-x-2 lg:grid-cols-3">
            {{-- Title --}}
            <div class="relative lg:col-span-3 text-2xl font-bold py-2 px-4">
                <p>Assignments</p>
            </div>

            <div class="relative lg:col-span-2">
                {{-- List of Assignments --}}
                <div class="flex items-center justify-start w-full mb-2 h-auto rounded">

                    <livewire:coordinator.assignments.list-of-assignments />

                </div>
            </div>

            {{-- List Overview --}}
            <div class="relative w-full">
                <livewire:coordinator.assignments.list-overview />
            </div>
        </div>
    </div>
</div>
