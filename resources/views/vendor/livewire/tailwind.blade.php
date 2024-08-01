<div class="flex items-center justify-center">
    @if ($paginator->hasPages())
        <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between">
            <div class="flex-1 flex items-center justify-between">
                <div>
                    <span class="relative z-0 inline-flex items-center rounded-md shadow-sm">
                        <span class="mx-1 mt-2 mb-4">
                            {{-- Previous Page Link --}}
                            @if ($paginator->onFirstPage())
                                <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                                    <span
                                        class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-indigo-200 bg-indigo-100 cursor-default rounded-l-md leading-5"
                                        aria-hidden="true">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                </span>
                            @else
                                <button type="button"
                                    wire:click.prevent="previousPage('{{ $paginator->getPageName() }}')"
                                    wire:loading.attr="disabled"
                                    class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-indigo-50 bg-indigo-900 rounded-l-md leading-5 hover:text-indigo-100 hover:bg-indigo-800 focus:z-10 focus:outline-none focus:border-transparent focus:ring ring-indigo-100 active:bg-indigo-1000 active:text-indigo-200 transition ease-in-out duration-150"
                                    aria-label="{{ __('pagination.previous') }}">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            @endif
                        </span>

                        {{-- Pagination Elements --}}
                        <span class="mx-1 mt-2 mb-4">
                            <p class="text-sm text-indigo-1100 leading-5 ">
                                <span>{!! __('Page') !!}</span>
                                <span class="font-bold">{{ $paginator->currentPage() }}</span>
                                <span>{!! __('of') !!}</span>
                                <span class="font-bold">{{ $paginator->lastPage() }}</span>
                            </p>
                        </span>

                        <span class="mx-1 mt-2 mb-4">
                            {{-- Next Page Link --}}
                            @if ($paginator->hasMorePages())
                                <button type="button" wire:click.prevent="nextPage('{{ $paginator->getPageName() }}')"
                                    wire:loading.attr="disabled"
                                    class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-indigo-50 bg-indigo-900 rounded-r-md leading-5 hover:text-indigo-100 hover:bg-indigo-800 focus:z-10 focus:outline-none focus:border-transparent focus:ring ring-indigo-100 active:bg-indigo-1000 active:text-indigo-200 transition ease-in-out duration-150"
                                    aria-label="{{ __('pagination.next') }}">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            @else
                                <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                                    <span
                                        class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-indigo-200 bg-indigo-100 cursor-default rounded-r-md leading-5"
                                        aria-hidden="true">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                </span>
                            @endif
                        </span>
                    </span>
                </div>
            </div>
        </nav>
    @endif
</div>
