<?php

namespace App\Livewire\Coordinator\Assignments;

use App\Models\Assignment;
use App\Models\Batch;
use App\Models\Beneficiary;
use App\Models\Code;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Reactive;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ViewBatchModal extends Component
{
    #[Reactive]
    #[Locked]
    public $passedId;
    public $accessCodeModal = false;
    public $forceSubmitConfirmationModal = false;
    public $revalidateConfirmationModal = false;
    #[Locked]
    public $code;
    #[Validate]
    public $password_force_submit;
    #[Validate]
    public $password_revalidate;
    # ------------------------------

    public function rules()
    {
        return [
            'password_force_submit' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!Hash::check($value, Auth::user()->password)) {
                        $fail('Wrong password.');
                    }
                },

            ],
            'password_revalidate' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!Hash::check($value, Auth::user()->password)) {
                        $fail('Wrong password.');
                    }
                },
            ]
        ];
    }

    public function messages()
    {
        return [
            'password_force_submit.required' => 'This field is required.',
            'password_revalidate.required' => 'This field is required.',
        ];
    }

    #[Computed]
    public function batch()
    {
        $batch = Batch::find(decrypt($this->passedId));

        return $batch;
    }

    #[Computed]
    public function assignments()
    {
        $assignments = Assignment::where('assignments.batches_id', decrypt($this->passedId))
            ->join('users', 'users.id', '=', 'assignments.users_id')
            ->whereNotIn('assignments.users_id', [Auth::id()])
            ->get();

        return $assignments;
    }

    #[Computed]
    public function getFullName($person)
    {
        $name = $person->first_name;

        if ($person->middle_name) {
            $name .= ' ' . $person->middle_name;
        }

        $name .= ' ' . $person->last_name;

        if ($person->extension_name) {
            $name .= ' ' . $person->extension_name;
        }

        return $name;
    }

    #[Computed]
    public function currentSlots()
    {
        $currentSlots = Beneficiary::join('batches', 'batches.id', '=', 'beneficiaries.batches_id')
            ->where('batches.id', decrypt($this->passedId))
            ->count();

        return $currentSlots;
    }

    #[Computed]
    public function accessCode()
    {
        $accessCode = Code::where('batches_id', decrypt($this->passedId))
            ->where('is_accessible', 'yes')
            ->first();

        return $accessCode;
    }

    public function generateCode()
    {
        $code = '';
        for ($a = 0; $a < 8; $a++) {
            $code .= fake()->randomElement(['#', '?']);
        }
        $this->code = fake()->bothify($code);

        Code::updateOrCreate(
            [
                'batches_id' => decrypt($this->passedId),
                'is_accessible' => 'yes',
            ],
            [
                'batches_id' => decrypt($this->passedId),
                'access_code' => $this->code,
                'is_accessible' => 'yes',
            ]
        );

        if ($this->batch->submission_status === 'unopened') {
            $this->batch->submission_status = 'encoding';
            $this->batch->save();
        }

        $this->dispatch('refreshAfterOpening');
    }

    public function forceSubmitOrResolve()
    {
        $this->validateOnly('password_force_submit');

        Code::find($this->accessCode->id)->update([
            'is_accessible' => 'no'
        ]);

        $this->batch->submission_status = 'submitted';
        $this->batch->save();


        $this->dispatch('refreshAfterOpening');
        $this->forceSubmitConfirmationModal = false;
    }

    public function revalidateSubmission()
    {
        $this->validateOnly('password_revalidate');

        $code = '';
        for ($a = 0; $a < 8; $a++) {
            $code .= fake()->randomElement(['#', '?']);
        }
        $this->code = fake()->bothify($code);

        Code::updateOrCreate(
            [
                'batches_id' => decrypt($this->passedId),
                'is_accessible' => 'yes',
            ],
            [
                'batches_id' => decrypt($this->passedId),
                'access_code' => $this->code,
                'is_accessible' => 'yes',
            ]
        );

        $this->batch->submission_status = 'revalidate';
        $this->batch->save();

        $this->dispatch('refreshAfterOpening');

        $this->accessCodeModal = true;
        $this->revalidateConfirmationModal = false;
    }

    public function resetEverything()
    {

    }

    public function render()
    {
        $this->code = $this->accessCode->access_code ?? null;
        return view('livewire.coordinator.assignments.view-batch-modal');
    }
}
