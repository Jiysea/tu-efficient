<?php

namespace App\Livewire\Barangay;

use App\Models\Batch;
use App\Models\Beneficiary;
use App\Models\Code;
use App\Models\Credential;
use App\Models\Implementation;
use App\Services\LogIt;
use App\Services\MoneyFormat;
use Carbon\Carbon;
use DB;
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
    public $credentialId;
    public $identity;
    public $special;
    public $showAlert = false;
    public $alertMessage = '';

    # --------------------------------------------------------------------------

    public $addBeneficiariesModal = false;
    public $editBeneficiaryModal = false;
    public $deleteBeneficiaryModal = false;
    public $viewCredentialsModal = false;
    public $submitBatchModal = false;
    public $exitBatchModal = false;
    public $searchBeneficiaries;
    public $selectedBeneficiaryRow = -1;
    protected $defaultPages = 15;
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

    public function exitBatch()
    {
        $this->redirectRoute('login');
    }

    public function submitBatch()
    {
        $this->authorizeBeforeExecuting();
        $this->validateOnly('confirm_submit');

        if ($this->beneficiaryCount === $this->batch?->slots_allocated) {
            DB::transaction(function () {
                $batch = Batch::find($this->batch->id);

                $batch->submission_status = 'submitted';
                Code::where('access_code', decrypt($this->accessCode))
                    ->where('is_accessible', 'yes')
                    ->update([
                        'is_accessible' => 'no'
                    ]);
                $batch->save();

                LogIt::set_barangay_submit($batch, $this->implementation, count($this->beneficiaries), $batch->slots_allocated, $this->specialCasesCount);
            });

            $this->submitBatchModal = false;
            $this->redirectRoute('login');

        }

    }

    public function viewCredential($type)
    {
        if ($type === 'identity') {

            foreach ($this->credentials as $credential) {
                if ($credential->for_duplicates === 'no') {
                    $this->credentialId = encrypt($credential->id);
                    $this->viewCredentialsModal = true;
                }
            }

        } elseif ($type === 'special') {

            foreach ($this->credentials as $credential) {
                if ($credential->for_duplicates === 'yes') {
                    $this->credentialId = encrypt($credential->id);
                    $this->viewCredentialsModal = true;
                }
            }
        }
    }

    public function selectBeneficiaryRow($key, $encryptedId)
    {
        if ($this->selectedBeneficiaryRow === $key) {
            $this->selectedBeneficiaryRow = -1;
            $this->beneficiaryId = null;

            $this->identity = null;
            $this->special = null;

        } else {
            $this->selectedBeneficiaryRow = $key;
            $this->beneficiaryId = $encryptedId;

            $this->identity = null;
            $this->special = null;

            foreach ($this->credentials as $credential) {
                if ($credential->for_duplicates === 'no') {
                    $this->identity = $credential->image_file_path;
                } elseif ($credential->for_duplicates === 'yes') {
                    $this->special = $credential->image_file_path;
                }
            }
        }
    }

    public function openEdit()
    {
        $this->editBeneficiaryModal = true;
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
        if ($this->accessCode) {
            # do a query of the batch using the access code
            $batch = Batch::join('codes', 'codes.batches_id', '=', 'batches.id')
                ->where('codes.access_code', decrypt($this->accessCode))
                ->select('batches.*')
                ->first();

            $this->batchId = encrypt($batch->id);
            return $batch;
        }
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
            $beneficiaries = Beneficiary::where('batches_id', $this->batch->id)
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

            return $beneficiaries;
        }
    }

    #[Computed]
    public function beneficiaryCount()
    {
        if ($this->accessCode) {
            $beneficiariesCount = Beneficiary::where('batches_id', $this->batch?->id)
                ->count();

            return $beneficiariesCount;
        }
    }

    #[Computed]
    public function specialCasesCount()
    {
        if ($this->accessCode) {
            $beneficiariesCount = Beneficiary::where('batches_id', $this->batch?->id)
                ->where('beneficiary_type', 'special case')
                ->count();

            return $beneficiariesCount;
        }
    }

    #[Computed]
    public function credentials()
    {
        if ($this->beneficiaryId) {
            $credentials = Credential::where('beneficiaries_id', decrypt($this->beneficiaryId))
                ->get();

            return $credentials;
        }
    }

    #[Computed]
    public function getIdType()
    {
        $type_of_id = null;

        if ($this->beneficiaryId) {

            if (str_contains($this->beneficiary->type_of_id, 'PWD')) {
                $type_of_id = 'PWD ID';
            } else if (str_contains($this->beneficiary->type_of_id, 'COMELEC')) {
                $type_of_id = 'Voter\'s ID';
            } else if (str_contains($this->beneficiary->type_of_id, 'PhilID')) {
                $type_of_id = 'PhilID';
            } else if (str_contains($this->beneficiary->type_of_id, '4Ps')) {
                $type_of_id = '4Ps ID';
            } else if (str_contains($this->beneficiary->type_of_id, 'IBP')) {
                $type_of_id = 'IBP ID';
            } else {
                $type_of_id = $this->beneficiary->type_of_id;
            }

        }

        return $type_of_id;
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
        $access = Code::where('access_code', decrypt($this->accessCode))
            ->where('is_accessible', 'yes')
            ->first();

        # check if it's null
        if (!$access) {

            # then redirect to their intended page...
            $this->redirectIntended();
        }
    }

    #[On('add-beneficiaries')]
    public function addBeneficiaries()
    {
        $this->showAlert = true;
        $this->alertMessage = 'Successfully added a beneficiary!';
        $this->dispatch('show-alert');
        $this->dispatch('init-reload')->self();
    }

    #[On('cannot-add-beneficiary')]
    public function cannotAddBeneficiary()
    {
        unset($this->beneficiaries);
        $this->showAlert = true;
        $this->alertMessage = 'Unable to add this beneficiary!';
        $this->dispatch('show-alert');
        $this->dispatch('init-reload')->self();
    }

    #[On('optimistic-lock')]
    public function optimisticLockBeneficiary($message)
    {
        unset($this->beneficiaries);
        $this->beneficiaryId = null;
        $this->selectedBeneficiaryRow = -1;
        $this->showAlert = true;
        $this->alertMessage = $message;
        $this->dispatch('show-alert');
        $this->dispatch('init-reload')->self();
    }

    #[On('edit-beneficiary')]
    public function editBeneficiary()
    {
        $this->beneficiaryId = null;
        $this->selectedBeneficiaryRow = -1;
        $this->showAlert = true;
        $this->alertMessage = 'Successfully modified a beneficiary!';
        $this->dispatch('show-alert');
        $this->dispatch('init-reload')->self();
    }

    public function deleteBeneficiary()
    {
        $this->authorizeBeforeExecuting();

        DB::transaction(function () {

            $beneficiary = Beneficiary::find(decrypt($this->beneficiaryId));
            $credentials = Credential::where('beneficiaries_id', decrypt($this->beneficiaryId))
                ->get();
            $old = $beneficiary;
            foreach ($credentials as $credential) {
                if (isset($credential->image_file_path) && Storage::exists($credential->image_file_path)) {
                    Storage::delete($credential->image_file_path);
                }
                $credential->delete();
            }
            $beneficiary->delete();

            LogIt::set_barangay_delete_beneficiary($beneficiary);
            $this->beneficiaryId = null;
            $this->selectedBeneficiaryRow = -1;

            $this->showAlert = true;
            $this->alertMessage = 'Successfully deleted a beneficiary!';
            $this->dispatch('show-alert');
            $this->dispatch('init-reload')->self();

        });
    }

    public function resetConfirm()
    {
        $this->reset('confirm_submit');
        $this->resetValidation(['confirm_submit']);
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
        $this->authorizeBeforeExecuting();
        return view('livewire.barangay.listing-page')
            ->title("Brgy. " . $this->batch->barangay_name . " | TU-Efficient");
    }
}
