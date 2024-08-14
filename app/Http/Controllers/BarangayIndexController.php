<?php

namespace App\Http\Controllers;

use App\Models\Code;
use Illuminate\Http\Request;

class BarangayIndexController extends Controller
{
    public function showAll($accessCode)
    {
        return view('pages.barangay.index', ['accessCode' => $accessCode]);

    }
}
