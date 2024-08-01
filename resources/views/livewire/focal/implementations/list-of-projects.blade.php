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
                <div class="absolute inset-y-0 rtl:inset-r-0 start-0 flex items-center ps-3 pointer-events-none">
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

    {{-- Table --}}
    <div class="relative min-h-60 max-h-60 overflow-y-auto overflow-x-auto">

        <table class=" w-full text-sm text-left text-indigo-1100">
            <thead class="text-xs text-indigo-50 uppercase bg-indigo-600 sticky top-0 whitespace-nowrap">
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
            <tbody class="text-xs">
                @foreach ($projects as $key => $project)
                    @php
                        $encryptedId = Crypt::encrypt($project['id']);
                    @endphp
                    <tr wire:click='selectRow({{ $key }}, "{{ $encryptedId }}")'
                        wire:key='{{ $key }}'
                        class="border-b {{ $selectedRow === $key ? 'bg-indigo-200' : '' }} hover:bg-indigo-100 whitespace-nowrap">
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
                        <td class="py-2 flex ">
                            <a href="#"
                                class="z-40 font-medium text-indigo-700 hover:text-indigo-500 active:text-indigo-900 bg-transparent hover:bg-indigo-100 active:bg-indigo-200 rounded mx-1 p-1">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                    class="w-4">
                                    <path fill-rule="evenodd"
                                        d="M12.53 16.28a.75.75 0 0 1-1.06 0l-7.5-7.5a.75.75 0 0 1 1.06-1.06L12 14.69l6.97-6.97a.75.75 0 1 1 1.06 1.06l-7.5 7.5Z"
                                        clip-rule="evenodd" />
                                </svg>

                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Create Button | Main Modal --}}

    <div id="create-modal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-5xl max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow ">
                <!-- Modal header -->
                <div class="flex items-center justify-between py-2 px-4 border-b rounded-t ">
                    <h3 class="text-lg font-semibold text-gray-900 ">
                        Create New Product
                    </h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center  "
                        data-modal-toggle="create-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <form class="p-4 md:p-5">
                    <div class="grid gap-4 mb-4 grid-cols-2">
                        <div class="col-span-2">
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 ">Name</label>
                            <input type="text" name="name" id="name"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5      "
                                placeholder="Type product name" required="">
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <label for="price" class="block mb-2 text-sm font-medium text-gray-900 ">Price</label>
                            <input type="number" name="price" id="price"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5      "
                                placeholder="$2999" required="">
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <label for="category"
                                class="block mb-2 text-sm font-medium text-gray-900 ">Category</label>
                            <select id="category"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5      ">
                                <option selected="">Select category</option>
                                <option value="TV">TV/Monitors</option>
                                <option value="PC">PC</option>
                                <option value="GA">Gaming/Console</option>
                                <option value="PH">Phones</option>
                            </select>
                        </div>
                        <div class="col-span-2">
                            <label for="description" class="block mb-2 text-sm font-medium text-gray-900 ">Product
                                Description</label>
                            <textarea id="description" rows="4"
                                class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500      "
                                placeholder="Write product description here"></textarea>
                        </div>
                    </div>
                    <button type="submit"
                        class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center   ">
                        <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                clip-rule="evenodd"></path>
                        </svg>
                        Add new product
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>
