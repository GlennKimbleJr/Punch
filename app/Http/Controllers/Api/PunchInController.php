<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PunchInController extends Controller
{
    public function store()
    {
        if (Auth::user()->isPunchedIn()) {
            return redirect()->back()->withErrors([
                'invalid-punch' => 'You are already punched in.',
            ]);
        }

        Auth::user()->punch();
    }
}
