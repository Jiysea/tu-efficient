<?php

namespace App\Livewire\Focal\Implementations;

use App\Models\Batch;
use App\Models\Beneficiary;
use App\Models\UserSetting;
use App\Services\JaccardSimilarity;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class ShowDuplicatesTable extends Component
{
    #[Reactive]
    #[Locked]
    public $beneficiaryId;
    public $duplicationThreshold;
    public $defaultShowDuplicates;

    #[Computed]
    public function batch()
    {
        return Batch::find($this->beneficiary?->batches_id);
    }

    #[Computed]
    public function beneficiary()
    {
        return Beneficiary::find($this->beneficiaryId ? decrypt($this->beneficiaryId) : null);
    }

    #[Computed]
    public function similarityResults()
    {
        return JaccardSimilarity::getResults($this->beneficiary?->first_name, $this->beneficiary?->middle_name, $this->beneficiary?->last_name, $this->beneficiary?->extension_name, $this->duplicationThreshold, $this->beneficiaryId);
    }

    #[Computed]
    public function isOverThreshold($person)
    {
        $results = null;

        if ($this->beneficiaryId) {
            $results = JaccardSimilarity::isOverThreshold($person, $this->duplicationThreshold);
        }

        return $results;
    }

    #[Computed]
    public function isOriginal()
    {
        $keptTrue = true;

        if ($this->beneficiaryId) {
            $thresholdResult = $this->isOverThreshold($this->beneficiary);
            foreach ($thresholdResult as $result) {
                $databaseBeneficiary = Beneficiary::find(decrypt($result['id']));

                if ($this->beneficiary?->created_at > $databaseBeneficiary->created_at) {
                    $keptTrue = false;
                }
            }
        }

        return $keptTrue;
    }

    #[Computed]
    public function hasPerfect()
    {
        foreach ($this->isOverThreshold($this->beneficiary) as $result) {
            if ($result['is_perfect']) {
                return true;
            }
        }

        return false;
    }

    #[Computed]
    public function settings()
    {
        return UserSetting::where('users_id', auth()->id())
            ->pluck('value', 'key');
    }

    public function render()
    {
        $this->duplicationThreshold = intval($this->settings->get('duplication_threshold', config('settings.duplication_threshold'))) / 100;
        $this->defaultShowDuplicates = intval($this->settings->get('default_show_duplicates', config('settings.default_show_duplicates')));
        return view('livewire.focal.implementations.show-duplicates-table');
    }
}
