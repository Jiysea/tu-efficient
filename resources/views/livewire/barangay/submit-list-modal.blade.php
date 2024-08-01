<div id="submit-modal" tabindex="-1"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow ">
            <button type="button"
                class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-green-200 hover:text-green-900 focus:outline-none rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center transition duration-300 ease-in-out"
                data-modal-hide="submit-modal">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
                <span class="sr-only">Close modal</span>
            </button>
            <div class="p-4 md:p-5 text-center">
                <svg class="mx-auto mb-4 text-green-400 w-12 h-12" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <h3 class="text-lg font-bold text-green-1100">Are you sure you want to
                    submit this list?</h3>
                <p class="text-sm mb-5 text-green-1100">(This access code will become unaccessible)</p>
                <a href="{{ route('login') }}">
                    <button type="button"
                        class="text-white bg-green-1000 hover:bg-green-900 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center transition duration-300 ease-in-out">
                        Yes, I'm sure
                    </button>
                </a>
                <button data-modal-hide="submit-modal" type="button"
                    class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-300 hover:bg-gray-100 hover:text-green-1000 focus:z-10 focus:ring-4 focus:ring-gray-100 transition duration-300 ease-in-out">No,
                    cancel</button>
            </div>
        </div>
    </div>
</div>
