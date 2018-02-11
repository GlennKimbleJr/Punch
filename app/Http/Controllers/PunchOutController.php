<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class PunchOutController extends Controller
{
    public function store()
    {
        if (!Auth::user()->isPunchedIn()) {
            return redirect()->back()->withErrors([
                'invalid-punch' => 'You cannot punch out without first being punched in.',
            ]);
        }

        Auth::user()->punch();
    }
}
