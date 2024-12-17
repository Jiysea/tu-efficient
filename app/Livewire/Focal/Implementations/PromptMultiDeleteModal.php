<?php

namespace App\Livewire\Focal\Implementations;

use App\Models\UserSetting;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class PromptMultiDeleteModal extends Component
{
    #[Reactive]
    #[Locked]
    public array $beneficiaryIds;
    public $defaultArchive;

    #[Computed]
    public function count()
    {
        return $this->beneficiaryIds ? count($this->beneficiaryIds) : 0;
    }

    #[Computed]
    public function settings()
    {
        return UserSetting::where('users_id', auth()->id())->pluck('value', 'key');
    }

    public function render()
    {
        $this->defaultArchive = intval($this->settings->get('default_archive', config('settings.default_archive')));
        return view('livewire.focal.implementations.prompt-multi-delete-modal');
    }
}
