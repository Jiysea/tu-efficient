<x-app-layout>
    <x-slot name="title">
        Barangay | TU-Efficient
    </x-slot>

    <x-slot name="favicons">
        <x-b-favicons />
    </x-slot>

    <header>
        <livewire:barangay.header />
    </header>

    <main>
        <div class="grid grid-cols-7 gap-4">
            {{-- Left --}}
            <div class="grid gap-y-4 col-span-7 lg:col-span-4 lg:ml-4">
                <livewire:barangay.batch-information />

                <livewire:barangay.list-of-beneficiaries />
            </div>

            {{-- Right --}}
            <div class="bg-white col-span-7 lg:col-span-3 h-full lg:mr-4">
                <livewire:barangay.beneficiary-preview />
            </div>
        </div>
    </main>

    {{-- Modals and Shits --}}
    <livewire:barangay.submit-list-modal />
    <livewire:barangay.add-beneficiary-modal />

</x-app-layout>
