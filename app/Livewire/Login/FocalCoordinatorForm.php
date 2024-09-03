<?php

namespace App\Livewire\Login;

use App\Models\User;
use Carbon\Carbon;
use Dotenv\Exception\ValidationException;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;

class FocalCoordinatorForm extends Component
{
    #[Validate('required|email|max:255')]
    public $email;

    #[Validate('required|max:255')]
    public $password;

    public function login()
    {
        $messages = session()->get('email', []);
        $this->validate();

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            if (strtolower(Auth::user()->user_type) === 'focal') {
                session()->regenerate();

                # Logs the login date of the user
                User::where('id', Auth::user()->id)->update(['last_login' => Carbon::now()]);

                return redirect()->route('focal.dashboard');
            } else if (strtolower(Auth::user()->user_type) === 'coordinator') {
                session()->regenerate();

                # Logs the login date of the user
                User::where('id', Auth::user()->id)->update(['last_login' => Carbon::now()]);

                return redirect()->route('coordinator.home');
            }
        }

        Auth::logout();

        // dd('under this swing');
        // session()->flash('email', 'Email or password do not match our records.');
        $messages[] = 'Email or password do not match our records.';
        session()->put('email', $messages);

    }

    public function removeSuccessMessage($sessionMessage, $index)
    {
        $messages = session()->get($sessionMessage, []);
        if (isset($messages[$index])) {
            unset($messages[$index]);
            session()->put($sessionMessage, array_values($messages));
        }
    }

    public function render()
    {
        return view('livewire.login.focal-coordinator-form');
    }
}
