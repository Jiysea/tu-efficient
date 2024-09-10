<div wire:ignore.self id="add-beneficiaries-modal" data-modal-backdrop="static" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div x-data="{
        init() {
            window.addEventListener('add-beneficiaries', () => {
                const modal = FlowbiteInstances.getInstance('Modal', 'add-beneficiaries-modal');
                modal.hide();
            });
        }
    }" class="relative p-4 w-full max-w-7xl max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-md shadow">
            <!-- Modal header -->
            <div class="flex items-center justify-between py-2 px-4 rounded-t-md">
                <h1 class="text-lg font-semibold text-indigo-1100 ">
                    Add New Beneficiaries
                </h1>
                <div class="flex items-center justify-center">
                    <button type="button"
                        class="text-indigo-400 bg-transparent focus:bg-indigo-200 focus:text-indigo-900 hover:bg-indigo-200 hover:text-indigo-900 outline-none rounded size-8 ms-auto inline-flex justify-center items-center focus:outline-none duration-200 ease-in-out"
                        data-modal-toggle="add-beneficiaries-modal" @click="trapAdd = false">
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
            <form wire:submit.prevent="saveBeneficiary" class="p-4 md:p-5">
                @csrf
                <div class="grid gap-4 mb-4 grid-cols-10 text-xs">

                    {{-- First Name --}}
                    <div class="relative col-span-full sm:col-span-3 mb-4 pb-1">
                        <label for="first_name" class="block mb-1 font-medium text-indigo-1100 ">First Name <span
                                class="text-red-700 font-normal text-sm">*</span></label>
                        <input type="text" id="first_name" autocomplete="off"
                            @blur="$wire.set('first_name', $el.value); $wire.nameCheck();"
                            class="text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out {{ $errors->has('first_name') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }}"
                            placeholder="Type first name">
                        @error('first_name')
                            <p class="text-red-500 absolute left-2 -bottom-4 z-10 text-xs">{{ $message }}</p>
                        @enderror
                    </div>
                    {{-- Middle Name --}}
                    <div class="relative col-span-full sm:col-span-2 mb-4 pb-1">
                        <label for="middle_name" class="block mb-1  font-medium text-indigo-1100 ">Middle Name</label>
                        <input type="text" id="middle_name" autocomplete="off"
                            @blur="$wire.set('middle_name', $el.value); $wire.nameCheck();"
                            class="text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600"
                            placeholder="(optional)">
                    </div>
                    {{-- Last Name --}}
                    <div class="relative col-span-full sm:col-span-2 mb-4 pb-1">
                        <label for="last_name" class="block mb-1  font-medium text-indigo-1100 ">Last Name <span
                                class="text-red-700 font-normal text-sm">*</span></label>
                        <input type="text" id="last_name" autocomplete="off"
                            @blur="$wire.set('last_name', $el.value); $wire.nameCheck();"
                            class="text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out {{ $errors->has('last_name') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }}"
                            placeholder="Type last name">
                        @error('last_name')
                            <p class="text-red-500 absolute left-2 -bottom-4 z-10 text-xs">{{ $message }}</p>
                        @enderror
                    </div>
                    {{-- Extension Name --}}
                    <div class="relative col-span-full sm:col-span-1 mb-4 pb-1">
                        <label for="extension_name" class="block mb-1 font-medium text-indigo-1100 ">Ext. Name</label>
                        <input type="text" id="extension_name" autocomplete="off"
                            @blur="$wire.set('extension_name', $el.value); $wire.nameCheck();"
                            class="text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600"
                            placeholder="III, Sr., etc.">
                    </div>
                    {{-- Birthdate --}}
                    <div class="relative col-span-full sm:col-span-2 mb-4 pb-1">
                        <label for="birthdate" class="block mb-1  font-medium text-indigo-1100 ">Birthdate <span
                                class="text-red-700 font-normal text-sm">*</span></label>
                        <div class="absolute start-0 bottom-3.5 flex items-center ps-3 pointer-events-none">
                            <svg class="size-4 duration-200 ease-in-out {{ $errors->has('birthdate') ? 'text-red-700' : 'text-indigo-900' }}"
                                aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path
                                    d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                            </svg>
                        </div>
                        <input datepicker datepicker-autohide datepicker-format="mm-dd-yyyy"
                            datepicker-min-date='{{ $minDate }}' datepicker-max-date='{{ $maxDate }}'
                            id="birthdate" autocomplete="off"
                            class="text-xs border outline-none rounded block w-full py-2 ps-9 duration-200 ease-in-out {{ $errors->has('birthdate') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }}"
                            placeholder="Select date">
                        @error('birthdate')
                            <p class="text-red-500 absolute left-2 -bottom-4 z-10 text-xs">{{ $message }}</p>
                        @enderror
                    </div>
                    {{-- Contact Number --}}
                    <div class="relative col-span-full sm:col-span-2 mb-4 pb-1">
                        <label for="contact_num" class="block mb-1 font-medium text-indigo-1100 ">Contact
                            Number <span class="text-red-700 font-normal text-sm">*</span></label>
                        <div {{-- x-effect="console.log(unmaskedBudget)" --}} class="relative">
                            <div
                                class="text-xs outline-none absolute inset-y-0 px-2 rounded-l flex items-center justify-center text-center duration-200 ease-in-out pointer-events-none {{ $errors->has('contact_num') ? ' bg-red-400 text-red-900 border border-red-500' : 'bg-indigo-700 text-indigo-50' }}">
                                <p class="flex text-center w-full relative items-center justify-center font-medium">
                                    +63
                                </p>
                            </div>
                            <input x-mask="99999999999" type="text" inputmode="numeric" min="0"
                                autocomplete="off" id="contact_num" @input="$wire.set('contact_num', $el.value);"
                                class="text-xs outline-none border ps-12 rounded block w-full pe-2 py-2 duration-200 ease-in-out {{ $errors->has('contact_num') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50  border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }}"
                                placeholder="ex. 09123456789">
                        </div>
                        @error('contact_num')
                            <p class="text-red-500 absolute left-2 -bottom-4 z-10 text-xs">{{ $message }}
                            </p>
                        @enderror
                    </div>
                    {{-- E-payment Account Number --}}
                    <div class="relative col-span-full sm:col-span-2 mb-4 pb-1">
                        <label for="e_payment_acc_num" class="block mb-1 font-medium text-indigo-1100 ">E-payment
                            Account No.</label>
                        <input type="text" id="e_payment_acc_num" autocomplete="off"
                            wire:model.blur="e_payment_acc_num"
                            class="text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600"
                            placeholder="Type e-payment account number">
                    </div>
                    {{-- Type of Beneficiary --}}
                    <div class="relative col-span-full sm:col-span-2 mb-4 pb-1">
                        <p class="mb-1 font-medium text-indigo-1100">Type of Beneficiary</p>
                        <div x-data="{
                            open: false,
                            beneficiary_type: $wire.entangle('beneficiary_type'),
                            toggle() {
                                if (this.open) {
                                    return this.close()
                                }
                        
                                this.$refs.beneficiaryTypeButton.focus()
                        
                                this.open = true
                            },
                            close(focusAfter) {
                                if (!this.open) return
                        
                                this.open = false
                        
                                focusAfter && focusAfter.focus()
                            },
                            selectOption(option) {
                                this.beneficiary_type = option;
                                this.close(this.$refs.beneficiaryTypeButton); // Close the dropdown after selecting an option
                            }
                        }"
                            x-on:keydown.escape.prevent.stop="close($refs.beneficiaryTypeButton)"
                            x-on:focusin.window="! $refs.panel.contains($event.target) && close()"
                            x-id="['beneficiary-type-button']" class="relative">
                            <!-- Button -->
                            <button x-ref="beneficiaryTypeButton" x-on:click="toggle()" :aria-expanded="open"
                                :aria-controls="$id('beneficiary-type-button')" type="button"
                                class="flex items-center justify-between w-full p-2 rounded text-xs border outline-1 duration-200 ease-in-out group bg-indigo-50 border-indigo-300 text-indigo-1100 outline-indigo-300 focus:outline-indigo-600 focus:border-indigo-600">
                                <span x-text="beneficiary_type"></span>
                                <!-- Display selected option -->

                                <!-- Heroicon: chevron-down -->
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="size-4 text-indigo-1100 group-hover:text-indigo-900 group-active:text-indigo-1000 duration-200 ease-in-out"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>

                            <!-- Panel -->
                            <div x-ref="panel" x-show="open" x-transition.origin.top.left
                                x-on:click.outside="close($refs.beneficiaryTypeButton)"
                                :id="$id('beneficiary-type-button')" style="display: none;"
                                class="absolute left-0 mt-2 w-full z-50 rounded bg-indigo-50 shadow-lg border border-indigo-500">
                                <button type="button" x-on:click="selectOption('Underemployed')"
                                    class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-indigo-1100 hover:text-indigo-900 focus:text-indigo-900 active:text-indigo-1000 hover:bg-indigo-100 focus:bg-indigo-100 active:bg-indigo-200">
                                    Underemployed
                                </button>

                                <button type="button" x-on:click="selectOption('Calamity Victim')"
                                    class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-indigo-1100 hover:text-indigo-900 focus:text-indigo-900 active:text-indigo-1000 hover:bg-indigo-100 focus:bg-indigo-100 active:bg-indigo-200">
                                    Calamity Victim
                                </button>
                            </div>
                        </div>
                    </div>
                    {{-- Occupation --}}
                    <div class="relative col-span-full sm:col-span-2 mb-4 pb-1">
                        <label for="occupation" class="block mb-1  font-medium text-indigo-1100 ">Occupation</label>
                        <input type="text" id="occupation" autocomplete="off" wire:model.blur="occupation"
                            class="text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600"
                            placeholder="Type occupation">
                    </div>
                    {{-- Sex --}}
                    <div class="relative col-span-full sm:col-span-1 mb-4 pb-1">
                        <p class="mb-1 font-medium text-indigo-1100 ">Sex</p>
                        <div x-data="{
                            open: false,
                            sex: $wire.entangle('sex'),
                            toggle() {
                                if (this.open) {
                                    return this.close()
                                }
                        
                                this.$refs.sexButton.focus()
                        
                                this.open = true
                            },
                            close(focusAfter) {
                                if (!this.open) return
                        
                                this.open = false
                        
                                focusAfter && focusAfter.focus()
                            },
                            selectOption(option) {
                                this.sex = option;
                                this.close(this.$refs.sexButton); // Close the dropdown after selecting an option
                            }
                        }" x-on:keydown.escape.prevent.stop="close($refs.sexButton)"
                            x-on:focusin.window="! $refs.panel.contains($event.target) && close()"
                            x-id="['sex-button']" class="relative">
                            <!-- Button -->
                            <button x-ref="sexButton" x-on:click="toggle()" :aria-expanded="open"
                                :aria-controls="$id('sex-button')" type="button"
                                class="flex items-center justify-between w-full p-2 rounded text-xs border outline-1 duration-200 ease-in-out group bg-indigo-50 border-indigo-300 text-indigo-1100 outline-indigo-300 focus:outline-indigo-600 focus:border-indigo-600">
                                <span x-text="sex"></span> <!-- Display selected option -->

                                <!-- Heroicon: chevron-down -->
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="size-4 text-indigo-1100 group-hover:text-indigo-900 group-active:text-indigo-1000 duration-200 ease-in-out"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>

                            <!-- Panel -->
                            <div x-ref="panel" x-show="open" x-transition.origin.top.left
                                x-on:click.outside="close($refs.sexButton)" :id="$id('sex-button')"
                                style="display: none;"
                                class="absolute left-0 mt-2 w-full z-50 rounded bg-indigo-50 shadow-lg border border-indigo-500">
                                <button type="button" x-on:click="selectOption('Male')"
                                    class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-indigo-1100 hover:text-indigo-900 focus:text-indigo-900 active:text-indigo-1000 hover:bg-indigo-100 focus:bg-indigo-100 active:bg-indigo-200">
                                    Male
                                </button>

                                <button type="button" x-on:click="selectOption('Female')"
                                    class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-indigo-1100 hover:text-indigo-900 focus:text-indigo-900 active:text-indigo-1000 hover:bg-indigo-100 focus:bg-indigo-100 active:bg-indigo-200">
                                    Female
                                </button>
                            </div>
                        </div>
                    </div>
                    {{-- Civil Status --}}
                    <div class="relative col-span-full sm:col-span-1 mb-4 pb-1">
                        <p class="mb-1 font-medium text-indigo-1100 ">Civil Status</p>
                        <div x-data="{
                            open: false,
                            civil_status: $wire.entangle('civil_status'),
                            spouse_first_name: $wire.entangle('spouse_first_name'),
                            spouse_middle_name: $wire.entangle('spouse_middle_name'),
                            spouse_last_name: $wire.entangle('spouse_last_name'),
                            spouse_extension_name: $wire.entangle('spouse_extension_name'),
                            toggle() {
                                if (this.open) {
                                    return this.close()
                                }
                        
                                this.$refs.civilStatusButton.focus()
                        
                                this.open = true
                            },
                            close(focusAfter) {
                                if (!this.open) return
                        
                                this.open = false
                        
                                focusAfter && focusAfter.focus()
                            },
                            selectOption(option) {
                                this.civil_status = option;
                        
                                if (this.civil_status === 'Single') {
                                    this.spouse_first_name = null;
                                    this.spouse_middle_name = null;
                                    this.spouse_last_name = null;
                                    this.spouse_extension_name = null;
                                }
                                this.close(this.$refs.civilStatusButton); // Close the dropdown after selecting an option
                            }
                        }" x-on:keydown.escape.prevent.stop="close($refs.civilStatusButton)"
                            x-on:focusin.window="! $refs.panel.contains($event.target) && close()"
                            x-id="['civil-status-button']" class="relative">
                            <!-- Button -->
                            <button x-ref="civilStatusButton" x-on:click="toggle()" :aria-expanded="open"
                                :aria-controls="$id('civil_status-button')" type="button"
                                class="flex items-center justify-between w-full p-2 rounded text-xs border outline-1 duration-200 ease-in-out group bg-indigo-50 border-indigo-300 text-indigo-1100 outline-indigo-300 focus:outline-indigo-600 focus:border-indigo-600">
                                <span x-text="civil_status"></span> <!-- Display selected option -->

                                <!-- Heroicon: chevron-down -->
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="size-4 text-indigo-1100 group-hover:text-indigo-900 group-active:text-indigo-1000 duration-200 ease-in-out"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>

                            <!-- Panel -->
                            <div x-ref="panel" x-show="open" x-transition.origin.top.left
                                x-on:click.outside="close($refs.civilStatusButton)" :id="$id('civil-status-button')"
                                style="display: none;"
                                class="absolute left-0 mt-2 w-full z-50 rounded bg-indigo-50 shadow-lg border border-indigo-500">
                                <button type="button" x-on:click="selectOption('Single'); $wire.$refresh();"
                                    class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-indigo-1100 hover:text-indigo-900 focus:text-indigo-900 active:text-indigo-1000 hover:bg-indigo-100 focus:bg-indigo-100 active:bg-indigo-200">
                                    Single
                                </button>

                                <button type="button" x-on:click="selectOption('Married'); $wire.$refresh();"
                                    class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-indigo-1100 hover:text-indigo-900 focus:text-indigo-900 active:text-indigo-1000 hover:bg-indigo-100 focus:bg-indigo-100 active:bg-indigo-200">
                                    Married
                                </button>
                            </div>
                        </div>
                    </div>
                    {{-- Average Monthly Income --}}
                    <div x-data="{
                        budgetToFloat: null,
                        budgetToInt: null,
                        unmaskedBudget: null,
                    
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
                    }" class="relative col-span-full sm:col-span-2 mb-4 pb-1">
                        <label for="avg_monthly_income" class="block mb-1  font-medium text-indigo-1100 ">Average
                            Monthly
                            Income</label>
                        <div class="relative">
                            <div
                                class="text-sm duration-200 ease-in-out absolute inset-y-0 px-3 rounded-l flex items-center justify-center text-center pointer-events-none {{ $errors->has('avg_monthly_income') ? ' bg-red-400 text-red-900 border border-red-500' : 'bg-indigo-700 text-indigo-50' }}">
                                <p class="flex text-center w-full relative items-center justify-center font-medium">₱
                                </p>
                            </div>
                            <input x-mask:dynamic="$money($input)" type="text" inputmode="numeric" min="0"
                                autocomplete="off" id="avg_monthly_income"
                                @input="demaskValue($el.value);
                                $wire.set('avg_monthly_income', unmaskedBudget);"
                                class="text-xs outline-none border ps-10 rounded block w-full pe-2 py-2 duration-200 ease-in-out {{ $errors->has('avg_monthly_income') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50  border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }}"
                                placeholder="0.00">
                        </div>
                        @error('avg_monthly_income')
                            <p class="text-red-500 absolute left-2 -bottom-4 z-10 text-xs">{{ $message }}
                            </p>
                        @enderror
                    </div>
                    {{-- Dependent --}}
                    <div class="relative col-span-full sm:col-span-3 mb-4 pb-1">
                        <div class="flex items-center">
                            <label for="dependent" class="block mb-1 font-medium text-indigo-1100 ">Dependent</label>
                            <p class="block mb-1 ms-2 text-gray-500 ">(must be 18+ years old)</p>
                        </div>
                        <input type="text" id="dependent" autocomplete="off" wire:model.blur="dependent"
                            class="text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600"
                            placeholder="Type dependent's name">
                    </div>
                    {{-- Interested in Wage Employment or Self-Employment --}}
                    <div class="relative col-span-full sm:col-span-2 mb-4 pb-1">
                        <div class="flex items-center">
                            <p class="inline mb-1 font-medium text-indigo-1100">I.i.W.E.o.S.E.</p>
                            <div class="relative flex items-center" id="self-employment-question-mark">
                                <svg @mouseenter="$store.selfEmploymentPopover.toggle()"
                                    @mouseleave="$store.selfEmploymentPopover.toggle()"
                                    class="size-3 outline-none duration-200 ease-in-out cursor-pointer block mb-1 ms-1 rounded-full text-gray-500 hover:text-indigo-700"
                                    aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path
                                        d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm0 16a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3Zm1-5.034V12a1 1 0 0 1-2 0v-1.418a1 1 0 0 1 1.038-.999 1.436 1.436 0 0 0 1.488-1.441 1.501 1.501 0 1 0-3-.116.986.986 0 0 1-1.037.961 1 1 0 0 1-.96-1.037A3.5 3.5 0 1 1 11 11.466Z" />
                                </svg>
                                {{-- Popovers --}}
                                <div id="self-employment-popover" x-transition.origin.bottom
                                    class="absolute z-50 text-xs whitespace-nowrap text-indigo-50 bg-indigo-900 rounded ms-2 p-2 shadow"
                                    :style='$store.selfEmploymentPopover.styles'
                                    x-show="$store.selfEmploymentPopover.on">
                                    <b>I</b>nterested <b>i</b>n <b>W</b>age <b>E</b>mployment
                                    <b>o</b>r
                                    <b>S</b>elf-<b>E</b>mployment?
                                </div>
                            </div>
                        </div>
                        <div x-data="{
                            open: false,
                            self_employment: $wire.entangle('self_employment'),
                            toggle() {
                                if (this.open) {
                                    return this.close()
                                }
                        
                                this.$refs.selfEmploymentButton.focus()
                        
                                this.open = true
                            },
                            close(focusAfter) {
                                if (!this.open) return
                        
                                this.open = false
                        
                                focusAfter && focusAfter.focus()
                            },
                            selectOption(option) {
                                this.self_employment = option;
                                this.close(this.$refs.selfEmploymentButton); // Close the dropdown after selecting an option
                            }
                        }"
                            x-on:keydown.escape.prevent.stop="close($refs.selfEmploymentButton)"
                            x-on:focusin.window="! $refs.panel.contains($event.target) && close()"
                            x-id="['self-employment-button']" class="relative">
                            <!-- Button -->
                            <button x-ref="selfEmploymentButton" x-on:click="toggle()" :aria-expanded="open"
                                :aria-controls="$id('self-employment-button')" type="button"
                                class="flex items-center justify-between w-full p-2 rounded text-xs border outline-1 duration-200 ease-in-out group bg-indigo-50 border-indigo-300 text-indigo-1100 outline-indigo-300 focus:outline-indigo-600 focus:border-indigo-600">
                                <span x-text="self_employment"></span> <!-- Display selected option -->

                                <!-- Heroicon: chevron-down -->
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="size-4 text-indigo-1100 group-hover:text-indigo-900 group-active:text-indigo-1000 duration-200 ease-in-out"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>

                            <!-- Panel -->
                            <div x-ref="panel" x-show="open" x-transition.origin.top.left
                                x-on:click.outside="close($refs.selfEmploymentButton)"
                                :id="$id('self-employment-button')" style="display: none;"
                                class="absolute left-0 mt-2 w-full z-50 rounded bg-indigo-50 shadow-lg border border-indigo-500">
                                <button type="button" x-on:click="selectOption('Yes')"
                                    class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-indigo-1100 hover:text-indigo-900 focus:text-indigo-900 active:text-indigo-1000 hover:bg-indigo-100 focus:bg-indigo-100 active:bg-indigo-200">
                                    Yes
                                </button>

                                <button type="button" x-on:click="selectOption('No')"
                                    class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-indigo-1100 hover:text-indigo-900 focus:text-indigo-900 active:text-indigo-1000 hover:bg-indigo-100 focus:bg-indigo-100 active:bg-indigo-200">
                                    No
                                </button>
                            </div>
                        </div>
                    </div>
                    {{-- Skills Training Needed --}}
                    <div class="relative col-span-full sm:col-span-2 mb-4 pb-1">
                        <p class="mb-1 font-medium text-indigo-1100 ">Skills Training Needed?</p>
                        <div x-data="{
                            open: false,
                            skills_training: $wire.entangle('skills_training'),
                            toggle() {
                                if (this.open) {
                                    return this.close()
                                }
                        
                                this.$refs.button.focus()
                        
                                this.open = true
                            },
                            close(focusAfter) {
                                if (!this.open) return
                        
                                this.open = false
                        
                                focusAfter && focusAfter.focus()
                            },
                            selectOption(option) {
                                this.skills_training = option;
                                this.close(this.$refs.button); // Close the dropdown after selecting an option
                            }
                        }" x-on:keydown.escape.prevent.stop="close($refs.button)"
                            x-on:focusin.window="! $refs.panel.contains($event.target) && close()" x-id="['button']"
                            class="relative">
                            <!-- Button -->
                            <button x-ref="button" x-on:click="toggle()" :aria-expanded="open"
                                :aria-controls="$id('button')" type="button"
                                class="flex items-center justify-between w-full p-2 rounded text-xs border outline-1 duration-200 ease-in-out group bg-indigo-50 border-indigo-300 text-indigo-1100 outline-indigo-300 focus:outline-indigo-600 focus:border-indigo-600">
                                <span x-text="skills_training"></span> <!-- Display selected option -->

                                <!-- Heroicon: chevron-down -->
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="size-4 text-indigo-1100 group-hover:text-indigo-900 group-active:text-indigo-1000 duration-200 ease-in-out"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>

                            <!-- Panel -->
                            <div x-ref="panel" x-show="open" x-transition.origin.top.left
                                x-on:click.outside="close($refs.button)" :id="$id('button')"
                                style="display: none;"
                                class="absolute left-0 mt-2 w-full z-50 rounded bg-indigo-50 shadow-lg border border-indigo-500">
                                <button type="button" x-on:click="selectOption('Yes')"
                                    class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-indigo-1100 hover:text-indigo-900 focus:text-indigo-900 active:text-indigo-1000 hover:bg-indigo-100 focus:bg-indigo-100 active:bg-indigo-200">
                                    Yes
                                </button>

                                <button type="button" x-on:click="selectOption('No')"
                                    class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-indigo-1100 hover:text-indigo-900 focus:text-indigo-900 active:text-indigo-1000 hover:bg-indigo-100 focus:bg-indigo-100 active:bg-indigo-200">
                                    No
                                </button>
                            </div>
                        </div>
                    </div>
                    {{-- Is PWD? --}}
                    <div class="relative col-span-full sm:col-span-1 mb-4 pb-1">
                        <div class="flex items-center">
                            <p class="inline mb-1 font-medium text-indigo-1100">Is PWD?</p>
                            {{-- Popover Thingy --}}
                            <div class="relative flex items-center" id="is-pwd-question-mark">
                                <svg @mouseenter="$store.isPWDPopover.toggle()"
                                    @mouseleave="$store.isPWDPopover.toggle()"
                                    class="size-3 outline-none duration-200 ease-in-out cursor-pointer block mb-1 ms-1 rounded-full text-gray-500 hover:text-indigo-700"
                                    aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path
                                        d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm0 16a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3Zm1-5.034V12a1 1 0 0 1-2 0v-1.418a1 1 0 0 1 1.038-.999 1.436 1.436 0 0 0 1.488-1.441 1.501 1.501 0 1 0-3-.116.986.986 0 0 1-1.037.961 1 1 0 0 1-.96-1.037A3.5 3.5 0 1 1 11 11.466Z" />
                                </svg>
                                {{-- Popover --}}
                                <div id="is-pwd-popover" x-transition.origin.bottom.right
                                    class="absolute z-50 text-xs whitespace-nowrap text-indigo-50 bg-indigo-900 rounded p-2 shadow"
                                    :style='$store.isPWDPopover.styles' x-show="$store.isPWDPopover.on">
                                    PWD stands for <b>P</b>erson <b>w</b>ith
                                    <b>D</b>isability
                                </div>
                            </div>
                        </div>
                        <div x-data="{
                            open: false,
                            is_pwd: $wire.entangle('is_pwd'),
                            toggle() {
                                if (this.open) {
                                    return this.close()
                                }
                        
                                this.$refs.isPWDButton.focus()
                        
                                this.open = true
                            },
                            close(focusAfter) {
                                if (!this.open) return
                        
                                this.open = false
                        
                                focusAfter && focusAfter.focus()
                            },
                            selectOption(option) {
                                this.is_pwd = option;
                                this.close(this.$refs.isPWDButton); // Close the dropdown after selecting an option
                            }
                        }" x-on:keydown.escape.prevent.stop="close($refs.isPWDButton)"
                            x-on:focusin.window="! $refs.panel.contains($event.target) && close()"
                            x-id="['is-pwd-button']" class="relative">
                            <!-- Button -->
                            <button x-ref="isPWDButton" x-on:click="toggle()" :aria-expanded="open"
                                :aria-controls="$id('is-pwd-button')" type="button"
                                class="flex items-center justify-between w-full p-2 rounded text-xs border outline-1 duration-200 ease-in-out group bg-indigo-50 border-indigo-300 text-indigo-1100 outline-indigo-300 focus:outline-indigo-600 focus:border-indigo-600">
                                <span x-text="is_pwd"></span> <!-- Display selected option -->

                                <!-- Heroicon: chevron-down -->
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="size-4 text-indigo-1100 group-hover:text-indigo-900 group-active:text-indigo-1000 duration-200 ease-in-out"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>

                            <!-- Panel -->
                            <div x-ref="panel" x-show="open" x-transition.origin.top.left
                                x-on:click.outside="close($refs.isPWDButton)" :id="$id('is-pwd-button')"
                                style="display: none;"
                                class="absolute left-0 mt-2 w-full z-50 rounded bg-indigo-50 shadow-lg border border-indigo-500">
                                <button type="button" x-on:click="selectOption('Yes'); $wire.$refresh();"
                                    class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-indigo-1100 hover:text-indigo-900 focus:text-indigo-900 active:text-indigo-1000 hover:bg-indigo-100 focus:bg-indigo-100 active:bg-indigo-200">
                                    Yes
                                </button>

                                <button type="button"
                                    x-on:click="
                                selectOption('No'); 
                                $wire.$refresh();
                                if ($wire.type_of_id === 'Person\'s With Disability (PWD) ID') {
                                    $wire.type_of_id = 'e-Card / UMID';
                                }
                                "
                                    class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-indigo-1100 hover:text-indigo-900 focus:text-indigo-900 active:text-indigo-1000 hover:bg-indigo-100 focus:bg-indigo-100 active:bg-indigo-200">
                                    No
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Bottom Section --}}
                    <div class="relative grid gap-x-4 gap-y-2 grid-cols-10 col-span-full grid-rows-2">
                        {{-- Proof of Identity --}}
                        <div class="relative col-span-full row-span-full sm:col-span-2 mb-4 pb-1">
                            <div class="flex flex-col items-start">
                                <div class="flex items-center">
                                    <p class="inline mb-1 font-medium text-indigo-1100">Proof of Identity <span
                                            class="text-gray-500">(optional)</span></p>
                                    {{-- Popover Thingy --}}
                                    <div class="relative flex items-center" id="identity-question-mark">
                                        <svg @mouseenter="$store.identityPopover.toggle()"
                                            @mouseleave="$store.identityPopover.toggle()"
                                            class="size-3 outline-none duration-200 ease-in-out cursor-pointer block mb-1 ms-1 rounded-full text-gray-500 hover:text-indigo-700"
                                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path
                                                d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm0 16a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3Zm1-5.034V12a1 1 0 0 1-2 0v-1.418a1 1 0 0 1 1.038-.999 1.436 1.436 0 0 0 1.488-1.441 1.501 1.501 0 1 0-3-.116.986.986 0 0 1-1.037.961 1 1 0 0 1-.96-1.037A3.5 3.5 0 1 1 11 11.466Z" />
                                        </svg>
                                        {{-- Popover --}}
                                        <div id="identity-popover" x-transition.origin.bottom
                                            class="absolute z-50 text-xs whitespace-nowrap text-indigo-50 bg-indigo-900 rounded p-2 shadow"
                                            :style='$store.identityPopover.styles' x-show="$store.identityPopover.on">
                                            It's basically an image of a beneficiary's ID card <br>
                                            to prove that their identity is legitimate.
                                        </div>
                                    </div>
                                </div>

                                <label for="image_file_path"
                                    class="flex flex-col items-center justify-center w-full h-40 border-2 border-indigo-300 border-dashed rounded cursor-pointer bg-indigo-50">
                                    {{-- Loading State for Changes --}}
                                    <div class="absolute items-center justify-center w-full h-[88%] z-50 text-indigo-900"
                                        wire:loading.flex wire:target="image_file_path">
                                        <div class="absolute bg-black opacity-5 rounded min-w-full min-h-full z-50">
                                            {{-- Darkness... --}}
                                        </div>
                                        <svg class="size-6 animate-spin" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4">
                                            </circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div class="relative flex flex-col items-center justify-center py-6">
                                        @if ($image_file_path && !$errors->has('image_file_path'))
                                            <img class="size-28" src="{{ $image_file_path->temporaryUrl() }}">
                                        @else
                                            <svg class="size-8 mb-4 text-gray-500" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 20 16">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2"
                                                    d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                            </svg>
                                            <p class="mb-2 text-xs text-gray-500"><span class="font-semibold">Click to
                                                    upload</span> or drag and drop</p>
                                            <p class="text-xs text-gray-500 ">PNG or JPG (MAX. 5MB)</p>
                                        @endif
                                    </div>
                                    <input id="image_file_path" wire:model="image_file_path" type="file"
                                        accept=".png,.jpg,.jpeg" class="hidden" />
                                </label>
                            </div>
                            @error('image_file_path')
                                <p
                                    class="text-center whitespace-nowrap w-full text-red-500 absolute -bottom-4 z-10 text-xs">
                                    {{ $message }}</p>
                            @enderror
                        </div>
                        {{-- ID Type --}}
                        <div class="relative col-span-full sm:col-span-4 sm:row-span-1 mb-4 pb-1">
                            <p class="mb-1 font-medium text-indigo-1100 ">ID Type</p>
                            <div x-data="{
                                open: false,
                                type_of_id: $wire.entangle('type_of_id'),
                                toggle() {
                                    this.open = !this.open;
                                },
                            
                                selectOption(option) {
                                    this.type_of_id = option;
                                    this.toggle(); // Close the dropdown after selecting an option
                                }
                            }" x-id="['button']" class="relative"
                                x-on:click.outside="open = false">
                                <!-- Button -->
                                <button x-ref="button" x-on:click="open = !open" :aria-expanded="open"
                                    :aria-controls="$id('button')" type="button"
                                    class="flex items-center justify-between w-full p-2 rounded text-xs border outline-1 duration-200 ease-in-out group bg-indigo-50 border-indigo-300 text-indigo-1100 outline-indigo-300 focus:outline-indigo-600 focus:border-indigo-600">
                                    <span x-text="type_of_id"></span> <!-- Display selected option -->

                                    <!-- Heroicon: chevron-down -->
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="size-4 text-indigo-1100 group-hover:text-indigo-900 group-active:text-indigo-1000 duration-200 ease-in-out"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>

                                <!-- Panel -->
                                <div x-ref="panel" x-show="open" x-transition.origin.top :id="$id('button')"
                                    style="display: none;"
                                    class="absolute left-0 mt-2 max-h-[10rem] w-full z-50 rounded bg-indigo-50 shadow-lg border overflow-y-scroll border-indigo-500 scrollbar-thin scrollbar-track-indigo-50 scrollbar-thumb-indigo-700">
                                    @if ($is_pwd === 'Yes')
                                        <button type="button"
                                            x-on:click="selectOption('Person\'s With Disability (PWD) ID')"
                                            class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-indigo-1100 hover:text-indigo-900 focus:text-indigo-900 active:text-indigo-1000 hover:bg-indigo-100 focus:bg-indigo-100 active:bg-indigo-200">
                                            Person's With Disability (PWD) ID
                                        </button>
                                    @endif

                                    @if ($birthdate && strtotime($birthdate) < strtotime(\Carbon\Carbon::now()->subYears(60)))
                                        <button type="button" x-on:click="selectOption('Senior Citizen ID')"
                                            class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-indigo-1100 hover:text-indigo-900 focus:text-indigo-900 active:text-indigo-1000 hover:bg-indigo-100 focus:bg-indigo-100 active:bg-indigo-200">
                                            Senior Citizen ID
                                        </button>
                                    @endif

                                    <button type="button" x-on:click="selectOption('e-Card / UMID')"
                                        class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-indigo-1100 hover:text-indigo-900 focus:text-indigo-900 active:text-indigo-1000 hover:bg-indigo-100 focus:bg-indigo-100 active:bg-indigo-200">
                                        e-Card / UMID
                                    </button>

                                    <button type="button" x-on:click="selectOption('Driver\'s License')"
                                        class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-indigo-1100 hover:text-indigo-900 focus:text-indigo-900 active:text-indigo-1000 hover:bg-indigo-100 focus:bg-indigo-100 active:bg-indigo-200">
                                        Driver's License
                                    </button>

                                    <button type="button" x-on:click="selectOption('Passport')"
                                        class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-indigo-1100 hover:text-indigo-900 focus:text-indigo-900 active:text-indigo-1000 hover:bg-indigo-100 focus:bg-indigo-100 active:bg-indigo-200">
                                        Passport
                                    </button>

                                    <button type="button" x-on:click="selectOption('Phil-health ID')"
                                        class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-indigo-1100 hover:text-indigo-900 focus:text-indigo-900 active:text-indigo-1000 hover:bg-indigo-100 focus:bg-indigo-100 active:bg-indigo-200">
                                        Phil-health ID
                                    </button>

                                    <button type="button" x-on:click="selectOption('Philippine Postal ID')"
                                        class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-indigo-1100 hover:text-indigo-900 focus:text-indigo-900 active:text-indigo-1000 hover:bg-indigo-100 focus:bg-indigo-100 active:bg-indigo-200">
                                        Philippine Postal ID
                                    </button>

                                    <button type="button" x-on:click="selectOption('SSS ID')"
                                        class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-indigo-1100 hover:text-indigo-900 focus:text-indigo-900 active:text-indigo-1000 hover:bg-indigo-100 focus:bg-indigo-100 active:bg-indigo-200">
                                        SSS ID
                                    </button>

                                    <button type="button"
                                        x-on:click="selectOption('COMELEC / Voter\'s ID / COMELEC Registration Form')"
                                        class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-indigo-1100 hover:text-indigo-900 focus:text-indigo-900 active:text-indigo-1000 hover:bg-indigo-100 focus:bg-indigo-100 active:bg-indigo-200">
                                        COMELEC / Voter's ID / COMELEC Registration Form
                                    </button>

                                    <button type="button"
                                        x-on:click="selectOption('Philippine Identification (PhilID / ePhilID)')"
                                        class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-indigo-1100 hover:text-indigo-900 focus:text-indigo-900 active:text-indigo-1000 hover:bg-indigo-100 focus:bg-indigo-100 active:bg-indigo-200">
                                        Philippine Identification (PhilID / ePhilID)
                                    </button>

                                    <button type="button" x-on:click="selectOption('NBI Clearance')"
                                        class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-indigo-1100 hover:text-indigo-900 focus:text-indigo-900 active:text-indigo-1000 hover:bg-indigo-100 focus:bg-indigo-100 active:bg-indigo-200">
                                        NBI Clearance
                                    </button>

                                    <button type="button"
                                        x-on:click="selectOption('Pantawid Pamilya Pilipino Program (4Ps) ID')"
                                        class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-indigo-1100 hover:text-indigo-900 focus:text-indigo-900 active:text-indigo-1000 hover:bg-indigo-100 focus:bg-indigo-100 active:bg-indigo-200">
                                        Pantawid Pamilya Pilipino Program (4Ps) ID
                                    </button>

                                    <button type="button"
                                        x-on:click="selectOption('Integrated Bar of the Philippines (IBP) ID')"
                                        class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-indigo-1100 hover:text-indigo-900 focus:text-indigo-900 active:text-indigo-1000 hover:bg-indigo-100 focus:bg-indigo-100 active:bg-indigo-200">
                                        Integrated Bar of the Philippines (IBP) ID
                                    </button>

                                    <button type="button" x-on:click="selectOption('BIR (TIN)')"
                                        class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-indigo-1100 hover:text-indigo-900 focus:text-indigo-900 active:text-indigo-1000 hover:bg-indigo-100 focus:bg-indigo-100 active:bg-indigo-200">
                                        BIR (TIN)
                                    </button>

                                    <button type="button" x-on:click="selectOption('Pag-ibig ID')"
                                        class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-indigo-1100 hover:text-indigo-900 focus:text-indigo-900 active:text-indigo-1000 hover:bg-indigo-100 focus:bg-indigo-100 active:bg-indigo-200">
                                        Pag-ibig ID
                                    </button>

                                    <button type="button" x-on:click="selectOption('Solo Parent ID')"
                                        class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-indigo-1100 hover:text-indigo-900 focus:text-indigo-900 active:text-indigo-1000 hover:bg-indigo-100 focus:bg-indigo-100 active:bg-indigo-200">
                                        Solo Parent ID
                                    </button>

                                    <button type="button" x-on:click="selectOption('Barangay ID')"
                                        class="flex items-center w-full outline-none first-of-type:rounded-t last-of-type:rounded-b p-2 text-left text-xs text-indigo-1100 hover:text-indigo-900 focus:text-indigo-900 active:text-indigo-1000 hover:bg-indigo-100 focus:bg-indigo-100 active:bg-indigo-200">
                                        Barangay ID
                                    </button>
                                </div>
                            </div>
                        </div>
                        {{-- ID Number --}}
                        <div class="relative col-span-full sm:col-span-4 sm:row-span-1 mb-4 pb-1">

                            <label for="id_number" class="block mb-1 font-medium text-indigo-1100 ">ID Number <span
                                    class="text-red-700 font-normal text-sm">*</span></label>
                            <input type="text" id="id_number" autocomplete="off" wire:model.blur="id_number"
                                class="text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out {{ $errors->has('id_number') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }}"
                                placeholder="Type ID number">
                            @error('id_number')
                                <p class="text-red-500 ms-2 mt-1 z-10 text-xs">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Spouse First Name --}}
                        <div class="relative col-span-full sm:col-span-3 mb-4 pb-1">
                            <label for="spouse_first_name"
                                class="flex items-end mb-1 font-medium h-6 {{ $civil_status === 'Married' ? 'text-indigo-1100' : 'text-gray-400' }}">Spouse
                                First Name @if ($civil_status === 'Married')
                                    <span class="text-red-700 font-normal text-sm ms-0.5">*</span>
                                @endif
                            </label>
                            <input type="text" id="spouse_first_name" autocomplete="off"
                                wire:model.blur="spouse_first_name" @if ($civil_status === 'Single') disabled @endif
                                class="text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out @if ($civil_status === 'Married') {{ $errors->has('spouse_first_name') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }}
                                    @else
                                    bg-gray-200 border-gray-300 text-gray-500 @endif"
                                placeholder="Type spouse first name">
                            @error('spouse_first_name')
                                <p class="text-red-500 ms-2 mt-1 z-10 text-xs">{{ $message }}</p>
                            @enderror
                        </div>
                        {{-- Spouse Middle Name --}}
                        <div class="relative col-span-full sm:col-span-2 mb-4 pb-1">
                            <label for="spouse_middle_name"
                                class="flex items-end mb-1 font-medium h-6 {{ $civil_status === 'Married' ? 'text-indigo-1100' : 'text-gray-400' }}">Spouse
                                Middle Name </label>
                            <input type="text" id="spouse_middle_name" autocomplete="off"
                                wire:model.blur="spouse_middle_name" @if ($civil_status === 'Single') disabled @endif
                                class="text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out 
                            {{ $civil_status === 'Married'
                                ? 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600'
                                : 'bg-gray-200 border-gray-300 text-gray-500' }}"
                                placeholder="(optional)">
                        </div>
                        {{-- Spouse Last Name --}}
                        <div class="relative flex flex-col col-span-full sm:col-span-2 mb-4 pb-1">
                            <label for="spouse_last_name"
                                class="flex items-end mb-1 font-medium h-6 {{ $civil_status === 'Married' ? 'text-indigo-1100' : 'text-gray-400' }}">Spouse
                                Last Name @if ($civil_status === 'Married')
                                    <span class="text-red-700 font-normal text-sm ms-0.5">*</span>
                                @endif
                            </label>
                            <input type="text" id="spouse_last_name" autocomplete="off"
                                wire:model.blur="spouse_last_name" @if ($civil_status === 'Single') disabled @endif
                                class="text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out 
                            
                            @if ($civil_status === 'Married') {{ $errors->has('spouse_first_name') ? 'border-red-500 bg-red-200 focus:ring-red-500 focus:border-red-300 focus:ring-offset-red-100 text-red-900 placeholder-red-600' : 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600' }}
                            @else
                            bg-gray-200 border-gray-300 text-gray-500 @endif"
                                placeholder="Type spouse last name">
                            @error('spouse_last_name')
                                <p class="text-red-500 ms-2 mt-1 z-10 text-xs">{{ $message }}</p>
                            @enderror
                        </div>
                        {{-- Spouse Extension Name --}}
                        <div class="relative col-span-full sm:col-span-1 mb-4 pb-1">
                            <label for="spouse_extension_name"
                                class="flex items-end mb-1 font-medium h-6 {{ $civil_status === 'Married' ? 'text-indigo-1100' : 'text-gray-400' }}">Spouse
                                Ext. Name</label>
                            <input type="text" id="spouse_extension_name" autocomplete="off"
                                wire:model.blur="spouse_extension_name"
                                @if ($civil_status === 'Single') disabled @endif
                                class="text-xs border outline-none rounded block w-full p-2 duration-200 ease-in-out 
                            {{ $civil_status === 'Married'
                                ? 'bg-indigo-50 border-indigo-300 text-indigo-1100 focus:ring-indigo-600 focus:border-indigo-600'
                                : 'bg-gray-200 border-gray-300 text-gray-500' }}"
                                placeholder="III, Sr., etc.">

                        </div>
                    </div>
                </div>
                {{-- Modal footer --}}
                <div class="w-full flex relative items-center justify-between">
                    <div
                        class="flex items-center border border-amber-300 bg-amber-50 text-amber-900 rounded text-sm p-2">
                        <p class="inline mx-2">Seems like this beneficiary has already been listed in the database this
                            year.
                            <button type="button" class="underline font-bold">Any reason why?</button>
                        </p>
                    </div>
                    <div class="flex items-center justify-end relative">
                        {{-- Loading State for Changes --}}
                        <div class="z-50 text-indigo-900" wire:loading wire:target="saveBeneficiary">
                            <svg class="size-6 mr-3 -ml-1 animate-spin" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4">
                                </circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                        </div>
                        <button type="submit" wire:loading.attr="disabled" wire:target="saveBeneficiary"
                            class="space-x-2 py-2 px-4 text-center text-white font-bold flex items-center bg-indigo-700 disabled:opacity-75 hover:bg-indigo-800 focus:ring-4 focus:outline-none focus:ring-indigo-300 rounded-md">
                            <p>ADD</p>
                            <svg class="size-5" xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink" width="400" height="400"
                                viewBox="0, 0, 400,400">
                                <g>
                                    <path
                                        d="M181.716 13.755 C 102.990 27.972,72.357 125.909,128.773 183.020 C 181.183 236.074,272.696 214.609,295.333 143.952 C 318.606 71.310,256.583 0.235,181.716 13.755 M99.463 202.398 C 60.552 222.138,32.625 260.960,26.197 304.247 C 24.209 317.636,24.493 355.569,26.629 361.939 C 30.506 373.502,39.024 382.022,50.561 385.877 C 55.355 387.479,56.490 387.500,136.304 387.500 L 217.188 387.500 209.475 379.883 C 171.918 342.791,164.644 284.345,192.232 241.338 C 195.148 236.792,195.136 236.719,191.484 236.719 C 169.055 236.719,137.545 223.179,116.259 204.396 L 108.691 197.717 99.463 202.398 M269.531 213.993 C 176.853 234.489,177.153 366.574,269.922 386.007 C 337.328 400.126,393.434 333.977,369.538 268.559 C 355.185 229.265,310.563 204.918,269.531 213.993 M293.788 265.042 C 298.143 267.977,299.417 271.062,299.832 279.675 L 300.199 287.301 307.825 287.668 C 319.184 288.215,324.219 292.002,324.219 300.000 C 324.219 307.998,319.184 311.785,307.825 312.332 L 300.199 312.699 299.832 320.325 C 299.285 331.684,295.498 336.719,287.500 336.719 C 279.502 336.719,275.715 331.684,275.168 320.325 L 274.801 312.699 267.175 312.332 C 255.816 311.785,250.781 307.998,250.781 300.000 C 250.781 292.002,255.816 288.215,267.175 287.668 L 274.801 287.301 275.168 279.675 C 275.715 268.316,279.502 263.281,287.500 263.281 C 290.019 263.281,291.997 263.835,293.788 265.042 "
                                        stroke="none" fill="currentColor" fill-rule="evenodd"></path>
                                </g>
                            </svg>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@script
    <script>
        const birthdatePicker = document.getElementById('birthdate');

        birthdatePicker.addEventListener('changeDate', function(event) {
            $wire.dispatchSelf('birthdate-change', {
                value: birthdatePicker.value
            });
        });

        Alpine.store('selfEmploymentPopover', {
            on: false,
            styles: '',

            toggle() {
                this.on = !this.on
                const svgIcon = document.getElementById('self-employment-question-mark');
                const popover = document.getElementById('self-employment-popover');
                $nextTick(() => {
                    this.styles =
                        `bottom: ${svgIcon.offsetHeight + 4}px; right: ${(-popover.offsetWidth + 11.4) / 2}px;`;
                });
            }
        });

        Alpine.store('isPWDPopover', {
            on: false,
            styles: '',

            toggle() {
                this.on = !this.on
                const svgIcon = document.getElementById('is-pwd-question-mark');
                $nextTick(() => {
                    this.styles =
                        `bottom: ${svgIcon.offsetHeight + 4}px; right: 0px;`;
                });


            }
        });

        Alpine.store('identityPopover', {
            on: false,
            styles: '',

            toggle() {
                this.on = !this.on
                const svgIcon = document.getElementById('identity-question-mark');
                const popover = document.getElementById('identity-popover');
                $nextTick(() => {
                    this.styles =
                        `bottom: ${svgIcon.offsetHeight + 4}px; right: ${(-popover.offsetWidth - 11.4) / 2}px;`;
                });
            }
        });
    </script>
@endscript
