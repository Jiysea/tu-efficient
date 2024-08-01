<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CoordinatorController extends Controller
{
    public function __invoke()
    {
        if (Auth::check()) {
            if (Auth::user()->user_type === 'Focal')
                return redirect()->route('focal.dashboard');
            else if (Auth::user()->user_type === 'Coordinator')
                return redirect()->route('coordinator.home');
        } else
            return view('landing.coordinator');
    }
}
