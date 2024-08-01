<div>
    <div class="relative flex my-4">

        <h1 class="text-xl font-bold me-4 ms-3">Implementations</h1>

        <button id="implementationTimeButton" data-dropdown-toggle="implementationTimeDropdown"
            class="text-indigo-50 bg-indigo-900 hover:bg-indigo-800 focus:ring-4 focus:outline-none focus:ring-indigo-300 font-medium rounded text-sm px-3 py-1 text-center inline-flex items-center"
            type="button"> {{ $currentItem }} <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true"
                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="m1 1 4 4 4-4" />
            </svg>
        </button>

        <!-- Dropdown menu -->
        <div id="implementationTimeDropdown"
            class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44">
            <ul class="py-2 text-sm text-gray-700 " aria-labelledby="implementationTimeButton">
                <li>
                    <a wire:click.prevent="updateCurrentItem('0')" wire:loading.attr="disabled"
                        aria-label="{{ __('This year') }}" class="block px-4 py-2 hover:bg-gray-100 cursor-pointer">This
                        year</a>
                </li>
                <li>
                    <a wire:click.prevent="updateCurrentItem(1)" wire:loading.attr="disabled"
                        aria-label="{{ __('This month') }}"
                        class="block px-4 py-2 hover:bg-gray-100 cursor-pointer">This
                        month</a>
                </li>
                <li>
                    <a wire:click.prevent="updateCurrentItem(2)" wire:loading.attr="disabled"
                        aria-label="{{ __('Past 3 months') }}"
                        class="block px-4 py-2 hover:bg-gray-100 cursor-pointer">Past
                        3 months</a>
                </li>
                <li>
                    <a wire:click.prevent="updateCurrentItem(3)" wire:loading.attr="disabled"
                        aria-label="{{ __('Past 6 months') }}"
                        class="block px-4 py-2 hover:bg-gray-100 cursor-pointer">Past
                        6 months</a>
                </li>
                <li>
                    <a wire:click.prevent="updateCurrentItem(4)" wire:loading.attr="disabled"
                        aria-label="{{ __('All time') }}" class="block px-4 py-2 hover:bg-gray-100 cursor-pointer">All
                        time</a>
                </li>
            </ul>
        </div>
    </div>

</div>
