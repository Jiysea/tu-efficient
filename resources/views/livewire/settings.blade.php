<x-slot:favicons>
    <x-f-favicons />
</x-slot>

<div class="flex justify-center items-center h-screen">
    <div class="relative grid grid-cols-8 place-self-center w-full max-w-5xl h-full">
        {{-- Navigation --}}
        <div class="flex flex-col col-span-2 items-start w-full h-full bg-[#161616] overflow-y-auto p-10">
            <a href="{{ route('focal.dashboard') }}"
                class="flex items-center justify-center border border-indigo-50 text-indigo-50 rounded px-4 py-2 w-full mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" xmlns:xlink="http://www.w3.org/1999/xlink"
                    width="400" height="400" viewBox="0, 0, 400,400">
                    <g>
                        <path
                            d="M90.363 104.248 C 86.973 106.080,4.008 188.228,1.874 191.866 C -0.758 196.350,-0.758 203.647,1.872 208.134 C 5.431 214.206,89.211 295.904,92.513 296.524 C 103.786 298.639,113.281 291.747,113.281 281.450 C 113.281 274.922,112.132 273.517,82.593 243.945 L 54.305 215.625 221.810 215.625 L 389.316 215.625 392.684 213.651 C 402.632 207.821,402.632 192.179,392.684 186.349 L 389.316 184.375 221.810 184.375 L 54.305 184.375 82.593 156.055 C 112.132 126.483,113.281 125.078,113.281 118.550 C 113.281 107.627,99.742 99.178,90.363 104.248 "
                            stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                    </g>
                </svg>
                <p class="ms-2">RETURN</p>
            </a>
            <ol class="text-indigo-50 text-lg font-bold">
                <li class="mt-1">
                    <a href="#general">
                        General
                    </a>
                    <ol class="text-gray-500 text-sm font-normal ms-6 mt-2">
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
        <form wire:submit.prevent="saveGeneral"
            class="flex flex-col col-span-6 text-indigo-50 text-sm w-full h-full ps-10 py-10">
            <p id="general" class="font-semibold text-lg mb-2"><span class="text-indigo-900 me-2">#</span>General</p>
            <div class="flex flex-col gap-4 items-start w-full h-full overflow-y-auto ">

                {{-- Minimum Wage --}}
                <div id="minimum_wage" class="flex items-center justify-start w-full bg-[#262626] py-4 px-10 rounded">
                    <p class="font-medium">Minimum Wage: </p>

                    <input type="text" wire:model.blur="minimum_wage" autocomplete="off"
                        placeholder="Enter a valid minimum wage"
                        class="{{ $errors->has('minimum_wage') ? 'bg-red-200 text-red-900 border-red-300 focus:border-red-500 placeholder-red-500' : 'border-[#6B6B6B] focus:border-indigo-300 bg-[#393939] placeholder-gray-500 focus:placeholder-indigo-500' }} focus:ring-0 outline-none flex items-center justify-center px-4 py-2 ms-4 text-sm duration-200 ease-in-out rounded" />
                </div>
                {{-- Duplication Threshold --}}
                <div id="duplication_threshold"
                    class="flex items-center justify-start w-full bg-[#262626] py-4 px-10 rounded">
                    <p class="font-medium">Duplication Threshold: </p>

                    <input type="number" wire:model.blur="duplication_threshold" autocomplete="off"
                        placeholder="Enter a valid threshold"
                        class="{{ $errors->has('duplication_threshold') ? 'bg-red-200 text-red-900 border-red-300 focus:border-red-500 placeholder-red-500' : 'border-[#6B6B6B] focus:border-indigo-300 bg-[#393939] placeholder-gray-500 focus:placeholder-indigo-500' }} focus:ring-0 outline-none flex items-center justify-center px-4 py-2 ms-4 text-sm duration-200 ease-in-out rounded" />
                </div>

                {{-- Extensive Matching --}}
                <div id="extensive_matching"
                    class="flex items-center justify-start w-full bg-[#262626] py-4 px-10 rounded">
                    <p class="font-medium">Matching Mode: </p>

                    {{-- 0 = direct matching; 1 = soft matching; 2 = extensive matching enabled --}}
                    <select autocomplete="off" wire:model.blur="extensive_matching"
                        class="text-xs duration-200 {{ $errors->has('extensive_matching') ? 'bg-red-200 text-red-900 border-red-300 focus:border-red-500 placeholder-red-500' : 'border-[#6B6B6B] focus:border-indigo-300 bg-[#393939] placeholder-gray-500 focus:placeholder-indigo-500' }} focus:ring-0 outline-none flex items-center justify-center px-4 py-2 ms-4 text-sm duration-200 ease-in-out rounded">
                        <option value="0" @if ($extensive_matching === 0) selected @endif>Direct</option>
                        <option value="1" @if ($extensive_matching === 1) selected @endif>Soft</option>
                        <option value="2" @if ($extensive_matching === 2) selected @endif>Extensive</option>
                    </select>
                </div>

                {{-- Save Button --}}
                <div class="flex items-center justify-end w-full">
                    <span x-data="{
                        savedGeneral: $wire.entangle('savedGeneral'),
                    
                    }" x-init="$watch('savedGeneral', value => {
                        if (value) {
                            setTimeout(() => {
                                savedGeneral = false;
                            }, 3000);
                        }
                    });" x-show="savedGeneral"
                        x-transition:enter="transition-opacity duration-300 ease-in-out"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                        x-transition:leave="transition-opacity duration-300 ease-in-out"
                        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                        class="text-red-500 me-2">Settings
                        Saved!</span>
                    <button type="submit"
                        class="flex items-center justify-center font-bold bg-indigo-700 outline-none px-4 py-2 text-sm duration-200 ease-in-out rounded">
                        SAVE
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>
