<x-slot:favicons>
    <x-f-favicons />
</x-slot>

<div x-data="{
    user_type: $wire.entangle('user_type'),
    wageInput: $wire.entangle('minimum_wage'),
    formattedWage: '',

    demaskWage(value) {
        if (!value) return null;
        // Remove commas
        formattedWage = value.replaceAll(',', '');

        // Add `00` or Remove dots (.) 
        formattedWage = parseInt(formattedWage * 100);
        console.log(formattedWage);
        return formattedWage || 0;
    },

    init() {
        // Initialize formatted value when component is loaded
        this.formattedWage = (this.wageInput / 100).toFixed(2);
    }
}" class="flex justify-center items-center h-screen">
    <div class="relative grid grid-cols-8 place-self-center w-full h-full divide-x divide-gray-100 text-indigo-1100">
        {{-- Navigation --}}
        <div class="flex flex-col col-span-2 items-start w-full h-full overflow-y-auto p-10">
            <div class="flex items-center justify-center mb-4">
                <a href="{{ route('focal.dashboard') }}"
                    class="grid place-items-center text-indigo-50 bg-indigo-700 hover:bg-indigo-800 active:bg-indigo-900 rounded p-2 w-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" xmlns:xlink="http://www.w3.org/1999/xlink"
                        width="400" height="400" viewBox="0, 0, 400,400">
                        <g>
                            <path
                                d="M90.363 104.248 C 86.973 106.080,4.008 188.228,1.874 191.866 C -0.758 196.350,-0.758 203.647,1.872 208.134 C 5.431 214.206,89.211 295.904,92.513 296.524 C 103.786 298.639,113.281 291.747,113.281 281.450 C 113.281 274.922,112.132 273.517,82.593 243.945 L 54.305 215.625 221.810 215.625 L 389.316 215.625 392.684 213.651 C 402.632 207.821,402.632 192.179,392.684 186.349 L 389.316 184.375 221.810 184.375 L 54.305 184.375 82.593 156.055 C 112.132 126.483,113.281 125.078,113.281 118.550 C 113.281 107.627,99.742 99.178,90.363 104.248 "
                                stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                        </g>
                    </svg>
                </a>
                <h1 class="font-bold ms-3 text-xl">Settings</h1>
            </div>
            <ol class="text-lg font-bold">
                <li class="mt-1">
                    <a href="#technical">
                        Technical
                    </a>
                    <ol class="text-gray-500 text-xs font-normal ms-6 mt-2">
                        <li class="mt-1"><a href="#minimum_wage">
                                Minimum Wage
                            </a></li>
                        <li class="mt-1"><a href="#duplication_threshold">
                                Duplication Threshold
                            </a></li>
                        <li class="mt-1"><a href="#extensive_matching">
                                Matching Mode
                            </a></li>
                    </ol>
                </li>
            </ol>

        </div>

        {{-- Settings --}}
        <div class="flex flex-col col-span-6 text-sm w-full h-full px-10 py-10">
            <form wire:submit.prevent="savedTechnical" class="flex flex-col bg-white rounded px-10 py-10">
                <p id="technical" class="font-semibold text-lg mb-2"><span
                        class="me-2 text-indigo-900 text-xl">#</span>Technical
                </p>
                <div class="flex flex-col gap-4 items-start w-full h-full overflow-y-auto ">

                    {{-- Minimum Wage --}}
                    <div id="minimum_wage" class="relative flex items-center justify-start w-full py-4 px-10 rounded">
                        <p class="font-medium">Minimum Wage: </p>

                        <div class="flex flex-col ms-2">

                            {{-- $wire.set('minimum_wage', unmaskedWage); --}}

                            <input x-mask:dynamic="$money($input)" type="text" inputmode="numeric" min="0"
                                autocomplete="off" id="minimum_wage" :value="formattedWage"
                                @input="wageInput = demaskWage($el.value); $wire.set('minimum_wage', wageInput);"
                                class="{{ $errors->has('minimum_wage') ? 'bg-red-200 text-red-900 border-red-300 focus:border-red-500 placeholder-red-500' : 'border-indigo-300 text-indigo-900 focus:border-indigo-500 bg-indigo-50 placeholder-indigo-500 focus:placeholder-indigo-500' }} focus:ring-0 outline-none flex items-center justify-center px-4 py-2 text-sm duration-200 ease-in-out rounded"
                                placeholder="Type minimum wage">
                            @error('minimum_wage')
                                <p class="text-red-500 mt-2 z-10 text-xs">{{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                    {{-- Duplication Threshold --}}
                    <div id="duplication_threshold" class="flex items-center justify-start w-full py-4 px-10 rounded">
                        <p class="font-medium">Duplication Threshold: </p>

                        <input type="number" wire:model.blur="duplication_threshold" autocomplete="off"
                            placeholder="Enter a valid threshold"
                            class="{{ $errors->has('duplication_threshold') ? 'bg-red-200 text-red-900 border-red-300 focus:border-red-500 placeholder-red-500' : 'border-indigo-300 text-indigo-900 focus:border-indigo-500 bg-indigo-50 placeholder-indigo-500 focus:placeholder-indigo-500' }} focus:ring-0 outline-none flex items-center justify-center px-4 py-2 text-sm duration-200 ease-in-out rounded" />
                    </div>

                    {{-- Extensive Matching --}}
                    <div id="extensive_matching" class="flex items-center justify-start w-full py-4 px-10 rounded">
                        <p class="font-medium">Matching Mode: </p>

                        {{-- 0 = direct matching; 1 = soft matching; 2 = extensive matching enabled --}}
                        <select autocomplete="off" wire:model.blur="extensive_matching"
                            class="text-xs duration-200 {{ $errors->has('extensive_matching') ? 'bg-red-200 text-red-900 border-red-300 focus:border-red-500 placeholder-red-500' : 'border-indigo-300 text-indigo-900 focus:border-indigo-500 bg-indigo-50 placeholder-indigo-500 focus:placeholder-indigo-500' }} focus:ring-0 outline-none flex items-center justify-center px-4 py-2 text-sm duration-200 ease-in-out rounded">
                            <option value="0" @if ($extensive_matching === 0) selected @endif>Direct</option>
                            <option value="1" @if ($extensive_matching === 1) selected @endif>Soft</option>
                            <option value="2" @if ($extensive_matching === 2) selected @endif>Extensive</option>
                        </select>
                    </div>

                    {{-- Save Button --}}
                    <div class="flex items-center justify-end w-full">
                        <span x-data="{
                            savedTechnical: $wire.entangle('savedTechnical'),
                        
                        }" x-init="$watch('savedTechnical', value => {
                            if (value) {
                                setTimeout(() => {
                                    savedTechnical = false;
                                }, 3000);
                            }
                        });" x-show="savedTechnical"
                            x-transition:enter="transition-opacity duration-300 ease-in-out"
                            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                            x-transition:leave="transition-opacity duration-300 ease-in-out"
                            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                            class="text-red-500 me-2">Settings
                            Saved!</span>
                        <button type="submit"
                            class="flex items-center justify-center font-bold text-indigo-50 bg-indigo-700 outline-none px-4 py-2 text-sm duration-200 ease-in-out rounded">
                            SAVE
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>
