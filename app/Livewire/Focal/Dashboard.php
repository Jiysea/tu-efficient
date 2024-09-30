<?php

namespace App\Livewire\Focal;

use App\Livewire\Coordinator\Assignments;
use App\Models\Batch;
use App\Models\Implementation;
use App\Models\User;
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
    public $implementationId;
    public $currentImplementation;
    public $start;
    public $end;
    public $defaultStart;
    public $defaultEnd;
    public $searchProject;


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
            $this->implementationId = $this->implementations[0]->id;
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
            $this->implementationId = $this->implementations[0]->id;
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
    public function implementations()
    {
        $implementations = Implementation::where('users_id', Auth::id())
            ->where('project_num', 'LIKE', '%' . $this->searchProject . '%')
            ->whereBetween('created_at', [$this->start, $this->end])
            ->select('id', 'project_num')
            ->latest('updated_at')
            ->get();

        return $implementations;
    }

    #[Computed]
    public function implementationCount()
    {
        $implementationCount = Implementation::join('batches', 'implementations.id', '=', 'batches.implementations_id')
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
            ->where('implementations.id', $this->implementationId)
            ->whereBetween('batches.created_at', [$this->start, $this->end])
            ->first();

        return $implementationCount;
    }
    public function setCharts()
    {
        $overallValues = [$this->implementationCount->total_male, $this->implementationCount->total_female];
        $pwdValues = [$this->implementationCount->total_pwd_male, $this->implementationCount->total_pwd_female];
        $seniorValues = [$this->implementationCount->total_senior_male, $this->implementationCount->total_senior_female];

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

    public function updateCurrentImplementation($key)
    {
        $this->resetPage();
        $this->currentImplementation = $this->implementations[$key]->project_num;
        $this->implementationId = $this->implementations[$key]->id;

        $this->setCharts();
    }

    public function mount()
    {
        if (Auth::user()->user_type !== 'focal') {
            $this->redirectIntended();
        }

        /*
         *  Setting default dates in the datepicker
         */
        $this->start = date('Y-m-d H:i:s', strtotime(now()->startOfYear()));
        $this->end = date('Y-m-d H:i:s', strtotime(now()));

        $this->defaultStart = date('m/d/Y', strtotime($this->start));
        $this->defaultEnd = date('m/d/Y', strtotime($this->end));

        $this->currentImplementation = $this->implementations[0]->project_num;
        $this->implementationId = $this->implementations[0]->id;
        /*
         *  Charts
         */
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
            ->where('implementations.id', $this->implementationId)
            ->whereBetween('batches.created_at', [$this->start, $this->end])
            ->groupBy(['batches.id', 'batches.barangay_name'])
            ->paginate(3);

        return $batches;
    }

    public function render()
    {
        return view('livewire.focal.dashboard');
    }
}
