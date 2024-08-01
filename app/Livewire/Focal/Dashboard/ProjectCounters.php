<?php

namespace App\Livewire\Focal\Dashboard;

use App\Models\Batch;
use App\Models\Implementation;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

class ProjectCounters extends Component
{
    #[Locked]
    public $counters;
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

        $this->updateCounters();
    }

    public function updateCounters()
    {
        $focalUserId = 2;
        $this->counters = Implementation::join('batches', 'implementations.id', '=', 'batches.implementations_id')
            ->where('implementations.users_id', $focalUserId)
            ->whereBetween('batches.created_at', [$this->before, $this->after])
            ->select([
                DB::raw('COUNT(DISTINCT implementations.id) AS total_implementations'),
                DB::raw('SUM(CASE WHEN batches.approval_status = "APPROVED" THEN 1 ELSE 0 END) AS total_approved_assignments'),
                DB::raw('SUM(CASE WHEN batches.approval_status = "PENDING" THEN 1 ELSE 0 END) AS total_pending_assignments'),
            ])
            ->first()
            ->toArray();
    }

    public function mount()
    {
        $focalUserId = 2;
        $this->before = Carbon::now()->startOfYear();
        $this->after = Carbon::now()->endOfYear();

        $this->counters = Implementation::join('batches', 'implementations.id', '=', 'batches.implementations_id')
            ->where('implementations.users_id', '=', $focalUserId)
            ->whereBetween('batches.created_at', [$this->before, $this->after])
            ->select([
                DB::raw('COUNT(DISTINCT implementations.id) AS total_implementations'),
                DB::raw('SUM(CASE WHEN batches.approval_status = "APPROVED" THEN 1 ELSE 0 END) AS total_approved_assignments'),
                DB::raw('SUM(CASE WHEN batches.approval_status = "PENDING" THEN 1 ELSE 0 END) AS total_pending_assignments'),
            ])
            ->first()
            ->toArray();
    }

    public function render()
    {
        return view('livewire.focal.dashboard.project-counters');
    }
}
