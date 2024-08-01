<?php

namespace App\Livewire\Focal\Dashboard;

use App\Models\Implementation;
use Carbon\Carbon;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Support\Arr;

class SummaryImplementation extends Component
{
    #[Locked]
    public $before;
    #[Locked]
    public $after;
    #[Locked]
    public $implementations;
    #[Locked]
    public $currentImplementation;

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
        $this->updateListOfImplementations();
    }

    public function updateCurrentImplementation($key)
    {
        $this->currentImplementation = Arr::get($this->implementations, $key . '.project_num');

        $this->dispatch('implementation-change', project_num: $this->currentImplementation);
    }

    public function updateListOfImplementations()
    {
        $focalUserId = 2;
        $this->implementations = Implementation::where('users_id', $focalUserId)
            ->whereBetween('created_at', [$this->before, $this->after])
            ->select('project_num')
            ->get()
            ->toArray();

        $this->currentImplementation = Arr::get($this->implementations, '0.project_num');

        $this->dispatch('implementation-change', project_num: $this->currentImplementation);
    }

    public function mount()
    {
        $focalUserId = 2;
        $this->before = Carbon::now()->startOfYear();
        $this->after = Carbon::now()->endOfYear();

        $this->implementations = Implementation::where('users_id', $focalUserId)
            ->whereBetween('created_at', [$this->before, $this->after])
            ->select('project_num')
            ->get()
            ->toArray();

        $this->currentImplementation = Arr::get($this->implementations, '0.project_num');
    }

    public function render()
    {
        return view('livewire.focal.dashboard.summary-implementation');
    }
}
