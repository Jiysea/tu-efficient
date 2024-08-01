<div class="lg:ml-64">

    <div class="p-2 min-h-screen">

        {{-- Nav Title and Time Dropdown --}}
        <div class="relative">
            <livewire:focal.implementations.time-dropdown />
        </div>

        <div class="relative grid grid-cols-1 w-full h-full gap-3 lg:grid-cols-3">

            {{-- List of Projects --}}
            <div class="lg:col-span-2 h-full w-full rounded bg-white shadow-sm">
                <livewire:focal.implementations.list-of-projects />
            </div>


            {{-- Batch Assignments --}}
            <div class="h-full w-full rounded bg-white shadow-sm">
                <livewire:focal.implementations.batch-assignments />
            </div>

            {{-- List of Beneficiaries by Batch --}}
            <div class="lg:col-span-2 h-full w-full rounded bg-white shadow-sm">
                <livewire:focal.implementations.list-of-beneficiaries />
            </div>

            {{-- ID Picture Preview --}}
            <div class="h-full w-full rounded bg-white shadow-sm">
                <livewire:focal.implementations.beneficiary-preview />
            </div>

        </div>
    </div>
</div>
