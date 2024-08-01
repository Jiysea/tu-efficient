<?php

namespace App\Http\Controllers;

use App\Models\Code;
use Illuminate\Http\Request;

class BarangayIndexController extends Controller
{
    public function showAll(Request $request)
    {
        $accessCode = json_decode($request->query('accessCode'));

        $data = $request->session()->all();
        // dd($data);
        if (session('access_code')) {
            return view('pages.barangay.index');
        } else if ($accessCode === null || $accessCode === '') {
            session()->flush();
            return redirect()->route('barangay');
        } else {
            session(['access_code' => $accessCode]);
            return view('pages.barangay.index');
        }
    }
}
