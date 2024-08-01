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

    <div x-data="{ currentView: 'dashboard' }" @set-current-page.window="currentView = $event.detail.pageName">
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
