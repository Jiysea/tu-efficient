<x-slot:favicons>
    <x-f-favicons />
</x-slot>

<div x-data="{ open: true, show: false, profileShow: false, rotation: 0, caretRotate: 0, dashboardHover: false, implementationsHover: false, umanagementHover: false, alogsHover: false, isAboveBreakpoint: true }" x-init="isAboveBreakpoint = window.matchMedia('(min-width: 1280px)').matches;
window.matchMedia('(min-width: 1280px)').addEventListener('change', event => {
    isAboveBreakpoint = event.matches;
});">
    {{-- @if (session()->has('success'))
        @foreach (session('success') as $message)
            <div x-data="{ show: true }" x-init="setTimeout(() => {
                show = false;
                $wire.removeSuccessMessage('success', '{{ $loop->index }}');
            }, 2000)" x-show="show" x-transition:enter="fade-enter"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="fade-leave-active" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed left-6 bottom-6 flex items-center bg-red-300 text-red-900 rounded-lg text-sm sm:text-md font-bold px-4 py-3"
                role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="fill-current w-4 h-4 mr-2">
                    <path fill-rule="evenodd"
                        d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-7-4a1 1 0 1 1-2 0 1 1 0 0 1 2 0ZM9 9a.75.75 0 0 0 0 1.5h.253a.25.25 0 0 1 .244.304l-.459 2.066A1.75 1.75 0 0 0 10.747 15H11a.75.75 0 0 0 0-1.5h-.253a.25.25 0 0 1-.244-.304l.459-2.066A1.75 1.75 0 0 0 9.253 9H9Z"
                        clip-rule="evenodd" />
                </svg>
                <p>{{ $message }}</p>
            </div>
        @endforeach
    @endif --}}


    <livewire:sidebar.focal-bar wire:key="{{ str()->random(50) }}" />

    <div :class="{
        'xl:ml-20': open === false,
        'xl:ml-64': open === true,
    }"
        class="ml-20 xl:ml-64 duration-500 ease-in-out">

        <div class="p-2 min-h-screen select-none">

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

</div>
