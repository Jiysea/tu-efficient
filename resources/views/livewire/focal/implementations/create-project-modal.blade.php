<div wire:ignore.self id="create-modal" tabindex="-1" data-modal-backdrop="static" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-2 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div x-trap="trapCreate" x-data="{
        budgetToFloat: null,
        budgetToInt: null,
        unmaskedBudget: null,
    
        init() {
            window.addEventListener('update-implementations', () => {
                budgetToFloat: null;
                budgetToInt: null;
                unmaskedBudget = null;
    
                const budgetAmount = document.getElementById('budget_amount');
                const modal = FlowbiteInstances.getInstance('Modal', 'create-modal');
    
                budgetAmount.value = null;
                modal.hide();
            });
        },
    
        demaskValue(value) {
            if (value) {
                // Remove commas
                this.budgetToFloat = value.replaceAll(',', '');
    
                // Check if there's a decimal point
                if (this.budgetToFloat.includes('.')) {
                    // Convert to float, format to 2 decimal places, then remove the decimal
                    this.budgetToInt = parseInt((parseFloat(this.budgetToFloat).toFixed(2)).replace('.', ''));
                } else {
                    // Append '00' if there's no decimal point
                    this.budgetToInt = parseInt(this.budgetToFloat + '00');
                }
            } else {
                this.budgetToInt = null;
            }
            this.unmaskedBudget = this.budgetToInt;
        }
    }" class="relative p-4 w-full max-w-5xl max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-md shadow">
            <!-- Modal header -->
            <div class="flex items-center justify-between py-2 px-4 rounded-t-md">
                <h1 class="text-lg font-semibold text-indigo-1100 ">
                    Create New Project Implementation
                </h1>
                <div class="flex items-center justify-center">
                    {{-- Loading State for Changes --}}
                    <div class="z-50 text-indigo-900" wire:loading wire:target="toggleTry">
                        <svg class="size-6 mr-3 -ml-1 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                    </div>
                    <button type="button" @click="trapCreate=false"
                        class="text-indigo-400 bg-transparent hover:bg-indigo-200 hover:text-indigo-900 rounded  w-8 h-8 ms-auto inline-flex justify-center items-center focus:outline-none duration-300 ease-in-out"
                        data-modal-toggle="create-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
            </div>
            <hr class="mx-4">
            <!-- Modal body -->
            <form wire:submit.prevent="saveProject" class="p-4 md:p-5">
                @csrf
                <div class="grid gap-4 mb-4 grid-cols-5 text-xs">

                    {{-- Project Number --}}
                    <div class="relative col-span-5 sm:col-span-2 mb-4">
                        <label for="project_num" class="block mb-1  font-medium text-indigo-1100 ">Project
                            Number <span class="text-red-700 font-normal text-sm">*</span> <span
                                class="text-gray-500 ms-2">prefix:
                                <strong>{{ substr(config('settings.project_number_prefix'), 0, strlen(config('settings.project_number_prefix')) - 1) }}</strong></span></label>
                        <input type="number" id="project_num" autocomplete="off" wire:model.blur="project_num"
                            class="text-xs duration-200 {{ $errors->has('project_num') ? 'border-red-500 border bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }} border rounded block w-full p-2.5 "
                            placeholder="Type project number">
                        @error('project_num')
                            <p class="text-red-500 absolute left-2 -bottom-4 z-10 text-xs">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Project Title --}}
                    <div class="relative col-span-5 sm:col-span-3 mb-4">
                        <label for="project_title" class="block mb-1  font-medium text-indigo-1100 ">Project
                            Title</label>
                        <input type="text" id="project_title" autocomplete="off" wire:model.blur="project_title"
                            class="text-xs duration-200 {{ $errors->has('project_title') ? 'border-red-500 border bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }} border rounded block w-full p-2.5       "
                            placeholder="Type project title">
                        @error('project_title')
                            <p class="text-red-500 absolute left-2 -bottom-4 z-10 text-xs">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Budget --}}
                    <div class="relative col-span-5 sm:col-span-2 mb-4">
                        <label for="budget_amount" class="block mb-1  font-medium text-indigo-1100 ">Budget <span
                                class="text-red-700 font-normal text-sm">*</span></label>
                        <div {{-- x-effect="console.log(unmaskedBudget)" --}} class="relative">
                            <div
                                class="text-sm {{ $errors->has('budget_amount') ? ' bg-red-400 text-red-900 border border-red-500' : 'bg-indigo-600 text-indigo-50' }} absolute inset-y-0 px-3 rounded-l flex items-center justify-center text-center pointer-events-none">
                                <p class="flex text-center w-full relative items-center justify-center font-semibold">₱
                                </p>
                            </div>
                            <input x-mask:dynamic="$money($input)" type="text" inputmode="numeric" min="0"
                                autocomplete="off" id="budget_amount"
                                @input="demaskValue($el.value);
                                $wire.set('budget_amount', unmaskedBudget);
                                $wire.toggleTry();"
                                class="text-xs duration-200 {{ $errors->has('budget_amount') ? 'border-red-500 border bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }} ps-11 border rounded block w-full pe-2.5 py-2.5"
                                placeholder="Type total budget">
                        </div>
                        @error('budget_amount')
                            <p class="text-red-500 absolute left-2 -bottom-4 z-10 text-xs">{{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Total Slots --}}
                    <div class="relative col-span-3 sm:col-span-2 mb-4">

                        <div class="flex items-center">
                            <label for="total_slots"
                                class="block mb-1 whitespace-nowrap font-medium text-indigo-1100 ">Total
                                Slots <span class="text-red-700 font-normal text-sm">*</span></label>
                            <div tabindex="-1" class="w-full mb-1 flex items-center justify-end">
                                <label class="inline-flex items-center cursor-pointer">
                                    <span
                                        class="me-2 text-xs {{ $isAutoComputeEnabled ? 'text-indigo-900' : 'text-gray-500' }} duration-150 ease-in-out">Auto
                                        compute by minimum wage</span>
                                    <input type="checkbox" id="auto-compute" wire:click="toggleTry" autocomplete="off"
                                        wire:model.live="isAutoComputeEnabled" class="sr-only peer">
                                    <div
                                        class="relative w-9 h-4 bg-gray-500 peer-focus:outline-none peer-focus:ring-1 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-[calc(100%+8px)] peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:size-3 after:transition-all duration-300 ease-in-out peer-checked:bg-indigo-900">
                                    </div>
                                </label>
                            </div>
                        </div>
                        <input type="number" min="0" id="total_slots" autocomplete="off"
                            wire:model.live="total_slots" @if ($isAutoComputeEnabled) disabled @endif
                            class="text-xs duration-300 ease-in-out {{ $isAutoComputeEnabled ? 'bg-gray-200 border-gray-300 text-indigo-1100 focus:ring-gray-800 focus:border-gray-800' : 'bg-indigo-50 autofill:bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }} {{ $errors->has('total_slots') ? 'border-red-500 border bg-red-200 autofill:bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : '' }} rounded border block w-full p-2.5"
                            placeholder="Type total slots">
                        @error('total_slots')
                            <p class="text-red-500 absolute left-2 -bottom-4 z-10 text-xs">{{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Days of Work --}}
                    <div class="relative col-span-2 sm:col-span-1 mb-4">
                        <label for="days_of_work" class="block mb-1  font-medium text-indigo-1100 ">Days of
                            Work <span class="text-red-700 font-normal text-sm">*</span></label>
                        <input type="number" min="0" max="15" id="days_of_work"
                            wire:model.live="days_of_work" @input="$wire.toggleTry()"
                            class="text-xs duration-200 {{ $errors->has('days_of_work') ? 'border-red-500 border bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }} border rounded block w-full p-2.5"
                            placeholder="Type days of work">
                        @error('days_of_work')
                            <p class="text-red-500 absolute left-2 -bottom-4 z-10 text-xs">{{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Province --}}
                    <div class="relative col-span-2 mb-4">
                        <label for="province" class="block mb-1  font-medium text-indigo-1100 ">Province <span
                                class="text-red-700 font-normal text-sm">*</span></label>
                        <select id="province" autocomplete="off" wire:model.live="province"
                            class="text-xs duration-200 {{ $errors->has('province') ? 'border-red-500 border bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-500 focus:border-indigo-500' }} border rounded block w-full p-2.5">
                            @foreach ($this->getProvince as $province)
                                <option>{{ $province }}</option>
                            @endforeach
                        </select>
                        @error('province')
                            <p class="text-red-500 absolute left-2 -bottom-4 z-10 text-xs">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- City/Municipality --}}
                    <div class="relative col-span-3 mb-4">
                        <label for="city_municipality" class="block mb-1  font-medium text-indigo-1100 ">
                            City/Municipality <span class="text-red-700 font-normal text-sm">*</span></label>
                        <select id="city_municipality" autocomplete="off" wire:model.live="city_municipality"
                            wire:key="{{ $province }}"
                            class="text-xs duration-200 {{ $errors->has('city_municipality') ? 'border-red-500 border bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-500 focus:border-indigo-500' }} border rounded block w-full p-2.5">
                            @foreach ($this->getCityMunicipality as $city_municipality)
                                <option>{{ $city_municipality }}</option>
                            @endforeach
                        </select>
                        @error('city_municipality')
                            <p class="text-red-500 absolute left-2 -bottom-4 z-10 text-xs">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- District --}}
                    <div class="relative col-span-2 mb-4">
                        <label for="district" class="block mb-1  font-medium text-indigo-1100 ">District <span
                                class="text-red-700 font-normal text-sm">*</span></label>
                        <select id="district" autocomplete="off" wire:model.live="district"
                            wire:key="{{ $district }}"
                            class="text-xs duration-200 {{ $errors->has('district') ? 'border-red-500 border bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-500 focus:border-indigo-500' }} border rounded block w-full p-2.5">
                            @foreach ($this->getDistrict as $district)
                                <option>{{ $district }}</option>
                            @endforeach
                        </select>
                        @error('district')
                            <p class="text-red-500 absolute left-2 -bottom-4 z-10 text-xs">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Purpose --}}
                    <div class="relative col-span-3 mb-4">
                        <label for="purpose" class="block mb-1  font-medium text-indigo-1100 ">Purpose of
                            Project <span class="text-red-700 font-normal text-sm">*</span></label>
                        <select id="purpose" autocomplete="off" wire:model.live="purpose"
                            class="text-xs duration-200 {{ $errors->has('purpose') ? 'border-red-500 border bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-500 focus:border-indigo-500' }} border rounded block w-full p-2.5">
                            <option value="">Select a purpose...</option>
                            <option>DUE TO DISADVANTAGE/DISPLACEMENT</option>
                        </select>
                        @error('purpose')
                            <p class="text-red-500 absolute left-2 -bottom-4 z-10 text-xs">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="w-full flex relative items-center justify-end">
                    {{-- Loading State for Changes --}}
                    <div class="z-50 text-indigo-900" wire:loading wire:target="saveProject">
                        <svg class="size-6 mr-3 -ml-1 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                    </div>
                    <button type="submit" wire:loading.attr="disabled" wire:target="saveProject"
                        class="space-x-2 py-2 px-4 text-center text-white font-bold flex items-center bg-indigo-700 disabled:opacity-75 hover:bg-indigo-800 focus:ring-4 focus:outline-none focus:ring-indigo-300 rounded-md">
                        <p>CREATE NEW PROJECT</p>
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-7"
                            xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                            viewBox="0, 0, 400,400">
                            <g>
                                <path
                                    d="M87.232 51.235 C 70.529 55.279,55.160 70.785,51.199 87.589 C 49.429 95.097,49.415 238.777,51.184 245.734 C 55.266 261.794,68.035 275.503,84.375 281.371 L 89.453 283.195 164.063 283.423 C 247.935 283.680,244.564 283.880,256.471 277.921 C 265.327 273.488,273.488 265.327,277.921 256.471 C 283.880 244.564,283.680 247.935,283.423 164.063 L 283.195 89.453 281.371 84.375 C 275.503 68.035,261.794 55.266,245.734 51.184 C 239.024 49.478,94.296 49.525,87.232 51.235 M326.172 101.100 C 323.101 102.461,320.032 105.395,318.240 108.682 C 316.870 111.194,316.777 115.490,316.406 193.359 L 316.016 275.391 313.810 281.633 C 308.217 297.460,296.571 308.968,280.859 314.193 L 275.391 316.012 193.359 316.404 L 111.328 316.797 108.019 318.693 C 97.677 324.616,97.060 340.415,106.903 347.255 L 110.291 349.609 195.575 349.609 L 280.859 349.609 287.500 347.798 C 317.300 339.669,339.049 318.056,347.783 287.891 L 349.592 281.641 349.816 196.680 C 350.060 104.007,350.312 109.764,345.807 104.807 C 341.717 100.306,332.072 98.485,326.172 101.100 M172.486 118.401 C 180.422 121.716,182.772 126.649,182.795 140.039 L 182.813 150.000 190.518 150.000 C 209.679 150.000,219.220 157.863,215.628 170.693 C 213.075 179.810,207.578 182.771,193.164 182.795 L 182.813 182.813 182.795 193.164 C 182.771 207.578,179.810 213.075,170.693 215.628 C 157.863 219.220,150.000 209.679,150.000 190.518 L 150.000 182.813 140.039 182.795 C 123.635 182.767,116.211 176.839,117.378 164.698 C 118.318 154.920,125.026 150.593,139.970 150.128 L 150.000 149.815 150.000 142.592 C 150.000 122.755,159.204 112.853,172.486 118.401 "
                                    stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                            </g>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
