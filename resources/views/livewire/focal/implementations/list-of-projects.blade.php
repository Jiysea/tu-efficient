<div class="relative">
    {{-- Loading State --}}
    <div class="absolute items-center justify-center z-50 min-h-full min-w-full text-indigo-900" wire:loading.flex>
        <div class="absolute min-h-full min-w-full bg-black opacity-5">
        </div>
        <svg class="w-8 h-8 mr-3 -ml-1 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
            </circle>
            <path class="opacity-75" fill="currentColor"
                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
            </path>
        </svg>
    </div>
    {{-- Upper/Header --}}
    <div class="relative max-h-12 items-center grid row-span-1 grid-cols-2">
        <div class="col-span-1">
            <h1 class="font-bold ml-4 my-2 text-indigo-1100">List of Projects</h1>

        </div>
        {{-- Search and Add Button | and Slots (for lower lg) --}}
        <div class="col-span-1 mx-2 flex items-center justify-end">
            <div class="relative me-2">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                    <svg class="w-3 h-3 text-indigo-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                    </svg>
                </div>
                <input type="text" id="project-search" maxlength="100"
                    class="ps-10 py-1 text-xs text-indigo-1100 placeholder-indigo-500 border border-indigo-300 rounded-lg w-full bg-indigo-50 focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="Search for project titles">
            </div>
            <button data-modal-target="create-modal" data-modal-toggle="create-modal"
                class="flex items-center bg-indigo-900 text-indigo-50 rounded-md px-4 py-1 text-sm font-bold focus:ring-indigo-500 focus:border-indigo-500 focus:outline-indigo-500">
                CREATE
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-5 ml-2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
            </button>
        </div>
    </div>

    {{-- List of Projects Table --}}
    <div class="relative min-h-60 max-h-60 overflow-y-auto overflow-x-auto">

        <table class="relative w-full text-sm text-left text-indigo-1100">
            <thead class="text-xs z-20 text-indigo-50 uppercase bg-indigo-600 sticky top-0 whitespace-nowrap">
                <tr>
                    <th scope="col" class="pe-2 ps-4 py-2">
                        project #
                    </th>
                    <th scope="col" class="pr-6 py-2">
                        project title
                    </th>
                    <th scope="col" class="pr-2 py-2">
                        total slots
                    </th>
                    <th scope="col" class="pr-2 py-2">
                        days of work
                    </th>
                    <th scope="col" class="px-2 py-2 text-center">

                    </th>
                </tr>
            </thead>
            <tbody class="relative text-xs">
                @foreach ($projects as $key => $project)
                    @php
                        $encryptedId = Crypt::encrypt($project['id']);
                    @endphp
                    <tr wire:click='selectRow({{ $key }}, "{{ $encryptedId }}")'
                        wire:key='{{ $key }}'
                        class="relative border-b {{ $selectedRow === $key ? 'bg-indigo-200' : '' }} hover:bg-indigo-100 whitespace-nowrap">
                        <th scope="row" class="pe-2 ps-4 py-2 font-medium text-indigo-1100 whitespace-nowrap ">
                            {{ $project['project_num'] }}
                        </th>
                        <td class="pr-6 py-2">
                            {{ $project['project_title'] }}
                        </td>
                        <td class="pr-2 py-2">
                            {{ $project['total_slots'] }}
                        </td>
                        <td class="pr-2 py-2">
                            {{ $project['days_of_work'] }}
                        </td>
                        <td class="py-2 flex">
                            <button @click.stop id="projectRowButton-{{ $key }}"
                                data-dropdown-toggle="projectRowDropdown-{{ $key }}"
                                class="z-0 font-medium text-indigo-700 hover:text-indigo-500 active:text-indigo-900 bg-transparent hover:bg-indigo-100 active:bg-indigo-200 rounded mx-1 p-1">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                    class="w-4">
                                    <path fill-rule="evenodd"
                                        d="M12.53 16.28a.75.75 0 0 1-1.06 0l-7.5-7.5a.75.75 0 0 1 1.06-1.06L12 14.69l6.97-6.97a.75.75 0 1 1 1.06 1.06l-7.5 7.5Z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                            <!-- Project Row Dropdown menu -->
                            <div id="projectRowDropdown-{{ $key }}"
                                class="absolute z-10 hidden bg-white divide-y border divide-gray-100 rounded-lg shadow w-40">
                                <ul class="py-2 text-sm text-gray-700"
                                    aria-labelledby="projectRowButton-{{ $key }}">
                                    <li>
                                        <a aria-label="{{ __('Projects Dropdown') }}"
                                            class="flex items-center justify-start text-indigo-1100 px-4 py-2 hover:bg-gray-100 cursor-pointer">

                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                fill="currentColor" class="size-6 pe-2">
                                                <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
                                                <path fill-rule="evenodd"
                                                    d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 0 1 0-1.113ZM17.25 12a5.25 5.25 0 1 1-10.5 0 5.25 5.25 0 0 1 10.5 0Z"
                                                    clip-rule="evenodd" />
                                            </svg>

                                            View Project
                                        </a>
                                    </li>
                                    <li>
                                        <a aria-label="{{ __('Settings') }}"
                                            class="flex items-center justify-start text-indigo-1100 px-4 py-2 hover:bg-gray-100 cursor-pointer">

                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                fill="currentColor" class="size-6 pe-2">
                                                <path
                                                    d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32l8.4-8.4Z" />
                                                <path
                                                    d="M5.25 5.25a3 3 0 0 0-3 3v10.5a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3V13.5a.75.75 0 0 0-1.5 0v5.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5V8.25a1.5 1.5 0 0 1 1.5-1.5h5.25a.75.75 0 0 0 0-1.5H5.25Z" />
                                            </svg>

                                            Edit Project
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Create Button | Main Modal --}}
    <livewire:focal.implementations.create-project-modal />

</div>
