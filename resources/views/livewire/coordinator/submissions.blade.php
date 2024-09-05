<x-slot:favicons>
    <x-f-favicons />
</x-slot>

<div x-cloak x-data="{ open: true, show: false, rotation: 0, caretRotate: 0, isAboveBreakpoint: true }" x-init="isAboveBreakpoint = window.matchMedia('(min-width: 1280px)').matches;
window.matchMedia('(min-width: 1280px)').addEventListener('change', event => {
    isAboveBreakpoint = event.matches;
});">
    <livewire:sidebar.coordinator-bar wire:key="{{ str()->random(50) }}" />

    <div x-data="{ scrollToTop() { document.getElementById('batches-table').scrollTo({ top: 0, behavior: 'smooth' }); } }" :class="{
        'xl:ml-20': open === false,
        'xl:ml-64': open === true,
    }"
        class="ml-20 xl:ml-64 duration-500 ease-in-out">
        <div class="p-2 min-h-screen select-none">

        </div>
    </div>
</div>
