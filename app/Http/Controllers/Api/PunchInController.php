<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PunchInController extends Controller
{
    public function store()
    {
        if (Auth::user()->isPunchedIn()) {
            return response()->json([
                'message' => 'You are already punched in.',
            ], 403);
        }

        Auth::user()->punch();

        return response()->json([
            'message' => 'You have been successfully punched in.',
        ], 200);
    }
}
