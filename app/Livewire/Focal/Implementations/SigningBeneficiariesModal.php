<?php

namespace App\Livewire\Focal\Implementations;

use App\Models\Batch;
use App\Models\Beneficiary;
use App\Models\Implementation;
use App\Services\LogIt;
use DB;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\QueryException;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Js;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class SigningBeneficiariesModal extends Component
{
    #[Reactive]
    #[Locked]
    public $batchId;
    public array $selectedRows = [];
    public $switching = false;
    public bool $switchSign = false; // false === contract (cos); true === payroll

    # --------------------------------------------------------------------------

    public function signContract()
    {
        # And then use DB::Transaction to ensure that only 1 record can be saved
        DB::transaction(function () {
            $marked_count = 0;
            $saved = false;
            try {
                foreach ($this->selectedRows as $key => $row) {
                    if (isset($this->beneficiaries[$key])) {
                        $beneficiary = Beneficiary::with([
                            'batch' => function ($q) {
                                $q->with([
                                    'implementation' => function ($q) {
                                        $q->lockForUpdate()->pluck('project_num');
                                    }
                                ])->lockForUpdate()->pluck('batch_num');
                            }
                        ])->lockForUpdate()->find(decrypt($row['id']));

                        $this->authorize('implementation-focal', $beneficiary->batch->implementation);

                        $beneficiary->is_signed = $row['is_signed'];
                        if ($beneficiary->is_signed)
                            $marked_count++;

                        if (!$beneficiary->is_signed && $beneficiary->is_paid) {
                            $this->selectedRows[$key]['is_paid'] = 0;
                            $beneficiary->is_paid = 0;
                        }

                        if ($beneficiary->isDirty()) {
                            $beneficiary->save();
                            $saved = true;
                        }
                    }
                }
                if ($marked_count > 0 && $saved) {
                    LogIt::set_check_beneficiaries_for_cos($beneficiary->batch->implementation, $beneficiary->batch, auth()->user(), $marked_count);
                    $this->dispatch('alertNotification', type: null, message: 'Successfully marked beneficiaries for COS', color: 'indigo');
                } elseif ($marked_count === 0 && $saved) {
                    LogIt::set_uncheck_beneficiaries_for_cos($beneficiary->batch->implementation, $beneficiary->batch, auth()->user());
                    $this->dispatch('alertNotification', type: null, message: 'Successfully unchecked all beneficiaries from COS', color: 'indigo');
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
            } finally {
                if (!$this->switching) {
                    $this->js('signingBeneficiariesModal = false');
                    $this->resetSigning();
                }
                $this->switching = false;
            }
        }, 5);
    }

    public function signPayroll()
    {
        # And then use DB::Transaction to ensure that only 1 record can be saved
        DB::transaction(function () {
            $marked_count = 0;
            $saved = false;
            try {
                foreach ($this->selectedRows as $key => $row) {
                    if (isset($this->beneficiaries[$key])) {
                        $beneficiary = Beneficiary::with([
                            'batch' => function ($q) {
                                $q->with([
                                    'implementation' => function ($q) {
                                        $q->lockForUpdate()->pluck('project_num');
                                    }
                                ])->lockForUpdate()->pluck('batch_num');
                            }
                        ])->lockForUpdate()->find(decrypt($row['id']));

                        $this->authorize('implementation-focal', $beneficiary->batch->implementation);

                        $beneficiary->is_paid = $row['is_paid'];
                        if ($beneficiary->is_paid)
                            $marked_count++;

                        if ($beneficiary->isDirty()) {
                            $beneficiary->save();
                            $saved = true;
                        }
                    }
                }
                if ($marked_count > 0 && $saved) {
                    LogIt::set_check_beneficiaries_for_payroll($beneficiary->batch->implementation, $beneficiary->batch, auth()->user(), $marked_count);
                    $this->dispatch('alertNotification', type: null, message: 'Successfully marked beneficiaries for payroll', color: 'indigo');
                } elseif ($marked_count === 0 && $saved) {
                    LogIt::set_uncheck_beneficiaries_for_payroll($beneficiary->batch->implementation, $beneficiary->batch, auth()->user());
                    $this->dispatch('alertNotification', type: null, message: 'Successfully unchecked all beneficiaries from payroll', color: 'indigo');
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
            } finally {
                if (!$this->switching) {
                    $this->js('signingBeneficiariesModal = false');
                    $this->resetSigning();
                }
                $this->switching = false;
            }
        }, 5);
    }

    # --------------------------------------------------------------------------

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
    public function beneficiaries()
    {
        return Beneficiary::where('batches_id', $this->batchId ? decrypt($this->batchId) : null)
            ->orderBy('last_name')
            ->get();
    }

    #[On('contractSigned')]
    public function initCheckSigned()
    {
        $this->selectedRows = $this->beneficiaries
            ->mapWithKeys(fn($beneficiary, $key) => [
                $key =>
                    [
                        'id' => encrypt($beneficiary->id),
                        'is_signed' => $beneficiary->is_signed,
                        'is_paid' => $beneficiary->is_paid
                    ]
            ])
            ->toArray();
    }

    #[Computed]
    public function contractCount()
    {
        $count = 0;
        foreach ($this->beneficiaries as $beneficiary) {
            if ($beneficiary->is_signed)
                $count++;
        }
        return $count;
    }

    #[Computed]
    public function full_name_last_first($person)
    {
        $full_name = $person->last_name;

        $full_name .= ', ' . $person->first_name;

        if ($person->middle_name) {
            $full_name .= ' ' . $person->middle_name;
        }

        if ($person->extension_name) {
            $full_name .= ' ' . $person->extension_name;
        }

        return $full_name;
    }

    #[Js]
    public function uncheckSelectAll()
    {
        return <<<JS
            const selectAll = document.getElementById('select-all-beneficiary');
            selectAll.checked = false;
        JS;
    }

    public function updating($prop, $value)
    {
        if ($prop === 'switchSign') {
            if (!$this->switchSign) {
                $this->switching = true;
                $this->signContract();
                $this->js('$wire.uncheckSelectAll()');
                unset($this->beneficiaries);
            } elseif ($this->switchSign) {
                $this->switching = true;
                $this->signPayroll();
                $this->js('$wire.uncheckSelectAll()');
                unset($this->beneficiaries);
            }
        }
    }

    public function resetSigning()
    {
        $this->resetExcept('batchId');
    }

    public function render()
    {
        return view('livewire.focal.implementations.signing-beneficiaries-modal');
    }
}
