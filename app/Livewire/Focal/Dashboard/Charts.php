<?php

namespace App\Livewire\Focal\Dashboard;

use App\Models\Batch;
use App\Models\Implementation;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

class Charts extends Component
{
    #[Locked]
    public $implementationCount = [];
    #[Locked]
    public $before;
    #[Locked]
    public $after;
    #[Locked]
    public $projectNum;

    #[On('time-change')]
    public function setCurrentTime($time)
    {
        // Get the start of the year
        $startOfYear = Carbon::now()->startOfYear();
        // Get the end of the year
        $endOfYear = Carbon::now()->endOfYear();
        // Get the start of the month
        $startOfMonth = Carbon::now()->startOfMonth();
        // Get the end of the month
        $endOfMonth = Carbon::now()->endOfMonth();
        // Get the date 3 months ago
        $threeMonthsAgo = Carbon::now()->subMonths(3);
        // Get the date 6 months ago
        $sixMonthsAgo = Carbon::now()->subMonths(6);
        // Get the date from a decade ago
        $aLongTimeAgo = Carbon::now()->subDecades(1);
        // Get the current date and time
        $now = Carbon::now();

        switch (strtolower($time)) {
            case 'this year':
                $this->before = $startOfYear;
                $this->after = $endOfYear;
                break;
            case 'this month':
                $this->before = $startOfMonth;
                $this->after = $endOfMonth;
                break;
            case 'past 3 months':
                $this->before = $threeMonthsAgo;
                $this->after = $now;
                break;
            case 'past 6 months':
                $this->before = $sixMonthsAgo;
                $this->after = $now;
                break;
            case 'all time':
                $this->before = $aLongTimeAgo;
                $this->after = $now;
                break;
        }

        $this->updateCharts();
    }

    #[On('implementation-change')]
    public function setImplementationProjectNum($project_num)
    {
        $this->projectNum = $project_num;
        $this->updateCharts();
    }

    public function updateCharts()
    {
        $focalUserId = 2;

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
            ->where('implementations.project_num', $this->projectNum)
            ->whereBetween('batches.created_at', [$this->before, $this->after])
            ->first()
            ->toArray();

        $this->dispatch('series-change', ['implementationCount' => $this->implementationCount]);
    }

    public function mount()
    {
        $focalUserId = 2;
        $this->before = Carbon::now()->startOfYear();
        $this->after = Carbon::now()->endOfYear();

        $this->projectNum = Batch::join('implementations', 'batches.implementations_id', '=', 'implementations.id')
            ->where('implementations.users_id', '=', $focalUserId)
            ->whereBetween('batches.created_at', [$this->before, $this->after])
            ->value('implementations.project_num');

        $implementations = Implementation::join('batches', 'implementations.id', '=', 'batches.implementations_id')
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
            ->where('implementations.project_num', $this->projectNum)
            ->whereBetween('batches.created_at', [$this->before, $this->after])
            ->first()
            ->toArray();

        // dd($implementations);
        // dd($this->totalOverall = [$implementations['total_male'], $implementations['total_female']]);
        // $this->totalPwd = [$implementations['total_pwd_male'], $implementations['total_pwd_female']];
        // $this->totalSenior = [$implementations['total_senior_male'], $implementations['total_senior_female']];
        $this->implementationCount = $implementations;
    }
    public function render()
    {
        return view('livewire.focal.dashboard.charts');
    }
}
