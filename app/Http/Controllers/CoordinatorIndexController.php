<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CoordinatorIndexController extends Controller
{
    public function __invoke()
    {
        if (Auth::user()->user_type === 'Focal')
            return redirect()->route('focal.dashboard');
        else if (Auth::user()->user_type === 'Coordinator')
            return view('pages.coordinator.index');
    }
}
