<?php

namespace App\Livewire\Coordinator\Submissions;

use Livewire\Attributes\Validate;
use Livewire\Component;

class DownloadOptionsAlert extends Component
{
    #[Validate]
    public $slots_allocated;
    public $totalSlots = 50;

    public function rules()
    {
        return [
            'slots_allocated' => [
                'required',
                'integer',
                'gte:0',
                'min:1',
                'lte:' . $this->totalSlots,
            ],
        ];
    }

    public function messages()
    {
        return [
            'slots_allocated.required' => 'Invalid :attribute amount.',
            'slots_allocated.integer' => ':attribute should be a valid number.',
            'slots_allocated.min' => ':attribute should be > 0.',
            'slots_allocated.gte' => ':attribute should be nonnegative.',
            'slots_allocated.lte' => ':attribute should be less than total.',
        ];
    }

    public function validationAttributes()
    {
        return [
            'slots_allocated' => 'Slots',
        ];
    }

    public function confirm()
    {
        $this->validate();
        $this->dispatch('download-options-confirmed', slots_allocated: $this->slots_allocated);
    }

    public function render()
    {
        return view('livewire.coordinator.submissions.download-options-alert');
    }
}
