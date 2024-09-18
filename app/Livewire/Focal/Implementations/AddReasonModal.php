<?php

namespace App\Livewire\Focal\Implementations;

use Livewire\Attributes\Validate;
use Livewire\Component;

class AddReasonModal extends Component
{
    #[Validate]
    public $reason_image_file_path;
    #[Validate]
    public $image_description;

    # ----------------------------------


    # Runs real-time depending on wire:model suffix
    public function rules()
    {
        return [
            'reason_image_file_path' => 'required|image|mimes:png,jpg,jpeg|max:5120',
            'image_description' => 'required',
        ];
    }

    # The validation error messages
    public function messages()
    {
        return [
            'image_description.required' => 'Description must not be left blank.',

            'reason_image_file_path.required' => 'Case proof is required.',
            'reason_image_file_path.image' => 'Case proof must be an image type.',
            'reason_image_file_path.mimes' => 'It must be in PNG or JPG format.',
            'reason_image_file_path.max' => 'Image size must not exceed 5MB.',
        ];
    }

    public function resetEverything()
    {
        $this->reset();
    }
    public function render()
    {
        return view('livewire.focal.implementations.add-reason-modal');
    }
}
