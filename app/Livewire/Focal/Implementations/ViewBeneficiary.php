<?php

namespace App\Livewire\Focal\Implementations;

use App\Models\Beneficiary;
use App\Models\Implementation;
use Carbon\Carbon;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class ViewBeneficiary extends Component
{
    #[Reactive]
    #[Locked]
    public $passedBeneficiaryId;
    public $editMode = false;
    public $deleteBeneficiaryModal = false;

    public function toggleEdit()
    {
        # Not Yet
        # $this->editMode = !$this->editMode;
    }

    public function editBeneficiary()
    {
        $this->dispatch('edit-beneficiary');
    }

    public function deleteBeneficiary()
    {
        $this->dispatch('delete-beneficiary');
        $this->deleteBeneficiaryModal = false;
    }

    #[Computed]
    public function projectInformation()
    {
        if ($this->passedBeneficiaryId) {
            $info = Implementation::join('batches', 'batches.implementations_id', '=', 'implementations.id')
                ->join('beneficiaries', 'beneficiaries.batches_id', '=', 'batches.id')
                ->where('beneficiaries.id', decrypt($this->passedBeneficiaryId))
                ->select(
                    [
                        'implementations.project_num',
                        'batches.batch_num',
                    ]
                )
                ->first();
            return $info;
        }
    }

    #[Computed]
    public function basicInformation()
    {
        # get each value first
        $values = [
            'First Name' => $this->beneficiary->first_name,
            'Middle Name' => $this->beneficiary->middle_name ?? '-',
            'Last Name' => $this->beneficiary->last_name,
            'Extension Name' => $this->beneficiary->extension_name ?? '-',
            'Birthdate' => Carbon::parse($this->beneficiary->birthdate)->format('M d, Y'),
            'Contact Number' => $this->beneficiary->contact_num,
            'Sex' => strtoupper(substr($this->beneficiary->sex, 0, 1)) . substr($this->beneficiary->sex, 1),
            'Age' => $this->beneficiary->age,
            'Civil Status' => strtoupper(substr($this->beneficiary->civil_status, 0, 1)) . substr($this->beneficiary->civil_status, 1),
        ];

        return $values;
    }

    #[Computed]
    public function addressInformation()
    {
        # get each value first
        $values = [
            'Province' => $this->beneficiary->province,
            'City/Municipality' => $this->beneficiary->city_municipality,
            'District' => $this->beneficiary->district,
            'Barangay' => $this->beneficiary->barangay_name,
        ];

        return $values;
    }

    #[Computed]
    public function additionalInformation()
    {
        # get each value first
        $values = [
            'Occupation' => $this->beneficiary->occupation ?? '-',
            'Avg. Monthly Income' => $this->beneficiary->avg_monthly_income ?? '-',
            'Type of Beneficiary' => strtoupper(substr($this->beneficiary->beneficiary_type, 0, 1)) . substr($this->beneficiary->beneficiary_type, 1),
            'e-Payment Account Number' => $this->beneficiary->e_payment_acc_num ?? '-',
            'Interested in Self-Employment' => strtoupper(substr($this->beneficiary->self_employment, 0, 1)) . substr($this->beneficiary->self_employment, 1),
            'Type of ID' => $this->beneficiary->type_of_id,
            'ID Number' => $this->beneficiary->id_number,
            'Dependent' => $this->beneficiary->dependent ?? '-',
            'Skills Training' => $this->beneficiary->skills_training ?? '-',
            'is PWD' => strtoupper(substr($this->beneficiary->is_pwd, 0, 1)) . substr($this->beneficiary->is_pwd, 1),
            'is Senior Citizen' => strtoupper(substr($this->beneficiary->is_senior_citizen, 0, 1)) . substr($this->beneficiary->is_senior_citizen, 1),
        ];

        return $values;
    }

    #[Computed]
    public function beneficiary()
    {
        if ($this->passedBeneficiaryId) {
            $beneficiary = Beneficiary::find(decrypt($this->passedBeneficiaryId));
            return $beneficiary;
        }
    }

    public function resetViewBeneficiary()
    {
        $this->resetExcept('passedBeneficiaryId');
    }

    public function render()
    {
        return view('livewire.focal.implementations.view-beneficiary');
    }
}
