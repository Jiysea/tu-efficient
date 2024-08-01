<?php

namespace App\Livewire\Focal\Dashboard;

use App\Models\Implementation;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

class TotalBeneficiariesCounter extends Component
{
    #[Locked]
    public $totalCounters;
    #[Locked]
    public $before;
    #[Locked]
    public $after;

    #[On('time-change')]
    public function setBetweenTime($time)
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

        $this->updateTotalCounters();
    }

    public function updateTotalCounters()
    {
        $focalUserId = 2;

        $this->totalCounters = Implementation::join('batches', 'implementations.id', '=', 'batches.implementations_id')
            ->join('beneficiaries', 'batches.id', '=', 'beneficiaries.batches_id')
            ->where('implementations.users_id', '=', $focalUserId)
            ->whereBetween('batches.created_at', [$this->before, $this->after])
            ->select([
                DB::raw('COUNT(DISTINCT beneficiaries.id) AS total_beneficiaries'),
                DB::raw('SUM(CASE WHEN beneficiaries.is_pwd = "Yes" THEN 1 ELSE 0 END) AS total_pwd_beneficiaries'),
                DB::raw('SUM(CASE WHEN beneficiaries.is_senior_citizen = "Yes" THEN 1 ELSE 0 END) AS total_senior_citizen_beneficiaries'),
            ])
            ->first()
            ->toArray();
    }

    public function mount()
    {
        $focalUserId = 2;
        $this->before = Carbon::now()->startOfYear();
        $this->after = Carbon::now()->endOfYear();

        $this->totalCounters = Implementation::join('batches', 'implementations.id', '=', 'batches.implementations_id')
            ->join('beneficiaries', 'batches.id', '=', 'beneficiaries.batches_id')
            ->where('implementations.users_id', '=', $focalUserId)
            ->whereBetween('batches.created_at', [$this->before, $this->after])
            ->select([
                DB::raw('COUNT(DISTINCT beneficiaries.id) AS total_beneficiaries'),
                DB::raw('SUM(CASE WHEN beneficiaries.is_pwd = "Yes" THEN 1 ELSE 0 END) AS total_pwd_beneficiaries'),
                DB::raw('SUM(CASE WHEN beneficiaries.is_senior_citizen = "Yes" THEN 1 ELSE 0 END) AS total_senior_citizen_beneficiaries'),
            ])
            ->first()
            ->toArray();
    }

    public function render()
    {
        return view('livewire.focal.dashboard.total-beneficiaries-counter');
    }
}
