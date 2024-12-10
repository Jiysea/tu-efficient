<?php

namespace App\Livewire\Coordinator\Assignments;

use App\Models\Assignment;
use App\Models\Batch;
use App\Models\Beneficiary;
use App\Models\Code;
use App\Services\LogIt;
use Carbon\Carbon;
use DB;
use Exception;
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
    public $passedBatchId;
    #[Locked]
    public $code;
    public $accessCodeModal = false;
    public $confirmModal = false;
    public $confirmType;

    # ------------------------------

    #[Validate]
    public $password_confirm;

    # ------------------------------

    public function rules()
    {
        return [
            'password_confirm' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!Hash::check($value, Auth::user()->password)) {
                        $fail('Wrong password.');
                    }
                },
            ],
        ];
    }

    public function messages()
    {
        return [
            'password_confirm.required' => 'This field is required.',
        ];
    }

    #[Computed]
    public function batch()
    {
        return Batch::find($this->passedBatchId ? decrypt($this->passedBatchId) : null);
    }

    #[Computed]
    public function assignments()
    {
        return Assignment::where('assignments.batches_id', $this->passedBatchId ? decrypt($this->passedBatchId) : null)
            ->join('users', 'users.id', '=', 'assignments.users_id')
            ->whereNotIn('assignments.users_id', [Auth::id()])
            ->get();
    }

    #[Computed]
    public function currentSlots()
    {
        return Beneficiary::join('batches', 'batches.id', '=', 'beneficiaries.batches_id')
            ->where('batches.id', $this->passedBatchId ? decrypt($this->passedBatchId) : null)
            ->count();
    }

    #[Computed]
    public function accessCode()
    {
        return Code::where('batches_id', $this->passedBatchId ? decrypt($this->passedBatchId) : null)
            ->where('is_accessible', 'yes')
            ->first();
    }

    public function confirmModalOpen(string $type)
    {
        try {
            $type = decrypt($type);

            if ($type === 'revalidate') {
                $this->confirmType = 'revalidate';
            } elseif ($type === 'approve') {
                $this->confirmType = 'approve';
            } elseif ($type === 'force_submit') {
                $this->confirmType = 'force_submit';
            } elseif ($type === 'resolve') {
                $this->confirmType = 'resolve';
            } else {
                $this->dispatch('error-handling', message: 'Invalid confirm type.');
                $this->js('$wire.$refresh();');
                return;
            }

        } catch (Exception $e) {
            $this->dispatch('error-handling', message: 'Invalid confirm type.');
            $this->js('$wire.$refresh();');
            return;
        }

        $this->confirmModal = true;
    }

    public function generateCode()
    {
        DB::transaction(function () {

            $batch = Batch::lockForUpdate()->find($this->passedBatchId ? decrypt($this->passedBatchId) : null);

            $code = '';
            for ($a = 0; $a < 8; $a++) {
                $code .= fake()->randomElement(['#', '?']);
            }
            $this->code = fake()->bothify($code);

            $code = Code::lockForUpdate()->updateOrCreate(
                [
                    'batches_id' => decrypt($this->passedBatchId),
                    'is_accessible' => 'yes',
                ],
                [
                    'batches_id' => decrypt($this->passedBatchId),
                    'access_code' => $this->code,
                    'is_accessible' => 'yes',
                ]
            );

            if ($batch->submission_status === 'unopened') {
                $batch->submission_status = 'encoding';
                $batch->save();

                LogIt::set_open_access($batch, $code, auth()->user());
                $this->dispatch('view-batch', message: 'Batch opened for encoding!');
            }
        });
    }

    public function approveSubmission()
    {
        $this->validateOnly(field: 'password_confirm');

        $batch = Batch::find($this->passedBatchId ? decrypt($this->passedBatchId) : null);
        $this->authorize('check-coordinator', $batch);

        $checkSlots = Batch::whereHas('beneficiary')
            ->where('batches.id', $batch->id)
            ->exists();

        if (
            $batch->approval_status !== 'approved' && ($batch->submission_status === 'submitted' ||
                $batch->submission_status === 'unopened') && $checkSlots
        ) {
            $batch->approval_status = 'approved';
            $batch->submission_status = 'submitted';
            $batch->save();
            LogIt::set_approve_batch($batch, auth()->user());

            $this->dispatch('view-batch', message: 'Successfully approved the batch assignment!');

        } else {
            $this->dispatch('view-batch', message: 'Cannot approve batch assignment when it is not submitted');
        }

        $this->resetConfirm();
        $this->js('confirmModal = false');
    }

    public function forceSubmitOrResolve()
    {
        $this->validateOnly('password_confirm');

        $batch = Batch::find($this->passedBatchId ? decrypt($this->passedBatchId) : null);
        $this->authorize('check-coordinator', $batch);

        Code::find($this->accessCode?->id)->update([
            'is_accessible' => 'no'
        ]);

        $batch->submission_status = 'submitted';
        $batch->save();

        LogIt::set_force_submit_batch($batch, auth()->user());
        $this->dispatch('view-batch', message: 'Batch has been submitted forcibly!');
        $this->resetConfirm();
        $this->js('confirmModal = false');
    }

    public function revalidateSubmission()
    {
        $this->validateOnly('password_confirm');

        $batch = Batch::find($this->passedBatchId ? decrypt($this->passedBatchId) : null);
        $this->authorize('check-coordinator', $batch);

        $code = '';
        for ($a = 0; $a < 8; $a++) {
            $code .= fake()->randomElement(['#', '?']);
        }
        $this->code = fake()->bothify($code);

        Code::updateOrCreate(
            [
                'batches_id' => decrypt($this->passedBatchId),
                'is_accessible' => 'yes',
            ],
            [
                'batches_id' => decrypt($this->passedBatchId),
                'access_code' => $this->code,
                'is_accessible' => 'yes',
            ]
        );

        $batch->submission_status = 'revalidate';
        $batch->save();

        LogIt::set_revalidate_batch($batch, auth()->user());
        $this->dispatch('view-batch', 'Batch reopened for revalidation!');

        $this->js('confirmModal = false');
        $this->resetConfirm();
        $this->accessCodeModal = true;
    }

    #[Computed]
    public function getFullName($person)
    {
        $name = null;
        if ($person) {
            $name = $person->first_name;

            if ($person->middle_name) {
                $name .= ' ' . $person->middle_name;
            }

            $name .= ' ' . $person->last_name;

            if ($person->extension_name) {
                $name .= ' ' . $person->extension_name;
            }
        }
        return $name;
    }

    public function resetConfirm()
    {
        $this->reset('password_confirm');
        $this->resetValidation();
    }

    public function mount()
    {

    }

    public function render()
    {
        $this->code = $this->accessCode?->access_code;
        return view('livewire.coordinator.assignments.view-batch-modal');
    }
}
