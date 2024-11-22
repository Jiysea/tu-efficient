<?php

namespace App\Livewire\Focal\Implementations;

use Arr;
use Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class ErrorPreviewModal extends Component
{
    #[Reactive]
    #[Locked]
    public $errorId;
    public $cachedResults;

    #[On('openError')]
    public function setErrorValues()
    {
        $this->cachedResults = cache("importing_" . Auth::id());
        unset($this->beneficiary);
    }

    #[Computed]
    public function beneficiary()
    {
        $beneficiary = null;

        if ($this->cachedResults) {
            $beneficiary = array_values(array_filter($this->cachedResults['beneficiaries'], function ($row) {
                return isset($row['row']) && intval($row['row']) === ($this->errorId ? intval(decrypt($this->errorId)) : null);
            }));
        }

        if ($beneficiary) {
            $beneficiary = $beneficiary[0];
        }
        return $beneficiary;
    }

    #[Computed]
    public function errorResults()
    {
        $errors = $this->beneficiary['errors'];
        return $errors;
    }

    public function checkIfErrors($errors)
    {
        $values = array_values($errors);

        foreach ($values as $value) {
            if (!is_null($value)) {
                return true; # Found a non-null value
            }
        }
        return false;
    }

    public function onlyErrors($errors)
    {
        $errors = array_filter($errors, function ($error) {
            return !is_null($error);
        });

        return $errors;
    }

    public function render()
    {
        return view('livewire.focal.implementations.error-preview-modal');
    }
}
