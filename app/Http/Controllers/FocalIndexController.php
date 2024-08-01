<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FocalIndexController extends Controller
{
    public function __invoke()
    {
        if (Auth::user()->user_type === 'Focal')
            return view('pages.focal.index');
        else if (Auth::user()->user_type === 'Coordinator')
            return redirect()->route('coordinator.home');
    }
}
