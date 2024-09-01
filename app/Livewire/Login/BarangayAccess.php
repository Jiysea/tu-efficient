<?php

namespace App\Livewire\Login;

use App\Models\Code;
use Livewire\Attributes\Validate;
use Livewire\Component;

class BarangayAccess extends Component
{
    #[Validate('required|max:8')]
    public $accessCode;
    public function checkAccess()
    {
        // $this->validate();

        // if (Code::where('access_code', $this->accessCode)->value('accessible') === 'Yes') {
        //     session()->regenerate();

        //     return redirect()->route('barangay.index', ['accessCode' => $this->accessCode]);
        // }

        // session()->flash('access-code', 'Access code do not match our records.');
        session()->flash('access-code', 'Temporarily blocked access. Please try again later.');
    }

    public function render()
    {
        return view('livewire.login.barangay-access');
    }
}
