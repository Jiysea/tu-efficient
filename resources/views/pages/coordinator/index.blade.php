<x-app-layout>
    <x-slot name="title">
        Coordinator | TU-Efficient
    </x-slot>

    <x-slot name="favicons">
        <x-c-favicons />
    </x-slot>


    <div x-data="{ currentView: 'assignments' }" @set-current-page.window="currentView = $event.detail.pageName">
        <livewire:sidebar.coordinator-bar />

        <div x-show="currentView === 'assignments'">
            <livewire:coordinator.assignments.assignments />
        </div>

        <div x-show="currentView === 'submissions'">
            {{-- <livewire:coordinator.submissions.submissions /> --}}
        </div>

        <div x-show="currentView === 'forms'">
            {{-- <livewire:coordinator.forms.forms /> --}}
        </div>
    </div>
</x-app-layout>
