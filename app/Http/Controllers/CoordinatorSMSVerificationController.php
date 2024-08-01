<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CoordinatorSMSVerificationController extends Controller
{
    public function __invoke()
    {
        return view('pages.coordinator.sms-verification');
    }
}
