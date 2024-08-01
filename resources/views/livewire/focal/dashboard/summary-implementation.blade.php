<div class="relative flex flex-row justify-between my-3 w-full">

    <h1 class="text-xl font-bold ms-3">Summary of Beneficiaries</h1>

    <button id="implementationButton" data-dropdown-toggle="implementationDropdown" data-dropdown-placement="bottom"
        class="text-indigo-50 bg-indigo-900 hover:bg-indigo-800 focus:ring-4 focus:outline-none focus:ring-indigo-300 font-medium rounded text-sm px-3 py-1 text-center inline-flex items-center"
        type="button">{{ $currentImplementation }} <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true"
            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="m1 1 4 4 4-4" />
        </svg>
    </button>

    <!-- Dropdown menu -->
    <div id="implementationDropdown" class="z-10 hidden bg-white rounded-lg shadow w-60">
        <div class="p-3">
            <label for="input-group-search" class="sr-only">Search</label>
            <div class="relative">
                <div class="absolute inset-y-0 rtl:inset-r-0 start-0 flex items-center ps-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                    </svg>
                </div>
                <input type="text" id="input-group-search"
                    class="block w-full p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-indigo-500 focus:border-indigo-500      "
                    placeholder="Search project number">
            </div>
        </div>
        <ul class="h-48 px-3 pb-3 overflow-y-auto text-sm text-gray-700 font-bold"
            aria-labelledby="implementationButton">
            @foreach ($implementations as $key => $implementation)
                <li wire:key={{ $key }}>
                    <a wire:click.prevent="updateCurrentImplementation({{ $key }})"
                        wire:loading.attr="disabled" aria-label="{{ __('Implementation') }}"
                        class="block px-4 py-2 hover:bg-gray-100 cursor-pointer">{{ $implementation['project_num'] }}</a>
                </li>
            @endforeach
        </ul>
    </div>

    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dropdownButton = document.getElementById('implementationButton');
            const dropdownMenu = document.getElementById('implementationDropdown');
            const dropdownItems = dropdownMenu.querySelectorAll('a');

            // Toggle dropdown visibility
            // dropdownButton.addEventListener('click', function(event) {
            //     event.stopPropagation(); // Prevent click event from bubbling up
            //     dropdownMenu.classList.toggle('hidden');
            // });

            // Change button text on dropdown item click
            dropdownItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    // e.preventDefault();
                    dropdownButton.innerHTML =
                        `${this.textContent} <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4" /></svg>`;
                    dropdownMenu.classList.add('hidden');
                });
            });

            // Close dropdown when clicking outside
            // document.addEventListener('click', function(e) {
            //     if (!dropdownButton.contains(e.target) && !dropdownMenu.contains(e.target)) {
            //         dropdownMenu.classList.add('hidden');
            //     }
            // });
        });
    </script> --}}

</div>
