<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FocalController extends Controller
{
    // public function authenticate(Request $request): RedirectResponse
    // {
    //     $credentials = $request->validate([
    //         'email' => ['required', 'email'],
    //         'password' => ['required'],
    //     ]);

    //     if (Auth::attempt($credentials)) {
    //         if (Auth::user()->user_type === 'Focal') {
    //             $request->session()->regenerate();

    //             return redirect()->intended('focal/home');
    //         }
    //     }

    //     Auth::logout();

    //     return back()->withErrors([
    //         'email' => 'The provided credentials do not match our records.',
    //     ])->onlyInput('email');
    // }

    public function __invoke()
    {
        if (Auth::check()) {
            if (Auth::user()->user_type === 'Focal')
                return redirect()->route('focal.dashboard');
            else if (Auth::user()->user_type === 'Coordinator')
                return redirect()->route('coordinator.home');
        } else
            return view('landing.focal');
    }
}
