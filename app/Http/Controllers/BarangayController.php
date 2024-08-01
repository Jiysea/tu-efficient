<?php

namespace App\Http\Controllers;

use App\Models\Code;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BarangayController extends Controller
{
    public function checkAccess(Request $request)
    {
        // dd(Code::where('access_code', $request->access_code)->value('access_code') !== null);
        if (Code::where('access_code', $request->access_code)->value('accessible') === 'Yes') {
            $request->session()->regenerate();

            return redirect()->route('barangay.index', ['accessCode' => json_encode($request->access_code)]);
        }

        return back()->withErrors([
            'access-code' => 'The provided access code do not match our records.',
        ])->onlyInput('access-code');
    }
    public function __invoke()
    {
        if (Auth::check()) {
            if (Auth::user()->user_type === 'Focal')
                return redirect()->route('focal.dashboard');
            else if (Auth::user()->user_type === 'Coordinator')
                return redirect()->route('coordinator.home');
        } else
            return view('landing.barangay');
    }
}
