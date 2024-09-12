<?php

namespace App\Livewire;

use Illuminate\Container\Attributes\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.guest')]
#[Title('Settings | TU-Efficient')]
class Settings extends Component
{
    public $savedGeneral = false;

    #[Validate]
    public $minimum_wage;

    #[Validate]
    public $duplication_threshold;

    #[Validate]
    public $extensive_matching;

    public function rules()
    {

        return [
            'minimum_wage' => 'required|numeric',
            'duplication_threshold' => 'required|integer',
            'extensive_matching' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'minimum_wage.required' => 'Minimum wage must not be empty.',
            'duplication_threshold.required' => 'This field must not be empty.',
            'extensive_matching.required' => 'Please pick a selection.',

            'minimum_wage.numeric' => 'Minimum wage should be a valid number.',
            'duplication_threshold.integer' => 'The threshold should be a valid number.',
        ];
    }

    public function saveGeneral()
    {
        $this->savedGeneral = false;
        $this->validateOnly(('minimum_wage'));

        $this->savedGeneral = true;
    }

    public function mount()
    {
        $this->minimum_wage = config('settings.minimum_wage');
        $this->duplication_threshold = config('settings.duplication_threshold');
        $this->extensive_matching = config('settings.extensive_matching');
    }
    public function render()
    {
        return view('livewire.settings');
    }
}
