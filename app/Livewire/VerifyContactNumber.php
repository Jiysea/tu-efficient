<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Vonage\Client;
use Vonage\Client\Credentials\Basic;
use Vonage\Verify\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

#[Layout('layouts.guest')]
#[Title('Verify Login | TU-Efficient')]
class VerifyContactNumber extends Component
{
    #[Validate]
    public $verification_code;
    public $request_id;
    public $alerts = [];

    public function rules()
    {
        return [
            'verification_code' => 'required|integer',
        ];
    }

    public function messages()
    {
        return [
            'verification_code.required' => 'This field is required.',
            'verification_code.integer' => 'Verification code should be a number.'
        ];
    }

    public function removeAlert($id)
    {
        $this->alerts = array_filter($this->alerts, function ($alert) use ($id) {
            return $alert['id'] !== $id;
        });
    }

    public function sendVerificationCode()
    {
        # Initialize Vonage Client
        $basic = new Basic(config('services.vonage.key'), config('services.vonage.secret'));
        $client = new Client($basic);

        # Send verification code to the user's phone number
        $request = new Request(substr(Auth::user()->contact_num, 1), "TU-Efficient", 6);
        $response = $client->verify()->start($request);

        # Save the request_id in the session
        session(['vonage_request_id' => $response->getRequestId()]);

        $this->alerts[] = [
            'message' => 'Verification code sent to your phone.',
            'id' => uniqid(),
        ];
    }

    public function verifyCode()
    {
        # Validate the code
        $this->validate();

        # Retrieve request_id from the authenticated user
        if (!session('vonage_request_id')) {
            $this->alerts[] = [
                'message' => 'Verification code sent to your phone.',
                'id' => uniqid(),
            ];
            return;
        }

        # Initialize Vonage Client
        $basic = new Basic(config('services.vonage.key'), config('services.vonage.secret'));
        $client = new Client($basic);

        # Check the verification code
        try {
            $result = $client->verify()->check(session('vonage_request_id'), $this->verification_code);
            if ($result->getStatus() === 0) { # Status 0 means success
                $user = Auth::user();

                $user->update(['mobile_verified_at' => now()]);

                $this->alerts[] = [
                    'message' => 'User verified successfully.',
                    'id' => uniqid(),
                ];

                $user->update(['ongoing_verification' => 0]);
                $user->save();

                if (Auth::user()->user_type === 'focal') {
                    $this->redirectRoute('focal.dashboard');
                } else if (Auth::user()->user_type === 'coordinator') {
                    $this->redirectRoute('coordinator.assignments');
                } else {
                    $this->redirectIntended();
                }
            } else {
                $this->alerts[] = [
                    'message' => 'Verification failed. Please try again.',
                    'id' => uniqid(),
                ];
            }
        } catch (\Exception $e) {
            $this->alerts[] = [
                'message' => 'Error verifying the code: ' . $e->getMessage(),
                'id' => uniqid(),
            ];
        }
    }

    public function mount()
    {
        if (Auth::check()) {
            $user = Auth::user();
            if (!$user->isOngoingVerification()) {
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
            $this->redirectIntended();
        }
    }

    public function render()
    {
        return view('livewire.verify-contact-number');
    }
}
