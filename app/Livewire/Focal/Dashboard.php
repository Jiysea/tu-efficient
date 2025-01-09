<?php

namespace App\Livewire\Focal;

use App\Models\Batch;
use App\Models\Beneficiary;
use App\Models\Implementation;
use App\Services\Essential;
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
    public $calendarStart;
    public $calendarEnd;
    public $defaultStart;
    public $defaultEnd;
    public $searchProjects;
    public $searchExportProject;
    public $showAlert = false;
    public $alertMessage;
    public $summaryUrl;
    public $showExportModal = false;
    public $exportChoice = 'selected_project';
    public $exportFormat = 'xlsx';
    public $export_start;
    public $export_end;
    public $defaultExportStart;
    public $defaultExportEnd;

    # -----------------------------------------------------------------------

    public function rules()
    {
        return [
            'exportImplementationId' => [
                'required'
            ],
        ];
    }

    public function messages()
    {
        return [
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
                'batches.is_sectoral',
                'batches.sector_title',
                'batches.barangay_name',
                DB::raw('SUM(CASE WHEN beneficiaries.sex = "male" THEN 1 ELSE 0 END) AS total_male'),
                DB::raw('SUM(CASE WHEN beneficiaries.sex = "female" THEN 1 ELSE 0 END) AS total_female'),
                DB::raw('SUM(CASE WHEN beneficiaries.is_pwd = "yes" AND beneficiaries.sex = "male" THEN 1 ELSE 0 END) AS total_pwd_male'),
                DB::raw('SUM(CASE WHEN beneficiaries.is_pwd = "yes" AND beneficiaries.sex = "female" THEN 1 ELSE 0 END) AS total_pwd_female'),
                DB::raw('SUM(CASE WHEN beneficiaries.is_senior_citizen = "yes" AND beneficiaries.sex = "male" THEN 1 ELSE 0 END) AS total_senior_male'),
                DB::raw('SUM(CASE WHEN beneficiaries.is_senior_citizen = "yes" AND beneficiaries.sex = "female" THEN 1 ELSE 0 END) AS total_senior_female'),
                DB::raw('SUM(CASE WHEN beneficiaries.is_signed AND beneficiaries.sex = "male" THEN 1 ELSE 0 END) AS total_contract_male'),
                DB::raw('SUM(CASE WHEN beneficiaries.is_signed AND beneficiaries.sex = "female" THEN 1 ELSE 0 END) AS total_contract_female'),
                DB::raw('SUM(CASE WHEN beneficiaries.is_paid AND beneficiaries.sex = "male" THEN 1 ELSE 0 END) AS total_payroll_male'),
                DB::raw('SUM(CASE WHEN beneficiaries.is_paid AND beneficiaries.sex = "female" THEN 1 ELSE 0 END) AS total_payroll_female'),
            ])
            ->where('implementations.id', isset($this->implementationId) ? decrypt($this->implementationId) : null)
            ->groupBy(['batches.is_sectoral', 'batches.barangay_name', 'batches.sector_title'])
            ->get();

        $summaryCount = Implementation::join('batches', 'implementations.id', '=', 'batches.implementations_id')
            ->join('beneficiaries', 'beneficiaries.batches_id', '=', 'batches.id')
            ->select([
                DB::raw('SUM(CASE WHEN beneficiaries.sex = "male" THEN 1 ELSE 0 END) AS total_male'),
                DB::raw('SUM(CASE WHEN beneficiaries.sex = "female" THEN 1 ELSE 0 END) AS total_female'),
                DB::raw('SUM(CASE WHEN beneficiaries.is_pwd = "yes" AND beneficiaries.sex = "male" THEN 1 ELSE 0 END) AS total_pwd_male'),
                DB::raw('SUM(CASE WHEN beneficiaries.is_pwd = "yes" AND beneficiaries.sex = "female" THEN 1 ELSE 0 END) AS total_pwd_female'),
                DB::raw('SUM(CASE WHEN beneficiaries.is_senior_citizen = "yes" AND beneficiaries.sex = "male" THEN 1 ELSE 0 END) AS total_senior_male'),
                DB::raw('SUM(CASE WHEN beneficiaries.is_senior_citizen = "yes" AND beneficiaries.sex = "female" THEN 1 ELSE 0 END) AS total_senior_female'),
                DB::raw('SUM(CASE WHEN beneficiaries.is_signed AND beneficiaries.sex = "male" THEN 1 ELSE 0 END) AS total_contract_male'),
                DB::raw('SUM(CASE WHEN beneficiaries.is_signed AND beneficiaries.sex = "female" THEN 1 ELSE 0 END) AS total_contract_female'),
                DB::raw('SUM(CASE WHEN beneficiaries.is_paid AND beneficiaries.sex = "male" THEN 1 ELSE 0 END) AS total_payroll_male'),
                DB::raw('SUM(CASE WHEN beneficiaries.is_paid AND beneficiaries.sex = "female" THEN 1 ELSE 0 END) AS total_payroll_female'),
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
        $contract = [
            'male' => $summaryCount->total_contract_male,
            'female' => $summaryCount->total_contract_female,
        ];
        $payroll = [
            'male' => $summaryCount->total_payroll_male,
            'female' => $summaryCount->total_payroll_female,
        ];

        # Implementation Information
        $implementation = [
            'project_num' => $currentImplementation->project_num,
            'project_title' => $currentImplementation->project_title ?? '-',
            'purpose' => $currentImplementation->purpose,
            'province' => $currentImplementation->province,
            'city_municipality' => $currentImplementation->city_municipality,
            'budget_amount' => $currentImplementation->budget_amount,
            'minimum_wage' => $currentImplementation->minimum_wage,
            'total_slots' => $currentImplementation->total_slots,
            'days_of_work' => $currentImplementation->days_of_work,
            'status' => $currentImplementation->status,
            'created_at' => $currentImplementation->created_at,
            'updated_at' => $currentImplementation->updated_at,
        ];

        # Barangay Information
        $batches = [];

        foreach ($currentBatches as $batch) {
            $batches[] = [
                'is_sectoral' => $batch->is_sectoral,
                'sector_title' => $batch->sector_title,
                'barangay_name' => $batch->barangay_name,
                'total_male' => $batch->total_male,
                'total_female' => $batch->total_female,
                'total_pwd_male' => $batch->total_pwd_male,
                'total_pwd_female' => $batch->total_pwd_female,
                'total_senior_male' => $batch->total_senior_male,
                'total_senior_female' => $batch->total_senior_female,
                'total_contract_male' => $batch->total_contract_male,
                'total_contract_female' => $batch->total_contract_female,
                'total_payroll_male' => $batch->total_payroll_male,
                'total_payroll_female' => $batch->total_payroll_female,
            ];
        }

        # Store the data to be printed in the session
        session([
            'print-summary-information' => [
                'overall' => $overall,
                'seniors' => $seniors,
                'pwds' => $pwds,
                'contract' => $contract,
                'payroll' => $payroll,
                'implementation' => $implementation,
                'batches' => $batches
            ]
        ]);

        # Dispatch an event to open the print dialog
        $this->dispatch('openPrintWindow', url: $this->summaryUrl, currentDate: now()->format('Y-m-d H:i:s'))->self();
    }

    public function showExport()
    {
        # By Date Range
        $this->defaultExportStart = $this->calendarStart;
        $this->defaultExportEnd = $this->calendarEnd;

        $this->export_start = $this->start;
        $this->export_end = $this->end;

        # By Selected Project
        if ($this->implementationId) {
            $this->exportImplementationId = $this->implementationId;
            $this->currentExportImplementation = $this->exportImplementations[0]->project_title ? ($this->exportImplementations[0]->project_title . ' / ' . $this->exportImplementations[0]->project_num) : $this->exportImplementations[0]->project_num;
        }

        $this->dispatch('init-reload')->self();
        $this->showExportModal = true;
    }

    public function exportSummary()
    {
        $data = [
            'date_range' => null,
            'selected_project' => null,
        ];

        if ($this->exportChoice === 'date_range') {

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
            $fileName = 'Summary-Report (' . now()->format('Y-m-d H_i_s') . ').xlsx';
        } elseif ($this->exportFormat === 'csv') {
            $writer = new Csv($spreadsheet);
            $fileName = 'Summary-Report (' . now()->format('Y-m-d H_i_s') . ').csv';
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
        return Implementation::where('id', $this->exportImplementationId ? decrypt($this->exportImplementationId) : null)
            ->get();
    }

    #[Computed]
    public function exportBatchesInfo()
    {
        return Batch::whereHas('beneficiary')
            ->where('batches.implementations_id', $this->exportImplementationId ? decrypt($this->exportImplementationId) : null)
            ->get();
    }

    #[Computed]
    public function exportBeneficiaryCount($encryptedId)
    {
        return Beneficiary::where('batches_id', decrypt($encryptedId))
            ->count();
    }

    #[Computed]
    public function exportBeneficiaryCountByImplementation()
    {
        return Beneficiary::join('batches', 'batches.id', '=', 'beneficiaries.batches_id')
            ->join('implementations', 'implementations.id', '=', 'batches.implementations_id')
            ->where('implementations.id', $this->exportImplementationId ? decrypt($this->exportImplementationId) : null)
            ->count();
    }

    #[Computed]
    public function exportImplementations()
    {
        return Implementation::whereHas('batch.beneficiary')
            ->where('users_id', auth()->id())
            ->when($this->exportChoice === 'selected_project' && $this->searchExportProject, function ($q) {
                $q->where(function ($query) {
                    $query->where('implementations.project_num', 'LIKE', '%' . $this->searchExportProject . '%')
                        ->orWhere('implementations.project_title', 'LIKE', '%' . $this->searchExportProject . '%');
                });
            })
            ->whereBetween('implementations.created_at', [$this->export_start, $this->export_end])
            ->latest('implementations.updated_at')
            ->select([
                'implementations.*'
            ])
            ->get();
    }

    public function resetExport()
    {
        $this->reset('defaultExportStart', 'defaultExportEnd', 'export_start', 'export_end');
        $this->resetValidation(['exportImplementationId']);

        if ($this->exportImplementations->isEmpty()) {
            $this->exportImplementationId = null;
            $this->currentExportImplementation = 'None';
        } else {
            $this->exportImplementationId = encrypt($this->exportImplementations[0]->id);
            $this->currentExportImplementation = $this->exportImplementations[0]->project_title ? ($this->exportImplementations[0]->project_title . ' / ' . $this->exportImplementations[0]->project_num) : $this->exportImplementations[0]->project_num;
        }

    }

    # ----------------------------------------------------------------------------------------------

    # Selects the implementation project 
    public function selectImplementation($encryptedId)
    {
        $this->implementationId = $encryptedId;
        $this->currentImplementation = $this->implementation?->project_num ?? 'None';
        $this->resetPage();
        $this->setCharts();
    }

    # Checks when the `Date Range` values has been changed compared to the default values
    # of when the page was loaded
    #[Computed]
    public function isDateRangeChanged()
    {
        return (Carbon::parse($this->start)->format('Y-m-d') !== Carbon::parse($this->defaultStart)->format('Y-m-d') ||
            Carbon::parse($this->end)->format('Y-m-d') !== Carbon::parse($this->defaultEnd)->format('Y-m-d'));
    }

    # Gets all the implementations from the start year to the end year based on selected date range year
    # (for example, you picked start: March 03, 2023 -> end: September 29, 2025
    # it will query all implementations from January 01, 2023 -> December 31, 2025 instead.)
    #[Computed]
    public function implementationTotalByYear()
    {
        return Implementation::where('users_id', auth()->id())
            ->whereBetween('created_at', [Carbon::parse($this->start)->startOfYear(), Carbon::parse($this->end)->endOfYear()])
            ->count();
    }

    # The `Total Implementations`, `Approved Batches` and `Pending Batches` counter
    #[Computed]
    public function implementationCounters()
    {
        return Implementation::where('implementations.users_id', auth()->id())
            ->whereBetween('created_at', [$this->start, $this->end])
            ->select([
                DB::raw('(
                SELECT COUNT(*)
                FROM implementations
                WHERE users_id = ' . auth()->id() . '
                AND created_at BETWEEN "' . $this->start . '" AND "' . $this->end . '"
            ) AS total_projects'),
                DB::raw('SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) AS total_pending'),
                DB::raw('SUM(CASE WHEN status = "implementing" THEN 1 ELSE 0 END) AS total_implementing'),
                DB::raw('SUM(CASE WHEN status = "concluded" THEN 1 ELSE 0 END) AS total_concluded'),
            ])->first();

    }

    #[Computed]
    public function batchCounters()
    {
        return Batch::join('implementations', 'implementations.id', '=', 'batches.implementations_id')
            ->where('implementations.users_id', auth()->id())
            ->whereBetween('batches.created_at', [$this->start, $this->end])
            ->select([
                DB::raw('SUM(CASE WHEN batches.approval_status = "approved" THEN 1 ELSE 0 END) AS total_approved'),
                DB::raw('SUM(CASE WHEN batches.approval_status = "pending" THEN 1 ELSE 0 END) AS total_pending'),
                DB::raw('SUM(CASE WHEN batches.submission_status = "unopened" THEN 1 ELSE 0 END) AS total_unopened'),
                DB::raw('SUM(CASE WHEN batches.submission_status = "encoding" THEN 1 ELSE 0 END) AS total_encoding'),
                DB::raw('SUM(CASE WHEN batches.submission_status = "revalidate" THEN 1 ELSE 0 END) AS total_revalidate'),
                DB::raw('SUM(CASE WHEN batches.submission_status = "submitted" THEN 1 ELSE 0 END) AS total_submitted'),
            ])->first();
    }

    #[Computed]
    public function totalCounters()
    {
        $totalCounters = Implementation::join('batches', 'implementations.id', '=', 'batches.implementations_id')
            ->join('beneficiaries', 'batches.id', '=', 'beneficiaries.batches_id')
            ->where('implementations.users_id', '=', auth()->id())
            ->whereBetween('batches.created_at', [$this->start, $this->end])
            ->select([
                DB::raw('COUNT(DISTINCT beneficiaries.id) AS total_beneficiaries'),
                DB::raw('SUM(CASE WHEN beneficiaries.is_pwd = "yes" THEN 1 ELSE 0 END) AS total_pwd_beneficiaries'),
                DB::raw('SUM(CASE WHEN beneficiaries.is_senior_citizen = "yes" THEN 1 ELSE 0 END) AS total_senior_citizen_beneficiaries'),
                DB::raw('SUM(CASE WHEN beneficiaries.is_signed THEN 1 ELSE 0 END) AS total_contract_beneficiaries'),
                DB::raw('SUM(CASE WHEN beneficiaries.is_paid THEN 1 ELSE 0 END) AS total_payroll_beneficiaries'),
            ])
            ->first();

        return $totalCounters;
    }

    # Used to check the total beneficiaries of the selected implementation project
    #[Computed]
    public function beneficiaryTotalByImplementation()
    {
        $count = Beneficiary::join('batches', 'batches.id', '=', 'beneficiaries.batches_id')
            ->join('implementations', 'implementations.id', '=', 'batches.implementations_id')
            ->where('implementations.id', $this->implementationId ? decrypt($this->implementationId) : null)
            ->count();
        return $count;
    }

    # Returns the implementation project based on the selected project 
    # from the `Summary of Beneficiaries` dropdown
    #[Computed]
    public function implementation()
    {
        return Implementation::find($this->implementationId ? decrypt($this->implementationId) : null);
    }

    # The general implementation projects based on search and date range, order by updated_at
    #[Computed]
    public function implementations()
    {
        $implementations = Implementation::where('users_id', auth()->id())
            ->when($this->searchProjects, function ($query) {
                $query->where(function ($q) {
                    $q->where('project_num', 'LIKE', '%' . $this->searchProjects . '%')
                        ->orWhere('project_title', 'LIKE', '%' . $this->searchProjects . '%');
                });
            })
            ->whereBetween('created_at', [$this->start, $this->end])
            ->latest('updated_at')
            ->get();

        return $implementations;
    }

    #[Computed]
    public function batches()
    {
        $batches = Batch::whereHas(
            'implementation',
            function ($q) {
                $q->where('implementations.users_id', auth()->id())
                    ->where('implementations.id', $this->implementationId ? decrypt($this->implementationId) : null);
            }
        )->join('beneficiaries', 'beneficiaries.batches_id', '=', 'batches.id')
            ->select([
                'batches.id',
                'batches.is_sectoral',
                'batches.sector_title',
                'batches.barangay_name',
                DB::raw('SUM(CASE WHEN beneficiaries.sex = "male" THEN 1 ELSE 0 END) AS total_male'),
                DB::raw('SUM(CASE WHEN beneficiaries.sex = "female" THEN 1 ELSE 0 END) AS total_female'),
                DB::raw('SUM(CASE WHEN beneficiaries.is_pwd = "yes" AND beneficiaries.sex = "male" THEN 1 ELSE 0 END) AS total_pwd_male'),
                DB::raw('SUM(CASE WHEN beneficiaries.is_pwd = "yes" AND beneficiaries.sex = "female" THEN 1 ELSE 0 END) AS total_pwd_female'),
                DB::raw('SUM(CASE WHEN beneficiaries.is_senior_citizen = "yes" AND beneficiaries.sex = "male" THEN 1 ELSE 0 END) AS total_senior_male'),
                DB::raw('SUM(CASE WHEN beneficiaries.is_senior_citizen = "yes" AND beneficiaries.sex = "female" THEN 1 ELSE 0 END) AS total_senior_female'),
                DB::raw('SUM(CASE WHEN beneficiaries.is_signed AND beneficiaries.sex = "male" THEN 1 ELSE 0 END) AS total_contract_male'),
                DB::raw('SUM(CASE WHEN beneficiaries.is_signed AND beneficiaries.sex = "female" THEN 1 ELSE 0 END) AS total_contract_female'),
                DB::raw('SUM(CASE WHEN beneficiaries.is_paid AND beneficiaries.sex = "male" THEN 1 ELSE 0 END) AS total_payroll_male'),
                DB::raw('SUM(CASE WHEN beneficiaries.is_paid AND beneficiaries.sex = "female" THEN 1 ELSE 0 END) AS total_payroll_female'),
            ])
            ->whereBetween('batches.created_at', [$this->start, $this->end])
            ->groupBy(['batches.id', 'batches.is_sectoral', 'batches.sector_title', 'batches.barangay_name'])
            ->paginate(2);

        return $batches;
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
                DB::raw('SUM(CASE WHEN beneficiaries.is_senior_citizen = "yes" AND beneficiaries.sex = "female" THEN 1 ELSE 0 END) AS total_senior_female'),
                DB::raw('SUM(CASE WHEN beneficiaries.is_signed AND beneficiaries.sex = "male" THEN 1 ELSE 0 END) AS total_contract_male'),
                DB::raw('SUM(CASE WHEN beneficiaries.is_signed AND beneficiaries.sex = "female" THEN 1 ELSE 0 END) AS total_contract_female'),
                DB::raw('SUM(CASE WHEN beneficiaries.is_paid AND beneficiaries.sex = "male" THEN 1 ELSE 0 END) AS total_payroll_male'),
                DB::raw('SUM(CASE WHEN beneficiaries.is_paid AND beneficiaries.sex = "female" THEN 1 ELSE 0 END) AS total_payroll_female'),
            ])
            ->where('implementations.users_id', auth()->id())
            ->where('implementations.id', $this->implementationId ? decrypt($this->implementationId) : null)
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

    #[Computed]
    public function total_contract()
    {
        if (is_null($this->summaryCount->total_contract_male) && is_null($this->summaryCount->total_contract_female)) {
            return null;
        } elseif (intval($this->summaryCount->total_contract_male) === 0 && intval($this->summaryCount->total_contract_female) === 0) {
            return 0;
        } else {
            $total = [
                'male' => $this->summaryCount->total_contract_male,
                'female' => $this->summaryCount->total_contract_female,
            ];
            return $total;
        }
    }

    #[Computed]
    public function total_payroll()
    {
        if (is_null($this->summaryCount->total_payroll_male) && is_null($this->summaryCount->total_payroll_female)) {
            return null;
        } elseif (intval($this->summaryCount->total_payroll_male) === 0 && intval($this->summaryCount->total_payroll_female) === 0) {
            return 0;
        } else {
            $total = [
                'male' => $this->summaryCount->total_payroll_male,
                'female' => $this->summaryCount->total_payroll_female,
            ];
            return $total;
        }
    }

    public function setCharts()
    {
        $overallValues = $this->total_beneficiaries;
        $pwdValues = $this->total_pwds;
        $seniorValues = $this->total_seniors;
        $contractValues = $this->total_contract;
        $payrollValues = $this->total_payroll;

        $this->dispatch('series-change', overallValues: $overallValues, pwdValues: $pwdValues, seniorValues: $seniorValues, contractValues: $contractValues, payrollValues: $payrollValues)->self();
    }

    # It's a Livewire `Hook` for properties so the system can take action
    # when a specific property has updated its state. 
    public function updated($prop)
    {
        if ($prop === 'defaultExportStart') {
            $format = Essential::extract_date($this->defaultExportStart, false);
            if ($format !== 'm/d/Y') {
                $this->defaultExportStart = Carbon::parse($this->export_start)->format('m/d/Y');
                return;
            }
            $choosenDate = Carbon::createFromFormat('m/d/Y', $this->defaultExportStart)->format('Y-m-d');
            $currentTime = now()->startOfDay()->format('H:i:s');

            $this->export_start = $choosenDate . ' ' . $currentTime;

            if (strtotime($this->export_start) > strtotime($this->export_end)) {
                $export_end = Carbon::parse($this->export_start)->addMonth()->endOfDay()->format('Y-m-d H:i:s');
                $this->export_end = $export_end;
                $this->defaultExportEnd = Carbon::parse($this->export_end)->format('m/d/Y');
            }

            if ($this->exportImplementations->isEmpty()) {
                $this->exportImplementationId = null;
                $this->currentExportImplementation = 'None';
            } else {
                $this->exportImplementationId = encrypt($this->exportImplementations[0]->id);
                $this->currentExportImplementation = $this->exportImplementations[0]->project_title ? ($this->exportImplementations[0]->project_title . ' / ' . $this->exportImplementations[0]->project_num) : $this->exportImplementations[0]->project_num;
            }

            $this->dispatch('init-reload')->self();
        }

        if ($prop === 'defaultExportEnd') {
            $format = Essential::extract_date($this->defaultExportEnd, false);
            if ($format !== 'm/d/Y') {
                $this->defaultExportEnd = Carbon::parse($this->export_end)->format('m/d/Y');
                return;
            }
            $choosenDate = Carbon::createFromFormat('m/d/Y', $this->defaultExportEnd)->format('Y-m-d');
            $currentTime = now()->endOfDay()->format('H:i:s');

            $this->export_end = $choosenDate . ' ' . $currentTime;

            if (strtotime($this->export_start) > strtotime($this->export_end)) {
                $export_start = Carbon::parse($this->export_end)->subMonth()->startOfDay()->format('Y-m-d H:i:s');
                $this->export_start = $export_start;
                $this->defaultExportStart = Carbon::parse($this->export_start)->format('m/d/Y');
            }

            if ($this->exportImplementations->isEmpty()) {
                $this->exportImplementationId = null;
                $this->currentExportImplementation = 'None';
            } else {
                $this->exportImplementationId = encrypt($this->exportImplementations[0]->id);
                $this->currentExportImplementation = $this->exportImplementations[0]->project_title ? ($this->exportImplementations[0]->project_title . ' / ' . $this->exportImplementations[0]->project_num) : $this->exportImplementations[0]->project_num;
            }

            $this->dispatch('init-reload')->self();
        }

        if ($prop === 'calendarStart') {
            $format = Essential::extract_date($this->calendarStart, false);
            if ($format !== 'm/d/Y') {
                $this->calendarStart = $this->defaultStart;
                return;
            }

            $this->reset('searchProjects');
            $choosenDate = Carbon::createFromFormat('m/d/Y', $this->calendarStart)->format('Y-m-d');
            $currentTime = now()->startOfDay()->format('H:i:s');

            $this->start = $choosenDate . ' ' . $currentTime;

            if (strtotime($this->start) > strtotime($this->end)) {
                $end = Carbon::parse($this->start)->addMonth()->endOfDay()->format('Y-m-d H:i:s');
                $this->end = $end;
                $this->calendarEnd = Carbon::parse($this->end)->format('m/d/Y');
            }

            if ($this->implementations->isEmpty()) {
                $this->implementationId = null;
                $this->currentImplementation = 'None';
            } else {
                $this->implementationId = encrypt($this->implementations[0]->id);
                $this->currentImplementation = $this->implementations[0]->project_num;
            }

            $this->resetPage();
            $this->setCharts();
            $this->dispatch('init-reload')->self();
        }

        if ($prop === 'calendarEnd') {
            $format = Essential::extract_date($this->calendarEnd, false);
            if ($format !== 'm/d/Y') {
                $this->calendarEnd = $this->defaultEnd;
                return;
            }

            $this->reset('searchProjects');
            $choosenDate = Carbon::createFromFormat('m/d/Y', $this->calendarEnd)->format('Y-m-d');
            $currentTime = now()->endOfDay()->format('H:i:s');

            $this->end = $choosenDate . ' ' . $currentTime;

            if (strtotime($this->start) > strtotime($this->end)) {
                $start = Carbon::parse($this->end)->subMonth()->startOfDay()->format('Y-m-d H:i:s');
                $this->start = $start;
                $this->calendarStart = Carbon::parse($this->start)->format('m/d/Y');
            }

            if ($this->implementations->isEmpty()) {
                $this->implementationId = null;
                $this->currentImplementation = 'None';
            } else {
                $this->implementationId = encrypt($this->implementations[0]->id);
                $this->currentImplementation = $this->implementations[0]->project_num;
            }

            $this->resetPage();
            $this->setCharts();
            $this->dispatch('init-reload')->self();
        }

        if ($prop === 'exportChoice') {

            if ($this->exportChoice === 'selected_project') {

                # By Date Range
                $this->defaultExportStart = $this->calendarStart;
                $this->defaultExportEnd = $this->calendarEnd;

                $this->export_start = $this->start;
                $this->export_end = $this->end;

                # By Selected Project
                if ($this->implementationId) {
                    $this->exportImplementationId = $this->implementationId;
                    $this->currentExportImplementation = $this->exportImplementations[0]->project_title ? ($this->exportImplementations[0]->project_title . ' / ' . $this->exportImplementations[0]->project_num) : $this->exportImplementations[0]->project_num;
                }
            }

            $this->dispatch('init-reload')->self();
        }
    }

    public function mount()
    {
        $user = Auth::user();
        if ($user->user_type !== 'focal') {
            $this->redirectIntended();
        }
        $this->summaryUrl = url('/print-summary');
        /*
         *  Setting default dates in the datepicker
         */
        $this->start = now()->subYear()->startOfYear()->format('Y-m-d H:i:s');
        $this->end = now()->endOfDay()->format('Y-m-d H:i:s');

        $this->export_start = $this->start;
        $this->export_end = $this->end;

        $this->calendarStart = Carbon::parse($this->start)->format('m/d/Y');
        $this->calendarEnd = Carbon::parse($this->end)->format('m/d/Y');

        $this->defaultStart = $this->calendarStart;
        $this->defaultEnd = $this->calendarEnd;

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
            $this->currentExportImplementation = $this->exportImplementations[0]->project_title ? ($this->exportImplementations[0]->project_title . ' / ' . $this->exportImplementations[0]->project_num) : $this->exportImplementations[0]->project_num;
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
