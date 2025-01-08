<?php

namespace App\Livewire\Focal\Implementations;

use App\Models\Batch;
use App\Models\Implementation;
use App\Services\LogIt;
use DB;
use Hash;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\QueryException;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Reactive;
use Livewire\Attributes\Validate;
use Livewire\Component;

class PromptConcludeModal extends Component
{
    #[Reactive]
    #[Locked]
    public $implementationId;
    #[Validate]
    public $password;

    # --------------------------------------------------------------------------

    public function rules()
    {
        return [
            'password' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!Hash::check($value, auth()->user()->password)) {
                        $fail('Wrong password.');
                    }
                },
            ],
        ];
    }

    public function messages()
    {
        return [
            'password.required' => 'This field is required.',
        ];
    }

    public function concludeProject()
    {
        DB::transaction(function () {
            try {
                $implementation = Implementation::with([
                    'batch' => function ($q) {
                        $q->lockForUpdate();
                    }
                ])->lockForUpdate()->find($this->implementationId ? decrypt($this->implementationId) : null);

                $implementation->status = 'concluded';
                $implementation->save();
                LogIt::set_mark_project_as_concluded($implementation, auth()->user());
                $this->dispatch('alertNotification', type: 'implementation', message: 'A project has been successfully concluded', color: 'indigo');
            } catch (AuthorizationException $e) {
                DB::rollBack();
                LogIt::set_log_exception('An unauthorized action has been made while deleting this project. Error ' . $e->getCode(), auth()->user(), $e->getTrace());
                $this->dispatch('alertNotification', type: 'implementation', message: $e->getMessage(), color: 'red');
            } catch (QueryException $e) {
                DB::rollBack();

                # Deadlock & LockWaitTimeoutException
                if ($e->getCode() === '1213' || $e->getCode() === '1205') {
                    $this->dispatch('alertNotification', type: 'beneficiary', message: 'Another user is modifying the record. Please try again. Error ' . $e->getCode(), color: 'red');
                } else {
                    LogIt::set_log_exception('An error has occured during execution. Error ' . $e->getCode(), auth()->user(), $e->getTrace());
                    $this->dispatch('alertNotification', type: 'beneficiary', message: 'An error has occured during execution. Error ' . $e->getCode(), color: 'red');
                }
            } catch (\Throwable $e) {
                DB::rollBack();
                LogIt::set_log_exception('An error has occured during execution. Error ' . $e->getCode(), auth()->user(), $e->getTrace());
                $this->dispatch('alertNotification', type: 'implementation', message: 'An error has occured during execution. Error ' . $e->getCode(), color: 'red');
            } finally {
                $this->resetModal();
                $this->js('promptConcludeModal = false;');
            }
        }, 5);
    }

    #[Computed]
    public function implementation()
    {
        return Implementation::find($this->implementationId ? decrypt($this->implementationId) : null);
    }

    #[Computed]
    public function batches()
    {
        return Batch::where('implementations_id', $this->implementation?->id)
            ->get();
    }

    public function resetModal()
    {
        $this->resetExcept('implementationId');
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.focal.implementations.prompt-conclude-modal');
    }
}
