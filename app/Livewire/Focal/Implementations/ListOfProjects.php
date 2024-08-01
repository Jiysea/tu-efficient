<?php

namespace App\Livewire\Focal\Implementations;

use App\Models\Implementation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

class ListOfProjects extends Component
{
    public $selectedRow = 0;
    #[Locked]
    public $projects;
    #[Locked]
    public $before;
    #[Locked]
    public $after;
    #[Locked]
    public $projectId;

    public function selectRow($key, $encryptedId)
    {
        $this->selectedRow = $key;
        $this->projectId = $encryptedId;

        $this->dispatch('change-project', projectId: $encryptedId);
    }

    #[On('implementation-time-change')]
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

        // $focalUserId = 2;
        // $this->projects = Implementation::where('users_id', $focalUserId)
        //     ->whereBetween('created_at', [$this->before, $this->after])
        //     ->get()
        //     ->toArray();

        $this->updateListOfImplementations();
    }

    public function updateListOfImplementations()
    {
        $focalUserId = 2;
        $this->projects = Implementation::where('users_id', $focalUserId)
            ->whereBetween('created_at', [$this->before, $this->after])
            ->get()
            ->toArray();

        $encryptedId = Crypt::encrypt($this->projects[0]['id']);
        $this->dispatch('change-project', projectId: $encryptedId);
    }

    public function mount()
    {
        $focalUserId = 2;
        $this->before = Carbon::now()->startOfYear();
        $this->after = Carbon::now()->endOfYear();

        $this->projects = Implementation::where('users_id', $focalUserId)
            ->whereBetween('created_at', [$this->before, $this->after])
            ->get()
            ->toArray();
    }

    public function render()
    {
        return view('livewire.focal.implementations.list-of-projects');
    }
}
