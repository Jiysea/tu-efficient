<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use RateLimiter;
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
            'verification_code' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'verification_code.required' => 'This field is required.',
        ];
    }

    #[Computed]
    public function user()
    {
        return Auth::user();
    }

    #[Computed]
    public function maskedContact()
    {
        $contact_num = Auth::user()->contact_num;
        # Calculate the number of characters to show and mask

        $visibleCount = ceil(strlen($contact_num) * 0.30);
        $maskedCount = strlen($contact_num) - 7;
        $startCount = 3;

        # Create the masked username
        $contactMasked = substr($contact_num, 0, $startCount) . str_repeat('*', $maskedCount) . substr($contact_num, -4, 4);

        # Reassemble the email with the masked username
        return $contactMasked;
    }

    public function removeAlert($id)
    {
        $this->alerts = array_filter($this->alerts, function ($alert) use ($id) {
            return $alert['id'] !== $id;
        });
    }

    public function sendVerificationCode()
    {
        $executed = RateLimiter::attempt(
            'resendSMS:' . Auth::id(),
            $perMinute = 1,
            function () {

                # Initialize Vonage Client
                $basic = new Basic(config('services.vonage.key'), config('services.vonage.secret'));
                $client = new Client(new \Vonage\Client\Credentials\Container($basic));

                # Send verification code to the user's phone number
                $request = new \Vonage\Verify2\Request\SMSRequest(substr(Auth::user()->contact_num, 1), "TU-Efficient");
                $getRequest = $client->verify2()->startVerification($request);

                # Save the request_id in the session
                session(['vonage_request_id' => $getRequest]);

                $this->alerts[] = [
                    'message' => 'Verification code sent to your phone.',
                    'id' => uniqid(),
                    'color' => 'indigo'
                ];

            }
        );

        if (!$executed) {
            $this->alerts[] = [
                'message' => 'You already requested one. Try again after a 2 minutes.',
                'id' => uniqid(),
                'color' => 'red',
            ];
        }


    }

    public function verifyCode()
    {
        # Validate the code
        $this->validate();

        $request = session('vonage_request_id');

        # Retrieve request_id from the authenticated user
        if (!$request) {
            $this->alerts[] = [
                'message' => 'Incorrect verification code.',
                'id' => uniqid(),
                'color' => 'red'
            ];
            return;
        }

        # Initialize Vonage Client
        $basic = new Basic(config('services.vonage.key'), config('services.vonage.secret'));
        $client = new Client(new \Vonage\Client\Credentials\Container($basic));

        # Check the verification code
        try {
            $result = $client->verify2()->check($request['request_id'], $this->verification_code);

            if ($result) {
                $user = Auth::user();

                $user->update(['mobile_verified_at' => now()]);

                $this->alerts[] = [
                    'message' => 'User authenticated successfully. Redirecting...',
                    'id' => uniqid(),
                    'color' => 'indigo'
                ];

                # Logs the login date of the user
                $user->update(['last_login' => now(), 'ongoing_verification' => 0]);

                # Sends a flash to the dashboard page to trigger the Heads-Up modal upon login
                User::where('id', Auth::user()->id)->update(['last_login' => Carbon::now()]);
                session()->flash('heads-up', Auth::user()->last_login);

                $this->redirectIntended();
            } else {
                $this->alerts[] = [
                    'message' => 'Verification failed. Please try again.',
                    'id' => uniqid(),
                    'color' => 'red'
                ];
            }
        } catch (\Exception $e) {
            $this->alerts[] = [
                'message' => 'Error verifying the code: ' . $e->getMessage(),
                'id' => uniqid(),
                'color' => 'red'
            ];
        }
    }

    public function mount()
    {
        if (!Auth::user()->isOngoingVerification()) {
            $this->redirect('/');
        }

    }

    public function render()
    {
        return view('livewire.verify-contact-number');
    }
}
