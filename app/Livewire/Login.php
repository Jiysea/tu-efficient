<?php

namespace App\Livewire;

use App\Models\Code;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.guest')]
#[Title('TU-Efficient | Efficiently manage your workspace')]
class Login extends Component
{

    #[Validate]
    public $email;

    #[Validate]
    public $password;

    #[Validate]
    public $access_code;

    # ----------------------------

    public function rules()
    {
        return [
            'email' => 'required|email|exists:users',
            'password' => 'required',
            'access_code' =>
                [
                    'required',
                    'max:8',
                    Rule::exists('codes', 'access_code')->where('is_accessible', 'yes'),
                ],
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'This field is required.',
            'email.email' => 'Invalid email address.',
            'email.exists' => 'This email does not exist.',

            'password.required' => 'This field is required.',

            'access_code.required' => 'This field is required.',
            'access_code.exists' => 'This code is either non-existent or inaccessible.',
            'access_code.max' => 'A valid code only contains 8 characters.',
        ];
    }

    public function login()
    {
        $this->validateOnly('email');
        $this->validateOnly('password');

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {

            session()->regenerate();

            # For Email && Mobile Verification (disabled the mobile verification)
            if (!Auth::user()->isEmailVerified()) {
                Auth::user()->update(['ongoing_verification' => 1]);
                $this->redirectRoute('verification.notice');
            } elseif (!Auth::user()->isOngoingVerification()) {
                Auth::user()->update(['ongoing_verification' => 1]);
            }

            # Logs the login date of the user (remove this when enabling mobile verification)
            User::where('id', Auth::user()->id)->update(['last_login' => Carbon::now()]);

            if (strtolower(Auth::user()->user_type) === 'focal') {

                # Sends a flash to the dashboard page to trigger the Heads-Up modal upon login
                session()->flash('heads-up', Auth::user()->last_login);

                $this->redirectRoute('focal.dashboard');

            } else if (strtolower(Auth::user()->user_type) === 'coordinator') {

                $this->redirectRoute('coordinator.assignments');
            }

        } else {
            Auth::logout();

            $this->addError('password', 'The password is incorrect.');
        }
    }

    public function access()
    {
        $this->validateOnly('access_code');

        if (Code::where('access_code', $this->access_code)->value('is_accessible') === 'yes') {
            session()->regenerate();

            $encryptedAccessCode = encrypt($this->access_code);

            session()->put('code', $encryptedAccessCode);

            $this->redirectRoute('barangay.index');
        }
    }

    public function mount()
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->isOngoingVerification()) {
                $this->redirectRoute('verify.mobile');
            } else {
                if (Auth::user()->user_type === 'focal')
                    return redirect()->route('focal.dashboard');
                else if (Auth::user()->user_type === 'coordinator')
                    return redirect()->route('coordinator.assignments');
            }
        } else {
            if (session('code')) {
                session()->invalidate();
                session()->flush();
                session()->regenerateToken();
            }
        }
    }
    public function render()
    {
        return view('livewire.login');
    }
}
