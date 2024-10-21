<?php

namespace App\Livewire\Focal;

use App\Models\Batch;
use App\Models\Implementation;
use App\Services\Annex;
use App\Services\Summary;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;

#[Layout('layouts.app')]
#[Title('Dashboard | TU-Efficient')]
class Dashboard extends Component
{
    use WithPagination, WithoutUrlPagination;

    #[Locked]
    public $implementationId;
    #[Locked]
    public $exportImplementationId;
    public $currentImplementation;
    public $currentExportImplementation;
    public $start;
    public $end;
    public $defaultStart;
    public $defaultEnd;
    public $export_start;
    public $export_end;
    public $searchProject;
    public $searchExportProject;
    public $showAlert = false;
    public $alertMessage;
    public $summaryUrl;
    public $showExportModal = false;
    public $exportChoice = 'date_range';
    public $exportFormat = 'xlsx';
    #[Validate]
    public $defaultExportStart;
    #[Validate]
    public $defaultExportEnd;

    # -----------------------------------------------------------------------

    public function rules()
    {
        return [
            'defaultExportStart' => [
                'required'
            ],
            'defaultExportEnd' => [
                'required'
            ],
            'exportImplementationId' => [
                'required'
            ],
        ];
    }

    public function messages()
    {
        return [
            'defaultExportStart.required' => 'This field is required.',
            'defaultExportEnd.required' => 'This field is required.',
            'exportImplementationId.required' => 'This field is required.',
        ];
    }

    public function printSummary()
    {
        # Compile all the data needed to print

        # Date range of the dataset
        // $start = Carbon::parse($this->start)->format('F d, Y');
        // $end = Carbon::parse($this->end)->format('F d, Y');

        $currentImplementation = Implementation::find($this->implementationId ? decrypt($this->implementationId) : null);
        $currentBatches = Batch::join('implementations', 'batches.implementations_id', '=', 'implementations.id')
            ->join('beneficiaries', 'beneficiaries.batches_id', '=', 'batches.id')
            ->select([
                'batches.barangay_name',
                DB::raw('SUM(CASE WHEN beneficiaries.sex = "male" THEN 1 ELSE 0 END) AS total_male'),
                DB::raw('SUM(CASE WHEN beneficiaries.sex = "female" THEN 1 ELSE 0 END) AS total_female'),
                DB::raw('SUM(CASE WHEN beneficiaries.is_pwd = "yes" AND beneficiaries.sex = "male" THEN 1 ELSE 0 END) AS total_pwd_male'),
                DB::raw('SUM(CASE WHEN beneficiaries.is_pwd = "yes" AND beneficiaries.sex = "female" THEN 1 ELSE 0 END) AS total_pwd_female'),
                DB::raw('SUM(CASE WHEN beneficiaries.is_senior_citizen = "yes" AND beneficiaries.sex = "male" THEN 1 ELSE 0 END) AS total_senior_male'),
                DB::raw('SUM(CASE WHEN beneficiaries.is_senior_citizen = "yes" AND beneficiaries.sex = "female" THEN 1 ELSE 0 END) AS total_senior_female')
            ])
            ->where('implementations.id', isset($this->implementationId) ? decrypt($this->implementationId) : null)
            ->groupBy(['batches.barangay_name'])
            ->get();

        $summaryCount = Implementation::join('batches', 'implementations.id', '=', 'batches.implementations_id')
            ->join('beneficiaries', 'beneficiaries.batches_id', '=', 'batches.id')
            ->select([
                DB::raw('SUM(CASE WHEN beneficiaries.sex = "male" THEN 1 ELSE 0 END) AS total_male'),
                DB::raw('SUM(CASE WHEN beneficiaries.sex = "female" THEN 1 ELSE 0 END) AS total_female'),
                DB::raw('SUM(CASE WHEN beneficiaries.is_pwd = "yes" AND beneficiaries.sex = "male" THEN 1 ELSE 0 END) AS total_pwd_male'),
                DB::raw('SUM(CASE WHEN beneficiaries.is_pwd = "yes" AND beneficiaries.sex = "female" THEN 1 ELSE 0 END) AS total_pwd_female'),
                DB::raw('SUM(CASE WHEN beneficiaries.is_senior_citizen = "yes" AND beneficiaries.sex = "male" THEN 1 ELSE 0 END) AS total_senior_male'),
                DB::raw('SUM(CASE WHEN beneficiaries.is_senior_citizen = "yes" AND beneficiaries.sex = "female" THEN 1 ELSE 0 END) AS total_senior_female')
            ])
            ->where('implementations.id', isset($this->implementationId) ? decrypt($this->implementationId) : null)
            ->first();

        # Current Implementation
        $overall = [
            'male' => $summaryCount->total_male,
            'female' => $summaryCount->total_female,
        ];
        $seniors = [
            'male' => $summaryCount->total_senior_male,
            'female' => $summaryCount->total_senior_female,
        ];
        $pwds = [
            'male' => $summaryCount->total_pwd_male,
            'female' => $summaryCount->total_pwd_female,
        ];

        # Implementation Information
        $implementation = [
            'project_num' => $currentImplementation->project_num,
            'project_title' => $currentImplementation->project_title ?? '-',
            'purpose' => $currentImplementation->purpose,
            'province' => $currentImplementation->province,
            'city_municipality' => $currentImplementation->city_municipality,
            'district' => $currentImplementation->district,
            'budget_amount' => $currentImplementation->budget_amount,
            'minimum_wage' => $currentImplementation->minimum_wage,
            'total_slots' => $currentImplementation->total_slots,
            'days_of_work' => $currentImplementation->days_of_work,
            'created_at' => $currentImplementation->created_at,
            'updated_at' => $currentImplementation->updated_at,
        ];

        # Barangay Information
        $barangays = [];

        foreach ($currentBatches as $batch) {
            $barangays[] = [
                'barangay_name' => $batch->barangay_name,
                'total_male' => $batch->total_male,
                'total_female' => $batch->total_female,
                'total_pwd_male' => $batch->total_pwd_male,
                'total_pwd_female' => $batch->total_pwd_female,
                'total_senior_male' => $batch->total_senior_male,
                'total_senior_female' => $batch->total_senior_female,
            ];
        }

        # Store the data to be printed in the session
        session([
            'print-summary-information' => [
                'overall' => $overall,
                'seniors' => $seniors,
                'pwds' => $pwds,
                'implementation' => $implementation,
                'barangays' => $barangays
            ]
        ]);

        # Dispatch an event to open the print dialog
        $this->dispatch('openPrintWindow', url: $this->summaryUrl)->self();
    }

    public function showExport()
    {
        $this->defaultExportStart = Carbon::parse($this->start)->format('m/d/Y');
        $this->defaultExportEnd = Carbon::parse($this->end)->format('m/d/Y');

        $date = Carbon::createFromFormat('m/d/Y', $this->defaultExportStart)->format('Y-m-d');
        $choosenDate = date('Y-m-d', strtotime($date));
        $currentTime = date('H:i:s', strtotime(now()));
        $this->export_start = $choosenDate . ' ' . $currentTime;

        $date = Carbon::createFromFormat('m/d/Y', $this->defaultExportEnd)->format('Y-m-d');
        $choosenDate = date('Y-m-d', strtotime($date));
        $currentTime = date('H:i:s', strtotime(now()));
        $this->export_end = $choosenDate . ' ' . $currentTime;

        $this->showExportModal = true;
    }

    public function export()
    {
        $data = [
            'date_range' => null,
            'selected_project' => null,
        ];

        if ($this->exportChoice === 'date_range') {

            $this->validate([
                'defaultExportStart' => [
                    'required'
                ],
                'defaultExportEnd' => [
                    'required'
                ],
            ], [
                'defaultExportStart.required' => 'This field is required.',
                'defaultExportEnd.required' => 'This field is required.',
            ]);

            $data['date_range'] = [
                'implementations' => $this->exportImplementations,
                'start' => $this->export_start,
                'end' => $this->export_end,
            ];

        } elseif ($this->exportChoice === 'selected_project') {

            $this->validate([
                'exportImplementationId' => [
                    'required'
                ],
            ], [
                'exportImplementationId.required' => 'This field is required.'
            ]);

            $data['selected_project'] = [
                'implementations' => $this->exportImplementation,
            ];

        }

        $spreadsheet = new Spreadsheet();
        $spreadsheet = Summary::exportSummary($spreadsheet, $data, $this->exportFormat);
        $writer = null;
        $fileName = null;

        if ($this->exportFormat === 'xlsx') {
            $writer = new Xlsx($spreadsheet);
            $fileName = 'Summary of Beneficiaries.xlsx';
        } elseif ($this->exportFormat === 'csv') {
            $writer = new Csv($spreadsheet);
            $fileName = 'Summary of Beneficiaries.csv';
            $writer->setDelimiter(';');
            $writer->setEnclosure('"');
        }

        $filePath = storage_path($fileName);
        $writer->save($filePath);

        # Download the file
        return response()->download($filePath)->deleteFileAfterSend(true);
    }

    public function selectExportImplementationRow($encryptedId)
    {
        $this->exportImplementationId = $encryptedId;
    }

    #[Computed]
    public function exportImplementation()
    {
        $implementation = Implementation::where('id', $this->exportImplementationId ? decrypt($this->exportImplementationId) : null)
            ->get();
        return $implementation;
    }

    #[Computed]
    public function exportImplementations()
    {
        $implementations = Implementation::whereHas('batch.beneficiary')
            ->where('users_id', Auth::id())
            ->when($this->exportChoice === 'selected_project' && isset($this->searchExportProject) && !empty($this->searchExportProject), function ($q) {
                $q->where('project_num', 'LIKE', '%' . $this->searchExportProject . '%');
            })
            ->when($this->exportChoice === 'date_range' && !is_null($this->export_start) && !is_null($this->export_end), function ($q) {
                $q->whereBetween('created_at', [$this->export_start, $this->export_end]);
            })
            ->latest('updated_at')
            ->select([
                'implementations.*'
            ])
            ->get();

        return $implementations;
    }

    public function resetExport()
    {
        $this->reset('defaultExportStart', 'defaultExportEnd', 'export_start', 'export_end');
        $this->resetValidation(['defaultExportStart', 'defaultExportEnd', 'exportImplementationId']);

        if ($this->exportImplementations->isEmpty()) {
            $this->exportImplementationId = null;
            $this->currentExportImplementation = 'None';
        } else {
            $this->exportImplementationId = encrypt($this->exportImplementations[0]->id);
            $this->currentExportImplementation = $this->exportImplementations[0]->project_num;
        }

    }

    #[On('start-change')]
    public function setStartDate($value)
    {
        $this->reset('searchProject');
        $choosenDate = date('Y-m-d', strtotime($value));
        $currentTime = date('H:i:s', strtotime(now()));

        $this->start = $choosenDate . ' ' . $currentTime;

        if ($this->implementations->isEmpty()) {
            $this->implementationId = null;
            $this->currentImplementation = 'None';
        } else {
            $this->implementationId = encrypt($this->implementations[0]->id);
            $this->currentImplementation = $this->implementations[0]->project_num;
        }

        $this->resetPage();
        $this->setCharts();
    }

    #[On('end-change')]
    public function setEndDate($value)
    {
        $this->reset('searchProject');
        $choosenDate = date('Y-m-d', strtotime($value));
        $currentTime = date('H:i:s', strtotime(now()));

        $this->end = $choosenDate . ' ' . $currentTime;

        if ($this->implementations->isEmpty()) {
            $this->implementationId = null;
            $this->currentImplementation = 'None';
        } else {
            $this->implementationId = encrypt($this->implementations[0]->id);
            $this->currentImplementation = $this->implementations[0]->project_num;
        }

        $this->resetPage();
        $this->setCharts();
    }

    #[Computed]
    public function projectCounters()
    {
        $projectCounters = Implementation::join('batches', 'implementations.id', '=', 'batches.implementations_id')
            ->where('implementations.users_id', Auth::id())
            ->whereBetween('batches.created_at', [$this->start, $this->end])
            ->select([
                DB::raw('COUNT(DISTINCT implementations.id) AS total_implementations'),
                DB::raw('SUM(CASE WHEN batches.approval_status = "approved" THEN 1 ELSE 0 END) AS total_approved_assignments'),
                DB::raw('SUM(CASE WHEN batches.approval_status = "pending" THEN 1 ELSE 0 END) AS total_pending_assignments'),
            ])
            ->first();

        return $projectCounters;
    }

    #[Computed]
    public function implementation()
    {
        $implementation = Implementation::find($this->implementationId ? decrypt($this->implementationId) : null);
        return $implementation;
    }

    #[Computed]
    public function implementations()
    {
        $implementations = Implementation::where('users_id', Auth::id())
            ->where('project_num', 'LIKE', '%' . $this->searchProject . '%')
            ->whereBetween('created_at', [$this->start, $this->end])
            ->latest('updated_at')
            ->get();

        return $implementations;
    }

    #[Computed]
    public function summaryCount()
    {
        $summaryCount = Implementation::join('batches', 'implementations.id', '=', 'batches.implementations_id')
            ->join('beneficiaries', 'beneficiaries.batches_id', '=', 'batches.id')
            ->select([
                DB::raw('SUM(CASE WHEN beneficiaries.sex = "male" THEN 1 ELSE 0 END) AS total_male'),
                DB::raw('SUM(CASE WHEN beneficiaries.sex = "female" THEN 1 ELSE 0 END) AS total_female'),
                DB::raw('SUM(CASE WHEN beneficiaries.is_pwd = "yes" AND beneficiaries.sex = "male" THEN 1 ELSE 0 END) AS total_pwd_male'),
                DB::raw('SUM(CASE WHEN beneficiaries.is_pwd = "yes" AND beneficiaries.sex = "female" THEN 1 ELSE 0 END) AS total_pwd_female'),
                DB::raw('SUM(CASE WHEN beneficiaries.is_senior_citizen = "yes" AND beneficiaries.sex = "male" THEN 1 ELSE 0 END) AS total_senior_male'),
                DB::raw('SUM(CASE WHEN beneficiaries.is_senior_citizen = "yes" AND beneficiaries.sex = "female" THEN 1 ELSE 0 END) AS total_senior_female')
            ])
            ->where('implementations.users_id', Auth::id())
            ->where('implementations.id', isset($this->implementationId) ? decrypt($this->implementationId) : null)
            ->whereBetween('batches.created_at', [$this->start, $this->end])
            ->first();

        return $summaryCount;
    }

    #[Computed]
    public function total_beneficiaries()
    {
        if (is_null($this->summaryCount->total_male) && is_null($this->summaryCount->total_female)) {
            return null;
        } elseif (intval($this->summaryCount->total_male) === 0 && intval($this->summaryCount->total_female) === 0) {
            return 0;
        } else {
            $total = [
                'male' => $this->summaryCount->total_male,
                'female' => $this->summaryCount->total_female,
            ];
            return $total;
        }
    }

    #[Computed]
    public function total_pwds()
    {
        if (is_null($this->summaryCount->total_pwd_male) && is_null($this->summaryCount->total_pwd_female)) {
            return null;
        } elseif (intval($this->summaryCount->total_pwd_male) === 0 && intval($this->summaryCount->total_pwd_female) === 0) {
            return 0;
        } else {
            $total = [
                'male' => $this->summaryCount->total_pwd_male,
                'female' => $this->summaryCount->total_pwd_female,
            ];
            return $total;
        }
    }

    #[Computed]
    public function total_seniors()
    {
        if (is_null($this->summaryCount->total_senior_male) && is_null($this->summaryCount->total_senior_female)) {
            return null;
        } elseif (intval($this->summaryCount->total_senior_male) === 0 && intval($this->summaryCount->total_senior_female) === 0) {
            return 0;
        } else {
            $total = [
                'male' => $this->summaryCount->total_senior_male,
                'female' => $this->summaryCount->total_senior_female,
            ];
            return $total;
        }
    }

    public function setCharts()
    {
        $overallValues = $this->total_beneficiaries;
        $pwdValues = $this->total_pwds;
        $seniorValues = $this->total_seniors;

        $this->dispatch('series-change', overallValues: $overallValues, pwdValues: $pwdValues, seniorValues: $seniorValues)->self();
    }

    #[Computed]
    public function beneficiaryCounters()
    {
        $beneficiaryCounters = Implementation::join('batches', 'implementations.id', '=', 'batches.implementations_id')
            ->join('beneficiaries', 'batches.id', '=', 'beneficiaries.batches_id')
            ->where('implementations.users_id', '=', Auth::id())
            ->whereBetween('batches.created_at', [$this->start, $this->end])
            ->select([
                DB::raw('COUNT(DISTINCT beneficiaries.id) AS total_beneficiaries'),
                DB::raw('SUM(CASE WHEN beneficiaries.is_pwd = "yes" THEN 1 ELSE 0 END) AS total_pwd_beneficiaries'),
                DB::raw('SUM(CASE WHEN beneficiaries.is_senior_citizen = "yes" THEN 1 ELSE 0 END) AS total_senior_citizen_beneficiaries'),
            ])
            ->first();

        return $beneficiaryCounters;
    }

    public function selectImplementation($key)
    {
        $this->resetPage();
        $this->currentImplementation = $this->implementations[$key]->project_num;
        $this->implementationId = encrypt($this->implementations[$key]->id);

        $this->setCharts();
    }

    #[Computed]
    public function batchesCount()
    {
        $batchesCount = $this->batches->total();

        return $batchesCount;
    }

    #[Computed]
    public function batches()
    {
        $batches = Batch::join('implementations', 'batches.implementations_id', '=', 'implementations.id')
            ->join('beneficiaries', 'beneficiaries.batches_id', '=', 'batches.id')
            ->select([
                'batches.id',
                'batches.barangay_name',
                DB::raw('SUM(CASE WHEN beneficiaries.sex = "male" THEN 1 ELSE 0 END) AS total_male'),
                DB::raw('SUM(CASE WHEN beneficiaries.sex = "female" THEN 1 ELSE 0 END) AS total_female'),
                DB::raw('SUM(CASE WHEN beneficiaries.is_pwd = "yes" AND beneficiaries.sex = "male" THEN 1 ELSE 0 END) AS total_pwd_male'),
                DB::raw('SUM(CASE WHEN beneficiaries.is_pwd = "yes" AND beneficiaries.sex = "female" THEN 1 ELSE 0 END) AS total_pwd_female'),
                DB::raw('SUM(CASE WHEN beneficiaries.is_senior_citizen = "yes" AND beneficiaries.sex = "male" THEN 1 ELSE 0 END) AS total_senior_male'),
                DB::raw('SUM(CASE WHEN beneficiaries.is_senior_citizen = "yes" AND beneficiaries.sex = "female" THEN 1 ELSE 0 END) AS total_senior_female')
            ])
            ->where('implementations.users_id', Auth::id())
            ->where('implementations.id', isset($this->implementationId) ? decrypt($this->implementationId) : null)
            ->whereBetween('batches.created_at', [$this->start, $this->end])
            ->groupBy(['batches.id', 'batches.barangay_name'])
            ->paginate(3);

        return $batches;
    }

    public function updated($prop)
    {
        if ($prop === 'defaultExportStart') {
            $this->validateOnly('defaultExportStart');
            $date = Carbon::createFromFormat('m/d/Y', $this->defaultExportStart)->format('Y-m-d');
            $choosenDate = date('Y-m-d', strtotime($date));
            $currentTime = date('H:i:s', strtotime(now()));
            $this->export_start = $choosenDate . ' ' . $currentTime;

            if ($this->exportImplementations->isEmpty()) {
                $this->exportImplementationId = null;
                $this->currentExportImplementation = 'None';
            } else {
                $this->exportImplementationId = encrypt($this->exportImplementations[0]->id);
                $this->currentExportImplementation = $this->exportImplementations[0]->project_num;
            }
        }

        if ($prop === 'defaultExportEnd') {
            $this->validateOnly('defaultExportEnd');
            $date = Carbon::createFromFormat('m/d/Y', $this->defaultExportEnd)->format('Y-m-d');
            $choosenDate = date('Y-m-d', strtotime($date));
            $currentTime = date('H:i:s', strtotime(now()));
            $this->export_end = $choosenDate . ' ' . $currentTime;

            if ($this->exportImplementations->isEmpty()) {
                $this->exportImplementationId = null;
                $this->currentExportImplementation = 'None';
            } else {
                $this->exportImplementationId = encrypt($this->exportImplementations[0]->id);
                $this->currentExportImplementation = $this->exportImplementations[0]->project_num;
            }
        }

        if ($prop === 'exportChoice') {
            $this->dispatch('init-reload')->self();
        }
    }

    public function mount()
    {
        if (Auth::user()->user_type !== 'focal') {
            $this->redirectIntended();
        }
        $this->summaryUrl = url('/print-summary');
        /*
         *  Setting default dates in the datepicker
         */
        $this->start = date('Y-m-d H:i:s', strtotime(now()->startOfYear()));
        $this->end = date('Y-m-d H:i:s', strtotime(now()));

        $this->defaultStart = date('m/d/Y', strtotime($this->start));
        $this->defaultEnd = date('m/d/Y', strtotime($this->end));

        $this->defaultExportStart = $this->defaultStart;
        $this->defaultExportEnd = $this->defaultEnd;

        if ($this->implementations->isEmpty()) {
            $this->implementationId = null;
            $this->currentImplementation = 'None';
        } else {
            $this->implementationId = encrypt($this->implementations[0]->id);
            $this->currentImplementation = $this->implementations[0]->project_num;
        }

        if ($this->exportImplementations->isEmpty()) {
            $this->exportImplementationId = null;
            $this->currentExportImplementation = 'None';
        } else {
            $this->exportImplementationId = encrypt($this->exportImplementations[0]->id);
            $this->currentExportImplementation = $this->exportImplementations[0]->project_num;
        }

        /*
         *  Charts
         */
        $this->setCharts();
    }

    public function render()
    {
        return view('livewire.focal.dashboard');
    }
}
