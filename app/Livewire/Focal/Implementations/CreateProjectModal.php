<?php

namespace App\Livewire\Focal\Implementations;

use App\Models\Implementation;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CreateProjectModal extends Component
{
    #[Validate('required', as: 'project number', onUpdate: false)]
    public $project_num;
    #[Validate('required', as: 'project title', onUpdate: false)]
    public $project_title;
    #[Locked]
    public $purpose;
    #[Validate('required', onUpdate: false)]
    public $district;
    #[Validate('required', onUpdate: false)]
    public $province;
    #[Validate('required', as: 'city/municipality', onUpdate: false)]
    public $city_municipality;
    #[Validate('required|min:0', as: 'budget amount', onUpdate: false)]
    public $budget_amount;
    #[Validate('required|min:0', as: 'total slots', onUpdate: false)]
    public $total_slots;
    #[Validate('required|min:0|max:15', as: 'days of work', onUpdate: false)]
    public $days_of_work;

    public function saveProject()
    {
        $validated = $this->validate();

        Implementation::create($validated);

        session()->flash('success', 'Project implementation successfully created!');
    }

    public function render()
    {
        return view('livewire.focal.implementations.create-project-modal');
    }
}
