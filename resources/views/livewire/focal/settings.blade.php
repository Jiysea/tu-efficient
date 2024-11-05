<x-slot:favicons>
    <x-f-favicons />
</x-slot>

<div x-data class="flex justify-center items-center min-h-screen">
    <div class="relative grid grid-cols-8 size-full divide-x divide-gray-500 divide-opacity-15 text-indigo-50">

        {{-- Navigation --}}
        <nav class="flex flex-col col-span-2 items-start size-full overflow-y-auto py-10 px-5">
            <span class="flex items-center justify-center mb-4">
                <a href="{{ route('focal.dashboard') }}"
                    class="flex items-center justify-center text-indigo-50 bg-indigo-700 hover:bg-indigo-800 active:bg-indigo-900 rounded p-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" xmlns:xlink="http://www.w3.org/1999/xlink"
                        width="400" height="400" viewBox="0, 0, 400,400">
                        <g>
                            <path
                                d="M90.363 104.248 C 86.973 106.080,4.008 188.228,1.874 191.866 C -0.758 196.350,-0.758 203.647,1.872 208.134 C 5.431 214.206,89.211 295.904,92.513 296.524 C 103.786 298.639,113.281 291.747,113.281 281.450 C 113.281 274.922,112.132 273.517,82.593 243.945 L 54.305 215.625 221.810 215.625 L 389.316 215.625 392.684 213.651 C 402.632 207.821,402.632 192.179,392.684 186.349 L 389.316 184.375 221.810 184.375 L 54.305 184.375 82.593 156.055 C 112.132 126.483,113.281 125.078,113.281 118.550 C 113.281 107.627,99.742 99.178,90.363 104.248 "
                                stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                        </g>
                    </svg>
                </a>
                <h1 class="whitespace-nowrap font-bold ms-3 text-xl">Personal Settings</h1>
            </span>
            <ol class="text-lg font-bold">
                <li class="mt-1">
                    <a href="#profile">
                        Profile
                    </a>
                    <ol class="text-gray-500 text-xs font-normal ms-6 mt-2 mb-6">
                        <li class="mt-1"><a href="#email">
                                Email
                            </a></li>
                    </ol>
                    <a href="#technical">
                        Technical
                    </a>
                    <ol class="text-gray-500 text-xs font-normal ms-6 mt-2 mb-6">
                        <li class="mt-1"><a href="#minimum_wage">
                                Minimum Wage
                            </a></li>
                        <li class="mt-1"><a href="#duplication_threshold">
                                Duplication Threshold
                            </a></li>
                        <li class="mt-1"><a href="#project_number_prefix">
                                Project Number Prefix
                            </a></li>
                        <li class="mt-1"><a href="#batch_number_prefix">
                                Batch Number Prefix
                            </a></li>
                        <li class="mt-1"><a href="#maximum_income">
                                Maximum Income
                            </a></li>
                    </ol>
                </li>
            </ol>
        </nav>

        {{-- Body --}}
        <div class="relative flex flex-col col-span-6 text-sm size-full px-16 py-5">

            {{-- Loading State --}}
            <svg xmlns="http://www.w3.org/2000/svg" class="absolute right-10 top-10 size-6 text-indigo-50 animate-spin"
                wire:loading
                wire:target="minimum_wage, duplication_threshold, project_number_prefix, batch_number_prefix, maximum_income"
                fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                </circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
            </svg>

            {{-- # Profile --}}
            <div class="relative flex flex-col px-10 py-5">
                <h1 id="profile" class="flex gap-3 font-semibold text-3xl mb-10">
                    <span class="text-indigo-700 text-3xl">#</span>
                    Profile
                </h1>

                <div class="flex flex-col gap-10 size-full text-sm px-10">
                    {{-- Email --}}
                    <span id="project_number_prefix" class="flex items-center justify-between w-full rounded">
                        <h1 class="font-medium text-indigo-300">Email Address</h1>

                        <div class="relative flex">
                            <span
                                class="text-zinc-100 bg-zinc-800 placeholder-zinc-500 selection:bg-indigo-900 selection:text-indigo-50 border-none focus:ring-0 outline-none w-56 px-3 py-2 text-xs duration-200 ease-in-out rounded">{{ $email }}</span>

                            <span
                                class="absolute flex items-center justify-center top-0 right-0 p-2 rounded duration-200 ease-in-out {{ $this->settings->get('project_number_prefix', config('settings.project_number_prefix')) !== $project_number_prefix ? 'text-indigo-50 bg-indigo-700 hover:bg-indigo-800 active:bg-indigo-900' : 'bg-zinc-900 text-zinc-500' }}">

                                {{-- Save Icon --}}
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" wire:loading.remove
                                    wire:target="" xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                    height="400" viewBox="0, 0, 400,400">
                                    <g>
                                        <path
                                            d="M38.095 14.017 C 26.775 17.636,17.071 27.593,13.739 39.009 C 11.642 46.193,11.873 354.876,13.980 361.648 C 19.802 380.355,32.269 387.500,59.086 387.500 L 74.919 387.500 75.172 328.320 C 75.419 270.395,75.459 269.037,77.083 264.259 C 81.184 252.188,90.624 243.010,102.734 239.319 C 110.072 237.083,291.047 237.108,297.656 239.346 C 309.831 243.469,318.888 252.402,322.917 264.259 C 324.541 269.037,324.581 270.395,324.828 328.320 L 325.081 387.500 340.987 387.500 C 367.924 387.500,380.708 380.015,386.261 360.991 C 388.052 354.858,388.165 97.267,386.379 93.907 C 385.025 91.358,308.141 14.589,305.715 13.363 C 304.653 12.826,298.546 12.500,289.541 12.500 L 275.072 12.500 274.805 64.648 C 274.607 103.235,274.287 117.544,273.576 119.670 C 271.146 126.930,264.002 133.923,256.768 136.121 C 252.067 137.550,147.936 137.551,143.236 136.122 C 135.869 133.883,128.898 127.062,126.424 119.670 C 125.713 117.544,125.393 103.235,125.195 64.648 L 124.928 12.500 83.753 12.542 C 46.038 12.580,42.201 12.704,38.095 14.017 M150.000 62.500 L 150.000 112.500 200.000 112.500 L 250.000 112.500 250.000 62.500 L 250.000 12.500 200.000 12.500 L 150.000 12.500 150.000 62.500 M105.657 264.058 C 99.653 267.719,100.006 263.657,100.003 329.102 L 100.000 387.500 200.000 387.500 L 300.000 387.500 299.997 329.102 C 299.994 263.657,300.347 267.719,294.343 264.058 C 290.342 261.619,109.658 261.619,105.657 264.058 "
                                            stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                    </g>
                                </svg>
                            </span>
                        </div>
                    </span>
                </div>
            </div>

            {{-- # Technical --}}
            <div class="relative flex flex-col px-10 py-5">

                <h1 id="technical" class="flex gap-3 font-semibold text-3xl mb-10">
                    <span class="text-indigo-700 text-3xl">#</span>
                    Technical
                </h1>

                <div class="flex flex-col gap-10 size-full text-sm px-10">

                    {{-- Minimum Wage --}}
                    <span id="minimum_wage" class="relative flex items-center justify-between w-full rounded">
                        <span class="flex flex-col">
                            <h1 class="font-medium text-indigo-300">Minimum Wage</h1>
                            <p class="text-xs text-zinc-500">It's the default minimum wage for calculating the total
                                slots on implementation projects.</p>
                        </span>

                        <div class="relative">
                            <span class="relative">
                                <span
                                    class="absolute left-0 px-3 py-1.5 rounded-l {{ $errors->has('minimum_wage') ? 'bg-[#2D1E20] text-red-500' : 'bg-zinc-900 text-zinc-500' }} select-none">
                                    ₱
                                </span>
                                <input x-mask:dynamic="$money($input)" type="text" inputmode="numeric" min="0"
                                    autocomplete="off" id="minimum_wage" wire:model.blur="minimum_wage"
                                    class="{{ $errors->has('minimum_wage') ? 'bg-[#442E30] text-red-50 placeholder-red-500' : 'text-zinc-100 bg-zinc-800 placeholder-zinc-500 selection:bg-indigo-900 selection:text-indigo-50' }} border-none focus:ring-0 outline-none w-56 ps-11 pe-3 py-1.5 text-sm duration-200 ease-in-out rounded"
                                    placeholder="Type minimum wage">
                            </span>
                            @error('minimum_wage')
                                <p class="absolute top-full right-0 mt-1 text-red-500 text-xs">{{ $message }}
                                </p>
                            @enderror
                        </div>
                    </span>

                    {{-- Duplication Threshold --}}
                    <span id="duplication_threshold" class="flex items-center justify-between w-full rounded">
                        <span class="flex flex-col">
                            <h1 class="font-medium text-indigo-300">Duplication Threshold</h1>
                            <p class="text-xs text-zinc-500">It's the default threshold for the similarity percentage of
                                duplicates.</p>
                        </span>

                        <div class="relative">
                            <span class="relative">
                                <input type="text" inputmode="numeric" min="0" max="100"
                                    autocomplete="off" id="duplication_threshold"
                                    wire:model.blur="duplication_threshold"
                                    class="{{ $errors->has('duplication_threshold') ? 'bg-[#442E30] text-red-50 placeholder-red-500' : 'text-zinc-100 bg-zinc-800 placeholder-zinc-500 selection:bg-indigo-900 selection:text-indigo-50' }} text-right border-none focus:ring-0 outline-none w-56 ps-3 pe-11 py-1.5 text-sm duration-200 ease-in-out rounded"
                                    placeholder="Type threshold (1-100%)">
                                <span
                                    class="absolute right-0 px-3 py-1.5 rounded-r {{ $errors->has('duplication_threshold') ? 'bg-[#2D1E20] text-red-500' : 'bg-zinc-900 text-zinc-500' }} select-none">
                                    %
                                </span>
                            </span>
                            @error('duplication_threshold')
                                <p class="absolute top-full right-0 mt-1 text-red-500 text-xs">{{ $message }}
                                </p>
                            @enderror
                        </div>
                    </span>

                    {{-- Project Number Prefix --}}
                    <span id="project_number_prefix" class="flex items-center justify-between w-full rounded">
                        <span class="flex flex-col">
                            <h1 class="font-medium text-indigo-300">Project Number Prefix</h1>
                            <p class="text-xs text-red-300">This will affect all of the existing projects.</p>
                        </span>

                        <div class="relative">
                            <input type="text" autocomplete="off" id="project_number_prefix"
                                wire:model.blur="project_number_prefix"
                                class="{{ $errors->has('project_number_prefix') ? 'bg-[#442E30] text-red-50 placeholder-red-500' : 'text-zinc-100 bg-zinc-800 placeholder-zinc-500 selection:bg-indigo-900 selection:text-indigo-50' }} border-none focus:ring-0 outline-none w-56 ps-3 pe-11 py-1.5 text-sm duration-200 ease-in-out rounded"
                                placeholder="Type prefix">

                            <button type="button" wire:click="saveProject" wire:loading.attr="disabled"
                                @if ($this->settings->get('project_number_prefix', config('settings.project_number_prefix')) === $project_number_prefix) disabled @endif
                                class="absolute flex items-center justify-center top-0 right-0 p-2 rounded duration-200 ease-in-out {{ $this->settings->get('project_number_prefix', config('settings.project_number_prefix')) !== $project_number_prefix ? 'text-indigo-50 bg-indigo-700 hover:bg-indigo-800 active:bg-indigo-900' : 'bg-zinc-900 text-zinc-500' }}">

                                {{-- Loading State --}}
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 animate-spin" wire:loading
                                    wire:target="saveProject" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4">
                                    </circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>

                                {{-- Save Icon --}}
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" wire:loading.remove
                                    wire:target="saveProject" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    width="400" height="400" viewBox="0, 0, 400,400">
                                    <g>
                                        <path
                                            d="M38.095 14.017 C 26.775 17.636,17.071 27.593,13.739 39.009 C 11.642 46.193,11.873 354.876,13.980 361.648 C 19.802 380.355,32.269 387.500,59.086 387.500 L 74.919 387.500 75.172 328.320 C 75.419 270.395,75.459 269.037,77.083 264.259 C 81.184 252.188,90.624 243.010,102.734 239.319 C 110.072 237.083,291.047 237.108,297.656 239.346 C 309.831 243.469,318.888 252.402,322.917 264.259 C 324.541 269.037,324.581 270.395,324.828 328.320 L 325.081 387.500 340.987 387.500 C 367.924 387.500,380.708 380.015,386.261 360.991 C 388.052 354.858,388.165 97.267,386.379 93.907 C 385.025 91.358,308.141 14.589,305.715 13.363 C 304.653 12.826,298.546 12.500,289.541 12.500 L 275.072 12.500 274.805 64.648 C 274.607 103.235,274.287 117.544,273.576 119.670 C 271.146 126.930,264.002 133.923,256.768 136.121 C 252.067 137.550,147.936 137.551,143.236 136.122 C 135.869 133.883,128.898 127.062,126.424 119.670 C 125.713 117.544,125.393 103.235,125.195 64.648 L 124.928 12.500 83.753 12.542 C 46.038 12.580,42.201 12.704,38.095 14.017 M150.000 62.500 L 150.000 112.500 200.000 112.500 L 250.000 112.500 250.000 62.500 L 250.000 12.500 200.000 12.500 L 150.000 12.500 150.000 62.500 M105.657 264.058 C 99.653 267.719,100.006 263.657,100.003 329.102 L 100.000 387.500 200.000 387.500 L 300.000 387.500 299.997 329.102 C 299.994 263.657,300.347 267.719,294.343 264.058 C 290.342 261.619,109.658 261.619,105.657 264.058 "
                                            stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                    </g>
                                </svg>
                            </button>
                            @error('project_number_prefix')
                                <p class="absolute top-full right-0 mt-1 text-red-500 text-xs">{{ $message }}
                                </p>
                            @enderror
                        </div>
                    </span>

                    {{-- Batch Number Prefix --}}
                    <span id="batch_number_prefix" class="flex items-center justify-between w-full rounded">
                        <span class="flex flex-col">
                            <h1 class="font-medium text-indigo-300">Batch Number Prefix</h1>
                            <p class="text-xs text-red-300">This will affect all of the existing batches.</p>
                        </span>

                        <div class="relative">
                            <input type="text" autocomplete="off" id="batch_number_prefix"
                                wire:model.blur="batch_number_prefix"
                                class="{{ $errors->has('batch_number_prefix') ? 'bg-[#442E30] text-red-50 placeholder-red-500' : 'text-zinc-100 bg-zinc-800 placeholder-zinc-500 selection:bg-indigo-900 selection:text-indigo-50' }} border-none focus:ring-0 outline-none w-56 ps-3 pe-11 py-1.5 text-sm duration-200 ease-in-out rounded"
                                placeholder="Type prefix">

                            <button type="button" wire:click="saveBatch" wire:loading.attr="disabled"
                                @if ($this->settings->get('batch_number_prefix', config('settings.batch_number_prefix')) === $batch_number_prefix) disabled @endif
                                class="absolute flex items-center justify-center right-0 top-0 p-2 rounded duration-200 ease-in-out {{ $this->settings->get('batch_number_prefix', config('settings.batch_number_prefix')) !== $batch_number_prefix ? 'text-indigo-50 bg-indigo-700 hover:bg-indigo-800 active:bg-indigo-900' : 'bg-zinc-900 text-zinc-500' }}">

                                {{-- Loading State --}}
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 animate-spin" wire:loading
                                    wire:target="saveBatch" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4">
                                    </circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>

                                {{-- Save Icon --}}
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" wire:loading.remove
                                    wire:target="saveBatch" xmlns:xlink="http://www.w3.org/1999/xlink" width="400"
                                    height="400" viewBox="0, 0, 400,400">
                                    <g>
                                        <path
                                            d="M38.095 14.017 C 26.775 17.636,17.071 27.593,13.739 39.009 C 11.642 46.193,11.873 354.876,13.980 361.648 C 19.802 380.355,32.269 387.500,59.086 387.500 L 74.919 387.500 75.172 328.320 C 75.419 270.395,75.459 269.037,77.083 264.259 C 81.184 252.188,90.624 243.010,102.734 239.319 C 110.072 237.083,291.047 237.108,297.656 239.346 C 309.831 243.469,318.888 252.402,322.917 264.259 C 324.541 269.037,324.581 270.395,324.828 328.320 L 325.081 387.500 340.987 387.500 C 367.924 387.500,380.708 380.015,386.261 360.991 C 388.052 354.858,388.165 97.267,386.379 93.907 C 385.025 91.358,308.141 14.589,305.715 13.363 C 304.653 12.826,298.546 12.500,289.541 12.500 L 275.072 12.500 274.805 64.648 C 274.607 103.235,274.287 117.544,273.576 119.670 C 271.146 126.930,264.002 133.923,256.768 136.121 C 252.067 137.550,147.936 137.551,143.236 136.122 C 135.869 133.883,128.898 127.062,126.424 119.670 C 125.713 117.544,125.393 103.235,125.195 64.648 L 124.928 12.500 83.753 12.542 C 46.038 12.580,42.201 12.704,38.095 14.017 M150.000 62.500 L 150.000 112.500 200.000 112.500 L 250.000 112.500 250.000 62.500 L 250.000 12.500 200.000 12.500 L 150.000 12.500 150.000 62.500 M105.657 264.058 C 99.653 267.719,100.006 263.657,100.003 329.102 L 100.000 387.500 200.000 387.500 L 300.000 387.500 299.997 329.102 C 299.994 263.657,300.347 267.719,294.343 264.058 C 290.342 261.619,109.658 261.619,105.657 264.058 "
                                            stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                    </g>
                                </svg>
                            </button>
                            @error('batch_number_prefix')
                                <p class="absolute top-full right-0 mt-1 text-red-500 text-xs">{{ $message }}
                                </p>
                            @enderror
                        </div>
                    </span>

                    {{-- Maximum Income --}}
                    <span id="maximum_income" class="flex items-center justify-between w-full rounded">
                        <span class="flex flex-col">
                            <h1 class="font-medium text-indigo-300">Maximum Income</h1>
                            <p class="text-xs text-zinc-500">It's the default ceiling for the average monthly income.
                            </p>
                        </span>

                        <div class="relative">
                            <span class="relative">
                                <span
                                    class="absolute left-0 px-3 py-1.5 rounded-l {{ $errors->has('maximum_income') ? 'bg-[#2D1E20] text-red-500' : 'bg-zinc-900 text-zinc-500' }} select-none">
                                    ₱
                                </span>
                                <input x-mask:dynamic="$money($input)" type="text" inputmode="numeric"
                                    min="0" autocomplete="off" id="maximum_income"
                                    wire:model.blur="maximum_income"
                                    class="{{ $errors->has('maximum_income') ? 'bg-[#442E30] text-red-50 placeholder-red-500' : 'text-zinc-100 bg-zinc-800 placeholder-zinc-500 selection:bg-indigo-900 selection:text-indigo-50' }} border-none focus:ring-0 outline-none w-56 ps-11 pe-3 py-1.5 text-sm duration-200 ease-in-out rounded"
                                    placeholder="Type minimum wage">
                            </span>
                            @error('maximum_income')
                                <p class="absolute top-full right-0 mt-1 text-red-500 text-xs">{{ $message }}
                                </p>
                            @enderror
                        </div>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
