<?php

namespace App\Livewire\Coordinator\Assignments;

use App\Models\Assignment;
use App\Models\Batch;
use App\Models\Beneficiary;
use App\Models\Code;
use App\Models\Implementation;
use App\Services\LogIt;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\QueryException;
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
    public $batchId;
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
    public function implementation()
    {
        return Implementation::find($this->batch?->implementations_id);
    }

    #[Computed]
    public function batch()
    {
        return Batch::find($this->batchId ? decrypt($this->batchId) : null);
    }

    #[Computed]
    public function assignments()
    {
        return Assignment::where('assignments.batches_id', $this->batchId ? decrypt($this->batchId) : null)
            ->join('users', 'users.id', '=', 'assignments.users_id')
            ->whereNotIn('assignments.users_id', [Auth::id()])
            ->get();
    }

    #[Computed]
    public function currentSlots()
    {
        return Beneficiary::join('batches', 'batches.id', '=', 'beneficiaries.batches_id')
            ->where('batches.id', $this->batchId ? decrypt($this->batchId) : null)
            ->count();
    }

    #[Computed]
    public function accessCode()
    {
        return Code::where('batches_id', $this->batchId ? decrypt($this->batchId) : null)
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
                $this->dispatch('alertNotification', type: null, message: 'Invalid confirm type', color: 'red');
                $this->js('$wire.$refresh();');
                return;
            }

        } catch (Exception $e) {
            $this->dispatch('alertNotification', type: null, message: 'Invalid confirm type', color: 'red');
            $this->js('$wire.$refresh();');
            return;
        }

        $this->confirmModal = true;
    }

    public function generateCode()
    {
        DB::transaction(function () {
            try {
                $batch = Batch::lockForUpdate()->findOrFail($this->batchId ? decrypt($this->batchId) : null);

                $code = '';
                for ($a = 0; $a < 8; $a++) {
                    $code .= fake()->randomElement(['#', '?']);
                }
                $this->code = fake()->bothify($code);

                $code = Code::lockForUpdate()->updateOrCreate(
                    [
                        'batches_id' => decrypt($this->batchId),
                        'is_accessible' => 'yes',
                    ],
                    [
                        'batches_id' => decrypt($this->batchId),
                        'access_code' => $this->code,
                        'is_accessible' => 'yes',
                    ]
                );

                if ($batch->submission_status === 'unopened') {
                    $batch->submission_status = 'encoding';
                    $batch->save();

                    LogIt::set_open_access($batch, $code, auth()->user());
                    $this->dispatch('alertNotification', type: null, message: 'A batch has been opened for encoding', color: 'blue');
                }
            } catch (AuthorizationException $e) {
                DB::rollBack();
                LogIt::set_log_exception('An unauthorized action has been made while adding a beneficiary. Error ' . $e->getCode(), auth()->user(), $e->getTrace());
                $this->dispatch('alertNotification', type: null, message: $e->getMessage(), color: 'red');
            } catch (QueryException $e) {
                DB::rollBack();

                # Deadlock & LockWaitTimeoutException
                if ($e->getCode() === '1213' || $e->getCode() === '1205') {
                    $this->dispatch('alertNotification', type: null, message: 'Another user is modifying the record. Please try again. Error ' . $e->getCode(), color: 'red');
                } else {
                    LogIt::set_log_exception('An error has occured during execution. Error ' . $e->getCode(), auth()->user(), $e->getTrace());
                    $this->dispatch('alertNotification', type: null, message: 'An error has occured during execution. Error ' . $e->getCode(), color: 'red');
                }
            } catch (\Throwable $e) {
                DB::rollBack();
                LogIt::set_log_exception('An error has occured during execution. Error ' . $e->getCode(), auth()->user(), $e->getTrace());
                $this->dispatch('alertNotification', type: null, message: 'An error has occured during execution. Error ' . $e->getCode(), color: 'red');
            }
        }, 5);
    }

    public function approveSubmission()
    {
        $this->validateOnly(field: 'password_confirm');

        DB::transaction(function () {
            try {
                $batch = Batch::lockForUpdate()->findOrFail($this->batchId ? decrypt($this->batchId) : null);
                $this->authorize('check-coordinator', $batch);

                $checkSlots = Batch::whereHas('beneficiary')
                    ->where('batches.id', $batch->id)
                    ->exists();

                if ($checkSlots) {
                    DB::rollBack();
                    $this->dispatch('alertNotification', type: null, message: 'This batch should have at least one beneficiary', color: 'red');
                    return;
                }

                if ($batch->approval_status !== 'approved') {
                    DB::rollBack();
                    $this->dispatch('alertNotification', type: null, message: 'This batch is already approved', color: 'red');
                    return;
                }

                if ($batch->submission_status === 'revalidate' || $batch->submission_status === 'encoding') {
                    DB::rollBack();
                    $this->dispatch('alertNotification', type: null, message: 'This batch should be submitted first', color: 'red');
                    return;
                }

                $batch->approval_status = 'approved';
                $batch->submission_status = 'submitted';
                $batch->save();
                LogIt::set_approve_batch($batch, auth()->user());
                $this->dispatch('alertNotification', type: null, message: 'Successfully approved the batch submission', color: 'blue');
            } catch (AuthorizationException $e) {
                DB::rollBack();
                LogIt::set_log_exception('An unauthorized action has been made while adding a beneficiary. Error ' . $e->getCode(), auth()->user(), $e->getTrace());
                $this->dispatch('alertNotification', type: null, message: $e->getMessage(), color: 'red');
            } catch (QueryException $e) {
                DB::rollBack();

                # Deadlock & LockWaitTimeoutException
                if ($e->getCode() === '1213' || $e->getCode() === '1205') {
                    $this->dispatch('alertNotification', type: null, message: 'Another user is modifying the record. Please try again. Error ' . $e->getCode(), color: 'red');
                } else {
                    LogIt::set_log_exception('An error has occured during execution. Error ' . $e->getCode(), auth()->user(), $e->getTrace());
                    $this->dispatch('alertNotification', type: null, message: 'An error has occured during execution. Error ' . $e->getCode(), color: 'red');
                }
            } catch (\Throwable $e) {
                DB::rollBack();
                LogIt::set_log_exception('An error has occured during execution. Error ' . $e->getCode(), auth()->user(), $e->getTrace());
                $this->dispatch('alertNotification', type: null, message: 'An error has occured during execution. Error ' . $e->getCode(), color: 'red');
            } finally {
                $this->resetConfirm();
                $this->js('confirmModal = false');
            }
        }, 5);
    }

    public function forceSubmitOrResolve()
    {
        $this->validateOnly('password_confirm');

        DB::transaction(function () {
            try {
                $batch = Batch::lockForUpdate()->findOrFail($this->batchId ? decrypt($this->batchId) : null);
                $this->authorize('check-coordinator', $batch);

                Code::find($this->accessCode?->id)->update([
                    'is_accessible' => 'no'
                ]);

                $batch->submission_status = 'submitted';
                $batch->save();

                LogIt::set_force_submit_batch($batch, auth()->user());
                $this->dispatch('alertNotification', type: null, message: 'Successfully submitted the batch submission', color: 'blue');
            } catch (AuthorizationException $e) {
                DB::rollBack();
                LogIt::set_log_exception('An unauthorized action has been made while adding a beneficiary. Error ' . $e->getCode(), auth()->user(), $e->getTrace());
                $this->dispatch('alertNotification', type: null, message: $e->getMessage(), color: 'red');
            } catch (QueryException $e) {
                DB::rollBack();

                # Deadlock & LockWaitTimeoutException
                if ($e->getCode() === '1213' || $e->getCode() === '1205') {
                    $this->dispatch('alertNotification', type: null, message: 'Another user is modifying the record. Please try again. Error ' . $e->getCode(), color: 'red');
                } else {
                    LogIt::set_log_exception('An error has occured during execution. Error ' . $e->getCode(), auth()->user(), $e->getTrace());
                    $this->dispatch('alertNotification', type: null, message: 'An error has occured during execution. Error ' . $e->getCode(), color: 'red');
                }
            } catch (\Throwable $e) {
                DB::rollBack();
                LogIt::set_log_exception('An error has occured during execution. Error ' . $e->getCode(), auth()->user(), $e->getTrace());
                $this->dispatch('alertNotification', type: null, message: 'An error has occured during execution. Error ' . $e->getCode(), color: 'red');
            } finally {
                $this->resetConfirm();
                $this->js('confirmModal = false');
            }
        }, 5);
    }

    public function revalidateSubmission()
    {
        $this->validateOnly('password_confirm');

        DB::transaction(function () {
            try {
                $batch = Batch::find($this->batchId ? decrypt($this->batchId) : null);
                $this->authorize('check-coordinator', $batch);

                $code = '';
                for ($a = 0; $a < 8; $a++) {
                    $code .= fake()->randomElement(['#', '?']);
                }
                $this->code = fake()->bothify($code);

                Code::lockForUpdate()->updateOrCreate(
                    [
                        'batches_id' => decrypt($this->batchId),
                        'is_accessible' => 'yes',
                    ],
                    [
                        'batches_id' => decrypt($this->batchId),
                        'access_code' => $this->code,
                        'is_accessible' => 'yes',
                    ]
                );

                $batch->submission_status = 'revalidate';
                $batch->save();

                LogIt::set_revalidate_batch($batch, auth()->user());
                $this->dispatch('alertNotification', type: null, message: 'Successfully reopened batch for revalidation', color: 'blue');
                $this->accessCodeModal = true;

            } catch (AuthorizationException $e) {
                DB::rollBack();
                LogIt::set_log_exception('An unauthorized action has been made while adding a beneficiary. Error ' . $e->getCode(), auth()->user(), $e->getTrace());
                $this->dispatch('alertNotification', type: null, message: $e->getMessage(), color: 'red');
            } catch (QueryException $e) {
                DB::rollBack();

                # Deadlock & LockWaitTimeoutException
                if ($e->getCode() === '1213' || $e->getCode() === '1205') {
                    $this->dispatch('alertNotification', type: null, message: 'Another user is modifying the record. Please try again. Error ' . $e->getCode(), color: 'red');
                } else {
                    LogIt::set_log_exception('An error has occured during execution. Error ' . $e->getCode(), auth()->user(), $e->getTrace());
                    $this->dispatch('alertNotification', type: null, message: 'An error has occured during execution. Error ' . $e->getCode(), color: 'red');
                }
            } catch (\Throwable $e) {
                DB::rollBack();
                LogIt::set_log_exception('An error has occured during execution. Error ' . $e->getCode(), auth()->user(), $e->getTrace());
                $this->dispatch('alertNotification', type: null, message: 'An error has occured during execution. Error ' . $e->getCode(), color: 'red');
            } finally {
                $this->js('confirmModal = false');
                $this->resetConfirm();
            }
        }, 5);
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
