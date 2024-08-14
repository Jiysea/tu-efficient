<x-app-layout>
    <x-slot name="title">
        Focal | TU-Efficient
    </x-slot>

    <x-slot name="favicons">
        <x-f-favicons />
    </x-slot>

    <style>
        /* Small screens */
        @media (max-width: 639px) {
            .apexcharts-legend-text {
                font-size: 0.625rem !important;
            }

            .apexcharts-datalabel-label {
                font-size: 0.625rem !important;
            }

            .apexcharts-datalabel-value {
                font-size: 0.75rem !important;
            }
        }

        /* Medium screens */
        @media (min-width: 640px) and (max-width: 767px) {
            .apexcharts-legend-text {
                font-size: 0.75rem !important;
            }

            .apexcharts-datalabel-label {
                font-size: 0.75rem !important;
            }

            .apexcharts-datalabel-value {
                font-size: 1rem !important;
            }
        }

        /* Large screens */
        @media (min-width: 768px) and (max-width: 1023px) {
            .apexcharts-legend-text {
                font-size: 0.75rem !important;
            }

            .apexcharts-datalabel-label {
                font-size: 1rem !important;
            }

            .apexcharts-datalabel-value {
                font-size: 1.125rem !important;
            }
        }

        /* Extra large screens */
        @media (min-width: 1024px) and (max-width: 1279px) {
            .apexcharts-legend-text {
                font-size: 0.75rem !important;
            }

            .apexcharts-datalabel-label {
                font-size: 0.75rem !important;
            }

            .apexcharts-datalabel-value {
                font-size: 1rem !important;
            }
        }

        /* 2XL screens */
        @media (min-width: 1280px) {
            .apexcharts-legend-text {
                font-size: 0.75rem !important;
            }

            .apexcharts-datalabel-label {
                font-size: 1rem !important;
            }

            .apexcharts-datalabel-value {
                font-size: 1.25rem !important;
            }
        }
    </style>

    @if (session()->has('success'))
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
    @endif

    <div x-data="{ currentView: 'implementations' }" @set-current-page.window="currentView = $event.detail.pageName">
        <livewire:sidebar.focal-bar />

        <div x-show="currentView === 'dashboard'">
            <livewire:focal.dashboard.dashboard />
        </div>

        <div x-show="currentView === 'implementations'">
            <livewire:focal.implementations.implementations />
        </div>

        <div x-show="currentView === 'user-management'">
            <livewire:focal.user-management.user-management />
        </div>
        <div x-show="currentView === 'logs'">
            <livewire:focal.system-logs.system-logs />
        </div>

    </div>



</x-app-layout>
