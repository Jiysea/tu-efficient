<?php

namespace App\Livewire\Focal;

use App\Models\Batch;
use App\Models\Implementation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Dashboard | TU-Efficient')]
class Dashboard extends Component
{
    use WithPagination, WithoutUrlPagination;

    #[Locked]
    public $batchesCount;
    #[Locked]
    public $implementationCount;
    #[Locked]
    public $currentImplementation;
    #[Locked]
    public $implementations;
    #[Locked]
    public $implementationsId;
    #[Locked]
    public $projectCounters;
    #[Locked]
    public $beneficiaryCounters;
    #[Locked]
    public $start;
    #[Locked]
    public $end;
    #[Locked]
    public $defaultStart;
    #[Locked]
    public $defaultEnd;
    public $searchProject;


    #[On('start-change')]
    public function setStartDate($value)
    {
        $this->start = date('Y-m-d', strtotime($value));
        $this->setProjectCounters();
        $this->setListOfImplementations();
    }

    #[On('end-change')]
    public function setEndDate($value)
    {
        $this->end = date('Y-m-d', strtotime($value));
        $this->setProjectCounters();
        $this->setListOfImplementations();
    }

    public function setProjectCounters()
    {
        $focalUserId = auth()->id();
        $this->projectCounters = Implementation::join('batches', 'implementations.id', '=', 'batches.implementations_id')
            ->where('implementations.users_id', $focalUserId)
            ->whereBetween('batches.created_at', [$this->start, $this->end])
            ->select([
                DB::raw('COUNT(DISTINCT implementations.id) AS total_implementations'),
                DB::raw('SUM(CASE WHEN batches.approval_status = "APPROVED" THEN 1 ELSE 0 END) AS total_approved_assignments'),
                DB::raw('SUM(CASE WHEN batches.approval_status = "PENDING" THEN 1 ELSE 0 END) AS total_pending_assignments'),
            ])
            ->first()
            ->toArray();
    }

    public function setListOfImplementations()
    {
        $focalUserId = auth()->id();
        $this->implementations = Implementation::where('users_id', $focalUserId)
            ->where('project_num', 'LIKE', '%' . $this->searchProject . '%')
            ->whereBetween('created_at', [$this->start, $this->end])
            ->select('id', 'project_num')
            ->get()
            ->toArray();

        $this->currentImplementation = $this->implementations[0]['project_num'] ?? 'None';
        $this->implementationsId = $this->implementations[0]['id'] ?? null;

        $this->resetPage();
        $this->setCharts();
        $this->setBeneficiaryCounters();
    }

    public function setCharts()
    {
        $focalUserId = auth()->id();

        $this->implementationCount = Implementation::join('batches', 'implementations.id', '=', 'batches.implementations_id')
            ->join('beneficiaries', 'beneficiaries.batches_id', '=', 'batches.id')
            ->select([
                DB::raw('SUM(CASE WHEN beneficiaries.sex = "Male" THEN 1 ELSE 0 END) AS total_male'),
                DB::raw('SUM(CASE WHEN beneficiaries.sex = "Female" THEN 1 ELSE 0 END) AS total_female'),
                DB::raw('SUM(CASE WHEN beneficiaries.is_pwd = "Yes" AND beneficiaries.sex = "Male" THEN 1 ELSE 0 END) AS total_pwd_male'),
                DB::raw('SUM(CASE WHEN beneficiaries.is_pwd = "Yes" AND beneficiaries.sex = "Female" THEN 1 ELSE 0 END) AS total_pwd_female'),
                DB::raw('SUM(CASE WHEN beneficiaries.is_senior_citizen = "Yes" AND beneficiaries.sex = "Male" THEN 1 ELSE 0 END) AS total_senior_male'),
                DB::raw('SUM(CASE WHEN beneficiaries.is_senior_citizen = "Yes" AND beneficiaries.sex = "Female" THEN 1 ELSE 0 END) AS total_senior_female')
            ])
            ->where('implementations.users_id', $focalUserId)
            ->where('implementations.id', $this->implementationsId)
            ->whereBetween('batches.created_at', [$this->start, $this->end])
            ->first()
            ->toArray();

        $overallValues = [$this->implementationCount['total_male'], $this->implementationCount['total_female']];
        $pwdValues = [$this->implementationCount['total_pwd_male'], $this->implementationCount['total_pwd_female']];
        $seniorValues = [$this->implementationCount['total_senior_male'], $this->implementationCount['total_senior_female']];

        $this->dispatch('series-change', overallValues: $overallValues, pwdValues: $pwdValues, seniorValues: $seniorValues)->self();
    }

    public function setBeneficiaryCounters()
    {
        $focalUserId = auth()->id();

        $this->beneficiaryCounters = Implementation::join('batches', 'implementations.id', '=', 'batches.implementations_id')
            ->join('beneficiaries', 'batches.id', '=', 'beneficiaries.batches_id')
            ->where('implementations.users_id', '=', $focalUserId)
            ->whereBetween('batches.created_at', [$this->start, $this->end])
            ->select([
                DB::raw('COUNT(DISTINCT beneficiaries.id) AS total_beneficiaries'),
                DB::raw('SUM(CASE WHEN beneficiaries.is_pwd = "Yes" THEN 1 ELSE 0 END) AS total_pwd_beneficiaries'),
                DB::raw('SUM(CASE WHEN beneficiaries.is_senior_citizen = "Yes" THEN 1 ELSE 0 END) AS total_senior_citizen_beneficiaries'),
            ])
            ->first()
            ->toArray();
    }

    public function updateCurrentImplementation($key)
    {
        $this->resetPage();
        $this->currentImplementation = $this->implementations[$key]['project_num'];
        $this->implementationsId = $this->implementations[$key]['id'];

        $this->setCharts();
        $this->setBeneficiaryCounters();
    }

    public function mount()
    {
        /*
         *  Setting default dates in the datepicker
         */
        $this->start = date('Y-m-d', strtotime(now()->startOfYear()));
        $this->end = date('Y-m-d', strtotime(now()));


        $this->defaultStart = date('m/d/Y', strtotime($this->start));
        $this->defaultEnd = date('m/d/Y', strtotime($this->end));

        /*
         *  Setting project counters (Total Implementations, Approved Assignments, Pending Assignments)
         */
        $this->setProjectCounters();

        /*
         *  Summary of Implementations
         */
        $this->setListOfImplementations();

        /*
         *  Charts
         */
        $this->setCharts();

        /*
         *   Setting the total counters of the beneficiaries
         */
        $this->setBeneficiaryCounters();
    }

    public function render()
    {
        $focalUserId = auth()->id();

        $this->implementations = Implementation::where('users_id', $focalUserId)
            ->where('project_num', 'LIKE', '%' . $this->searchProject . '%')
            ->whereBetween('created_at', [$this->start, $this->end])
            ->select('id', 'project_num')
            ->get()
            ->toArray();

        // $this->updateCurrentImplementation();

        $batches = Batch::join('implementations', 'batches.implementations_id', '=', 'implementations.id')
            ->join('beneficiaries', 'beneficiaries.batches_id', '=', 'batches.id')
            ->select([
                'batches.id',
                'batches.barangay_name',
                DB::raw('SUM(CASE WHEN beneficiaries.sex = "Male" THEN 1 ELSE 0 END) AS total_male'),
                DB::raw('SUM(CASE WHEN beneficiaries.sex = "Female" THEN 1 ELSE 0 END) AS total_female'),
                DB::raw('SUM(CASE WHEN beneficiaries.is_pwd = "Yes" AND beneficiaries.sex = "Male" THEN 1 ELSE 0 END) AS total_pwd_male'),
                DB::raw('SUM(CASE WHEN beneficiaries.is_pwd = "Yes" AND beneficiaries.sex = "Female" THEN 1 ELSE 0 END) AS total_pwd_female'),
                DB::raw('SUM(CASE WHEN beneficiaries.is_senior_citizen = "Yes" AND beneficiaries.sex = "Male" THEN 1 ELSE 0 END) AS total_senior_male'),
                DB::raw('SUM(CASE WHEN beneficiaries.is_senior_citizen = "Yes" AND beneficiaries.sex = "Female" THEN 1 ELSE 0 END) AS total_senior_female')
            ])
            ->where('implementations.users_id', $focalUserId)
            ->where('implementations.id', $this->implementationsId)
            ->whereBetween('batches.created_at', [$this->start, $this->end])
            ->groupBy(['batches.id', 'batches.barangay_name'])
            ->paginate(3);

        $this->batchesCount = $batches->total();

        if (Auth::user()->user_type === 'Focal')
            return view('livewire.focal.dashboard', ['batches' => $batches]);
        else if (Auth::user()->user_type === 'Coordinator')
            return redirect()->route('coordinator.home');
    }
}
