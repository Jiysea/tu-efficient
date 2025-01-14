<div x-data="{ expanded: false }" class="relative col-span-full">
    @if (
        $this->defaultShowDuplicates &&
            $this->beneficiaryId &&
            $this->isOverThreshold($this->beneficiary) &&
            !$this->isOriginal)
        <div class="flex items-center justify-between border rounded text-xs p-2 duration-200 ease-in-out"
            :class="{
                // A Perfect Duplicate
                'border-red-300 bg-red-50 text-red-900': {{ json_encode($this->hasPerfect) }},
                // A Possible Duplicate
                'border-amber-300 bg-amber-50 text-amber-900': {{ json_encode(!$this->hasPerfect) }},
            }">
            {{-- Possible Duplicates && Unresolved --}}
            @if (!$this->hasPerfect)
                <p class="inline mx-2">There are
                    possible
                    duplicates found
                    associated with this name.
                    <button type="button" @click="expanded = ! expanded"
                        class="outline-none underline underline-offset-2 font-bold">Show possible duplicates</button>
                </p>
            @elseif($this->hasPerfect)
                <p class="inline mx-2">There is a perfect duplicate
                    associated with this name.
                    <button type="button" @click="expanded = ! expanded"
                        class="outline-none underline underline-offset-2 font-bold">Show possible duplicates</button>
                </p>
            @endif
        </div>

        {{-- TABLE AREA --}}
        <div x-show="expanded"
            class="relative min-h-56 max-h-56 rounded border text-xs mt-2 overflow-x-auto overflow-y-auto scrollbar-thin 
            border-indigo-300 text-indigo-1100 scrollbar-track-indigo-50 scrollbar-thumb-indigo-700">
            <table class="relative w-full text-sm text-left select-auto">
                <thead class="text-xs z-20 uppercase sticky top-0 whitespace-nowrap bg-indigo-500 text-indigo-50">
                    <tr>
                        <th scope="col" class="ps-4 py-2">
                            similarity %
                        </th>
                        <th scope="col" class="p-2">
                            project number
                        </th>
                        <th scope="col" class="p-2">
                            batch number
                        </th>
                        <th scope="col" class="p-2">
                            first name
                        </th>
                        <th scope="col" class="p-2">
                            middle name
                        </th>
                        <th scope="col" class="p-2">
                            last name
                        </th>
                        <th scope="col" class="p-2">
                            ext.
                        </th>
                        <th scope="col" class="p-2">
                            birthdate
                        </th>
                        <th scope="col" class="p-2">
                            contact #
                        </th>
                        <th scope="col" class="p-2">
                            barangay
                        </th>
                        <th scope="col" class="p-2">
                            sex
                        </th>
                        <th scope="col" class="p-2">
                            age
                        </th>
                        <th scope="col" class="p-2">
                            beneficiary type
                        </th>
                        <th scope="col" class="p-2">
                            id type
                        </th>
                        <th scope="col" class="p-2">
                            id #
                        </th>
                        <th scope="col" class="p-2">
                            pwd
                        </th>
                        <th scope="col" class="p-2">
                            dependent
                        </th>
                    </tr>
                </thead>
                <tbody class="text-xs relative">
                    @forelse ($this->similarityResults ?? [] as $key => $result)
                        <tr wire:key='batch-{{ $key }}' class="relative whitespace-nowrap hover:bg-gray-50">
                            <td class="ps-4 py-2 font-medium">
                                {{ $result['coEfficient'] }}%
                            </td>
                            <td class="p-2">
                                {{ $result['project_num'] }}
                            </td>
                            <td class="p-2">
                                {{ $result['batch_num'] }}
                            </td>
                            <td class="p-2">
                                <span
                                    class="{{ mb_strtoupper($this->beneficiary?->first_name, 'UTF-8') === mb_strtoupper($result['first_name'], 'UTF-8') ? 'bg-red-200 text-red-900' : 'bg-amber-200 text-amber-900' }} z-50 rounded py-0.5 px-1.5">
                                    {{ $result['first_name'] }}
                                </span>
                            </td>
                            <td class="p-2">
                                <span class="rounded py-0.5 px-1.5"
                                    :class="{
                                        'bg-zinc-200 text-zinc-900': {{ json_encode(!isset($this->beneficiary?->middle_name)) }},
                                        'bg-red-200 text-red-900': {{ json_encode(mb_strtoupper($this->beneficiary?->middle_name, 'UTF-8') === mb_strtoupper($result['middle_name'], 'UTF-8')) }},
                                        'bg-amber-200 text-amber-900': {{ json_encode(mb_strtoupper($this->beneficiary?->middle_name, 'UTF-8') !== mb_strtoupper($result['middle_name'], 'UTF-8')) }},
                                    }">
                                    {{ $result['middle_name'] ?? '-' }}
                                </span>

                            </td>
                            <td class="p-2">
                                <span
                                    class="{{ mb_strtoupper($this->beneficiary?->last_name, 'UTF-8') === mb_strtoupper($result['last_name'], 'UTF-8') ? 'bg-red-200 text-red-900' : 'bg-amber-200 text-amber-900' }} rounded py-0.5 px-1.5">
                                    {{ $result['last_name'] }}
                                </span>

                            </td>
                            <td class="p-2">
                                <span class="rounded py-0.5 px-1.5"
                                    :class="{
                                        'bg-zinc-200 text-zinc-900': {{ json_encode(!isset($this->beneficiary?->extension_name)) }},
                                        'bg-red-200 text-red-900': {{ json_encode(mb_strtoupper($this->beneficiary?->extension_name, 'UTF-8') === mb_strtoupper($result['extension_name'], 'UTF-8')) }},
                                        'bg-amber-200 text-amber-900': {{ json_encode(mb_strtoupper($this->beneficiary?->extension_name, 'UTF-8') !== mb_strtoupper($result['extension_name'], 'UTF-8')) }},
                                    }">
                                    {{ $result['extension_name'] ?? '-' }}
                                </span>

                            </td>
                            <td class="p-2">
                                <span class="rounded py-0.5 px-1.5"
                                    :class="{
                                        'bg-zinc-200 text-zinc-900': {{ json_encode(!isset($this->beneficiary?->birthdate)) }},
                                        'bg-red-200 text-red-900': {{ json_encode(
                                            \App\Services\Essential::extract_date($this->beneficiary?->birthdate, true, 'Y-m-d') ===
                                                \App\Services\Essential::extract_date($result['birthdate'], true, 'Y-m-d'),
                                        ) }},
                                        'bg-amber-200 text-amber-900': {{ json_encode(
                                            \App\Services\Essential::extract_date($this->beneficiary?->birthdate, true, 'Y-m-d') !==
                                                \App\Services\Essential::extract_date($result['birthdate'], true, 'Y-m-d'),
                                        ) }},
                                    }">
                                    {{ \Carbon\Carbon::parse($result['birthdate'])->format('M d, Y') }}
                                </span>

                            </td>
                            <td class="p-2">
                                {{ $result['contact_num'] }}
                            </td>
                            <td class="p-2">
                                <span class="rounded py-0.5 px-1.5"
                                    :class="{
                                        'bg-zinc-200 text-zinc-900': {{ json_encode(!isset($this->batch?->barangay_name)) }},
                                        'bg-red-200 text-red-900': {{ json_encode($this->batch?->barangay_name === $result['barangay_name']) }},
                                        'bg-amber-200 text-amber-900': {{ json_encode($this->batch?->barangay_name !== $result['barangay_name']) }},
                                    }">
                                    {{ $result['barangay_name'] }}
                                </span>
                            </td>
                            <td class="p-2 capitalize">
                                {{ $result['sex'] }}
                            </td>
                            <td class="p-2">
                                {{ $result['age'] }}
                            </td>
                            <td class="p-2 capitalize">
                                {{ $result['beneficiary_type'] }}
                            </td>
                            <td class="p-2">
                                {{ $result['type_of_id'] }}
                            </td>
                            <td class="p-2">
                                {{ $result['id_number'] }}
                            </td>
                            <td class="p-2 capitalize">
                                {{ $result['is_pwd'] }}
                            </td>
                            <td class="p-2">
                                {{ $result['dependent'] ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td>No possible duplicates found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Legend --}}
        <div x-show="expanded" class="flex flex-wrap rounded text-xs mt-2 text-zinc-900">
            <p class="flex items-center justify-center gap-1">
                <span class="font-semibold">Legend:</span>
                <span class="flex items-center gap-1">
                    <span class="bg-zinc-200 text-zinc-900 rounded p-1">User input is empty</span>
                </span>
                <span class="flex items-center gap-1">
                    <span class="bg-red-200 text-red-900 rounded p-1">User input is similar to the
                        possible duplicate field</span>
                </span>
                <span class="flex items-center gap-1">
                    <span class="bg-amber-200 text-amber-900 rounded p-1">User input is different
                        from the
                        possible duplicate field</span>
                </span>
            </p>
        </div>
    @endif
</div>
