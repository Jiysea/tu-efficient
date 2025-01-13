<?php

namespace App\Livewire\Focal\Implementations;

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

class PromptImplementingModal extends Component
{
    #[Reactive]
    #[Locked]
    public $implementationId;
    #[Validate]
    public $password;

    # Runs real-time depending on wire:model suffix
    public function rules()
    {
        return [
            'password' =>
                [
                    'required',
                    function ($attr, $value, $fail) {
                        if (!Hash::check($this->password, auth()->user()->getAuthPassword())) {
                            $fail('Incorrect password.');
                        }
                    }
                ],
        ];
    }

    # The validation error messages
    public function messages()
    {
        return [
            'password.required' => 'This field is required.',
        ];
    }

    public function markForImplementation()
    {
        $this->validate();

        DB::transaction(function () {
            try {
                $implementation = Implementation::with([
                    'batch' => function ($q) {
                        $q->lockForUpdate();
                    }
                ])->lockForUpdate()->find($this->implementationId ? decrypt($this->implementationId) : null);
                $this->authorize('implementation-focal', $implementation);
                $implementation->status = 'implementing';
                $implementation->save();

                LogIt::set_mark_project_for_implementation($implementation, auth()->user());
                $this->dispatch('alertNotification', type: 'implementation', message: 'Successfully marked project for implementation', color: 'indigo');

            } catch (AuthorizationException $e) {
                DB::rollBack();
                LogIt::set_log_exception('An unauthorized action has been made modifying this project. Error ' . $e->getCode(), auth()->user(), $e->getTrace());
                $this->dispatch('alertNotification', type: 'implementation', message: $e->getMessage(), color: 'red');
            } catch (QueryException $e) {
                DB::rollBack();

                # Deadlock & LockWaitTimeoutException
                if ($e->getCode() === '1213' || $e->getCode() === '1205') {
                    $this->dispatch('alertNotification', type: 'implementation', message: 'Another user is modifying the record. Please try again. Error ' . $e->getCode(), color: 'red');
                } else {
                    LogIt::set_log_exception('An error has occured during execution. Error ' . $e->getCode(), auth()->user(), $e->getTrace());
                    $this->dispatch('alertNotification', type: 'implementation', message: 'An error has occured during execution. Error ' . $e->getCode(), color: 'red');
                }
            } catch (\Throwable $e) {
                DB::rollBack();
                LogIt::set_log_exception('An error has occured during execution. Error ' . $e->getCode(), auth()->user(), $e->getTrace());
                $this->dispatch('alertNotification', type: 'implementation', message: 'An error has occured during execution. Error ' . $e->getCode(), color: 'red');
            } finally {
                $this->js('promptImplementingModal = false; viewProjectModal = false;');
                $this->resetModal();
            }
        });
    }

    public function markAsPending()
    {
        $this->validate();

        DB::transaction(function () {
            try {
                $implementation = Implementation::lockForUpdate()->find($this->implementationId ? decrypt($this->implementationId) : null);
                $this->authorize('implementation-focal', $implementation);
                $implementation->status = 'pending';
                $implementation->save();

                LogIt::set_mark_project_as_pending($implementation, auth()->user());
                $this->dispatch('alertNotification', type: 'implementation', message: 'Successfully marked project as pending', color: 'indigo');

            } catch (AuthorizationException $e) {
                DB::rollBack();
                LogIt::set_log_exception('An unauthorized action has been made modifying this project. Error ' . $e->getCode(), auth()->user(), $e->getTrace());
                $this->dispatch('alertNotification', type: 'implementation', message: $e->getMessage(), color: 'red');
            } catch (QueryException $e) {
                DB::rollBack();

                # Deadlock & LockWaitTimeoutException
                if ($e->getCode() === '1213' || $e->getCode() === '1205') {
                    $this->dispatch('alertNotification', type: 'implementation', message: 'Another user is modifying the record. Please try again. Error ' . $e->getCode(), color: 'red');
                } else {
                    LogIt::set_log_exception('An error has occured during execution. Error ' . $e->getCode(), auth()->user(), $e->getTrace());
                    $this->dispatch('alertNotification', type: 'implementation', message: 'An error has occured during execution. Error ' . $e->getCode(), color: 'red');
                }
            } catch (\Throwable $e) {
                DB::rollBack();
                LogIt::set_log_exception('An error has occured during execution. Error ' . $e->getCode(), auth()->user(), $e->getTrace());
                $this->dispatch('alertNotification', type: 'implementation', message: 'An error has occured during execution. Error ' . $e->getCode(), color: 'red');
            } finally {
                $this->js('promptImplementingModal = false; viewProjectModal = false;');
                $this->resetModal();
            }
        });
    }

    #[Computed]
    public function implementation()
    {
        return Implementation::find($this->implementationId ? decrypt($this->implementationId) : null);
    }

    public function resetModal()
    {
        $this->resetExcept('implementationId');
        $this->resetValidation();
    }

    public function mount()
    {

    }

    public function render()
    {
        return view('livewire.focal.implementations.prompt-implementing-modal');
    }
}
