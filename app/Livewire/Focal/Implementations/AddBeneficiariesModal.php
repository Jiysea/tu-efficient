<?php

namespace App\Livewire\Focal\Implementations;

use App\Models\Beneficiary;
use App\Services\JaccardSimilarity;
use Carbon\Carbon;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class AddBeneficiariesModal extends Component
{
    use WithFileUploads;

    #[Reactive]
    #[Locked]
    public $batchId;
    #[Locked]
    public $maxDate;
    #[Locked]
    public $minDate;

    # ------------------------------------

    protected $jaccardSimilarity;

    # ------------------------------------

    #[Validate]
    public $first_name;
    public $middle_name;
    #[Validate]
    public $last_name;
    public $extension_name;
    #[Validate]
    public $birthdate;
    public $sex = 'Male';
    #[Validate]
    public $contact_num;
    #[Validate]
    public $occupation;
    public $civil_status = 'Single';
    #[Validate]
    public $avg_monthly_income;
    #[Validate]
    public $dependent;
    public $e_payment_acc_num;
    public $self_employment = 'No';
    public $beneficiary_type = 'Underemployed';
    public $skills_training = 'No';
    public $is_pwd = 'No';
    #[Validate]
    public $image_file_path;
    public $type_of_id = 'e-Card / UMID';
    #[Validate]
    public $id_number;
    #[Validate]
    public $spouse_first_name;
    public $spouse_middle_name;
    #[Validate]
    public $spouse_last_name;
    public $spouse_extension_name;

    # Runs real-time depending on wire:model suffix
    public function rules()
    {
        return [
            'first_name' => 'required',
            'last_name' => 'required',
            'birthdate' => 'required',
            'contact_num' => 'required',
            'avg_monthly_income' => 'required_unless:occupation,null',
            'image_file_path' => 'nullable|image|mimes:png,jpg,jpeg|max:5120',
            'id_number' => 'required',
            'spouse_first_name' => 'required_if:civil_status,Married',
            'spouse_last_name' => 'required_if:civil_status,Married',
        ];
    }

    # The validation error messages
    public function messages()
    {
        return [
            'first_name.required' => ':attribute should not be empty.',
            'last_name.required' => ':attribute should not be empty.',
            'birthdate.required' => ':attribute should not be empty.',
            'contact_num.required' => ':attribute should not be empty.',
            'avg_monthly_income.required_unless' => 'This field is required.',
            'id_number.required' => ':attribute should not be empty.',
            'spouse_first_name.required_if' => 'This field is required.',
            'spouse_last_name.required_if' => 'This field is required.',

            'image_file_path.image' => ':attribute should be an image type.',
            'image_file_path.mimes' => ':attribute should be in PNG or JPG format.',
        ];
    }

    # Validation attribute names for human readability purpose
    # for example: The project_num should not be empty.
    # instead of that: The project number should not be empty.
    public function validationAttributes()
    {
        return [
            'first_name' => 'First name',
            'last_name' => 'Last name',
            'birthdate' => 'Birth date',
            'contact_num' => 'Contact number',
            'id_number' => 'ID Number',
            'image_file_path' => 'File',
        ];
    }

    #[On('birthdate-change')]
    public function setBirthdate($value)
    {
        $choosenDate = Carbon::createFromFormat('m-d-Y', $value)->format('Y-m-d');

        $this->birthdate = $choosenDate;

        if ($this->type_of_id === 'Senior Citizen ID' && strtotime($this->birthdate) > strtotime(Carbon::now()->subYears(60))) {
            $this->type_of_id = 'e-Card / UMID';
        }

    }

    # a livewire action executes after clicking the `Create Project` button
    public function saveBeneficiary()
    {
        $this->validate();
        if (!$this->middle_name || $this->middle_name === '') {
            $middle_name = '-';
        }

        # other attributes not in this form:
        # city_municipality, province, district, age, is_senior_citizen, is_pwd,

        // Beneficiary::create([
        //     'users_id' => Auth()->id(),
        //     'project_num' => $this->project_num,
        //     'project_title' => $this->project_title,
        //     'purpose' => $this->purpose,
        //     'district' => $this->district,
        //     'province' => $this->province,
        //     'city_municipality' => $this->city_municipality,
        //     'budget_amount' => $this->budget_amount,
        //     'total_slots' => $this->total_slots,
        //     'days_of_work' => $this->days_of_work
        // ]);

        // $this->reset();
        // $this->dispatch('add-beneficiaries');

    }

    public function mount()
    {
        $this->jaccardSimilarity = new JaccardSimilarity();

        // dd($this->jaccardSimilarity
        //     ->calculateSimilarity(
        //         'Rizal y Mercado Protacio Realonda Alonso José 1990-01-01',
        //         'José Protacio Rizal Mercado y Alonso Realonda 2001-07-28',
        //     ));
    }

    public function render()
    {
        $this->maxDate = date('m-d-Y', strtotime(Carbon::now()->subYears(18)));
        $this->minDate = date('m-d-Y', strtotime(Carbon::now()->subYears(100)));

        return view('livewire.focal.implementations.add-beneficiaries-modal');
    }
}
