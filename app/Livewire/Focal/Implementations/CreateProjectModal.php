<?php

namespace App\Livewire\Focal\Implementations;

use App\Models\Implementation;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CreateProjectModal extends Component
{
    #[Validate('required')]
    public $project_num;
    #[Validate('required')]
    public $project_title;
    #[Validate('required')]
    public $purpose;
    #[Validate('required')]
    public $district;
    #[Validate('required')]
    public $province;
    #[Validate('required')]
    public $city_municipality;
    #[Validate('required|numeric|min:1')]
    public $budget_amount;
    #[Validate('required|numeric|integer|min:1')]
    public $total_slots;
    #[Validate('required|numeric|integer|min:1|max:15')]
    public $days_of_work;

    public function saveProject()
    {
        $this->validate();

        Implementation::create([
            'users_id' => Auth()->id(),
            'project_num' => $this->project_num,
            'project_title' => $this->project_title,
            'purpose' => $this->purpose,
            'district' => $this->district,
            'province' => $this->province,
            'city_municipality' => $this->city_municipality,
            'budget_amount' => $this->budget_amount,
            'total_slots' => $this->total_slots,
            'days_of_work' => $this->days_of_work
        ]);

        $messages[] = 'Project implementation successfully created!';
        session()->put('success', $messages);
        // session()->flash('success', 'Project implementation successfully created!');
    }

    public function render()
    {
        return view('livewire.focal.implementations.create-project-modal');
    }
}
