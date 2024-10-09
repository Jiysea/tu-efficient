<div x-cloak class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto backdrop-blur-sm z-50" x-show="viewProjectModal">

    <!-- Modal -->
    <div x-show="viewProjectModal" x-trap.noscroll="viewProjectModal"
        class="min-h-screen p-4 flex items-center justify-center z-50 select-none">

        {{-- The Modal --}}
        <div class="relative size-full max-w-5xl">
            <div class="relative bg-white rounded-md shadow">

                <!-- Modal Header -->
                <div class="flex items-center justify-between py-2 px-4 rounded-t-md">
                    <span class="flex items-center justify-center">
                        <h1 class="text-sm sm:text-base font-semibold text-indigo-1100">View Project
                        </h1>

                    </span>
                    <div class="flex items-center justify-center">
                        {{-- Loading State for Changes --}}
                        <div class="z-50 text-indigo-900" wire:loading>
                            <svg class="size-6 mr-3 -ml-1 animate-spin" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4">
                                </circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                        </div>

                        {{-- Close Button --}}
                        <button type="button" @click="$wire.resetViewProject(); viewProjectModal = false;"
                            class="outline-none text-indigo-400 hover:bg-indigo-200 hover:text-indigo-900 rounded  size-8 ms-auto inline-flex justify-center items-center duration-300 ease-in-out">
                            <svg class="size-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                            <span class="sr-only">Close Modal</span>
                        </button>
                    </div>
                </div>

                <hr class="">

                {{-- Modal body --}}
                @if ($this->implementation)
                    <form wire:submit.prevent="editProject" class="pt-5 pb-6 px-3 md:px-12 text-indigo-1100 text-xs">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 sm:gap-4">

                            {{-- IF Edit Mode is ON --}}
                            @if ($editMode)
                                {{-- Project Number --}}
                                <div class="flex flex-1 flex-col relative mb-4">
                                    <label for="project_num" class="block mb-1  font-medium text-indigo-1100 ">Project
                                        Number <span class="text-red-700 font-normal text-sm">*</span> <span
                                            class="text-gray-500 ms-2">prefix:
                                            <strong>{{ substr($projectNumPrefix ?? config('settings.project_number_prefix'), 0, strlen($projectNumPrefix ?? config('settings.project_number_prefix')) - 1) }}</strong></span></label>
                                    <input type="text" id="project_num" autocomplete="off"
                                        wire:model.blur="project_num"
                                        class="text-xs duration-200 {{ $errors->has('project_num') ? 'border-red-500 border bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }} border rounded block w-full p-2.5 "
                                        placeholder="Type project number">
                                    @error('project_num')
                                        <p class="text-red-500 absolute left-2 -bottom-4 z-10 text-xs">
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                {{-- Project Title --}}
                                <div class="flex flex-1 flex-col relative mb-4">
                                    <label for="project_title" class="block mb-1 font-medium text-indigo-1100 ">Project
                                        Title</label>
                                    <input type="text" id="project_title" autocomplete="off"
                                        wire:model.blur="project_title"
                                        class="text-xs duration-200 {{ $errors->has('project_title') ? 'border-red-500 border bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }} border rounded block w-full p-2.5"
                                        placeholder="Type project title">
                                    @error('project_title')
                                        <p class="text-red-500 absolute left-2 -bottom-4 z-10 text-xs">
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                {{-- Budget --}}
                                <div class="flex flex-1 flex-col relative mb-4">
                                    @if ($isEmpty)
                                        <label for="budget_amount"
                                            class="block mb-1  font-medium text-indigo-1100 ">Budget
                                            <span class="text-red-700 font-normal">*</span></label>
                                        <div class="relative">

                                            <div
                                                class="text-sm {{ $errors->has('budget_amount') ? ' bg-red-400 text-red-900 border border-red-500' : 'bg-indigo-600 text-indigo-50' }} absolute inset-y-0 px-3 rounded-l flex items-center justify-center text-center pointer-events-none">
                                                <p
                                                    class="flex text-center w-full relative items-center justify-center font-semibold">
                                                    ₱
                                                </p>
                                            </div>
                                            <input x-mask:dynamic="$money($input)" type="text" min="0"
                                                @blur="$wire.autoCompute();" autocomplete="off" id="budget_amount"
                                                wire:model.blur="budget_amount"
                                                class="text-xs duration-200 {{ $errors->has('budget_amount') ? 'border-red-500 border bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }} ps-11 border rounded block w-full pe-2.5 py-2.5"
                                                placeholder="Type total budget">
                                        </div>
                                        @error('budget_amount')
                                            <p class="text-red-500 absolute left-2 -bottom-4 z-10 text-xs">
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    @else
                                        <p class="block mb-1 font-medium text-indigo-1100">
                                            Budget
                                        </p>
                                        <span
                                            class="flex flex-1 text-sm rounded p-2.5 bg-indigo-50 text-indigo-700 font-medium">₱
                                            {{ number_format($this->implementation->budget_amount / 100, 2, '.', ',') }}</span>
                                    @endif
                                </div>

                                {{-- Minimum Wage --}}
                                <div class="flex flex-1 flex-col relative mb-4">
                                    @if ($isEmpty)
                                        <label for="minimum_wage"
                                            class="block mb-1  font-medium text-indigo-1100 ">Minimum Wage
                                            <span class="text-red-700 font-normal">*</span></label>
                                        <div class="relative">

                                            <div
                                                class="text-sm {{ $errors->has('minimum_wage') ? ' bg-red-400 text-red-900 border border-red-500' : 'bg-indigo-600 text-indigo-50' }} absolute inset-y-0 px-3 rounded-l flex items-center justify-center text-center pointer-events-none">
                                                <p
                                                    class="flex text-center w-full relative items-center justify-center font-semibold">
                                                    ₱
                                                </p>
                                            </div>
                                            <input x-mask:dynamic="$money($input)" type="text" min="0"
                                                @blur="$wire.autoCompute();" autocomplete="off" id="minimum_wage"
                                                wire:model.blur="minimum_wage"
                                                class="text-xs duration-200 {{ $errors->has('minimum_wage') ? 'border-red-500 border bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }} ps-11 border rounded block w-full pe-2.5 py-2.5"
                                                placeholder="Type total budget">
                                        </div>
                                        @error('minimum_wage')
                                            <p class="text-red-500 absolute left-2 -bottom-4 z-10 text-xs">
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    @else
                                        <p class="block mb-1 font-medium text-indigo-1100">
                                            Minimum Wage
                                        </p>
                                        <span
                                            class="flex flex-1 text-sm rounded p-2.5 bg-indigo-50 text-indigo-700 font-medium">₱
                                            {{ number_format($this->implementation->minimum_wage / 100, 2, '.', ',') }}</span>
                                    @endif
                                </div>

                                {{-- Total Slots --}}
                                <div class="flex flex-1 flex-col relative mb-4">
                                    @if ($isEmpty)
                                        <div class="flex items-center">
                                            <label for="total_slots"
                                                class="block mb-1 whitespace-nowrap font-medium text-indigo-1100 ">Total
                                                Slots <span class="text-red-700 font-normal text-xs">*</span></label>
                                            <div tabindex="-1" class="w-full mb-1 flex items-center justify-end">
                                                <label class="inline-flex items-center cursor-pointer">
                                                    <span
                                                        class="me-2 text-xs sm:text-2xs md:text-xs {{ $isAutoComputeEnabled ? 'text-indigo-900' : 'text-gray-500' }} duration-150 ease-in-out">Auto
                                                        compute</span>
                                                    <input type="checkbox" id="auto-compute" wire:click="autoCompute"
                                                        autocomplete="off" wire:model.blur="isAutoComputeEnabled"
                                                        class="sr-only peer">
                                                    <div
                                                        class="relative w-9 h-4 bg-gray-500 peer-focus:outline-none peer-focus:ring-1 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-[calc(100%+8px)] peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:size-3 after:transition-all duration-300 ease-in-out peer-checked:bg-indigo-900">
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                        <input type="number" min="0" id="total_slots" autocomplete="off"
                                            wire:model.blur="total_slots"
                                            @if ($isAutoComputeEnabled) disabled @endif
                                            class="text-xs duration-300 ease-in-out {{ $isAutoComputeEnabled ? 'bg-gray-200 border-gray-300 text-indigo-1100 focus:ring-gray-800 focus:border-gray-800' : 'bg-indigo-50 autofill:bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }} {{ $errors->has('total_slots') ? 'border-red-500 border bg-red-200 autofill:bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : '' }} rounded border block w-full p-2.5"
                                            placeholder="Type total slots">
                                        @error('total_slots')
                                            <p class="text-red-500 absolute left-2 -bottom-4 z-10 text-xs">
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    @else
                                        <p class="block mb-1 font-medium text-indigo-1100">
                                            Total Slots
                                        </p>
                                        <span
                                            class="flex flex-1 text-sm rounded p-2.5 bg-indigo-50 text-indigo-700 font-medium">
                                            {{ $this->implementation->total_slots }}</span>
                                    @endif
                                </div>

                                {{-- Days Of Work --}}
                                <div class="flex flex-1 flex-col relative mb-4">
                                    @if ($isEmpty)
                                        <label for="days_of_work"
                                            class="block mb-1  font-medium text-indigo-1100 ">Days
                                            of
                                            Work <span class="text-red-700 font-normal text-xs">*</span></label>
                                        <input type="number" min="0" max="15" id="days_of_work"
                                            wire:model.blur="days_of_work" @blur="$wire.autoCompute()"
                                            class="text-xs duration-200 {{ $errors->has('days_of_work') ? 'border-red-500 border bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }} border rounded block w-full p-2.5"
                                            placeholder="Type days of work">
                                        @error('days_of_work')
                                            <p class="text-red-500 absolute left-2 -bottom-4 z-10 text-xs">
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    @else
                                        <p class="block mb-1 font-medium text-indigo-1100">
                                            Days of Work
                                        </p>
                                        <span
                                            class="flex flex-1 text-sm rounded p-2.5 bg-indigo-50 text-indigo-700 font-medium">
                                            {{ $this->implementation->days_of_work }}</span>
                                    @endif
                                </div>

                                {{-- Province --}}
                                <div class="flex flex-1 flex-col relative mb-4">
                                    @if ($isEmpty)
                                        <label for="province"
                                            class="block mb-1  font-medium text-indigo-1100 ">Province</label>
                                        <select id="province" autocomplete="off" wire:model.blur="province"
                                            class="text-xs duration-200 {{ $errors->has('province') ? 'border-red-500 border bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-500 focus:border-indigo-500' }} border rounded block w-full p-2.5">
                                            @foreach ($this->provinces as $province)
                                                <option>{{ $province }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('province')
                                            <p class="text-red-500 absolute left-2 -bottom-4 z-10 text-xs">
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    @else
                                        <p class="block mb-1 font-medium text-indigo-1100">
                                            Province
                                        </p>
                                        <span
                                            class="flex flex-1 text-sm rounded p-2.5 bg-indigo-50 text-indigo-700 font-medium">
                                            {{ $this->implementation->province }}</span>
                                    @endif
                                </div>

                                {{-- City|Municipality --}}
                                <div class="flex flex-1 flex-col relative mb-4">
                                    @if ($isEmpty)
                                        <label for="city_municipality"
                                            class="block mb-1  font-medium text-indigo-1100 ">
                                            City/Municipality</label>
                                        <select id="city_municipality" autocomplete="off"
                                            wire:model.blur="city_municipality" wire:key="{{ $province }}"
                                            class="text-xs duration-200 {{ $errors->has('city_municipality') ? 'border-red-500 border bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-500 focus:border-indigo-500' }} border rounded block w-full p-2.5">
                                            @foreach ($this->cities_municipalities as $city_municipality)
                                                <option>{{ $city_municipality }}</option>
                                            @endforeach
                                        </select>
                                        @error('city_municipality')
                                            <p class="text-red-500 absolute left-2 -bottom-4 z-10 text-xs">
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    @else
                                        <p class="block mb-1 font-medium text-indigo-1100">
                                            City/Municipality
                                        </p>
                                        <span
                                            class="flex flex-1 text-sm rounded p-2.5 bg-indigo-50 text-indigo-700 font-medium">
                                            {{ $this->implementation->city_municipality }}</span>
                                    @endif
                                </div>

                                {{-- District --}}
                                <div class="flex flex-1 flex-col relative mb-4">
                                    @if ($isEmpty)
                                        <label for="district"
                                            class="block mb-1  font-medium text-indigo-1100 ">District</label>
                                        <select id="district" autocomplete="off" wire:model.blur="district"
                                            wire:key="{{ $district }}"
                                            class="text-xs duration-200 {{ $errors->has('district') ? 'border-red-500 border bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-500 focus:border-indigo-500' }} border rounded block w-full p-2.5">
                                            @foreach ($this->districts as $district)
                                                <option>{{ $district }}</option>
                                            @endforeach
                                        </select>
                                        @error('district')
                                            <p class="text-red-500 absolute left-2 -bottom-4 z-10 text-xs">
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    @else
                                        <p class="block mb-1 font-medium text-indigo-1100">
                                            District
                                        </p>
                                        <span
                                            class="flex flex-1 text-sm rounded p-2.5 bg-indigo-50 text-indigo-700 font-medium">
                                            {{ $this->implementation->district }}</span>
                                    @endif
                                </div>

                                {{-- Purpose --}}
                                <div class="flex flex-1 flex-col relative mb-4">
                                    <label for="purpose" class="block mb-1 font-medium text-indigo-1100 ">Purpose
                                        of the
                                        Project</label>
                                    <select id="purpose" autocomplete="off" wire:model.blur="purpose"
                                        class="text-xs duration-200 {{ $errors->has('purpose') ? 'border-red-500 border bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-500 focus:border-indigo-500' }} border rounded block w-full p-2.5">
                                        <option value="">Select a purpose...</option>
                                        <option>DUE TO DISPLACEMENT/DISADVANTAGE</option>
                                    </select>
                                    @error('purpose')
                                        <p class="text-red-500 absolute left-2 -bottom-4 z-10 text-xs">
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                {{-- Save & Cancel Buttons --}}
                                <div
                                    class="flex items-center {{ $isEmpty ? 'justify-end' : 'justify-between' }} col-span-full gap-2 sm:gap-4">
                                    @if (!$isEmpty)
                                        <span
                                            class="flex flex-1 items-center justify-start font-medium border bg-red-100 border-red-300 text-red-950 rounded text-xs p-3 outline-none">
                                            <svg class="size-3.5 me-2" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path
                                                    d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                                            </svg>
                                            Some fields can only be editable if this project has no batches yet.
                                        </span>
                                    @endif

                                    <div class="flex items-center justify-center">
                                        <button type="submit"
                                            class="duration-200 ease-in-out flex flex-1 items-center justify-center px-2 py-2.5 rounded outline-none font-bold text-sm bg-green-700 hover:bg-green-800 active:bg-green-900 text-green-50">
                                            SAVE
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4 ms-2"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                                height="400" viewBox="0, 0, 400,400">
                                                <g>
                                                    <path
                                                        d="M179.372 38.390 C 69.941 52.432,5.211 171.037,53.012 269.922 C 112.305 392.582,285.642 393.654,346.071 271.735 C 403.236 156.402,307.211 21.986,179.372 38.390 M273.095 139.873 C 278.022 142.919,280.062 149.756,277.522 154.718 C 275.668 158.341,198.706 250.583,194.963 253.668 C 189.575 258.110,180.701 259.035,173.828 255.871 C 168.508 253.422,123.049 207.486,121.823 203.320 C 119.042 193.868,129.809 184.732,138.528 189.145 C 139.466 189.620,149.760 199.494,161.402 211.088 L 182.569 232.168 220.917 186.150 C 242.008 160.840,260.081 139.739,261.078 139.259 C 264.132 137.789,270.227 138.101,273.095 139.873 "
                                                        stroke="none" fill="currentColor" fill-rule="evenodd">
                                                    </path>
                                                </g>
                                            </svg>
                                        </button>

                                        <button type="button" wire:click.prevent="toggleEdit"
                                            wire:loading.attr="disabled" wire:target="toggleEdit"
                                            class="duration-200 ease-in-out flex shrink items-center justify-center ms-2 p-3 rounded outline-none font-bold text-sm border border-red-700 hover:border-transparent hover:bg-red-800 active:bg-red-900 text-red-700 hover:text-red-50">

                                            <svg class="size-3.5" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 14 14">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2"
                                                    d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            @endif

                            {{-- IF Edit Mode is OFF --}}
                            @if (!$editMode)
                                {{-- Project Number OFF --}}
                                <div class="relative flex flex-col mb-4">
                                    <p class="block mb-1 font-medium text-indigo-1100">
                                        Project Number
                                    </p>
                                    <span
                                        class="flex flex-1 text-sm rounded p-2.5 bg-indigo-50 text-indigo-700 font-medium">{{ $this->implementation->project_num }}</span>
                                </div>

                                {{-- Project Title --}}
                                <div class="relative flex flex-col mb-4">
                                    <p class="block mb-1 font-medium text-indigo-1100">
                                        Project Title
                                    </p>
                                    <span
                                        class="flex flex-1 text-sm rounded p-2.5 bg-indigo-50 text-indigo-700 font-medium">{{ $this->implementation->project_title }}</span>
                                </div>

                                {{-- Edit | Delete Buttons OFF --}}
                                <div x-data="{ deleteProjectModal: $wire.entangle('deleteProjectModal') }" class="flex justify-center items-center">
                                    <button type="button" wire:loading.attr="disabled" wire:target="toggleEdit"
                                        @if (!$isApproved) wire:click.prevent="toggleEdit" @else disabled @endif
                                        class="duration-200 ease-in-out flex flex-1 items-center justify-center px-2 py-2.5 rounded outline-none font-bold text-sm disabled:border disabled:cursor-not-allowed disabled:bg-gray-100 disabled:text-gray-400 bg-indigo-700 hover:bg-indigo-800 active:bg-indigo-900 text-indigo-50">
                                        EDIT
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4 ms-2"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                            viewBox="0, 0, 400,400">
                                            <g>
                                                <path
                                                    d="M183.594 33.724 C 46.041 46.680,-16.361 214.997,79.188 315.339 C 177.664 418.755,353.357 357.273,366.362 214.844 C 369.094 184.922,365.019 175.000,350.000 175.000 C 337.752 175.000,332.824 181.910,332.797 199.122 C 332.620 313.749,199.055 374.819,112.519 299.840 C 20.573 220.173,78.228 67.375,200.300 67.202 C 218.021 67.177,225.000 62.316,225.000 50.000 C 225.000 34.855,214.674 30.796,183.594 33.724 M310.472 33.920 C 299.034 36.535,291.859 41.117,279.508 53.697 C 262.106 71.421,262.663 73.277,295.095 105.627 C 319.745 130.213,321.081 131.250,328.125 131.250 C 338.669 131.250,359.145 110.836,364.563 94.922 C 376.079 61.098,344.986 26.032,310.472 33.920 M230.859 103.584 C 227.434 105.427,150.927 181.930,149.283 185.156 C 146.507 190.604,132.576 248.827,133.144 252.610 C 134.190 259.587,140.413 265.810,147.390 266.856 C 151.173 267.424,209.396 253.493,214.844 250.717 C 218.334 248.939,294.730 172.350,296.450 168.905 C 298.114 165.572,298.148 158.158,296.516 154.253 C 295.155 150.996,253.821 108.809,248.119 104.858 C 244.261 102.184,234.765 101.484,230.859 103.584 "
                                                    stroke="none" fill="currentColor" fill-rule="evenodd">
                                                </path>
                                            </g>
                                        </svg>
                                    </button>

                                    {{-- Delete/Trash Button --}}
                                    <button type="button"
                                        @if ($isEmpty) @click="deleteProjectModal = !deleteProjectModal;" @else disabled @endif
                                        class="duration-200 ease-in-out flex shrink items-center justify-center ms-2 p-2 rounded outline-none font-bold text-sm border {{ $isEmpty ? 'border-red-700 hover:border-transparent hover:bg-red-800 active:bg-red-900 text-red-700 hover:text-red-50' : ' bg-gray-100 text-gray-400 cursor-not-allowed' }} ">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-6"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                            viewBox="0, 0, 400,400">
                                            <g>
                                                <path
                                                    d="M171.190 38.733 C 151.766 43.957,137.500 62.184,137.500 81.778 L 137.500 87.447 107.365 87.669 L 77.230 87.891 74.213 91.126 C 66.104 99.821,71.637 112.500,83.541 112.500 L 87.473 112.500 87.682 220.117 L 87.891 327.734 90.158 333.203 C 94.925 344.699,101.988 352.414,112.661 357.784 C 122.411 362.689,119.829 362.558,202.364 362.324 L 277.734 362.109 283.203 359.842 C 294.295 355.242,302.136 348.236,307.397 338.226 C 312.807 327.930,312.500 335.158,312.500 218.195 L 312.500 112.500 316.681 112.500 C 329.718 112.500,334.326 96.663,323.445 89.258 C 320.881 87.512,320.657 87.500,291.681 87.500 L 262.500 87.500 262.500 81.805 C 262.500 61.952,248.143 43.817,228.343 38.660 C 222.032 37.016,177.361 37.073,171.190 38.733 M224.219 64.537 C 231.796 68.033,236.098 74.202,237.101 83.008 L 237.612 87.500 200.000 87.500 L 162.388 87.500 162.929 83.008 C 164.214 72.340,170.262 65.279,179.802 63.305 C 187.026 61.811,220.311 62.734,224.219 64.537 M171.905 172.852 C 174.451 174.136,175.864 175.549,177.148 178.095 L 178.906 181.581 178.906 225.000 L 178.906 268.419 177.148 271.905 C 172.702 280.723,160.426 280.705,155.859 271.873 C 154.164 268.596,154.095 181.529,155.785 178.282 C 159.204 171.710,165.462 169.602,171.905 172.852 M239.776 173.257 C 240.888 174.080,242.596 175.927,243.573 177.363 L 245.349 179.972 245.135 225.476 C 244.898 276.021,245.255 272.640,239.728 276.767 C 234.458 280.702,226.069 278.285,222.852 271.905 L 221.094 268.419 221.094 225.000 L 221.094 181.581 222.852 178.095 C 226.079 171.694,234.438 169.304,239.776 173.257 "
                                                    stroke="none" fill="currentColor" fill-rule="evenodd">
                                                </path>
                                            </g>
                                        </svg>
                                    </button>

                                    {{-- Delete Project Modal --}}
                                    <div x-cloak>
                                        <!-- Modal Backdrop -->
                                        <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50"
                                            x-show="deleteProjectModal">
                                        </div>

                                        <!-- Modal -->
                                        <div x-trap.inert="deleteProjectModal" x-show="deleteProjectModal"
                                            x-trap.noscroll="deleteProjectModal"
                                            class="fixed inset-0 p-4 flex items-center justify-center overflow-y-auto z-50 select-none max-h-full">

                                            {{-- The Modal --}}
                                            <div class="relative w-full max-w-xl max-h-full">
                                                <div class="relative bg-white rounded-md shadow">
                                                    <!-- Modal Header -->
                                                    <div
                                                        class="flex items-center justify-between py-2 px-4 rounded-t-md">
                                                        <h1
                                                            class="text-sm sm:text-base font-semibold text-indigo-1100">
                                                            Delete the Project
                                                        </h1>

                                                        {{-- Close Button --}}
                                                        <button type="button" @click="deleteProjectModal = false;"
                                                            class="outline-none text-indigo-400 hover:bg-indigo-200 hover:text-indigo-900 rounded  size-8 ms-auto inline-flex justify-center items-center duration-300 ease-in-out">
                                                            <svg class="size-3" aria-hidden="true"
                                                                xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                viewBox="0 0 14 14">
                                                                <path stroke="currentColor" stroke-linecap="round"
                                                                    stroke-linejoin="round" stroke-width="2"
                                                                    d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                                            </svg>
                                                            <span class="sr-only">Close Modal</span>
                                                        </button>
                                                    </div>

                                                    <hr class="">

                                                    {{-- Modal body --}}
                                                    <div
                                                        class="grid w-full place-items-center pt-5 pb-6 px-3 md:px-12 text-indigo-1100 text-xs">
                                                        <p class="font-medium text-sm mb-1">
                                                            Are you sure about deleting this project?
                                                        </p>
                                                        <p class="text-gray-500 text-sm mb-4">
                                                            (This is action is irreversible)
                                                        </p>
                                                        <div class="flex items-center justify-center w-full gap-4">
                                                            <div class="relative me-2">
                                                                <input type="password" id="view-project-password"
                                                                    wire:model.blur="password"
                                                                    class="flex {{ $errors->has('password') ? 'border-red-500 focus:border-red-500 bg-red-100 text-red-700 placeholder-red-500 focus:ring-0' : 'border-blue-300 bg-blue-50' }} rounded outline-none border p-2.5 text-sm select-all duration-200 ease-in-out"
                                                                    placeholder="Enter your password">
                                                                @error('password')
                                                                    <p
                                                                        class="absolute top-full left-0 text-xs text-red-700">
                                                                        {{ $message }}
                                                                    </p>
                                                                @enderror
                                                            </div>
                                                            <button type="button"
                                                                class="duration-200 ease-in-out flex items-center justify-center px-2 py-2.5 rounded outline-none font-bold text-sm bg-indigo-700 hover:bg-indigo-800 active:bg-indigo-900 text-indigo-50"
                                                                wire:click.prevent="deleteProject">CONFIRM</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Budget OFF --}}
                                <div class="relative flex flex-col mb-4">
                                    <p class="block mb-1 font-medium text-indigo-1100">
                                        Budget
                                    </p>
                                    <span
                                        class="flex flex-1 text-sm rounded p-2.5 bg-indigo-50 text-indigo-700 font-medium">₱
                                        {{ number_format($this->implementation->budget_amount / 100, 2, '.', ',') }}</span>
                                </div>

                                {{-- Minimum Wage OFF --}}
                                <div class="relative flex flex-col mb-4">
                                    <p class="block mb-1 font-medium text-indigo-1100">
                                        Minimum Wage
                                    </p>
                                    <span
                                        class="flex flex-1 text-sm rounded p-2.5 bg-indigo-50 text-indigo-700 font-medium">₱
                                        {{ number_format($this->implementation->minimum_wage / 100, 2, '.', ',') }}</span>
                                </div>

                                {{-- Total Slots OFF --}}
                                <div class="relative flex flex-col mb-4">
                                    <p class="block mb-1 font-medium text-indigo-1100">
                                        Total Slots
                                    </p>
                                    <span
                                        class="flex flex-1 text-sm rounded p-2.5 bg-indigo-50 text-indigo-700 font-medium">{{ $this->implementation->total_slots }}</span>
                                </div>

                                {{-- Days Of Work OFF --}}
                                <div class="relative flex flex-col mb-4">
                                    <p class="block mb-1 font-medium text-indigo-1100">
                                        Days of Work
                                    </p>
                                    <span
                                        class="flex flex-1 text-sm rounded p-2.5 bg-indigo-50 text-indigo-700 font-medium">
                                        {{ $this->implementation->days_of_work }}</span>
                                </div>

                                {{-- Province OFF --}}
                                <div class="relative flex flex-col mb-4">
                                    <p class="block mb-1 font-medium text-indigo-1100">
                                        Province
                                    </p>
                                    <span
                                        class="flex flex-1 text-sm rounded p-2.5 bg-indigo-50 text-indigo-700 font-medium">
                                        {{ $this->implementation->province }}</span>
                                </div>

                                {{-- City|Municipality OFF --}}
                                <div class="relative flex flex-col mb-4">
                                    <p class="block mb-1 font-medium text-indigo-1100">
                                        City/Municipality
                                    </p>
                                    <span
                                        class="flex flex-1 text-sm rounded p-2.5 bg-indigo-50 text-indigo-700 font-medium">
                                        {{ $this->implementation->city_municipality }}</span>
                                </div>

                                {{-- District OFF --}}
                                <div class="relative flex flex-col mb-4">
                                    <p class="block mb-1 font-medium text-indigo-1100">
                                        District
                                    </p>
                                    <span
                                        class="flex flex-1 text-sm rounded p-2.5 bg-indigo-50 text-indigo-700 font-medium">
                                        {{ $this->implementation->district }}</span>
                                </div>

                                {{-- Purpose OFF --}}
                                <div class="relative flex flex-col mb-4">
                                    <p class="block mb-1 font-medium text-indigo-1100">
                                        Purpose of the Project
                                    </p>
                                    <span
                                        class="flex flex-1 text-sm rounded p-2.5 bg-indigo-50 text-indigo-700 font-medium">
                                        {{ $this->implementation->purpose }}</span>
                                </div>

                                {{-- Date Created && Last Updated OFF --}}
                                <div
                                    class="flex flex-col sm:flex-row items-center justify-between col-span-full gap-2 sm:gap-4">
                                    <div class="flex flex-1 items-center justify-center">
                                        <p class="font-bold text-indigo-1100">
                                            Date of Creation:
                                        </p>
                                        <span
                                            class="flex flex-1 ms-2 text-xs rounded px-2 py-1 bg-indigo-50 text-indigo-700 font-medium">
                                            {{ date('M d, Y @ h:i:s a', strtotime($this->implementation->created_at)) }}</span>
                                    </div>

                                    <div class="flex flex-1 items-center justify-center">
                                        <p class="font-bold text-indigo-1100">
                                            Last Updated:
                                        </p>
                                        <span
                                            class="flex flex-1 ms-2 text-xs rounded px-2 py-1 bg-indigo-50 text-indigo-700 font-medium">
                                            {{ date('M d, Y @ h:i:s a', strtotime($this->implementation->updated_at)) }}</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </form>
                @endif

            </div>
        </div>
    </div>
</div>
