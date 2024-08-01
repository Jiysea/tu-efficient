 <div class="lg:ml-64">
     <div class="p-2 min-h-screen">

         {{-- Nav Title and Time Dropdown --}}
         <div class="relative">
             <livewire:focal.dashboard.time-dropdown />
         </div>

         {{-- Project Counters --}}
         <div class="relative">
             <livewire:focal.dashboard.project-counters />
         </div>

         <div class="relative grid w-full gap-x-2 lg:grid-cols-3">
             <div class="relative lg:col-span-2">
                 {{-- Summary of Beneficiaries --}}
                 <div class="flex items-center w-auto">
                     <livewire:focal.dashboard.summary-implementation />
                 </div>
                 {{-- The Charts (Male and Female) --}}
                 <div class="flex items-center justify-start w-full mb-2 h-auto rounded bg-white ">

                     <livewire:focal.dashboard.charts />

                 </div>
                 <div class="grid grid-cols-2 gap-x-2 w-full">

                     {{-- Total Beneficiaries Counter --}}
                     <div class="flex items-center justify-center h-32 rounded bg-white">

                         <livewire:focal.dashboard.total-beneficiaries-counter />

                     </div>
                     {{-- Print and Export Buttons --}}
                     <div class="flex items-center justify-center h-32 rounded bg-white">

                         <livewire:focal.dashboard.print-export-buttons />

                     </div>
                 </div>
             </div>

             {{-- Per Barangay Counters --}}
             <div class="relative gap-x-2 mt-2 w-full">
                 <livewire:focal.dashboard.beneficiaries-by-barangay />
             </div>
         </div>
     </div>
 </div>
