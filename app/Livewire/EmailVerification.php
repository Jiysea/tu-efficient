<?php

namespace App\Livewire;

use App\Models\User;
use Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use RateLimiter;

#[Layout('layouts.guest')]
#[Title('Email Verification')]
class EmailVerification extends Component
{
    public $alerts = [];

    #[Computed]
    public function user()
    {
        return Auth::user();
    }

    #[Computed]
    public function maskedEmail()
    {
        // Split the email into username and domain
        list($username, $domain) = explode('@', Auth::user()->email);

        // Calculate the number of characters to show and mask
        $visibleCount = ceil(strlen($username) * 0.30);
        $maskedCount = strlen($username) - $visibleCount;

        // Create the masked username
        $usernameMasked = substr($username, 0, $visibleCount) . str_repeat('*', $maskedCount);

        // Reassemble the email with the masked username
        return $usernameMasked . '@' . $domain;
    }

    public function resendEmail()
    {
        $user = User::find($this->user->id);

        $executed = RateLimiter::attempt(
            'resendEmail:' . $user->id,
            $perMinute = 1,
            function () use ($user) {

                $user->sendEmailVerificationNotification();

                $this->alerts[] = [
                    'message' => 'Verification email sent!',
                    'id' => uniqid(),
                    'color' => 'indigo'
                ];
            }
        );

        if (!$executed) {
            $this->alerts[] = [
                'message' => 'You already requested one. Try again after a minute.',
                'id' => uniqid(),
                'color' => 'red',
            ];
        }
    }

    public function removeAlert($id)
    {
        $this->alerts = array_filter($this->alerts, function ($alert) use ($id) {
            return $alert['id'] !== $id;
        });
    }

    public function mount()
    {
        if ($this->user->email_verified_at !== null) {
            $this->redirect('/');
        }

        $this->resendEmail();
    }

    public function render()
    {
        return view('livewire.email-verification');
    }
}
