<div class="flex flex-col h-[29rem] sm:h-full w-full rounded justify-between bg-white">
    <div class="">
        <div class="flex flex-row items-center justify-between my-2 mx-4">
            <p class="text-xl font-bold text-indigo-1100">
                By Barangay
            </p>

            <p class="text-base px-4 rounded-lg font-bold bg-indigo-100 text-indigo-900">
                {{ $batchesCount }}
            </p>
        </div>

        {{-- Add Foreach here --}}
        @foreach ($batches as $key => $batch)
            <div wire:key="{{ $key }}"
                class="flex flex-row items-center rounded-lg bg-indigo-50 shadow-sm p-2 mx-4 my-2">
                <div class="flex flex-col w-full">
                    {{-- Barangay Name --}}
                    <p class="text-md mx-1 mb-2 font-bold">{{ $batch->barangay_name }}</p>
                    <div class="flex flex-row items-center justify-between font-semibold mb-1">
                        <div class="text-xs flex justify-between w-full mx-2">
                            <p class="text-[#e74c3c]">Total Male</p>
                            <p class="">{{ $batch->total_male }}</p>
                        </div>
                        <div class="text-xs flex justify-between w-full mx-2">
                            <p class="text-[#d4ac0d]">Total Female</p>
                            <p class="">{{ $batch->total_female }}</p>
                        </div>
                    </div>
                    <div class="flex flex-row items-center justify-between mb-1">
                        <div class="text-xs flex justify-between w-full mx-2">
                            <p class="">Senior Citizens</p>
                            <p class="">{{ $batch->total_senior_male }}</p>
                        </div>
                        <div class="text-xs flex justify-between w-full mx-2">
                            <p class="">Senior Citizens</p>
                            <p class="">{{ $batch->total_senior_female }}</p>
                        </div>
                    </div>
                    <div class="flex flex-row items-center justify-between mb-1">
                        <div class="text-xs flex justify-between w-full mx-2">
                            <p class="">PWDs</p>
                            <p class="">{{ $batch->total_pwd_male }}</p>
                        </div>
                        <div class="text-xs flex justify-between w-full mx-2">
                            <p class="">PWDs</p>
                            <p class="">{{ $batch->total_pwd_female }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    {{ $batches->links() }}
</div>
