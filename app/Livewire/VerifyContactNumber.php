<?php

namespace App\Livewire;

use Vonage\Client;
use Vonage\Client\Credentials\Basic;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class VerifyContactNumber extends Component
{
    public $phoneNumber;
    public $verificationCode;
    public $requestId;

    public function sendVerificationCode()
    {
        # Validate the phone number
        $this->validate([
            'phoneNumber' => 'required|numeric|digits_between:10,15',
        ]);

        # Initialize Vonage Client
        $basic = new Basic(env('VONAGE_API_KEY'), env('VONAGE_API_SECRET'));
        $client = new Client($basic);

        # Send verification code to the user's phone number
        $request = new \Vonage\Verify\Request($this->phoneNumber, "YourApp");
        $response = $client->verify()->start($request);

        # Save the request_id and phone number in the user record
        $user = Auth::user();
        $user->phone_number = $this->phoneNumber;
        $user->request_id = $response->getRequestId();
        $user->save();

        session()->flash('message', 'Verification code sent to your phone.');
    }

    public function verifyCode()
    {
        # Validate the code
        $this->validate([
            'verificationCode' => 'required|numeric',
        ]);

        # Retrieve request_id from the authenticated user
        $user = Auth::user();
        if (!$user->request_id) {
            session()->flash('error', 'No verification request found.');
            return;
        }

        # Initialize Vonage Client
        $basic = new Basic(env('VONAGE_API_KEY'), env('VONAGE_API_SECRET'));
        $client = new Client($basic);

        # Check the verification code
        try {
            $result = $client->verify()->check($user->request_id, $this->verificationCode);
            if ($result->getStatus() === 0) { # Status 0 means success
                $user->phone_verified_at = now();
                $user->save();

                session()->flash('success', 'Phone number verified successfully.');
            } else {
                session()->flash('error', 'Verification failed. Please try again.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error verifying the code: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.verify-contact-number');
    }
}
