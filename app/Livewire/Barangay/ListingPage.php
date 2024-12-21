<?php

namespace App\Livewire\Barangay;

use App\Models\Archive;
use App\Models\Batch;
use App\Models\Beneficiary;
use App\Models\Code;
use App\Models\Credential;
use App\Models\Implementation;
use App\Models\User;
use App\Services\LogIt;
use DB;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Session;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use Storage;

#[Layout('layouts.app')]
class ListingPage extends Component
{
    use WithFileUploads;

    #[Locked]
    #[Session('code')]
    public $accessCode;
    #[Locked]
    public $batchId;
    #[Locked]
    public $beneficiaryId;
    #[Locked]
    public array $beneficiaryIds = [];
    #[Locked]
    public $credentialId;
    public $alerts = [];

    # --------------------------------------------------------------------------

    public $addBeneficiariesModal = false;
    public $editBeneficiaryModal = false;
    public $archiveBeneficiaryModal = false;
    public $promptMultiArchiveModal = false;
    public $viewCredentialsModal = false;
    public $submitBatchModal = false;
    public $exitBatchModal = false;
    public $searchBeneficiaries;
    public array $selectedBeneficiaryRow = [];
    public $anchorBeneficiaryKey = -1;
    protected $defaultPages = 30;
    public $beneficiaries_on_page = 30;

    # --------------------------------------------------------------------------

    #[Validate]
    public $confirm_submit;

    # --------------------------------------------------------------------------

    # The validation rules, it runs every model update or calling validate()/validateOnly() methods
    public function rules()
    {
        return [
            'confirm_submit' => [
                'required',
                function ($attr, $value, $fail) {
                    if ($value !== 'CONFIRM') {
                        $fail('Please type it correctly.');
                    }
                },
            ],
        ];
    }

    # The validation error messages
    public function messages()
    {
        return [

            'confirm_submit.required' => 'This field is required.',

        ];
    }

    public function openEdit()
    {
        $this->editBeneficiaryModal = true;
    }

    public function viewCredential($type)
    {
        if ($type === 'identity') {

            $id = Credential::where('beneficiaries_id', $this->beneficiaryId ? decrypt($this->beneficiaryId) : null)
                ->where('for_duplicates', 'no')
                ->first('id')
                ->id;

            $this->credentialId = encrypt($id);
            $this->viewCredentialsModal = true;

        } elseif ($type === 'special') {

            $id = Credential::where('beneficiaries_id', $this->beneficiaryId ? decrypt($this->beneficiaryId) : null)
                ->where('for_duplicates', 'yes')
                ->first('id')
                ->id;

            $this->credentialId = encrypt($id);
            $this->viewCredentialsModal = true;
        }
    }

    public function selectBeneficiaryRow($key, $encryptedId, $type = 'row-based')
    {
        if ($type === 'row-based') {
            if (!in_array($key, $this->selectedBeneficiaryRow) || count($this->selectedBeneficiaryRow) !== 1) {
                $this->selectedBeneficiaryRow = [$key => $key];
                $this->beneficiaryIds = [$key => $encryptedId];
                $this->anchorBeneficiaryKey = $key;
                $this->beneficiaryId = $encryptedId;
            } else {
                $this->resetBeneficiary();
            }
        } elseif ($type === 'checkbox') {
            if (!in_array($key, $this->selectedBeneficiaryRow)) {
                $this->selectedBeneficiaryRow[$key] = $key;
                $this->beneficiaryIds[$key] = $encryptedId;
                $this->anchorBeneficiaryKey = $key;
                $this->beneficiaryId = $encryptedId;
            } else {
                unset($this->selectedBeneficiaryRow[$key], $this->beneficiaryIds[$key]);
            }
        } else {
            $this->resetBeneficiary();
        }
        $this->dispatch('init-reload')->self();
    }

    # Used to Multi-Select rows from the beneficiary table
    public function selectShiftBeneficiary($key, $encryptedId)
    {
        # Checks if there are already selected `keys` to proceed with multi-select
        if (count($this->selectedBeneficiaryRow) > 0) {

            # First, we get the lowest and highest `key` value among the selected rows
            $lowKey = min($this->selectedBeneficiaryRow);
            $highKey = max($this->selectedBeneficiaryRow);
            $centerKey = $this->anchorBeneficiaryKey;

            # Temporarily store an instance of selected rows for later use
            $tempSelectedRows = $this->selectedBeneficiaryRow;

            # Empty the selected rows or basically reset them before we do the multi-select
            # in order to avoid unexpected results
            $this->selectedBeneficiaryRow = [];
            $this->beneficiaryIds = [];

            # When selecting a row in the table, there are 7 possibilities but before that here are some
            # key points to note:
            # - When you select a row, the system will store the `key` and the `id` of that row
            # - The `id` is basically the beneficiary model ID
            # - After storing the `key`, it will be used to indicate which rows were selected on the front-end
            # - But when it comes to multi-selection, there would be more than 1 `key` stored
            # - Basically, the `key`s will be stored in an Array (same goes for the `id`s)
            # - The problem here would be to determine how to select rows closely similar to
            #   the traditional multi-selection
            # - That's why I get the `min` and `max` from the selected rows and
            #   use the `key` as the Center
            #
            # Now for the 7 possibilities, this is based on traditional shift-click selection behavior:

            # 1.) When the selected row `key` is LOWER THAN the lowest selected row
            if ($key < $lowKey) {

                foreach (range($key, $centerKey) as $num) {
                    $this->selectedBeneficiaryRow[$num] = $num;
                    $this->beneficiaryIds[$num] = encrypt($this->beneficiaries[$num]->id);
                }
            }

            # 2.) When the selected row `key` is GREATER THAN the lowest selected row 
            #       but LOWER THAN highest selected row
            elseif ($key > $lowKey && $key < $centerKey && $key < $highKey) {

                foreach (range($key, $centerKey) as $num) {
                    $this->selectedBeneficiaryRow[$num] = $num;
                    $this->beneficiaryIds[$num] = encrypt($this->beneficiaries[$num]->id);
                }
            }

            # 3.) When the selected row `key` is EQUAL TO the lowest selected row 
            #       & LOWER THAN the highest selected row
            elseif ($key > $lowKey && $key > $centerKey && $key < $highKey) {

                foreach (range($centerKey, $key) as $num) {
                    $this->selectedBeneficiaryRow[$num] = $num;
                    $this->beneficiaryIds[$num] = encrypt($this->beneficiaries[$num]->id);
                }
            }

            # 4.) When the selected row `key` is GREATER THAN the lowest selected row 
            #       & highest selected row
            elseif ($key > $highKey) {

                foreach (range($centerKey, $key) as $num) {
                    $this->selectedBeneficiaryRow[$num] = $num;
                    $this->beneficiaryIds[$num] = encrypt($this->beneficiaries[$num]->id);
                }
            }

            # 5.) When the selected row `key` is EQUAL TO the highest selected row
            #       but there are multiple selected rows
            elseif (($key === $centerKey) && count($tempSelectedRows) > 1) {
                $this->selectedBeneficiaryRow = [$key => $key];
                $this->beneficiaryIds = [$key => $encryptedId];
                $this->anchorBeneficiaryKey = $key;
                $this->beneficiaryId = $encryptedId;
            }

            # 6.) When the selected row `key` is EQUAL TO the highest selected row
            #       but there is only one selected row
            elseif (($key === $centerKey) && count($tempSelectedRows) === 1) {
                $this->resetBeneficiary();
            }
        }

        # Otherwise, it will just select the single row if there are no selected rows yet
        else {
            $this->selectedBeneficiaryRow = [$key => $key];
            $this->beneficiaryIds = [$key => $encryptedId];
            $this->anchorBeneficiaryKey = $key;
            $this->beneficiaryId = $encryptedId;
        }

        # Finally, we bust the cache just to make sure the rows were updated
        unset($this->implementations);
        unset($this->batches);
        unset($this->beneficiaries);
        $this->dispatch('init-reload')->self();
    }

    public function removeBeneficiaries()
    {
        $count = count($this->selectedBeneficiaryRow);
        DB::transaction(function () use ($count) {
            try {
                foreach ($this->beneficiaryIds as $key => $id) {
                    # Eager loading with batches would help with preventing Deadlocks
                    $beneficiary = Beneficiary::with([
                        'batch' => function ($query) {
                            $query->lockForUpdate();
                        }
                    ])->lockForUpdate()->findOrFail($id ? decrypt($id) : null);
                    $batch = $beneficiary->batch;
                    $implementation = Implementation::findOrFail($batch->implementations_id);
                    $user = User::find($implementation->users_id);
                    $this->authorizeBeforeExecuting();

                    $credentials = Credential::where('beneficiaries_id', $id ? decrypt($id) : null)
                        ->lockForUpdate()
                        ->get();

                    # Archive their credentials first
                    foreach ($credentials as $credential) {

                        Archive::create([
                            'last_id' => $credential->id,
                            'source_table' => 'credentials',
                            'data' => $credential->toArray(),
                            'archived_at' => now()
                        ]);
                        $credential->deleteOrFail();
                        if ($credential->for_duplicates === 'yes') {
                            LogIt::set_barangay_archive_beneficiary_special_case($implementation, $batch, $beneficiary, $credential, auth()->user());
                        }
                    }

                    # then archive the Beneficiary record
                    Archive::create([
                        'last_id' => $beneficiary->id,
                        'source_table' => 'beneficiaries',
                        'data' => $beneficiary->makeHidden('batch')->toArray(),
                        'archived_at' => now()
                    ]);
                    $beneficiary->deleteOrFail();

                    if (mb_strtolower($beneficiary->beneficiary_type, "UTF-8") === 'underemployed') {
                        LogIt::set_barangay_archive_beneficiary($implementation, $batch, $beneficiary, $this->accessCode);
                    }
                }

                $this->dispatch('alertNotification', type: 'beneficiary', message: 'Successfully archived ' . ($count > 1 ? ($count . ' beneficiaries') : 'a beneficiary'), color: 'green');

            } catch (QueryException $e) {
                DB::rollBack();

                # Deadlock & LockWaitTimeoutException
                if ($e->getCode() === '1213' || $e->getCode() === '1205') {
                    $this->dispatch('alertNotification', type: 'beneficiary', message: 'Another user is modifying the record. Please try again. Error ' . $e->getCode(), color: 'red');
                } else {
                    LogIt::set_log_barangay_exception('An error has occured during execution. Error ' . $e->getCode(), $this->accessCode, $e->getTrace(), ['regional_office' => $user->regional_office, 'field_office' => $user->field_office]);
                    $this->dispatch('alertNotification', type: 'beneficiary', message: 'An error has occured during execution. Error ' . $e->getCode(), color: 'red');
                }
            } catch (\Throwable $e) {
                DB::rollBack();
                LogIt::set_log_barangay_exception('An error has occured during execution. Error ' . $e->getCode(), $this->accessCode, $e->getTrace(), ['regional_office' => $user->regional_office, 'field_office' => $user->field_office]);
                $this->dispatch('alertNotification', type: 'beneficiary', message: 'An error has occured during execution. Error ' . $e->getCode(), color: 'red');
            } finally {
                $this->resetBeneficiary();
            }
        }, 5);
    }

    public function deleteBeneficiary()
    {
        DB::transaction(function () {
            try {
                # Eager loading with batches would help with preventing Deadlocks
                $beneficiary = Beneficiary::with([
                    'batch' => function ($query) {
                        $query->lockForUpdate();
                    }
                ])->lockForUpdate()->findOrFail($this->beneficiaryId ? decrypt($this->beneficiaryId) : null);
                $batch = $beneficiary->batch;
                $implementation = Implementation::findOrFail($batch->implementations_id);
                $user = User::find($implementation->users_id);
                $this->authorizeBeforeExecuting();

                $credentials = Credential::where('beneficiaries_id', $this->beneficiaryId ? decrypt($this->beneficiaryId) : null)
                    ->lockForUpdate()
                    ->get();

                # Archive their credentials first
                foreach ($credentials as $credential) {

                    Archive::create([
                        'last_id' => $credential->id,
                        'source_table' => 'credentials',
                        'data' => $credential->toArray(),
                        'archived_at' => now()
                    ]);
                    $credential->deleteOrFail();
                    if ($credential->for_duplicates === 'yes') {
                        LogIt::set_barangay_archive_beneficiary_special_case($implementation, $batch, $beneficiary, $credential, auth()->user());
                    }
                }

                # then archive the Beneficiary record
                Archive::create([
                    'last_id' => $beneficiary->id,
                    'source_table' => 'beneficiaries',
                    'data' => $beneficiary->makeHidden('batch')->toArray(),
                    'archived_at' => now()
                ]);
                $beneficiary->deleteOrFail();

                if (mb_strtolower($beneficiary->beneficiary_type, "UTF-8") === 'underemployed') {
                    LogIt::set_barangay_archive_beneficiary($implementation, $batch, $beneficiary, $this->accessCode);
                }

                $this->dispatch('alertNotification', type: 'beneficiary', message: 'Successfully archived a beneficiary', color: 'green');
            } catch (QueryException $e) {
                DB::rollBack();

                # Deadlock & LockWaitTimeoutException
                if ($e->getCode() === '1213' || $e->getCode() === '1205') {
                    $this->dispatch('alertNotification', type: 'beneficiary', message: 'Another user is modifying the record. Please try again. Error ' . $e->getCode(), color: 'red');
                } else {
                    LogIt::set_log_barangay_exception('An error has occured during execution. Error ' . $e->getCode(), $this->accessCode, $e->getTrace(), ['regional_office' => $user->regional_office, 'field_office' => $user->field_office]);
                    $this->dispatch('alertNotification', type: 'beneficiary', message: 'An error has occured during execution. Error ' . $e->getCode(), color: 'red');
                }
            } catch (\Throwable $e) {
                DB::rollBack();
                LogIt::set_log_barangay_exception('An error has occured during execution. Error ' . $e->getCode(), $this->accessCode, $e->getTrace(), ['regional_office' => $user->regional_office, 'field_office' => $user->field_office]);
                $this->dispatch('alertNotification', type: 'beneficiary', message: 'An error has occured during execution. Error ' . $e->getCode(), color: 'red');
            } finally {
                $this->resetBeneficiary();
            }
        }, 5);
    }

    public function submitBatch()
    {
        $this->validateOnly('confirm_submit');

        DB::transaction(function () {
            try {
                $batch = Batch::lockForUpdate()->findOrFail($this->batch->id);
                $this->authorizeBeforeExecuting();
                $implementation = Implementation::find($batch->implementations_id);
                $user = User::find($implementation->users_id);
                $beneficiaryCount = Beneficiary::where('batches_id', $this->accessCode ? $this->batch?->id : null)
                    ->count();

                if ($beneficiaryCount !== $batch?->slots_allocated) {
                    DB::rollBack();
                    $this->dispatch('alertNotification', type: 'beneficiary', message: 'Could not submit when there are remaining slots.', color: 'red');
                    return;
                }

                $batch->submission_status = 'submitted';
                Code::where('access_code', decrypt($this->accessCode))
                    ->where('is_accessible', 'yes')
                    ->update([
                        'is_accessible' => 'no'
                    ]);
                $batch->save();

                LogIt::set_barangay_submit($implementation, $batch, count($this->beneficiaries), $batch->slots_allocated, $this->specialCasesCount, $this->accessCode);
                $this->redirectRoute('login');

            } catch (QueryException $e) {
                DB::rollBack();

                # Deadlock & LockWaitTimeoutException
                if ($e->getCode() === '1213' || $e->getCode() === '1205') {
                    $this->dispatch('alertNotification', type: 'beneficiary', message: 'Another user is modifying the record. Please try again. Error ' . $e->getCode(), color: 'red');
                } else {
                    LogIt::set_log_barangay_exception('An error has occured during execution. Error ' . $e->getCode(), $this->accessCode, $e->getTrace(), ['regional_office' => $user->regional_office, 'field_office' => $user->field_office]);
                    $this->dispatch('alertNotification', type: 'beneficiary', message: 'An error has occured during execution. Error ' . $e->getCode(), color: 'red');
                }
            } catch (\Throwable $e) {
                DB::rollBack();
                LogIt::set_log_barangay_exception('An error has occured during execution. Error ' . $e->getCode(), $this->accessCode, $e->getTrace(), ['regional_office' => $user->regional_office, 'field_office' => $user->field_office]);
                $this->dispatch('alertNotification', type: 'beneficiary', message: 'An error has occured during execution. Error ' . $e->getCode(), color: 'red');
            } finally {
                $this->submitBatchModal = false;
            }
        });
    }

    #[Computed]
    public function implementation()
    {
        $implementation = Implementation::find($this->batch?->implementations_id);

        return $implementation;
    }

    #[Computed]
    public function batch()
    {
        # do a query of the batch using the access code
        $batch = Batch::join('codes', 'codes.batches_id', '=', 'batches.id')
            ->where('codes.access_code', $this->accessCode ? decrypt($this->accessCode) : null)
            ->select('batches.*')
            ->first();

        $this->batchId = encrypt($batch->id);
        return $batch;
    }

    #[Computed]
    public function beneficiary()
    {
        $beneficiary = Beneficiary::find($this->beneficiaryId ? decrypt($this->beneficiaryId) : null);
        return $beneficiary;
    }

    #[Computed]
    public function beneficiaries()
    {
        if ($this->accessCode) {
            # do a query of all the beneficiaries listed on a particular batch
            return Beneficiary::where('batches_id', $this->batch?->id)
                ->when($this->searchBeneficiaries, function ($q) {
                    # Check if the search field starts with '#' and filter by contact number
                    if (str_contains($this->searchBeneficiaries, '#')) {
                        $searchValue = trim(str_replace('#', '', $this->searchBeneficiaries));

                        if (strpos($searchValue, '0') === 0) {
                            $searchValue = substr($searchValue, 1);
                        }
                        $q->where('beneficiaries.contact_num', 'LIKE', '%' . $searchValue . '%');
                    }

                    # Or search for male beneficiaries
                    else if (str_contains(strtolower(trim($this->searchBeneficiaries)), 'male')) {
                        $searchValue = strtolower(trim($this->searchBeneficiaries));
                        $q->where('beneficiaries.sex', $searchValue);
                    }

                    # Or search for female beneficiaries
                    else if (str_contains(strtolower(trim($this->searchBeneficiaries)), 'female')) {
                        $searchValue = strtolower(trim($this->searchBeneficiaries));
                        $q->where('beneficiaries.sex', $searchValue);
                    }

                    # Otherwise, search by first, middle, last or extension name
                    else {
                        $q->where(function ($query) {
                            $query->where('beneficiaries.first_name', 'LIKE', '%' . $this->searchBeneficiaries . '%')
                                ->orWhere('beneficiaries.middle_name', 'LIKE', '%' . $this->searchBeneficiaries . '%')
                                ->orWhere('beneficiaries.last_name', 'LIKE', '%' . $this->searchBeneficiaries . '%')
                                ->orWhere('beneficiaries.extension_name', 'LIKE', '%' . $this->searchBeneficiaries . '%');
                        });
                    }
                })
                ->take($this->beneficiaries_on_page)
                ->get();
        }
    }

    #[Computed]
    public function identity()
    {
        return Credential::where('beneficiaries_id', $this->beneficiaryId ? decrypt($this->beneficiaryId) : null)
            ->where('for_duplicates', 'no')
            ->first()
            ->image_file_path;
    }

    #[Computed]
    public function special()
    {
        return Credential::where('beneficiaries_id', $this->beneficiaryId ? decrypt($this->beneficiaryId) : null)
            ->where('for_duplicates', 'yes')
            ->first()
                ?->image_file_path;
    }

    #[Computed]
    public function beneficiaryCount()
    {
        return Beneficiary::where('batches_id', $this->accessCode ? $this->batch?->id : null)
            ->count();
    }

    #[Computed]
    public function specialCasesCount()
    {
        return Beneficiary::where('batches_id', $this->accessCode ? $this->batch?->id : null)
            ->where('beneficiary_type', 'special case')
            ->count();
    }

    #[Computed]
    public function credentials()
    {
        return Credential::where('beneficiaries_id', $this->beneficiaryId ? decrypt($this->beneficiaryId) : null)
            ->get();
    }

    #[Computed]
    public function getIdType()
    {
        if (str_contains($this->beneficiary?->type_of_id, 'PWD')) {
            return 'PWD ID';
        } else if (str_contains($this->beneficiary?->type_of_id, 'COMELEC')) {
            return 'Voter\'s ID';
        } else if (str_contains($this->beneficiary?->type_of_id, 'PhilID')) {
            return 'PhilID';
        } else if (str_contains($this->beneficiary?->type_of_id, '4Ps')) {
            return '4Ps ID';
        } else if (str_contains($this->beneficiary?->type_of_id, 'IBP')) {
            return 'IBP ID';
        } else {
            return $this->beneficiary?->type_of_id;
        }
    }

    #[Computed]
    public function full_name($person)
    {
        $full_name = $person->first_name;

        if ($person->middle_name) {
            $full_name .= ' ' . $person->middle_name;
        }

        $full_name .= ' ' . $person->last_name;

        if ($person->extension_name) {
            $full_name .= ' ' . $person->extension_name;
        }

        return $full_name;
    }

    public function loadMoreBeneficiaries()
    {
        $this->beneficiaries_on_page += $this->defaultPages;
        $this->dispatch('init-reload')->self();
    }

    # Should be called for every execution/action to check if the code is accessible
    public function authorizeBeforeExecuting()
    {
        # do a query for that code (if it's set)
        $access = Code::lockForUpdate()->where('access_code', decrypt($this->accessCode))
            ->where('is_accessible', 'yes')
            ->first();

        # check if it's null
        if (!$access) {

            # then redirect to their intended page...
            $this->redirectIntended();
        }
    }

    #[On('alertNotification')]
    public function alertNotification($type, $message, $color)
    {
        if ($type === 'beneficiary') {
            $this->resetBeneficiary();
        }

        $this->alerts[] = [
            'message' => $message,
            'id' => uniqid(),
            'color' => $color
        ];

        $this->dispatch('init-reload')->self();
    }

    public function removeAlert($id)
    {
        $this->alerts = array_filter($this->alerts, function ($alert) use ($id) {
            return $alert['id'] !== $id;
        });
    }

    public function resetBeneficiary()
    {
        $this->reset('beneficiaryId', 'selectedBeneficiaryRow');
        unset($this->beneficiaries);
    }

    public function resetConfirm()
    {
        $this->reset('confirm_submit');
        $this->resetValidation(['confirm_submit']);
    }

    public function exitBatch()
    {
        $this->redirectRoute('login');
    }

    public function mount()
    {
        # check if there's an authenticated user logged in
        if (Auth::check()) {
            # then check if it's a Focal user
            if (Auth::user()->user_type === 'focal')

                # then redirect to their index page...
                return redirect()->route('focal.dashboard');

            # check if it's a Coordinator user
            else if (Auth::user()->user_type === 'coordinator')

                # then redirect to their index page...
                return redirect()->route('coordinator.assignments');
        } else {
            # checks if there's an access code in the session
            if (!$this->accessCode) {
                # redirects to login page if none...
                $this->redirectIntended();
            } else {
                # else, it would then check if the access code is accessible.
                $this->authorizeBeforeExecuting();
            }
        }
    }

    public function render()
    {
        return view('livewire.barangay.listing-page')
            ->title(($this->batch?->is_sectoral ? $this->batch?->sector_title : ("Brgy. " . $this->batch?->barangay_name)) . " | TU-Efficient");
    }
}
