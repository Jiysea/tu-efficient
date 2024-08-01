<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FocalSMSVerificationController extends Controller
{
    public function __invoke()
    {
        return view('pages.focal.sms-verification');
    }
}
